<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trafalgar - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* إضافة الأنماط الخاصة بصفحة تسجيل الدخول */
        .login-section {
            padding: 4rem 2rem;
            background-color: #ffffff;
            text-align: center;
        }

        .login-container {
            max-width: 500px;
            margin: 0 auto;
            background: #f9f9ff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .login-form input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .login-form input:focus {
            border-color: #2d71f4;
        }

        .login-form button {
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

        .login-form button:hover {
            background-color: #235ab9;
        }

        .login-form p {
            font-size: 0.9rem;
            color: #666;
        }

        .login-form a {
            color: #2d71f4;
            text-decoration: none;
            font-weight: 600;
        }

        .login-form a:hover {
            text-decoration: underline;
        }
    </style>
        <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">

</head>
<body>
    <header>
        <nav class="navbar">
            <div class=""><img src="{{asset('warehouse/img/logo.svg')}}" alt="PharmaLink"></div>
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

    <section class="login-section">
        <div class="login-container">
            <div class=""><img src="{{asset('warehouse/img/logo.svg')}}" alt="PharmaLink"></div>

            <h2 class="login-title">Login to Your Account</h2>
            <form class="login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <p>Don't have an account? <a href="{{ route('register') }}">Sign up here</a></p>
            </form>
        </div>
    </section>

    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-brand">
                <h2 class="footer-logo">PharmaLink</h2>
                <p class="footer-description">
                    PharmaLink تقدم حلولاً متطورة لإدارة الصيدليات والمستودعات بسهولة وكفاءة عالية.
                </p>
                <p class="footer-copyright">
                    © PharmaLink 2025. جميع الحقوق محفوظة.
                </p>
            </div>

                <div class="footer-column">
                    <h3 class="footer-heading">المنصة</h3>
                    <ul class="footer-list">
                        <li><a href="#">عن المنصة</a></li>
                        <li><a href="#">الصيدليات</a></li>
                        <li><a href="#">الأدوية</a></li>
                        <li><a href="#">الموردون</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3 class="footer-heading">الدعم</h3>
                    <ul class="footer-list">
                        <li><a href="#">مركز المساعدة</a></li>
                        <li><a href="#">اتصل بنا</a></li>
                        <li><a href="#">الأسئلة الشائعة</a></li>
                        <li><a href="#">كيفية العمل</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const menuIcon = document.querySelector('.menu-icon');
        const navLinks = document.querySelector('.nav-links');

        menuIcon.addEventListener('click', () => {
            navLinks.classList.toggle('nav-active');
        });
    </script>
</body>
</html>
