<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trafalgar - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* نفس الأنماط السابقة بدون تغيير */
        .signup-section {
            padding: 4rem 2rem;
            background-color: #ffffff;
            text-align: center;
        }

        .signup-container {
            max-width: 500px;
            margin: 0 auto;
            background: #f9f9ff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .signup-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .account-type {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 0.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .account-type::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 1px;
            height: 80%;
            background-color: #ddd;
        }

        .account-type div {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .account-type div:hover {
            background-color: #f0f5ff;
        }

        .account-type div.active {
            background-color: #2d71f4;
            color: #ffffff;
            border-radius: 8px;
        }

        .account-type div.active i {
            color: #ffffff;
        }

        .account-type div i {
            font-size: 1.2rem;
            color: #666;
        }

        .signup-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .signup-form input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .signup-form input:focus {
            border-color: #2d71f4;
        }

        .signup-form button {
            background-color: #2d71f4;
            color: #fff;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup-form button:hover {
            background-color: #235ab9;
        }

        .signup-form p {
            font-size: 0.9rem;
            color: #666;
        }

        .signup-form a {
            color: #2d71f4;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-form a:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo"><img src="{{ asset('assets/images/logo.svg') }}" alt="Trafalgar"></div>
            <div class="menu-icon">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Find a doctor</a></li>
                <li><a href="#">Apps</a></li>
                <li><a href="#">Testimonials</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="{{ route('login') }}" class="login-btn">Login</a></li>
                <li><a href="{{ route('register') }}" class="register-btn">Sign up</a></li>
            </ul>
        </nav>
    </header>

    <section class="signup-section">
        <div class="signup-container">
            <h2 class="signup-title">Create an Account</h2>

            <div class="account-type">
                <div id="sidili" class="active" data-type="pharmacy">
                    <i class="fas fa-user"></i>
                    <span>صيدلي</span>
                </div>
                <div id="mustawda" data-type="warehouse">
                    <i class="fas fa-warehouse"></i>
                    <span>مستودع</span>
                </div>
            </div>

            <form class="signup-form" action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="role" id="account_type" value="pharmacy">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit">Sign Up</button>
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </form>
        </div>
    </section>

    <footer class="footer-section">
        <div class="container footer-container">
            <div class="footer-brand">
                <h2 class="footer-logo">Trafalgar</h2>
                <p class="footer-description">
                    Trafalgar provides progressive, and affordable healthcare, accessible on mobile and online for everyone
                </p>
                <p class="footer-copyright">
                    ©Trafalgar PTY LTD 2020. All rights reserved
                </p>
            </div>
            <div class="footer-links">
                <div class="footer-column">
                    <h3 class="footer-heading">Company</h3>
                    <ul class="footer-list">
                        <li><a href="#">About</a></li>
                        <li><a href="#">Testimonials</a></li>
                        <li><a href="#">Find a doctor</a></li>
                        <li><a href="#">Apps</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3 class="footer-heading">Region</h3>
                    <ul class="footer-list">
                        <li><a href="#">Indonesia</a></li>
                        <li><a href="#">Singapore</a></li>
                        <li><a href="#">Hongkong</a></li>
                        <li><a href="#">Canada</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3 class="footer-heading">Help</h3>
                    <ul class="footer-list">
                        <li><a href="#">Help center</a></li>
                        <li><a href="#">Contact support</a></li>
                        <li><a href="#">Instructions</a></li>
                        <li><a href="#">How it works</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const sidili = document.getElementById('sidili');
        const mustawda = document.getElementById('mustawda');
        const accountTypeInput = document.getElementById('account_type');

        sidili.addEventListener('click', () => {
            sidili.classList.add('active');
            mustawda.classList.remove('active');
            accountTypeInput.value = 'pharmacist';
        });

        mustawda.addEventListener('click', () => {
            mustawda.classList.add('active');
            sidili.classList.remove('active');
            accountTypeInput.value = 'warehouse';
        });
    </script>
</body>
</html>