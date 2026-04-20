@extends('new_ui.latest_ui.app')

@section('content')

<!-- Hero Section -->
<div class="container-fluid herobanner courses-hero">
    <div class="container">
        <section>
            <div class="heroinformation">
                <div class="row">
                    <div class="col-md-7">
                        <h1 class="hero-title">Empower Your Child to Excel</h1>
                        <h4 class="hero-tagline">in Global Olympiads</h4>
                        <p class="hero-description">A structured, fun, and results-driven learning path for Classes 1–8.<br>Master complex concepts, build problem-solving skills, and gain<br>the confidence to rank among the top.</p>
                        <div>
                            <div class="hero-button">Book a Free Demo <img src="{{ asset('images/new_ui/header/arrow.png') }}" alt="arrow"></div>
                        </div>
                    </div>
                    <div class="col-md-5"></div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Why Students Win with Us -->
<section class="why-win-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <!-- Left Side -->
            <div class="col-lg-5 col-md-12">
                <div class="why-win-left">
                    <h2 class="why-title">Why Students <span class="teal-text">Win with Us</span></h2>
                    <p class="why-subtitle">focuses directly on the <span class="gold-text">outcome/result.</span></p>
                    <div class="why-illustration">
                        <svg viewBox="0 0 320 220" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#047185" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <!-- Girl figure -->
                            <circle cx="75" cy="55" r="18" fill="none"/>
                            <path d="M55 85 Q50 110 48 140" />
                            <path d="M95 85 Q100 110 102 140" />
                            <path d="M48 140 L45 185" />
                            <path d="M102 140 L105 185" />
                            <path d="M55 85 Q75 95 95 85" />
                            <!-- Ponytail -->
                            <path d="M57 45 Q35 50 32 75 Q30 95 40 105" />
                            <!-- Books in hand -->
                            <rect x="85" y="110" width="28" height="8" rx="1" fill="#047185" stroke="none" opacity="0.15"/>
                            <rect x="87" y="118" width="26" height="8" rx="1" fill="#047185" stroke="none" opacity="0.1"/>
                            <rect x="89" y="126" width="24" height="8" rx="1" fill="#047185" stroke="none" opacity="0.08"/>
                            <!-- Smile -->
                            <path d="M70 62 Q75 66 80 62" stroke-width="1.2"/>
                            <!-- Eyes -->
                            <circle cx="68" cy="54" r="1.5" fill="#047185" stroke="none"/>
                            <circle cx="82" cy="54" r="1.5" fill="#047185" stroke="none"/>
                            <!-- Wavy underline -->
                            <path d="M20 195 Q55 175 90 195 T160 195 T230 195 T300 195" stroke="#047185" stroke-width="2" fill="none" opacity="0.4"/>
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Right Side -->
            <div class="col-lg-7 col-md-12">
                <div class="why-win-right">
                    <div class="win-feature">
                        <div class="win-tag">Live Interactive Learning:</div>
                        <p>Not just recorded videos—real-time interaction with tutors to clear doubts instantly.</p>
                    </div>
                    <div class="win-feature">
                        <div class="win-tag">Expert Faculty:</div>
                        <p>Learn from the country's best educators specializing in competitive exams.</p>
                    </div>
                    <div class="win-feature">
                        <div class="win-tag">Personalized Attention:</div>
                        <p>Small group or private 1-on-1 sessions tailored to your child's learning pace.</p>
                    </div>
                    <div class="win-feature">
                        <div class="win-tag">Comprehensive Material:</div>
                        <p>Access to curated Study Material and specialized Test Series to build exam temperament.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Olympiad for Class 2 -->
