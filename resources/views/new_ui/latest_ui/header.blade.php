<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<header>
    <!-- Topbar -->
            <div class="col-md-12">
                <div class="topbar">
                    <div class="container d-flex justify-content-between align-items-center">
                            <div class="chat-link">
                               <img  width="15%" src="{{ asset('images/new_ui/header/whatsapp.png') }}" alt="whatsapp"> Start Chat Now
                            </div>
                            <div class="contact-info">
                                <img width="8%" src="{{ asset('images/new_ui/header/phone.png') }}" alt="phone"> +91 70226 78333 , +91 92179 64222
                            </div>
                    </div>
            </div>
        </div>

    <!-- Navbar -->
        <div class="col-md-12">
            <div class="main-navbar">
                <div class="container navigation-area-top">
                            <nav class="navbar navbar-expand-lg">
                            <!-- Logo -->
                            <a class="navbar-brand" href="#">
                             <img src="{{ asset('images/new_ui/header/logo.png') }}" alt="Logo">
                            </a>
                
                            <!-- Mobile Toggle -->
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                
                            <!-- Menu -->
                            <div class="collapse navbar-collapse justify-content-center navigation-area" id="mainNavbar">
                
                                <ul class="navbar-nav">
                
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">About Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Courses</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Olympiad Exams</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Self Learning</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">How It Works</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Contact Us</a>
                                    </li>
                                </ul>
                            </div>
                
                            <!-- Buttons -->
                            <div class="auth-buttons">
                                <a href="#" class="btn register-btn">
                                    <img src="{{ asset('images/new_ui/header/login.png') }}" alt="login">
                                </a>
                            
                                <a href="#" class="btn login-btn">
                                    <img src="{{ asset('images/new_ui/header/register.png') }}" alt="register">
                                </a>
                            </div>
        
                        </nav>
                </div>
            </div>
        </div>
        
 

</header>