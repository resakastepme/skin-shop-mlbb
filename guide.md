# Project Context: MLBB Skin Gift Shop (Laravel)

## 1. Deskripsi Proyek

Proyek ini adalah website untuk menjual saldo Diamond (DM) Mobile Legends milik Admin dengan cara "Gift Skin".
Tujuan utamanya adalah agar user bisa memesan skin, dan admin akan mengirimkan skin tersebut via in-game gift.

**Tech Stack:** Laravel 13 (Backend & Admin Panel), MySQL (database, sudah terkoneksi ke MySQL lokal — lihat `.env`), Blade + Bootstrap 5 (Frontend User & Admin). Website mendukung dua bahasa (English dan Indonesia), dengan English sebagai default.

## 2. Aturan Bisnis & Logika Utama

* **Katalog Dinamis:** Halaman depan HANYA menampilkan skin yang harga DM-nya kurang dari atau sama dengan **Sisa DM Admin**. Sisa DM Admin dinamis dan tersimpan di database.

* **Syarat Gift:** User WAJIB mencentang persetujuan bahwa mereka sudah berteman dengan akun MLBB Admin minimal selama 7 hari sebelum bisa melanjutkan proses pemesanan.

* **Kuantitas Order:** Satu order hanya untuk **1 skin dengan qty 1**. Tidak ada fitur keranjang/multi-item.

* **Sistem Reservasi/Booking (30 Menit):**
  * Saat user melakukan *checkout* (submit form), sistem membuat Order berstatus `pending`.
  * Saldo DM Admin **langsung dipotong sementara** di sistem agar skin/DM tidak diorder orang lain di waktu yang sama (mencegah *overselling*).
  * Setelah submit form, User akan di-redirect ke WhatsApp Admin dengan membawa format pesan detail order.

* **Alur Pembayaran (Manual):**
  * Pembayaran dilakukan manual via transfer **DANA** ke nomor yang tersimpan di `settings.dana_number`.
  * User mengonfirmasi pembayaran (kirim bukti transfer) lewat **WhatsApp** Admin.
  * Setelah pembayaran diverifikasi, Admin menekan tombol **Approve** di Admin Panel secara manual. Tidak ada payment gateway otomatis.

* **Auto-Cancel & Refund:**
  * Terdapat Task Scheduler (Cron Job) yang berjalan secara periodik untuk mengecek order berstatus `pending` yang usianya sudah lebih dari 30 menit.
  * Jika ditemukan, status order otomatis diubah menjadi `canceled` dan Saldo DM tersebut **dikembalikan (refund)** ke saldo Admin.

* **Admin Panel:**
  * **Autentikasi:** Menggunakan sistem login bawaan Laravel (session-based, tabel `users`). Hanya untuk Admin — tidak ada registrasi publik (akun admin dibuat via Seeder).
  * **URL Rahasia:** Seluruh rute admin berada di bawah prefix `/admin-{hash}/*`, di mana `{hash}` adalah string rahasia yang disimpan di `.env` (cth: `ADMIN_URL_HASH=x7k2m9`). Prefix dibaca via config, bukan hardcode. Ini lapisan keamanan tambahan (security by obscurity) di atas middleware `auth`.
  * **Manajemen Order:** Admin bisa melihat daftar seluruh order yang masuk (terutama yang berstatus `pending`).
    * Tombol **Approve**: Mengubah status menjadi `success`. (DM tidak perlu dikembalikan karena memang sudah terpotong dan terpakai di awal reservasi).
    * Tombol **Cancel**: Mengubah status menjadi `canceled` secara manual dan **me-refund** saldo DM ke Admin.
  * **CRUD Penuh:** Admin bisa melakukan CRUD untuk semua tabel utama dari Admin Panel:
    * **Skins:** create, read, update, delete — termasuk **upload gambar** (file disimpan di `storage/app/public/skins`, path-nya disimpan ke kolom `image_url`, diakses via `php artisan storage:link`).
    * **Orders:** lihat detail, ubah status (Approve/Cancel), edit, dan hapus.
    * **Settings:** edit konfigurasi global termasuk penyesuaian manual `current_diamond_balance` (cth: setelah admin top-up DM).

* **Format Order Code:** Berbasis tanggal + suffix acak agar unik dan mudah dilacak. Format: `ORD-YYYYMMDD-XXXX` (cth: `ORD-20260707-A3F9`), di mana `XXXX` adalah 4 karakter alfanumerik uppercase acak.

