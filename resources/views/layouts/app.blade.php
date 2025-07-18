<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restaurant Menu') }}</title>
    
    <!-- Preload important resources -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" as="style">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Global Preloader Styles */
        #global-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        
        #global-preloader.dark {
            background: #111827;
        }
        
        /* Fun food-themed spinner */
        .preloader-spinner {
            width: 60px;
            height: 60px;
            position: relative;
            animation: bounce 1s infinite ease-in-out;
        }
        
        .preloader-spinner:before {
            content: "🍽️";
            font-size: 60px;
            position: absolute;
            animation: spin 1.5s infinite linear;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.2); }
            100% { transform: rotate(360deg) scale(1); }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        /* Menu item animations */
        .menu-item {
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        /* Smooth transition for page content */
        .page-content {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        /* Category tabs animation */
        .category-tab {
            transition: all 0.2s ease;
        }
        
        .category-tab:hover, .category-tab.active {
            transform: scale(1.05);
        }
        
        /* Floating food icons animation */
        .floating-food {
            position: absolute;
            opacity: 0.1;
            animation: float 15s infinite linear;
            z-index: -1;
        }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-50px) rotate(180deg); }
            100% { transform: translateY(0) rotate(360deg); }
        }
    </style>
</head>

<body class="font-sans antialiased bg-white text-gray-800">
    <!-- Global Preloader -->
    <div id="global-preloader">
        <div class="preloader-spinner"></div>
    </div>

    <!-- Floating decorative food icons -->
    <div class="floating-food" style="top:20%; left:10%; font-size:40px;">🍕</div>
    <div class="floating-food" style="top:70%; left:85%; font-size:50px; animation-delay:2s">🍔</div>
    <div class="floating-food" style="top:40%; left:75%; font-size:30px; animation-delay:4s">🍣</div>
    <div class="floating-food" style="top:80%; left:15%; font-size:45px; animation-delay:6s">🍜</div>

    <div class="min-h-screen bg-gray-50 page-content">
        {{-- @include('layouts.navigation') --}}

        <!-- Page Content -->
        <main class="container mx-auto px-4 py-8">
            {{ $slot }}
        </main>
        
        <script>
            // Hide preloader when everything is loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const preloader = document.getElementById('global-preloader');
                    
                    if (preloader) {
                        preloader.style.opacity = '0';
                        // Remove preloader after fade out
                        setTimeout(function() {
                            preloader.style.display = 'none';
                        }, 500);
                    }
                }, 500); // Minimum show time
            });
            
            // Fallback in case load event doesn't fire
            setTimeout(function() {
                const preloader = document.getElementById('global-preloader');
                
                if (preloader && preloader.style.display !== 'none') {
                    preloader.style.opacity = '0';
                    setTimeout(() => preloader.style.display = 'none', 500);
                }
            }, 3000); // Maximum show time
            
            // Add animation to menu items when they come into view
            document.addEventListener('DOMContentLoaded', function() {
                const menuItems = document.querySelectorAll('.menu-item');
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-pop');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });
                
                menuItems.forEach(item => observer.observe(item));
            });
        </script>
    </div>
</body>
</html>