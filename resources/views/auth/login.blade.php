<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,400;0,700;1,700&family=Inter:wght@800&family=Irish+Grover&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">

    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="welcome-text">
                <h1><i>Welcome to</i></h1>
                <h2><i>Bulldogs Exchange</i></h2>
                <h3><i>Online Store</i></h3>
            </div>

            <div class="nu-text">NU</div>
            <img src="{{ asset('Images/navbar-logo.png') }}" alt="NU Logo" class="logo">

            <div class="qr-code">
                <div class="qr-inner">
                    <i class="fas fa-shopping-cart cart-icon"></i>
                </div>
            </div>
        </div>

        <!-- Right Section - Login Form -->
        <div class="right-section">
            <div class="login-section">
                <div class="brand-logo">
                    <span class="brand-b">B</span>
                    <span class="brand-x">D</span>
                    <span class="brand-d">X</span>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <x-input-label for="email" :value="__('Email')" />
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="fas fa-user"></i></div>
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="username" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="fas fa-lock"></i></div>
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required autocomplete="current-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>


                    <!-- Account Type section -->
                    <div class="form-group" id="role-section">
                        <label for="role">Account Type</label>
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="fas fa-user-tag"></i></div>
                            <select name="role" id="role" class="account-type" required>
                                <option value="" disabled selected>Select Account Type</option>
                                <option value="Student">Student</option>
                                <option value="Non-Student">Non-Student</option>
                                <option value="Faculty">Faculty</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="login-btn">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>

                    <div class="signup-link">
                        <span>Don't have an account?</span>
                        <a href="{{ route('register') }}">Sign-up</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer"></div>
    </div>


</x-guest-layout>