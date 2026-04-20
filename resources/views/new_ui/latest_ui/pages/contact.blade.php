@extends('new_ui.latest_ui.app')

@section('content')

<!-- Hero Section -->
<div class="container-fluid herobanner">
    <div class="container">
        <section>
            <div class="heroinformation">
                <div class="row">
                    <div class="col-md-7">
                        <h1 class="hero-title">Unlock Your Potential with </h1>
                        <h4 class="hero-tagline">Smart Learning & Olympiad Excellence</h4>
                        <p class="hero-description">A complete platform for academic growth, competitive exam <br>preparation, and Olympiad success — guided by expert mentors.</p>
                        <div>
                            <div class="hero-button">Prepare for Olympiads <img src="{{ asset('images/new_ui/header/arrow.png') }}" alt="arrow"></div>
                        </div>
                    </div>
                    <div class="col-md-5"></div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="row g-4">
            <!-- Left: Contact Info -->
            <div class="col-lg-5 col-md-12">
                <div class="contact-info-wrapper">
                    <h3>Talk to Our Support Team</h3>
                    <p class="contact-desc">Whether you need help choosing the right course or want to know more about our teaching methodology, we are here to help.</p>

                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon"><i class="bi bi-telephone-fill"></i></div>
                            <div class="contact-text">
                                <a href="tel:+917022678333">+91 70226 78333</a>, <a href="tel:+919217964222">+91 92179 64222</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
                            <div class="contact-text">
                                <a href="mailto:info@vaagaacademy.com">info@vaagaacademy.com</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="bi bi-whatsapp"></i></div>
                            <div class="contact-text">
                                <a href="https://wa.me/917022678333">+91 70226 78333</a> (WhatsApp)
                            </div>
                        </div>
                    </div>

                    <div class="social-links">
                        <a href="#" class="social-icon" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-icon" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    </div>

                    <div class="contact-image">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Student studying">
                    </div>
                </div>
            </div>

            <!-- Right: Inquiry Form -->
            <div class="col-lg-7 col-md-12">
                <div class="inquiry-form-wrapper">
                    <span class="tagline">SEND IT TODAY!</span>
                    <h2>Quick Inquiry Form</h2>

                    <form action="{{ route('home.submitForm') }}" method="POST" class="inquiry-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="first_name" class="form-control-line" placeholder="First Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="last_name" class="form-control-line" placeholder="Last Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control-line" placeholder="E-mail" required>
                            </div>
                            <div class="col-md-6">
                                <input type="tel" name="phone" class="form-control-line" placeholder="Phone no" required>
                            </div>
                            <div class="col-12">
                                <select name="subject" class="form-control-line" required>
                                    <option value="" disabled selected>Subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="courses">Courses</option>
                                    <option value="olympiad">Olympiad Preparation</option>
                                    <option value="demo">Book a Free Demo</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control-line" rows="5" placeholder="Message" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn-send">Send <i class="bi bi-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="map-container">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3507.8451040575!2d76.9786!3d28.4506!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d1781ef8e0a0f%3A0x6a52e6f8d1f0f0f0!2sSector%2086%2C%20Gurugram%2C%20Haryana!5e0!3m2!1sen!2sin!4v1600000000000!5m2!1sen!2sin"
            width="100%"
            height="450"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <div class="testimonials-header">
            <div class="header-left">
                <span class="tagline">Testimonials</span>
                <h2>What Students Say</h2>
                <p>Real experiences from students achieving academic success and excelling in Olympiad exams.</p>
            </div>
            <div class="header-right">
                <div class="carousel-nav">
                    <button class="nav-arrow prev"><i class="bi bi-arrow-left"></i></button>
                    <div class="nav-dots">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                    <button class="nav-arrow next"><i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-video">
                <img src="https://placehold.co/600x400/333/fff?text=Video+Testimonial" alt="Video testimonial">
                <div class="play-button">
                    <i class="bi bi-play-fill"></i>
                </div>
            </div>
            <div class="testimonial-cards">
                <div class="t-card t-dark">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Riya Sharma</strong>
                        <span>Class 9 Student</span>
                    </div>
                </div>
                <div class="t-card t-teal">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Riya Sharma</strong>
                        <span>Class 9 Student</span>
                    </div>
                </div>
                <div class="t-card t-light">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Riya Sharma</strong>
                        <span>Class 9 Student</span>
                    </div>
                </div>
                <div class="t-card t-teal">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Aarav Patel</strong>
                        <span>Class 8 Student</span>
                    </div>
                </div>
                <div class="t-card t-light">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Priya Gupta</strong>
                        <span>Class 10 Student</span>
                    </div>
                </div>
                <div class="t-card t-dark">
                    <div class="stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="quote">The teaching approach is very clear and concept-based. I especially appreciate how subject mentors are always available. My confidence for Olympiads soared.</p>
                    <div class="author">
                        <strong>Karan Singh</strong>
                        <span>Class 7 Student</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 4% !important;"></section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Testimonials Carousel
    var cardsContainer = document.querySelector('.testimonial-cards');
    var prevBtn = document.querySelector('.carousel-nav .nav-arrow.prev');
    var nextBtn = document.querySelector('.carousel-nav .nav-arrow.next');
    var dots = document.querySelectorAll('.carousel-nav .nav-dots .dot');

    if (cardsContainer && prevBtn && nextBtn && dots.length) {
        var currentIndex = 0;
        var totalCards = cardsContainer.querySelectorAll('.t-card').length;
        var cardWidth = 320 + 16;

        function updateDots(index) {
            dots.forEach(function (dot, i) {
                dot.classList.toggle('active', i === index);
            });
        }

        function scrollToIndex(index) {
            if (index < 0) index = totalCards - 1;
            if (index >= totalCards) index = 0;
            cardsContainer.scrollTo({ left: cardWidth * index, behavior: 'smooth' });
            currentIndex = index;
            updateDots(currentIndex);
        }

        prevBtn.addEventListener('click', function () { scrollToIndex(currentIndex - 1); });
        nextBtn.addEventListener('click', function () { scrollToIndex(currentIndex + 1); });
        dots.forEach(function (dot, index) {
            dot.addEventListener('click', function () { scrollToIndex(index); });
        });
    }
});
</script>
@endsection