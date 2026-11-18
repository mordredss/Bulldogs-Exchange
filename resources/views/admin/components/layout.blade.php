<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Layout' }}</title>
        <link rel="stylesheet" href="{{ asset('css/adminlayout.css') }}">
        @if (!empty($css))
            <link rel="stylesheet" href="{{ asset('css/' . $css) }}">
        @endif
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="no-transition">
        <div class="admin-wrapper">
            <div class="sidebar-container">
                <script>
                    (function() {
                        var state = localStorage.getItem('sidebarState') || 'expanded';
                        document.write('<div class="sidebar ' + state + '" id="sidebar">');
                    })();
                </script>
                    <!-- Header -->
                    <div class="header">
                        <div class="logo">
                            <img src="{{ asset('images/admin/navbar-logo.svg') }}" alt="">
                        </div>
                        <div class="brand-title">
                            <span class="nu">NU </span><span class="bdx">BDX</span>
                        </div>
                        <button class="collapse-btn" onclick="toggleSidebar()">
                            <img src="{{ asset('images/admin/chevrons-left 1.svg') }}" class="collapse-icon" alt="">
                            <img src="{{ asset('images/admin/sidebar.svg') }}" class="expand-icon" alt="Expand sidebar">
                        </button>
                        <div class="header-divider"></div>
                    </div>

                    <!-- Navigation -->
                    <nav class="nav-menu">
                        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <div class="grid-icon">
                                    <img src="{{ asset('images/admin/grid.svg') }}" alt="">
                                </div>
                            </div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.productmanagement.index') }}" class="nav-item {{ request()->routeIs('admin.productmanagement.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <img src="{{ asset('images/admin/briefcase.svg') }}" alt="">
                            </div>
                            <span class="nav-text">Products</span>
                        </a>
                        <a href="{{ route('admin.ordermanagement.index') }}" class="nav-item {{ request()->routeIs('admin.ordermanagement.*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <img src="{{ asset('images/admin/folder.svg') }}" alt="">
                                </div>
                                <span class="nav-text">Orders</span>
                        </a>
                        <a href="{{ route('admin.analytics.index') }}" class="nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <img src="{{ asset('images/admin/bitcoin-icons_graph-outline.svg') }}" alt="">
                                </div>
                                <span class="nav-text">Analytics</span>
                        </a>
                    </nav>

                    <!-- Footer -->
                    <div class="footer">
                        <div class="user-avatar"></div>
                        <div class="user-info">
                            Welcome,<br>{{ Auth::user()->username }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <img src="{{ asset('images/admin/log-out 1.svg') }}" alt="" >
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="main-content">
                {{ $slot }}
            </div>
        </div>
        <script>
            function setSidebarState(state) {
                localStorage.setItem('sidebarState', state);
            }

            function getSidebarState() {
                return localStorage.getItem('sidebarState') || 'expanded';
            }

            function applySidebarState() {
                const sidebar = document.getElementById('sidebar');
                const state = getSidebarState();
                sidebar.classList.remove('expanded', 'collapsed');
                sidebar.classList.add(state);
            }

            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('expanded')) {
                    sidebar.classList.remove('expanded');
                    sidebar.classList.add('collapsed');
                    setSidebarState('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    sidebar.classList.add('expanded');
                    setSidebarState('expanded');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                applySidebarState();
                
                setTimeout(function() {
                    document.body.classList.remove('no-transition');
                }, 10);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar.classList.contains('expanded')) {
                        toggleSidebar();
                    }
                }
            });
        </script>
    </body>
</html>