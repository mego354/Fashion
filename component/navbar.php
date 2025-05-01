<?php
require_once 'models/Genre.php';

// Get all genres for the navigation
$navGenreModel = new Genre();
$navGenres = $navGenreModel->readAll();
?>
    <!-- Theme Selector -->
    <div class="theme-selector dropdown dropdown-top dropdown-end">
        <label tabindex="0" class="btn btn-circle m-1">
            <i class="fas fa-palette"></i>
        </label>
        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
            <li><a onclick="changeTheme('light')">Light</a></li>
            <li><a onclick="changeTheme('dark')">Dark</a></li>
            <li><a onclick="changeTheme('cupcake')">Cupcake</a></li>
            <li><a onclick="changeTheme('bumblebee')">Bumblebee</a></li>
            <li><a onclick="changeTheme('emerald')">Emerald</a></li>
            <li><a onclick="changeTheme('corporate')">Corporate</a></li>
            <li><a onclick="changeTheme('synthwave')">Synthwave</a></li>
            <li><a onclick="changeTheme('valentine')">Valentine</a></li>
            <li><a onclick="changeTheme('halloween')">Halloween</a></li>
            <li><a onclick="changeTheme('garden')">Garden</a></li>
            <li><a onclick="changeTheme('forest')">Forest</a></li>
            <li><a onclick="changeTheme('lofi')">Lo-Fi</a></li>
            <li><a onclick="changeTheme('pastel')">Pastel</a></li>
            <li><a onclick="changeTheme('fantasy')">Fantasy</a></li>
            <li><a onclick="changeTheme('luxury')">Luxury</a></li>
        </ul>
    </div>

    <!-- Font Selector -->
    <div class="font-selector z-[100] fixed left-5 bottom-5 dropdown dropdown-top">
        <label tabindex="0" class="btn btn-circle m-1">
            <i class="fas fa-font"></i>
        </label>
        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
            <li><a onclick="changeFont('Poppins')">Poppins</a></li>
            <li><a onclick="changeFont('Roboto')">Roboto</a></li>
            <li><a onclick="changeFont('Lato')">Sans Serif</a></li>
        </ul>
    </div>

    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-md sticky top-0 z-50">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="index.php">Home</a></li>
                    <li>
                        <a>Categories</a>
                        <ul class="p-2">
                        <?php foreach ($navGenres as $genre): ?>
                            <li><a href="product_list.php?genres=<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name']) ?></a></li>
                        <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="product_list.php">Shop All</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost normal-case text-xl" href="index.php">
                <span class="text-primary">Fashion</span>Hub
            </a>
        </div>
        <div class="navbar-center sm:hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="index.php">Home</a></li>
                <?php foreach ($navGenres as $genre): ?>
                    <li><a href="product_list.php?genres=<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name']) ?></a></li>
                <?php endforeach; ?>

                <li><a href="product_list.php">Shop All</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <div class="form-control mr-2">
                <input type="text" placeholder="Search" class="input input-bordered w-auto hidden md:inline-block" />
            </div>
            <button class="btn btn-ghost btn-circle" onclick="toggleWishlist()">
                <div class="indicator">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="badge badge-sm indicator-item -top-1" id="wishlist-count">0</span>
                </div>
            </button>
            <button class="btn btn-ghost btn-circle mr-2" onclick="toggleCart()">
                <div class="indicator">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="badge badge-sm indicator-item -top-1" id="cart-count">0</span>
                </div>
            </button>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                    <div class="relative w-10 rounded-full bg-primary text-primary-content">
                      <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">JD</span>
                    </div>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <!-- <li><a onclick="navigateTo('profile')">Profile</a></li> -->
                    <li><a href="user_order.php">Orders</a></li>
                    <li><a href="#" id="logout-link">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>