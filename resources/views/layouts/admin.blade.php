<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('app.admin_panel')) — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f3f5; }
        .sidebar { min-height: 100vh; }
        .sidebar .nav-link { color: rgba(255, 255, 255, .75); }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { color: #fff; background-color: rgba(255, 255, 255, .1); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-3 col-lg-2 d-md-block bg-dark sidebar p-3">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none fw-bold">
                    <i class="bi bi-gem me-2"></i>{{ __('app.admin_panel') }}
                </a>
                <hr class="text-secondary">
                <ul class="nav nav-pills flex-column gap-1">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i>{{ __('app.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="bi bi-receipt me-2"></i>{{ __('app.orders') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.skins.index') }}" class="nav-link {{ request()->routeIs('admin.skins.*') ? 'active' : '' }}">
                            <i class="bi bi-palette me-2"></i>{{ __('app.skins') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="bi bi-gear me-2"></i>{{ __('app.settings') }}
                        </a>
                    </li>
                </ul>
                <hr class="text-secondary">
                <div class="d-flex flex-column gap-2">
                    <div class="btn-group btn-group-sm" role="group" aria-label="{{ __('app.language') }}">
                        <a href="{{ route('lang.switch', 'en') }}" class="btn btn-outline-light {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('lang.switch', 'id') }}" class="btn btn-outline-light {{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-box-arrow-right me-1"></i>{{ __('app.logout') }}
                        </button>
                    </form>
                </div>
            </aside>

            <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
