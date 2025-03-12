<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trafalgar - Virtual Healthcare</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        header {
            background: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d71f4;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        .nav-links li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links li a:hover {
            color: #2d71f4;
        }

        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4rem 2rem;
            background-color: #f9f9ff;
        }

        .hero-content {
            max-width: 500px;
        }

        .hero-content h1 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            color: #666;
        }

        .cta-button {
            display: inline-block;
            background-color: #2d71f4;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #235ab9;
        }

        .hero-image img {
            width: 100%;
            max-width: 500px;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9ff;
            color: #333;
        }

        .services-section {
            padding: 4rem 2rem;
            background-color: #ffffff;
            text-align: center;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .section-description {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        /* Grid Styling */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            justify-content: center;
        }

        .service-card {
            background: #f9f9ff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-card img {
            width: 50px;
            height: 50px;
            margin-bottom: 1rem;
        }

        .service-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #333;
        }

        .service-card p {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.6;
        }

        .learn-more-btn {
            display: inline-block;
            margin-top: 2rem;
            background-color: #ffffff;
            border: #2d71f4 solid;
            color:#2d71f4;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .learn-more-btn:hover {
            background-color:#2d71f4 ;
            color: #f9f9ff;
        }

        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9ff;
            color: #333;
        }

        .testimonial-section {
            padding: 4rem 2rem;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .testimonial-container {
            max-width: 800px;
            background: linear-gradient(135deg, #68d2ff, #4683f5);
            border-radius: 16px;
            padding: 2.5rem;
            text-align: center;
            color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .testimonial-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .testimonial-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .testimonial-image img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #ffffff;
        }

        .testimonial-text {
            max-width: 500px;
            text-align: left;
        }

        .testimonial-text h3 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .testimonial-position {
            font-size: 0.9rem;
            color: #d3e6ff;
            margin-bottom: 1rem;
        }

        .testimonial-quote {
            font-size: 1rem;
            line-height: 1.6;
            font-style: italic;
            color: #e6f0ff;
        }

        .testimonial-navigation {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            position: absolute; /* لجعلها خارج المربع */
            bottom: -40px; /* تحديد مسافة تحت المربع */
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-arrow {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #4683f5;
            cursor: pointer;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .nav-arrow:hover {
            transform: scale(1.2);
            color: #ffffff;
        }

        .dots {
            display: flex;
            gap: 0.5rem;
        }

        .dot {
            width: 10px;
            height: 10px;
            background-color: #d3e6ff;
            border-radius: 50%;
            cursor: pointer;
        }

        .dot.active {
            background-color: #4683f5;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9ff;
            color: #333;
        }

        .articles-section {
            padding: 4rem 2rem;
            background-color: #ffffff;
            text-align: center;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: #333;
        }

        .articles-container {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .article-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 300px;
            text-align: left;
        }

        .article-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .article-content {
            padding: 1.5rem;
        }

        .article-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .article-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .read-more {
            font-size: 0.9rem;
            font-weight: 500;
            color: #4683f5;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .read-more:hover {
            color: #0056b3;
        }

        .view-all-container {
            margin-top: 2rem;
        }

        .view-all-button {
            font-size: 1rem;
            color: #4683f5;
            text-decoration: none;
            border: 2px solid #4683f5;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .view-all-button:hover {
            background-color: #4683f5;
            color: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9ff;
            color: #333;
        }

        .footer-section {
            background: linear-gradient(to right, #5c9ded, #71c3fc);
            color: #ffffff;
            padding: 3rem 2rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-brand {
            flex: 1;
            min-width: 250px;
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-description {
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .footer-copyright {
            font-size: 0.8rem;
            color: #c2e1ff;
        }

        /* روابط الأعمدة */
        .footer-links {
            display: flex;
            flex: 2;
            justify-content: space-between;
            gap: 2rem;
        }

        .footer-column {
            min-width: 150px;
        }

        .footer-heading {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-list li {
            margin-bottom: 0.5rem;
        }

        .footer-list a {
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-list a:hover {
            color: #c2e1ff;
        }

        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                flex-direction: column;
            }
        }

        /* Responsive Navigation */
        .menu-icon {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-icon div {
            width: 25px;
            height: 3px;
            background-color: #333;
            margin: 4px;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .menu-icon {
                display: flex;
            }

            .nav-links {
                position: absolute;
                right: 0px;
                height: 92vh;
                top: 8vh;
                background-color: #ffffff;
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 50%;
                transform: translateX(100%);
                transition: transform 0.5s ease-in-out;
            }

            .nav-links li {
                opacity: 0;
            }

            .nav-links li a {
                color: #333;
            }

            .nav-active {
                transform: translateX(0%);
            }

            @keyframes navLinkFade {
                from {
                    opacity: 0;
                    transform: translateX(50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0px);
                }
            }

            .toggle .nav-links li {
                animation: navLinkFade 0.5s ease forwards;
            }
        }

        .login-btn {
            color: #2d71f4 !important;
            font-weight: 600 !important;
            padding: 8px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #f0f5ff;
        }

        .register-btn {
            background-color: #2d71f4;
            color: white !important;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600 !important;
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            background-color: #235ab9;
        }

        @media (max-width: 768px) {
            .login-btn, .register-btn {
                margin: 10px 0;
            }
        }
    </style>
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

    <main class="hero">
        <div class="hero-content">
            <h1>Virtual healthcare for you</h1>
            <p>Trafalgar provides progressive, and affordable healthcare, accessible on mobile and online for everyone.</p>
            <a href="#" class="cta-button">Consult today</a>
        </div>
        <div class="hero-image">
            <img src="{{ asset('assets/images/illustration.svg') }}" alt="illustration">
        </div>
    </main>

    <section class="services-section">
        <div class="container">
            <h2 class="section-title">Our services</h2>
            <p class="section-description">
                We provide to you the best choices for you. Adjust it to your health needs and make sure your undergo treatment with our highly qualified doctors you can consult with us which type of service is suitable for your health.
            </p>

            <div class="services-grid">

                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame.svg') }}" alt="Search doctor icon">
                    <h3>Search doctor</h3>
                    <p>Choose your doctor from thousands of specialist, general, and trusted hospitals.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame1.svg') }}" alt="Online pharmacy icon">
                    <h3>Online pharmacy</h3>
                    <p>Buy your medicines with our mobile application with a simple delivery system.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame3.svg') }}" alt="Consultation icon">
                    <h3>Consultation</h3>
                    <p>Free consultation with our trusted doctors and get the best recommendations.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame4.svg') }}" alt="Details info icon">
                    <h3>Details info</h3>
                    <p>Free consultation with our trusted doctors and get the best recommendations.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame5.svg') }}" alt="Emergency care icon">
                    <h3>Emergency care</h3>
                    <p>You can get 24/7 urgent care for yourself or your family.</p>
                </div>

                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame6.svg') }}" alt="Tracking icon">
                    <h3>Tracking</h3>
                    <p>Track and save your medical history and health data.</p>
                </div>
            </div>

            <a href="#" class="learn-more-btn">Learn more</a>
        </div>
    </section>

    <main class="hero">
        <div class="hero-image">
            <img src="{{ asset('assets/images/trafalgar-illustration2.svg') }}" alt="illustration">
        </div>
        <div class="hero-content">
            <h1>Leading healthcare providers</h1>
            <p>Trafalgar provides progressive, and affordable healthcare, accessible on mobile and online for everyone. To us, it's not just work. We take pride
                in the solutions we deliver</p>
            <a href="#" class="learn-more-btn">Learn more</a>
        </div>

    </main>

    <main class="hero">
        <div class="hero-content">
            <h1>Download our <br>
                mobile apps</h1>
            <p>Our dedicated patient engagement app and
                web portal allow you to access information instantaneously (no tedious form, long calls,
                or administrative hassle) and securely</p>
            <a href="#" class="learn-more-btn">Download</a>
        </div>
        <div class="hero-image">
            <img src="{{ asset('assets/images/trafalgar-illustration1.svg') }}" alt="illustration">
        </div>
    </main>
    <section class="testimonial-section">
        <div class="testimonial-container">
            <h2 class="testimonial-title">What our customer are saying</h2>
            <div class="testimonial-content">

                <div class="testimonial-image">
                    <img src="{{ asset('assets/images/Mask-Group.svg') }}" alt="Edward Newgate">
                </div>

                <div class="testimonial-text">
                    <h3>Edward Newgate</h3>
                    <p class="testimonial-position">Founder Circle</p>
                    <p class="testimonial-quote">
                        "Our dedicated patient engagement app and web portal allow you to access information instantaneously (no tedious form, long calls, or administrative hassles) and securely."
                    </p>
                </div>
            </div>

            <div class="testimonial-navigation">
                <button class="nav-arrow">&larr;</button>
                <div class="dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
                <button class="nav-arrow">&rarr;</button>
            </div>
        </div>
    </section>

    <section class="articles-section">
        <div class="container">
            <h2 class="section-title">Check out our latest article</h2>
            <div class="articles-container">

                <div class="article-card">
                    <img src="{{ asset('assets/images/Mask-Group11.svg') }}" alt="Disease detection" class="article-image">
                    <div class="article-content">
                        <h3 class="article-title">Disease detection, check up in the laboratory</h3>
                        <p class="article-description">
                            In this case, the role of the health laboratory is very important to do a disease detection...
                        </p>
                        <a href="#" class="read-more">Read more →</a>
                    </div>
                </div>

                <div class="article-card">
                    <img src="{{ asset('assets/images/Mask-Group1.svg') }}" alt="Herbal medicines" class="article-image">
                    <div class="article-content">
                        <h3 class="article-title">Herbal medicines that are safe for consumption</h3>
                        <p class="article-description">
                            Herbal medicine is very widely used at this time because of its very good for your health...
                        </p>
                        <a href="#" class="read-more">Read more →</a>
                    </div>
                </div>

                <div class="article-card">
                    <img src="{{ asset('assets/images/Mask-Group2.svg') }}" alt="Healthy facial skin" class="article-image">
                    <div class="article-content">
                        <h3 class="article-title">Natural care for healthy facial skin</h3>
                        <p class="article-description">
                            A healthy lifestyle should start from now and also for your skin health. There are some...
                        </p>
                        <a href="#" class="read-more">Read more →</a>
                    </div>
                </div>
            </div>

            <div class="view-all-container">
                <a href="#" class="view-all-button">View all</a>
            </div>
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
        const menuIcon = document.querySelector('.menu-icon');
        const navLinks = document.querySelector('.nav-links');

        menuIcon.addEventListener('click', () => {
            navLinks.classList.toggle('nav-active');
        });
    </script>
</body>
</html>