<section class="olympiad-cards-section">
    <div class="container">
        <div class="class-bar" data-bs-toggle="collapse" data-bs-target="#class2Cards" aria-expanded="true">
            <h3>Olympiad for Class 2</h3>
            <span class="toggle-icon"><i class="bi bi-chevron-up"></i></span>
        </div>
        <div class="collapse show" id="class2Cards">
            <div class="cards-carousel-wrapper">
                <button class="carousel-arrow arrow-left" onclick="scrollClass2(-1)"><i class="bi bi-chevron-left"></i></button>
                <div class="cards-scroll-row" id="class2Scroll">
                    <!-- Math -->
                    <div class="olympiad-card-h">
                        <div class="card-left bg-teal">
                            <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Math Olympiad">
                        </div>
                        <div class="card-right">
                            <h4>Math Olympiad:</h4>
                            <div class="card-badges">
                                <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 2</span>
                            </div>
                            <ul class="card-specs">
                                <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                            </ul>
                            <div class="card-actions">
                                <a href="#" class="btn-enroll">Enroll Now</a>
                                <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- Science -->
                    <div class="olympiad-card-h">
                        <div class="card-left bg-gold">
                            <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Science Olympiad">
                        </div>
                        <div class="card-right">
                            <h4>Science Olympiad:</h4>
                            <div class="card-badges">
                                <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 2</span>
                            </div>
                            <ul class="card-specs">
                                <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                            </ul>
                            <div class="card-actions">
                                <a href="#" class="btn-enroll">Enroll Now</a>
                                <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- English -->
                    <div class="olympiad-card-h">
                        <div class="card-left bg-teal">
                            <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="English Olympiad">
                        </div>
                        <div class="card-right">
                            <h4>English Olympiad:</h4>
                            <div class="card-badges">
                                <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 2</span>
                            </div>
                            <ul class="card-specs">
                                <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                            </ul>
                            <div class="card-actions">
                                <a href="#" class="btn-enroll">Enroll Now</a>
                                <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- GK Olympiad -->
                    <div class="olympiad-card-h">
                        <div class="card-left bg-teal">
                            <img src="https://images.unsplash.com/photo-1457369804613-52c61a468e7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="GK Olympiad">
                        </div>
                        <div class="card-right">
                            <h4>GK Olympiad:</h4>
                            <div class="card-badges">
                                <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 2</span>
                            </div>
                            <ul class="card-specs">
                                <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                            </ul>
                            <div class="card-actions">
                                <a href="#" class="btn-enroll">Enroll Now</a>
                                <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- Cyber Olympiad -->
                    <div class="olympiad-card-h">
                        <div class="card-left bg-gold">
                            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Cyber Olympiad">
                        </div>
                        <div class="card-right">
                            <h4>Cyber Olympiad:</h4>
                            <div class="card-badges">
                                <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 2</span>
                            </div>
                            <ul class="card-specs">
                                <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                            </ul>
                            <div class="card-actions">
                                <a href="#" class="btn-enroll">Enroll Now</a>
                                <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-arrow arrow-right" onclick="scrollClass2(1)"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>

