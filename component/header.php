<?php
include 'init.php';
$basePath = rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/api/';
$baseUrl = $basePath;
?><!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $pageTitle = "Home"?><?php echo $pageTitle?></title>
    
    <!-- Load Font Awesome from CDN for spinner icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <!-- Using tailwindcss -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.5.0/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Using jQuery from CDN for simplicity -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }
        
        .page-section {
            animation: fadeIn 0.4s ease-in-out;
        }
        
        .page-section.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .theme-selector {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 100;
        }
        
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .deal-card {
            overflow: hidden;
            position: relative;
        }
        
        .deal-card img {
            transition: transform 0.5s;
        }
        
        .deal-card:hover img {
            transform: scale(1.05);
        }
        
        .carousel-container {
            overflow-x: hidden;
            position: relative;
        }
        
        .carousel {
            display: flex;
            transition: transform 0.5s ease;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Custom styles for tabs */
        .tab-active {
            border-bottom: 2px solid;
        }
        
        /* Responsive font sizes */
        @media (max-width: 640px) {
            h1 { font-size: 1.5rem; }
            h2 { font-size: 1.25rem; }
            p { font-size: 0.875rem; }
        }
        
        /* Animation for cart/wishlist badges */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .badge-pulse {
            animation: pulse 0.5s;
        }

        /* New notification styles */
        .notification-container {
            position: fixed;
            top: 4rem; /* 64px, below navbar */
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            z-index: 1000; /* Above navbar */
        }
        .notification-container .alert {
            transition: opacity 0.3s ease-in-out;
        }
        .notification-container .alert.opacity-0 {
            opacity: 0;
        }
        .notification-container .alert.opacity-100 {
            opacity: 1;
        }
        @media (max-width: 640px) {
            .notification-container {
                top: 3.5rem; /* Adjust for smaller screens */
                right: 0.5rem;
                left: 0.5rem; /* Full width with padding */
            }
            .notification-container .alert {
                max-width: none; /* Full width on small screens */
            }
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.93);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }
        
        .spinner {
            font-size: 50px;
            color: #3498db;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .hidden {
            opacity: 0;
            pointer-events: none;
        }
        
    </style>
</head>
<body>