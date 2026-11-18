<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NU Bulldogs Exchange</title>
    <link rel="stylesheet" href="{{ asset('css/customerlayout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/'.$css) }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="customer-body">
    <div class="main-content-wrapper">
        <nav class="navbar">
            <div class="navbar-container">
                <div class="navbar-left">
                    <div class="navbar-logo"><img class="navbar-logo" src="{{ asset('images/landing-page/BDX logo.png') }}"
                            alt="BDX logo"></div>
                    <div class="navbar-title">NU BULLDOGS EXCHANGE</div>
                </div>

                <div class="navbar-nav">
                    @forelse($navCategories ?? [] as $category)
                    <a href="{{ route('category.products', optional($category)->slug) }}" class="nav-item">
                        {{ optional($category)->name }}
                    </a>
                    @empty
                    <a href="#" class="nav-item">Mens</a>
                    <a href="#" class="nav-item">Womens</a>
                    <a href="#" class="nav-item">Uniforms</a>
                    <a href="#" class="nav-item">Wearables</a>
                    <a href="#" class="nav-item">Accessories</a>
                    @endforelse

                    <a href="{{ route('customer.products.index') }}" class="nav-item nav-item-all">All Products</a>
                </div>

                  <div class="navbar-right">
                  @auth
                      <div class="authentication-links">
                          <a href="{{route('profile.edit')}}" class="auth-link">Welcome! {{ Auth::user()->username }}</a>
                      </div>

                  @else
                  <div class="authentication-links">
                      <a href="{{route('register')}}" class="auth-link">Sign up</a> | <a href="{{route('login')}}" class="auth-link">Log in</a>
                  </div>
                  @endauth

                  <div class="navbar-icons">
                  @auth
                      <a href="{{ route('cart.index') }}" class="icon cart-icon" title="Shopping Cart">
                          <img src="{{ asset('images/landing-page/cart-icon.svg') }}" alt="cart">
                          <span class="cart-count" id="cartCount">0</span>
                      </a>
                      <div class="icon profile-icon profile-dropdown" title="Profile">
                          <img src="{{ asset('images/landing-page/profile-icon.svg') }}" alt="profile">
                          <div class="profile-dropdown-menu">
                              <a href="{{ route('customer.orders.index') }}" class="dropdown-item">My Orders</a>
                              <a href="{{ route('profile.edit') }}" class="dropdown-item">Profile Settings</a>
                              <a href="{{ route('cart.index') }}" class="dropdown-item">Shopping Cart</a>
                              <div class="dropdown-divider"></div>
                              <form method="POST" action="{{ route('logout') }}">
                                  @csrf
                                  <button type="submit" class="dropdown-item logout-btn">Logout</button>
                              </form>
                          </div>
                      </div>
                  @else
                      <a href="{{ route('login') }}" class="icon cart-icon" title="Login to view cart">
                          <img src="{{ asset('images/landing-page/cart-icon.svg') }}" alt="cart">
                      </a>
                      <a href="{{ route('login') }}" class="icon profile-icon" title="Login">
                          <img src="{{ asset('images/landing-page/profile-icon.svg') }}" alt="profile">
                      </a>
                  @endauth
              </div>
            </div>
            </div>
        </nav>
        {{ $slot }}
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-main">
                <div class="footer-brand">
                    <img src="{{ asset('images/landing-page/BDX logo.png') }}" alt="BDX logo" class="footer-logo">
                    <span class="footer-title">NU Bulldogs Exchange</span>
                </div>
                <div class="footer-links">
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Contact Us</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                </div>
                <div class="footer-socials">
                    <a href="#" class="social-icon" title="Facebook"><img src="{{ asset('images/landing-page/facebook.svg') }}" alt="Facebook"></a>
                    <a href="#" class="social-icon" title="Twitter"><img src="{{ asset('images/landing-page/twitter.svg') }}" alt="Twitter"></a>
                    <a href="#" class="social-icon" title="Instagram"><img src="{{ asset('images/landing-page/instagram.svg') }}" alt="Instagram"></a>
                </div>
            </div>
            <div class="footer-bottom">
                <span class="footer-copyright">
                    &copy; {{ date('Y') }} NU Bulldogs Exchange. All rights reserved.
                </span>
            </div>
        </div>
    </footer>

    <script>
    // Update cart counter
    function updateCartCount() {
        @auth
        fetch('/api/cart/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) {
                cartCount.textContent = data.count || 0;
                if (data.count > 0) {
                    cartCount.style.display = 'flex';
                } else {
                    cartCount.style.display = 'none';
                }
            }
        })
        .catch(error => console.log('Cart count update failed:', error));
        @endauth
    }

    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', updateCartCount);

    // Update cart count when items are added (call this after successful cart additions)
    window.updateCartCount = updateCartCount;
    </script>
</body>
</html>