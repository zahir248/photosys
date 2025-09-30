<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhotoSys - Professional Photo Management Platform</title>
    <meta name="description" content="Professional photo management platform with organization support, album management, and flexible sharing options. Built for photographers, teams, and businesses.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: #ffffff;
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            padding: 1rem 0;
            transform: translateY(0);
        }

        .navbar.navbar-hidden {
            transform: translateY(-100%);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--primary-color) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand img {
            height: 3.5rem;
            width: auto;
            object-fit: contain;
        }

        .navbar-nav .nav-link {
            color: var(--secondary-color) !important;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            min-height: 44px;
            display: flex;
            align-items: center;
            margin-right: 1rem;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(37, 99, 235, 0.1);
            transform: translateY(-1px);
        }

        .btn-nav {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-height: 44px;
            justify-content: center;
            margin-left: 0.5rem;
        }

        .btn-nav:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding-top: 100px; /* Adjusted padding for navbar height consistency */
        }
        
        .hero-section .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%232563eb" stop-opacity="0.1"/><stop offset="100%" stop-color="%232563eb" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23a)"/><circle cx="800" cy="800" r="400" fill="url(%23a)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-content .lead {
            font-size: 1.375rem;
            color: var(--secondary-color);
            font-weight: 400;
            margin-bottom: 2rem;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }

        .btn-hero {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1.125rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }

        .btn-hero:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        .btn-hero-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1.125rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-hero-outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .hero-stats {
            display: flex;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary-color);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .hero-visual {
            position: relative;
            z-index: 2;
            margin-top: 2rem;
            padding: 1rem;
        }

        .hero-visual .card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: all 0.3s ease;
            margin: 0 auto;
            max-width: 400px;
            padding: 1.5rem;
        }

        .hero-visual .card:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        /* Features Section */
        .features-section {
            padding: 6rem 0;
            background: white;
        }
        
        .features-section .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--secondary-color);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 1.5rem;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-color);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            opacity: 0.9;
        }

        .feature-icon i {
            position: relative;
            z-index: 2;
        }

        .feature-card h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--secondary-color);
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="white" stop-opacity="0.1"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23a)"/><circle cx="800" cy="800" r="400" fill="url(%23a)"/></svg>');
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .cta-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .cta-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 1.25rem 2.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 1.125rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
        }

        .btn-cta:hover {
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        /* Footer */
        .footer {
            background: white;
            color: var(--dark-color);
            padding: 3rem 0 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .footer .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .footer-brand img {
            height: 2.5rem;
            width: auto;
            object-fit: contain;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            border-top: 1px solid var(--border-color);
            padding-top: 2rem;
            margin-top: 2rem;
            text-align: center;
            color: var(--secondary-color);
        }

        /* Responsive Design */
        
        /* Mobile First - Base styles for mobile */
        .navbar-brand {
            font-size: 1.5rem;
        }
        
        .navbar-brand img {
            height: 3rem;
        }
        
        .hero-content h1 {
            font-size: 2.5rem;
            text-align: center;
        }
        
        .hero-content .lead {
            font-size: 1.125rem;
            text-align: center;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .hero-buttons {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
            justify-content: center;
        }
        
        .btn-hero, .btn-hero-outline {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            justify-content: center;
        }
        
        .hero-stats {
            justify-content: center;
            gap: 2rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .hero-visual .card {
            transform: none;
            margin-top: 2rem;
            padding: 1.25rem;
        }
        
        .hero-visual {
            margin-top: 2.5rem;
            padding: 1.5rem;
        }
        
        .feature-card {
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .feature-card h5 {
            font-size: 1.25rem;
        }
        
        .cta-title {
            font-size: 2rem;
        }
        
        .cta-subtitle {
            font-size: 1.125rem;
        }
        
        .btn-cta {
            padding: 1rem 2rem;
            font-size: 1rem;
        }
        
        .footer {
            padding: 2rem 0 1.5rem;
        }
        
        .footer-brand {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .footer-links {
            margin-bottom: 1.5rem;
        }
        
        .footer .row {
            text-align: center;
        }
        
        .footer .col-lg-2,
        .footer .col-lg-4 {
            margin-bottom: 2rem;
        }
        
        .footer .col-lg-2:last-child,
        .footer .col-lg-4:last-child {
            margin-bottom: 0;
        }
        
        /* Small Mobile (320px - 480px) */
        @media (max-width: 480px) {
            .navbar {
                padding: 0.5rem 0;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .navbar-brand img {
                height: 2.5rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
                min-height: 48px;
                margin-bottom: 0.25rem;
            }
            
            .btn-nav {
                padding: 0.75rem 1.25rem;
                font-size: 0.9rem;
                min-height: 48px;
                width: 100%;
                margin-top: 0.5rem;
            }
            
            .navbar-collapse {
                padding-top: 1rem;
            }
            
            .navbar-nav {
                text-align: center;
            }
            
            .hero-section {
                padding-top: 100px;
                min-height: 90vh;
            }
            
            .hero-section .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .hero-content h1 {
                font-size: 2rem;
                line-height: 1.2;
                text-align: center;
            }
            
            .hero-content .lead {
                font-size: 1rem;
                margin-bottom: 1.5rem;
                text-align: center;
            }
            
            .hero-buttons {
                margin-bottom: 2rem;
                justify-content: center;
            }
            
            .btn-hero, .btn-hero-outline {
                padding: 0.75rem 1.25rem;
                font-size: 0.9rem;
            }
            
            .hero-stats {
                gap: 1.5rem;
            }
            
            .stat-number {
                font-size: 1.75rem;
            }
            
            .stat-label {
                font-size: 0.75rem;
            }
            
            .section-title {
                font-size: 1.75rem;
            }
            
            .section-subtitle {
                font-size: 1rem;
            }
            
            .feature-card {
                padding: 1.25rem;
                margin-bottom: 1.5rem;
            }
            
            .features-section {
                padding: 3rem 0;
            }
            
            .features-section .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .section-header {
                margin-bottom: 2.5rem;
            }
            
            .feature-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            .feature-card h5 {
                font-size: 1.125rem;
            }
            
            .cta-title {
                font-size: 1.75rem;
            }
            
            .cta-subtitle {
                font-size: 1rem;
            }
            
            .btn-cta {
                padding: 0.875rem 1.75rem;
                font-size: 0.9rem;
                width: 100%;
                max-width: 280px;
            }
            
            .hero-visual .card {
                padding: 1rem;
            }
            
            .hero-visual {
                margin-top: 2rem;
                padding: 1rem;
            }
            
            .cta-section {
                padding: 3rem 0;
            }
            
            .cta-section .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .footer {
                padding: 2rem 0 1.5rem;
            }
            
            .footer .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .footer .col-lg-2,
            .footer .col-lg-4 {
                margin-bottom: 1.5rem;
            }
        }
        
        /* Tablet Portrait (481px - 768px) */
        @media (min-width: 481px) and (max-width: 768px) {
            .hero-section {
                padding-top: 100px;
            }
            .hero-content h1 {
                font-size: 3rem;
            }
            
            .hero-content .lead {
                font-size: 1.25rem;
            }
            
            .section-title {
                font-size: 2.25rem;
            }
            
            .hero-buttons {
                flex-direction: row;
                justify-content: center;
                gap: 1rem;
            }
            
            .btn-hero, .btn-hero-outline {
                flex: 1;
                max-width: 200px;
            }
            
            .hero-stats {
                gap: 2.5rem;
            }
            
            .stat-number {
                font-size: 2.25rem;
            }
            
            .feature-card {
                padding: 2rem;
            }
            
            .feature-icon {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
            }
            
            .cta-title {
                font-size: 2.5rem;
            }
            
            .cta-subtitle {
                font-size: 1.125rem;
            }
            
            .features-section {
                padding: 4rem 0;
            }
            
            .cta-section {
                padding: 4rem 0;
            }
            
            .footer {
                padding: 2.5rem 0 2rem;
            }
            
            .hero-visual .card {
                padding: 1.5rem;
            }
            
            .hero-visual {
                margin-top: 2.5rem;
                padding: 1.25rem;
            }
        }
        
        /* Tablet Landscape (769px - 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .hero-content h1 {
                font-size: 3.5rem;
            }
            
            .hero-content .lead {
                font-size: 1.375rem;
            }
            
            .section-title {
                font-size: 2.75rem;
            }
            
            .hero-buttons {
                flex-direction: row;
                gap: 1.25rem;
            }
            
            .hero-stats {
                gap: 3rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .hero-visual .card {
                transform: perspective(1000px) rotateY(-3deg) rotateX(3deg);
            }
            
            .features-section {
                padding: 5rem 0;
            }
            
            .cta-section {
                padding: 5rem 0;
            }
            
            .footer {
                padding: 3rem 0 2rem;
            }
            
            .hero-visual .card {
                padding: 1.5rem;
            }
            
            .hero-visual {
                margin-top: 3rem;
                padding: 1.5rem;
            }
        }
        
        /* Large Desktop (1025px+) - Original styles maintained */
        @media (min-width: 1025px) {
            .hero-content h1 {
                font-size: 4rem;
            }
            
            .hero-content .lead {
                font-size: 1.375rem;
            }
            
            .section-title {
                font-size: 3rem;
            }
            
            .hero-buttons {
                flex-direction: row;
                gap: 1rem;
            }
            
            .hero-stats {
                gap: 3rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .hero-visual .card {
                transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
    </style>
    </head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="PhotoSys Logo">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    @auth
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="btn-nav" href="{{ route('dashboard') }}">
                            <i class="bi bi-house"></i> Go to Dashboard
                        </a>
                    @else
                        <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                        <a class="btn-nav" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="animate-fade-in-up">Professional Photo Management Made Simple</h1>
                    <p class="lead animate-fade-in-up animate-delay-1">
                        The most powerful and intuitive photo management platform for photographers, 
                        teams, and businesses. Organize, share, and collaborate on your visual content 
                        with enterprise-grade security and performance.
                    </p>
                    <div class="hero-buttons animate-fade-in-up animate-delay-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-hero">
                                <i class="bi bi-house"></i> Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-hero">
                                <i class="bi bi-rocket-takeoff"></i> Start Free Trial
                            </a>
                            <a href="{{ route('login') }}" class="btn-hero-outline">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 hero-visual">
                    <div class="card animate-fade-in-up animate-delay-2">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-camera text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">PhotoSys Dashboard</h6>
                                    <small class="text-muted">Professional Photo Management</small>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="bg-light rounded p-3 text-center">
                                        <i class="bi bi-images text-primary fs-4"></i>
                                        <div class="small fw-bold mt-1">1,247</div>
                                        <div class="small text-muted">Photos</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light rounded p-3 text-center">
                                        <i class="bi bi-folder text-success fs-4"></i>
                                        <div class="small fw-bold mt-1">23</div>
                                        <div class="small text-muted">Albums</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light rounded p-3 text-center">
                                        <i class="bi bi-people text-info fs-4"></i>
                                        <div class="small fw-bold mt-1">5</div>
                                        <div class="small text-muted">Teams</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose PhotoSys?</h2>
                <p class="section-subtitle">
                    Built for professionals who demand the best in photo management, 
                    organization, and collaboration tools.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5>Team Collaboration</h5>
                        <p>
                            Create organizations and invite team members to collaborate on photo projects. 
                            Set permissions, assign roles, and work together seamlessly.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-folder-fill"></i>
                        </div>
                        <h5>Smart Organization</h5>
                        <p>
                            Organize photos into albums with intelligent categorization. 
                            Use tags, metadata, and AI-powered suggestions for effortless organization.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5>Enterprise Security</h5>
                        <p>
                            Bank-level security with end-to-end encryption, secure sharing links, 
                            and comprehensive access controls to protect your valuable content.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <h5>Cloud Storage</h5>
                        <p>
                            Unlimited cloud storage with automatic backup and sync. 
                            Access your photos from anywhere, on any device, at any time.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5>Analytics & Insights</h5>
                        <p>
                            Detailed analytics on photo usage, storage consumption, and team activity. 
                            Make data-driven decisions about your visual content strategy.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <h5>Lightning Fast</h5>
                        <p>
                            Optimized for speed with CDN delivery, smart caching, and responsive design. 
                            Upload, view, and share photos in milliseconds.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Transform Your Photo Management?</h2>
                <p class="cta-subtitle">
                    Join thousands of professionals who trust PhotoSys for their photo management needs. 
                    Start your free trial today and experience the difference.
                </p>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-cta">
                        <i class="bi bi-house"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-cta">
                        <i class="bi bi-rocket-takeoff"></i> Start Free Trial
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <a href="/" class="footer-brand">
                        <img src="{{ asset('images/logo.png') }}" alt="PhotoSys Logo">
                    </a>
                    <p class="text-muted">
                        Professional photo management platform built for photographers, 
                        teams, and businesses who demand the best.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Product</h6>
                    <ul class="footer-links">
                        <li><a href="#">Features</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Integrations</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="footer-links">
                        <li><a href="#">About</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Status</a></li>
                        <li><a href="#">Community</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Legal</h6>
                    <ul class="footer-links">
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Terms</a></li>
                        <li><a href="#">Security</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 PhotoSys. All rights reserved. Built with Laravel and ❤️</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Navbar Scroll Hide/Show Script -->
    <script>
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');
        const scrollThreshold = 100; // Minimum scroll distance to trigger hide/show
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
                // Scrolling down - hide navbar
                navbar.classList.add('navbar-hidden');
            } else {
                // Scrolling up - show navbar
                navbar.classList.remove('navbar-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    </script>
    </body>
</html>