<!-- Accordion Classes 3-8 -->
<section class="accordion-section">
    <div class="container">
        <div class="accordion" id="olympiadAccordion">
            <!-- Class 3 -->
            <div class="accordion-item-custom">
                <div class="accordion-bar bar-light-teal" data-bs-toggle="collapse" data-bs-target="#class3" aria-expanded="false">
                    <h4>Olympiad for Class 3</h4>
                    <span class="acc-icon"><i class="bi bi-arrow-up-right-circle"></i></span>
                </div>
                <div id="class3" class="collapse" data-bs-parent="#olympiadAccordion">
                    <div class="accordion-body-custom">
                        <div class="cards-scroll-row">
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Math">
                                </div>
                                <div class="card-right">
                                    <h4>Math Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 3</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Science">
                                </div>
                                <div class="card-right">
                                    <h4>Science Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 3</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="English">
                                </div>
                                <div class="card-right">
                                    <h4>English Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 3</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- GK Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1457369804613-52c61a468e7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="GK Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>GK Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 3</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Cyber Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Cyber Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>Cyber Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 3</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Class 4 -->
            <div class="accordion-item-custom">
                <div class="accordion-bar bar-gold" data-bs-toggle="collapse" data-bs-target="#class4" aria-expanded="false">
                    <h4>Olympiad for Class 4</h4>
                    <span class="acc-icon"><i class="bi bi-arrow-up-right-circle"></i></span>
                </div>
                <div id="class4" class="collapse" data-bs-parent="#olympiadAccordion">
                    <div class="accordion-body-custom">
                        <div class="cards-scroll-row">
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Math">
                                </div>
                                <div class="card-right">
                                    <h4>Math Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 4</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Science">
                                </div>
                                <div class="card-right">
                                    <h4>Science Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 4</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="English">
                                </div>
                                <div class="card-right">
                                    <h4>English Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 4</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- GK Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1457369804613-52c61a468e7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="GK Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>GK Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 4</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Cyber Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Cyber Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>Cyber Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 4</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Class 5 -->
            <div class="accordion-item-custom">
                <div class="accordion-bar bar-teal" data-bs-toggle="collapse" data-bs-target="#class5" aria-expanded="false">
                    <h4>Olympiad for Class 5</h4>
                    <span class="acc-icon"><i class="bi bi-arrow-up-right-circle"></i></span>
                </div>
                <div id="class5" class="collapse" data-bs-parent="#olympiadAccordion">
                    <div class="accordion-body-custom">
                        <div class="cards-scroll-row">
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Math">
                                </div>
                                <div class="card-right">
                                    <h4>Math Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 5</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Science">
                                </div>
                                <div class="card-right">
                                    <h4>Science Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 5</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="English">
                                </div>
                                <div class="card-right">
                                    <h4>English Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 5</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- GK Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1457369804613-52c61a468e7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="GK Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>GK Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 5</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Cyber Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Cyber Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>Cyber Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 5</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Class 6 -->
            <div class="accordion-item-custom">
                <div class="accordion-bar bar-light-teal" data-bs-toggle="collapse" data-bs-target="#class6" aria-expanded="false">
                    <h4>Olympiad for Class 6</h4>
                    <span class="acc-icon"><i class="bi bi-arrow-up-right-circle"></i></span>
                </div>
                <div id="class6" class="collapse" data-bs-parent="#olympiadAccordion">
                    <div class="accordion-body-custom">
                        <div class="cards-scroll-row">
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Math">
                                </div>
                                <div class="card-right">
                                    <h4>Math Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 6</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Science">
                                </div>
                                <div class="card-right">
                                    <h4>Science Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 6</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="English">
                                </div>
                                <div class="card-right">
                                    <h4>English Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 6</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- GK Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1457369804613-52c61a468e7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="GK Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>GK Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 6</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Cyber Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Cyber Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>Cyber Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 6</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Class 7 -->
            <div class="accordion-item-custom">
                <div class="accordion-bar bar-gold" data-bs-toggle="collapse" data-bs-target="#class7" aria-expanded="false">
                    <h4>Olympiad for Class 7</h4>
                    <span class="acc-icon"><i class="bi bi-arrow-up-right-circle"></i></span>
                </div>
                <div id="class7" class="collapse" data-bs-parent="#olympiadAccordion">
                    <div class="accordion-body-custom">
                        <div class="cards-scroll-row">
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Math">
                                </div>
                                <div class="card-right">
                                    <h4>Math Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 7</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Science">
                                </div>
                                <div class="card-right">
                                    <h4>Science Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 7</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="English">
                                </div>
                                <div class="card-right">
                                    <h4>English Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 7</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- GK Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1457369804613-52c61a468e7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="GK Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>GK Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 7</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Cyber Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Cyber Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>Cyber Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 7</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Class 8 -->
            <div class="accordion-item-custom">
                <div class="accordion-bar bar-teal" data-bs-toggle="collapse" data-bs-target="#class8" aria-expanded="false">
                    <h4>Olympiad for Class 8</h4>
                    <span class="acc-icon"><i class="bi bi-arrow-up-right-circle"></i></span>
                </div>
                <div id="class8" class="collapse" data-bs-parent="#olympiadAccordion">
                    <div class="accordion-body-custom">
                        <div class="cards-scroll-row">
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Math">
                                </div>
                                <div class="card-right">
                                    <h4>Math Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 8</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Science">
                                </div>
                                <div class="card-right">
                                    <h4>Science Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 8</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="English">
                                </div>
                                <div class="card-right">
                                    <h4>English Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 8</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- GK Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-teal">
                                    <img src="https://images.unsplash.com/photo-1457369804613-52c61a468e7d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="GK Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>GK Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 8</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Cyber Olympiad -->
                            <div class="olympiad-card-h">
                                <div class="card-left bg-gold">
                                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Cyber Olympiad">
                                </div>
                                <div class="card-right">
                                    <h4>Cyber Olympiad:</h4>
                                    <div class="card-badges">
                                        <span class="cbadge"><i class="bi bi-person-fill"></i> Olympiad</span>
                                        <span class="cbadge"><i class="bi bi-mortarboard-fill"></i> Class 8</span>
                                    </div>
                                    <ul class="card-specs">
                                        <li><i class="bi bi-check-circle-fill"></i> 24 Session</li>
                                        <li><i class="bi bi-check-circle-fill"></i> 15 Mock test</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Study Material</li>
                                    </ul>
                                    <div class="card-actions">
                                        <a href="#" class="btn-enroll">Enroll Now</a>
                                        <div class="btn-subscribe">Subscription <span>2999/-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bottom CTA Section -->
<section class="courses-cta-section">
    <div class="cta-bg">
        <div class="cta-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <div class="cta-content">
                            <h2>Book a Free Trial Class</h2>
                            <p>Help your child take the first step towards Olympiad excellence. Our expert mentors will assess their strengths and create a personalized learning plan.</p>
                            <a href="#" class="btn-cta">Get Started <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 4% !important;"></section>

<script>
/* Carousel scroll for Class 2 cards */
function scrollClass2(dir) {
    var el = document.getElementById('class2Scroll');
    if (!el) return;
    var cardW = el.querySelector('.olympiad-card-h');
    var step = cardW ? (cardW.offsetWidth + 16) : 360;
    el.scrollBy({ left: dir * step, behavior: 'smooth' });
}

/* Accordion bar icon toggle */
document.addEventListener('DOMContentLoaded', function () {
    var bars = document.querySelectorAll('.accordion-bar, .class-bar');
    bars.forEach(function (bar) {
        bar.addEventListener('click', function () {
            var icon = bar.querySelector('.toggle-icon i, .acc-icon i');
            if (!icon) return;
            setTimeout(function () {
                var expanded = bar.getAttribute('aria-expanded') === 'true';
                if (bar.classList.contains('class-bar')) {
                    icon.className = expanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
                } else {
                    icon.className = expanded ? 'bi bi-arrow-down-circle' : 'bi bi-arrow-up-right-circle';
                }
            }, 10);
        });
    });
});
</script>
@endsection