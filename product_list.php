<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/Genre.php'; // Make sure you have a Genre model

// Initialize models
$productModel = new Product();
$genreModel = new Genre();

// Get all genres for filter
$allGenres = $genreModel->readAll();

// Get price range for dynamic filter
$priceRange = $productModel->getPriceRange();
$minPrice = floor($priceRange['min_price']);
$maxPrice = ceil($priceRange['max_price']);

// Handle filters
$filters = [
    'price_min' => isset($_GET['price_min']) ? (float)$_GET['price_min'] : $minPrice,
    'price_max' => isset($_GET['price_max']) ? (float)$_GET['price_max'] : $maxPrice,
    'genres' => isset($_GET['genres']) ? (array)$_GET['genres'] : [],
    'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'rating_high'
];

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 8;

// Fetch filtered products
$result = $productModel->getFilteredProducts($filters, $page, $perPage);
$products = $result['products'];
$totalProducts = $result['total'];
$totalPages = ceil($totalProducts / $perPage);

include 'component/header.php';
include 'component/navbar.php';
?>

<main class="page-section active min-h-[93vh]">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row">
            <!-- Filter Sidebar -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-4">
                <div class="bg-base-100 shadow-lg rounded-lg p-4 sticky top-24">
                    <h3 class="text-xl font-bold mb-4">Filters</h3>
                    <form id="filter-form" method="GET" action="product_list.php">
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">Price Range</h4>
                            <div class="flex flex-col space-y-2">
                                <div class="flex justify-between gap-2">
                                    <input type="number" name="price_min" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= htmlspecialchars($filters['price_min']) ?>" class="input input-bordered input-sm w-full" placeholder="Min" />
                                    <input type="number" name="price_max" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= htmlspecialchars($filters['price_max']) ?>" class="input input-bordered input-sm w-full" placeholder="Max" />
                                </div>
                                <div class="text-xs text-center">
                                    Price range: $<?= $minPrice ?> - $<?= $maxPrice ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categories/Genres Filter -->
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">Categories</h4>
                            <div class="flex flex-col gap-2 max-h-48 overflow-y-auto">
                                <?php foreach ($allGenres as $genre): ?>
                                    <div class="form-control">
                                        <label class="cursor-pointer flex items-center gap-2">
                                            <input type="checkbox" name="genres" value="<?= $genre['id'] ?>" class="checkbox checkbox-sm checkbox-primary" 
                                                <?= in_array($genre['id'], $filters['genres']) ? 'checked' : '' ?> />
                                            <span><?= htmlspecialchars($genre['name']) ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Hidden sort field to maintain sort when filtering -->
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($filters['sort']) ?>" id="sort-field">

                        <div class="flex justify-between mt-6">
                            <button type="button" class="btn btn-sm btn-outline" onclick="resetFilters()">Reset</button>
                            <button type="submit" class="btn btn-sm btn-primary">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="w-full md:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">All Products (<?= $totalProducts ?>)</h2>
                    <div class="flex gap-2 items-center">
                        <span class="text-sm">Sort by:</span>
                        <select class="select select-bordered select-sm" onchange="updateSort(this.value)">
                            <option value="name_asc" <?= $filters['sort'] === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                            <option value="name_desc" <?= $filters['sort'] === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                            <option value="rating_high" <?= $filters['sort'] === 'rating_high' ? 'selected' : '' ?>>Rating (High to Low)</option>
                            <!-- <option value="rating_low" <?= $filters['sort'] === 'rating_low' ? 'selected' : '' ?>>Rating (Low to High)</option> -->
                            <option value="price_low" <?= $filters['sort'] === 'price_low' ? 'selected' : '' ?>>Price (Low to High)</option>
                            <option value="price_high" <?= $filters['sort'] === 'price_high' ? 'selected' : '' ?>>Price (High to Low)</option>
                            <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest First</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card card bg-base-100 shadow-xl">
                            <figure class="px-4 pt-4 relative">
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded-xl h-48 w-full object-cover" />
                                <?php if (isset($product['old_price']) && $product['old_price'] > $product['price']): ?>
                                    <div class="badge badge-secondary absolute top-2 left-2">SALE</div>
                                <?php elseif (strtotime($product['created_at']) > strtotime('-30 days')): ?>
                                    <div class="badge badge-secondary absolute top-2 left-2">NEW</div>
                                <?php endif; ?>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title text-lg"><?= htmlspecialchars($product['name']) ?></h3>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-bold">$<?= number_format($product['price'], 2) ?></span>
                                        <?php if (isset($product['old_price']) && $product['old_price'] > $product['price']): ?>
                                            <span class="text-sm line-through opacity-50">$<?= number_format($product['old_price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="rating rating-sm">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" name="rating-<?= $product['id'] ?>" class="mask mask-star-2 bg-orange-400" <?= $i <= $product['rating'] ? 'checked' : '' ?> />
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="card-actions justify-between mt-2">
                                    <button class="btn btn-sm btn-outline" onclick="addToWishlist(<?= $product['id'] ?>)">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" onclick="addToCart(<?= $product['id'] ?>)">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- No Products Found Message -->
                <?php if (empty($products)): ?>
                <div class="text-center py-8">
                    <h3 class="text-xl font-semibold">No products found</h3>
                    <p class="mt-2">Try adjusting your filters or search criteria</p>
                </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="flex justify-center my-8">
                    <div class="btn-group">
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="btn btn-sm <?= $page <= 1 ? 'btn-disabled' : '' ?>">«</a>
                        
                        <?php 
                        // Calculate pages to show
                        $startPage = max(1, min($page - 2, $totalPages - 4));
                        $endPage = min($totalPages, max($page + 2, 5));
                        
                        for ($i = $startPage; $i <= $endPage; $i++): 
                        ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="btn btn-sm <?= $page === $i ? 'btn-active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="btn btn-sm <?= $page >= $totalPages ? 'btn-disabled' : '' ?>">»</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</main>

<?php include 'component/footer.php'; ?>

<script>

function resetFilters() {
    // Redirect to the product page with only the sort parameter preserved
    const currentSort = document.getElementById('sort-field').value;
    window.location.href = 'product_list.php?sort=' + currentSort;
}

function updateSort(sortValue) {
    // Update the hidden sort field
    document.getElementById('sort-field').value = sortValue;
    
    // Get current URL and parameters
    let currentUrl = new URL(window.location.href);
    let params = new URLSearchParams(currentUrl.search);
    
    // Update sort parameter
    params.set('sort', sortValue);
    
    // Redirect with updated parameters
    window.location.href = currentUrl.pathname + '?' + params.toString();
}

</script>
