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
            <div class=""><img src="{{ asset('warehouse/img/logo.svg') }}" alt="PharmaLink"></div>
            <div class="menu-icon">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Find</a></li>
                <li><a href="#">Apps</a></li>
                <li><a href="#">Testimonials</a></li>
                <li><a href="#">About us</a></li>
                @if(Auth::check())


                    @if (Auth::user()->warehouse)
                        <li><a href="{{ route('warehouse.dashboard') }}" class="register-btn"> حسابي</a></li>
                    @elseif(Auth::user()->role = 'pharmacy')
                        <li><a href="{{ route('pharmacy.warehouses.index') }}" class="register-btn"> حسابي</a></li>
                    @endif
                @else
                    <li><a href="{{ route('login') }}" class="login-btn">Login</a></li>
                    <li><a href="{{ route('register') }}" class="register-btn">Sign up</a></li>
                @endif

            </ul>
        </nav>
    </header>


    <main class="hero">
        <div class="hero-content">
            <h1>إدارة ذكية للصيدليات والمستودعات</h1>
            <p>PharmaLink توفر حلولاً متكاملة لإدارة الأدوية والمستودعات بسهولة وكفاءة عالية، متاحة على الهاتف والويب
                للجميع.</p>
            <a href="#" class="cta-button">ابدأ الآن</a>
        </div>
        <div class="hero-image">
            <img src="{{ asset('assets/images/illustration.svg') }}" alt="إدارة ذكية">
        </div>
    </main>

    <section class="services-section">
        <div class="container">
            <h2 class="section-title">خدماتنا</h2>
            <p class="section-description">
                نحن نوفر لك أفضل الخيارات لإدارة احتياجاتك الصحية بكفاءة، مع خدمات مصممة لتلبية متطلبات الصيدليات
                والمستودعات.
            </p>
            <div class="services-grid">
                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame.svg') }}" alt="بحث عن صيدلية">
                    <h3>البحث عن صيدلية</h3>
                    <p>ابحث عن الصيدليات القريبة منك بسهولة وسرعة.</p>
                </div>
                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame1.svg') }}" alt="طلب الأدوية">
                    <h3>طلب الأدوية</h3>
                    <p>اطلب أدويتك من خلال تطبيقنا مع خدمة توصيل سريعة.</p>
                </div>
                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame3.svg') }}" alt="إدارة المستودعات">
                    <h3>إدارة المستودعات</h3>
                    <p>تابع مخزونك بدقة مع أدوات إدارة متقدمة.</p>
                </div>
                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame4.svg') }}" alt="تقارير مفصلة">
                    <h3>تقارير مفصلة</h3>
                    <p>احصل على تقارير دقيقة لتحسين أداء عملك.</p>
                </div>
                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame5.svg') }}" alt="دعم فوري">
                    <h3>دعم فوري</h3>
                    <p>دعم فني متواصل لضمان استمرارية عملك.</p>
                </div>
                <div class="service-card">
                    <img src="{{ asset('assets/images/Frame6.svg') }}" alt="تتبع الطلبات">
                    <h3>تتبع الطلبات</h3>
                    <p>راقب طلباتك في الوقت الفعلي بسهولة.</p>
                </div>
            </div>
            <a href="#" class="learn-more-btn">تعرف على المزيد</a>
        </div>
    </section>


    <main class="hero">
        <div class="hero-image">
            <img src="{{ asset('assets/images/trafalgar-illustration2.svg') }}" alt="إدارة متقدمة">
        </div>
        <div class="hero-content">
            <h1>إدارة متقدمة للخدمات الصحية</h1>
            <p>نقدم حلولاً مبتكرة لإدارة الصيدليات والمستودعات بسهولة وأمان، مع التركيز على تحسين الكفاءة.</p>
            <a href="#" class="learn-more-btn">تعرف على المزيد</a>
        </div>
    </main>





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
