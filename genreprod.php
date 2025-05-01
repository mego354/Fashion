<?php
include 'component/header.php';
include 'component/navbar.php';
?>
    <main id="login-page" class="page-section active min-h-[93vh]">
<?php
require_once 'models/Product.php';
$product = new Product();

// 1. Top Rated Products
$topRatedProducts = $product->getTopRatedProducts(5);
// 2. Top New Products
$topRatedProducts = $product->getTopRatedProducts(5);

// 2. Get All Genres (you need a Genre class or manually query genres table)
require_once 'models/Genre.php';
$genre = new Genre();
$allGenres = $genre->readAll(); // assuming you have Genre class

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>

<h1>Top 5 Rated Products</h1>
<ul>
    <?php foreach ($topRatedProducts as $item): ?>
        <li><?php echo htmlspecialchars($item['name']); ?> - Rating: <?php echo $item['rating']; ?></li>
    <?php endforeach; ?>
</ul>

<hr>

<h1>Products by Genre</h1>
<?php foreach ($allGenres as $genreItem): ?>
    <h2><?php echo htmlspecialchars($genreItem['name']); ?></h2>
    <ul>
        <?php 
            $products = $product->getProductsByGenreSorted($genreItem['id']);
            foreach ($products as $prod): 
        ?>
            <li><?php echo htmlspecialchars($prod['name']); ?> - Rating: <?php echo $prod['rating']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>

</body>
</html>

    </main>
<?php include 'component/footer.php'; ?>

<script>      

</script>

</body>
</html>
