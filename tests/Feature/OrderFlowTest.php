<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Setting;
use App\Models\Skin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private Setting $settings;

    private Skin $skin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->settings = Setting::create([
            'admin_ml_id' => '123456789',
            'admin_ml_nickname' => 'AdminStore',
            'dana_number' => '081234567890',
            'whatsapp_number' => '6281234567890',
            'current_diamond_balance' => 1000,
        ]);

        $this->skin = Skin::create([
            'name' => 'Test Skin',
            'hero_name' => 'Alucard',
            'type' => 'Epic',
            'price_diamond' => 899,
            'price_rupiah' => 290000,
            'is_active' => true,
        ]);
    }

    private function buyerPayload(): array
    {
        return [
            'buyer_name' => 'Budi',
            'buyer_email' => 'budi@example.com',
            'buyer_whatsapp' => '081298765432',
            'buyer_ml_nickname' => 'BudiGamer',
            'buyer_ml_id' => '987654321',
            'buyer_ml_server' => '1234',
            'friend_agreement' => '1',
        ];
    }

    public function test_home_only_shows_affordable_active_skins(): void
    {
        $tooExpensive = Skin::create([
            'name' => 'Expensive Skin', 'hero_name' => 'Gusion', 'type' => 'Legend',
            'price_diamond' => 5000, 'price_rupiah' => 1500000, 'is_active' => true,
        ]);
        $inactive = Skin::create([
            'name' => 'Hidden Skin', 'hero_name' => 'Miya', 'type' => 'Elite',
            'price_diamond' => 100, 'price_rupiah' => 40000, 'is_active' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Test Skin')
            ->assertDontSee($tooExpensive->name)
            ->assertDontSee($inactive->name);
    }

    public function test_checkout_deducts_balance_and_creates_pending_order(): void
    {
        $response = $this->post(route('checkout.store', $this->skin), $this->buyerPayload());

        $order = Order::firstOrFail();

        $response->assertRedirect(route('orders.show', $order->order_code));
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertSame(899, $order->total_diamond);
        $this->assertSame(1000 - 899, $this->settings->fresh()->current_diamond_balance);
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-[A-Z0-9]{4}$/', $order->order_code);
    }

    public function test_checkout_requires_friend_agreement(): void
    {
        $payload = $this->buyerPayload();
        unset($payload['friend_agreement']);

        $this->post(route('checkout.store', $this->skin), $payload)
            ->assertSessionHasErrors('friend_agreement');

        $this->assertSame(0, Order::count());
        $this->assertSame(1000, $this->settings->fresh()->current_diamond_balance);
    }

    public function test_checkout_rejected_when_balance_insufficient(): void
    {
        $this->settings->update(['current_diamond_balance' => 100]);

        $this->post(route('checkout.store', $this->skin), $this->buyerPayload())
            ->assertRedirect(route('home'));

        $this->assertSame(0, Order::count());
        $this->assertSame(100, $this->settings->fresh()->current_diamond_balance);
    }

    public function test_expired_pending_order_is_auto_canceled_and_refunded(): void
    {
        $this->post(route('checkout.store', $this->skin), $this->buyerPayload());
        $this->assertSame(101, $this->settings->fresh()->current_diamond_balance);

        $this->travel(Order::RESERVATION_MINUTES + 1)->minutes();
        $this->artisan('orders:cancel-expired')->assertSuccessful();

        $this->assertSame(Order::STATUS_CANCELED, Order::firstOrFail()->status);
        $this->assertSame(1000, $this->settings->fresh()->current_diamond_balance);
    }

    public function test_fresh_pending_order_is_not_auto_canceled(): void
    {
        $this->post(route('checkout.store', $this->skin), $this->buyerPayload());

        $this->travel(Order::RESERVATION_MINUTES - 5)->minutes();
        $this->artisan('orders:cancel-expired')->assertSuccessful();

        $this->assertSame(Order::STATUS_PENDING, Order::firstOrFail()->status);
        $this->assertSame(101, $this->settings->fresh()->current_diamond_balance);
    }

    public function test_admin_approve_keeps_balance_deducted(): void
    {
        $this->post(route('checkout.store', $this->skin), $this->buyerPayload());
        $order = Order::firstOrFail();

        $this->actingAs(User::factory()->create())
            ->patch(route('admin.orders.approve', $order))
            ->assertRedirect();

        $this->assertSame(Order::STATUS_SUCCESS, $order->fresh()->status);
        $this->assertSame(101, $this->settings->fresh()->current_diamond_balance);
    }

    public function test_admin_cancel_refunds_balance(): void
    {
        $this->post(route('checkout.store', $this->skin), $this->buyerPayload());
        $order = Order::firstOrFail();

        $this->actingAs(User::factory()->create())
            ->patch(route('admin.orders.cancel', $order))
            ->assertRedirect();

        $this->assertSame(Order::STATUS_CANCELED, $order->fresh()->status);
        $this->assertSame(1000, $this->settings->fresh()->current_diamond_balance);
    }

    public function test_deleting_pending_order_refunds_balance_first(): void
    {
        $this->post(route('checkout.store', $this->skin), $this->buyerPayload());
        $order = Order::firstOrFail();

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.orders.destroy', $order))
            ->assertRedirect(route('admin.orders.index'));

        $this->assertSame(0, Order::count());
        $this->assertSame(1000, $this->settings->fresh()->current_diamond_balance);
    }

    public function test_public_order_page_masks_contact_details(): void
    {
        $this->post(route('checkout.store', $this->skin), $this->buyerPayload());
        $order = Order::firstOrFail();

        $this->get(route('orders.show', $order->order_code))
            ->assertOk()
            ->assertSee($order->order_code)
            ->assertDontSee('budi@example.com')
            ->assertDontSee('081298765432');
    }

    public function test_track_order_redirects_to_order_page(): void
    {
        $this->post(route('checkout.store', $this->skin), $this->buyerPayload());
        $order = Order::firstOrFail();

        $this->get(route('orders.track', ['code' => $order->order_code]))
            ->assertRedirect(route('orders.show', $order->order_code));

        $this->get(route('orders.track', ['code' => 'ORD-00000000-XXXX']))
            ->assertOk()
            ->assertSee(__('app.order_not_found'));
    }

    public function test_admin_panel_requires_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_locale_switch_persists_in_session(): void
    {
        $this->get(route('lang.switch', 'id'));
        $this->get(route('home'))->assertSee('Pesan Sekarang');

        $this->get(route('lang.switch', 'en'));
        $this->get(route('home'))->assertSee('Order Now');
    }
}
