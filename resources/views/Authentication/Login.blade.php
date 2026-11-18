<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulldogs Exchange - Login</title>
    <link rel="stylesheet" href="{{ asset('css/Login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,400;0,700;1,700&family=Inter:wght@800&family=Irish+Grover&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section">

            <div class="welcome-text">
                <h1><i>Welcome to</i></h1>
                <h2><i>Bulldogs Exchange</i></h2>
                <h3><i>Online Store</i></h3>
            </div>

            <div class="nu-text">
                NU
            </div>

            <img src="Images/navbar-logo.png" alt="NU Logo" class="logo">

            <div class="qr-code">
                <div class="qr-inner">
                    <i class="fas fa-shopping-cart cart-icon"></i>
                </div>
            </div>


        </div>
        <!--Left End-->

        <!-- Right Section - Login Form -->
        <div class="right-section">
            <div class="login-section">
                <div class="brand-logo">
                    <span class="brand-b">B</span>
                    <span class="brand-x">D</span>
                    <span class="brand-d">X</span>
                </div>

                <form class="login-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="fas fa-user"></i></div>
                            <input type="text" id="username" placeholder="Enter Username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="fas fa-lock"></i></div>
                            <input type="password" id="password" placeholder="Password">
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="Account Type">Account Type</label>
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="fas fa-user-tag"></i></div>
                            <select class="account-type" id="account-type">
                                <option value="" disabled selected> &nbsp; Select Account Type</option>
                                <option value="student">&nbsp; Student</option>
                                <option value="faculty">&nbsp; Faculty</option>
                                <option value="staff">&nbsp; Staff</option>
                            </select>
                        </div>


                    </div>

                    <button type="submit" class="login-btn">Log In</button>
                </form>

                <div class="signup-link">
                    <span>Don't have an account?</span>
                    <a href="{{route('Register')}}">Sign-up</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer"></div>
    </div>
</body>

</html>