# E-Commerce PHP Web Application

## Overview
This is a PHP-based e-commerce web application built with MySQL for the database. It provides a user-friendly interface for online shopping with features like product listings, cart, wishlist, checkout, and more. The app supports multiple themes using DaisyUI and custom fonts for a modern look and feel.

## Features
- **Themes & Styling**: Multiple themes using DaisyUI with customizable fonts for a visually appealing experience.
- **Product Listings**:
  - Filter products by category and price range.
  - Sort products by name, price, or rating.
  - Homepage displays newest arrivals and bestsellers.
- **Wishlist**: Users can add products to their wishlist for future purchases.
- **Cart**: Add, update, or remove products in the shopping cart.
- **Checkout**:
  - CRUD operations for addresses and credit cards.
  - Secure checkout process for completing purchases.

## File Structure
```
/api/                  - API endpoints for backend functionality
/component/            - Reusable UI components
/footer.php            - Footer component for the app
/header.php            - Header component for the app
/init.php              - Initialization script for the app
/navbar.php            - Navigation bar component
/config/               - Configuration files
  database.php         - Database connection settings
/models/               - Data models for the app
  Address.php          - Model for handling addresses
  Cart.php             - Model for cart functionality
  CreditCard.php       - Model for credit card management
  Genre.php            - Model for product categories/genres
  Order.php            - Model for order management
  Product.php          - Model for product data
  User.php             - Model for user data
  Wishlist.php         - Model for wishlist functionality
/updates/              - Update scripts (if any)
/checkout.php          - Checkout page
/genreproduct.php      - Page for genre-based product listings
/index.php             - Homepage of the app
/login.php             - Login page
/product_list.php      - Product listing page
/products.php          - Product management page
/schema.sql            - SQL script for setting up the database
/user_order.php        - User order history page
/README.md             - This file
```

## Setup Instructions
Follow these steps to set up and run the e-commerce web app locally using XAMPP.

### Prerequisites
- XAMPP installed on your system (includes Apache and MySQL)
- A web browser (e.g., Chrome, Firefox)

### Steps to Run the App
1. **Install XAMPP and Start Services**:
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Start the Apache and MySQL services from the XAMPP control panel

2. **Clone the Project**:
   - Copy the project folder (FASHION-PHP) to the `htdocs` directory in your XAMPP installation (e.g., `C:\xampp\htdocs\`)

3. **Edit the Database Configuration**:
   - Open the `config/database.php` file
   - Update the database credentials as needed. By default, it uses:
     ```
     Host: localhost
     Database Name: ecommerce
     Username: root
     Password:  (update this if your MySQL password is different)
     ```
   - Save the changes

4. **Set Up the Database**:
   - Open phpMyAdmin by navigating to `http://localhost/phpmyadmin` in your browser
   - Create a new database named `ecommerce`
   - Import the `schema.sql` file:
     1. In phpMyAdmin, select the `ecommerce` database
     2. Go to the "Import" tab
     3. Choose the `schema.sql` file from the project folder
     4. Click "Go" to execute the script
   - This will create the necessary tables (users, genres, products) and set up the database structure

5. **Run the Application**:
   - Open your browser and navigate to `http://localhost/FASHION-PHP`
   - The homepage (`index.php`) will load, displaying the newest products and bestsellers
   - Use the navigation bar to explore product listings, add items to your cart or wishlist, and proceed to checkout

## Usage
- Browse products on the homepage or use the product listing page to filter by category or price range
- Sort products by name, price, or rating
- Add products to your cart or wishlist
- Proceed to checkout to manage addresses, credit cards, and complete your purchase
- Switch between DaisyUI themes to customize the app's appearance

## Notes
- Ensure Apache and MySQL are running in XAMPP while using the app
- If you encounter database connection issues, double-check the credentials in `config/database.php`