* **Cek Status Order (Publik, Tanpa Login):**
  * User bisa melacak ordernya sendiri menggunakan `order_code`.
  * Rute pencarian: halaman/form input `order_code` (cth: `GET /track-order`) yang mencari order dan menampilkan hasilnya.
  * Rute detail: `GET /order/{order_code}` menampilkan detail order — status (`pending`/`success`/`canceled`), nama skin, total DM & Rupiah, dan sisa waktu reservasi jika masih `pending`.
  * Halaman detail ini juga dipakai sebagai halaman tujuan setelah checkout (sebelum/sesudah redirect WhatsApp), agar user langsung punya link untuk memantau ordernya.
  * `order_code` cukup acak sehingga tidak mudah ditebak, tapi TETAP jangan tampilkan data sensitif lengkap di halaman publik (cth: masking sebagian email/nomor WhatsApp buyer).

## 3. Skema Database Utama

### A. Tabel `settings` (Konfigurasi Global & Saldo)
Tabel ini krusial untuk menyimpan saldo DM yang berjalan.
* `id`
* `admin_ml_id` (string)
* `admin_ml_nickname` (string)
* `dana_number` (string)
* `whatsapp_number` (string)
* `current_diamond_balance` (integer) - *Ini adalah field paling penting untuk memfilter katalog di frontend.*

### B. Tabel `skins` (Katalog Skin)
* `id`
* `name` (string) - cth: "Alucard - Lightborn"
* `hero_name` (string)
* `type` (string) - cth: Epic, Special
* `price_diamond` (integer)
* `price_rupiah` (integer)
* `image_url` (string, nullable) - *path file hasil upload dari Admin Panel, bukan URL eksternal*
* `is_active` (boolean)

### C. Tabel `orders` (Data Transaksi)
* `id`
* `order_code` (string, unique)
* `skin_id` (foreign key ke tabel skins)
* `buyer_name` (string)
* `buyer_email` (string)
* `buyer_whatsapp` (string)
* `buyer_ml_nickname` (string)
* `buyer_ml_id` (string)
* `buyer_ml_server` (string)
* `total_diamond` (integer)
* `total_rupiah` (integer)
* `status` (enum: 'pending', 'success', 'canceled') - default: 'pending'

### D. Tabel `users` (Akun Admin)
Tabel bawaan Laravel, dipakai HANYA untuk login Admin Panel (tidak ada registrasi publik).
* `id`
* `name` (string)
* `email` (string, unique)
* `password` (hashed)

## 4. Instruksi Khusus untuk AI / Claude Code

Saat saya meminta Anda (Claude) untuk menulis kode (Controller, Model, Migration, atau View) untuk proyek ini, WAJIB ikuti panduan berikut:

1. **Logika Transaksional:** Selalu patuhi logika **Reservasi 30 Menit** dan **Pemotongan Saldo di Awal** (saat checkout).
2. **Database Transaction:** Gunakan `DB::transaction()` untuk setiap operasi yang melibatkan pembuatan Order dan pengubahan saldo DM di tabel `settings` (baik saat checkout, cancel, atau auto-cancel) agar data selalu konsisten.
3. **Framework CSS:** Desain seluruh UI (Frontend maupun Admin Panel) menggunakan **Bootstrap 5**. Jangan gunakan Tailwind CSS.
4. **Lokalisasi (Multi-language):** Implementasikan fitur multi-bahasa (English & Indonesia) dengan **English sebagai default**. Gunakan sistem lokalisasi bawaan Laravel (file lang `en` dan `id`, serta helper `__()` atau `@lang` di Blade) untuk SEMUA teks antarmuka pengguna (UI). Sediakan juga fitur/rute sederhana untuk mengubah *locale* menggunakan Session.
5. **Rute Admin:** Semua rute Admin Panel WAJIB berada di bawah prefix dinamis dari `.env` (`ADMIN_URL_HASH`) yang dibaca lewat file config (cth: `config('app.admin_url_hash')`), dan dilindungi middleware `auth`. Jangan pernah hardcode prefix admin di routes atau view — gunakan named routes (`route('admin.orders.index')`) agar prefix bisa diganti tanpa mengubah kode.
6. **Upload Gambar:** Gambar skin di-upload lewat form Admin Panel, disimpan ke disk `public` (`storage/app/public/skins`), dan divalidasi (tipe image, ukuran maksimal wajar, cth: 2MB). Saat skin dihapus atau gambarnya diganti, file lama ikut dihapus dari storage.
