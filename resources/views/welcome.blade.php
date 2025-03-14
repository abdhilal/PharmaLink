<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trafalgar - Virtual Healthcare</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

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
