<?php
include 'component/header.php';
include 'component/navbar.php';
?>
    <main id="login-page" class="page-section active min-h-[93vh]">
        <!-- Hero Banner -->
        <div class="hero min-h-[60vh] bg-base-200" style="background-image: url('https://marketplace.canva.com/EAFWecuevFk/1/0/800w/canva-grey-brown-minimalist-summer-season-collections-banner-landscape-VFmX9x2J3KE.jpg');">
            <div class="hero-overlay bg-opacity-60"></div>
            <div class="hero-content text-center text-neutral-content">
                <div class="max-w-md">
                    <h1 class="mb-5 text-5xl font-bold">Summer Collection 2023</h1>
                    <p class="mb-5">Discover our latest trends and styles for the summer season. Beat the heat with our cool, comfortable, and fashionable clothing.</p>
                    <button class="btn btn-primary" onclick="navigateTo('products')">Shop Now</button>
                </div>
            </div>
        </div>

        <!-- Deals Section -->
        <div class="container mx-auto px-4 py-10">
            <h2 class="text-3xl font-bold mb-6 text-center">Featured Deals</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Deal Card 1 -->
                <div class="deal-card rounded-lg overflow-hidden shadow-lg bg-base-100 relative">
                    <img src="https://www.verticalplus.co.uk/wp-content/uploads/2019/06/shutterstock_286324817.jpg" alt="Summer Sale" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Summer Sale</h3>
                        <p class="mb-2">Up to 50% off on summer collection</p>
                        <div class="relative">
                            <button class="btn btn-sm btn-circle btn-info absolute -top-10 right-0" onclick="toggleDealInfo('deal1')">
                                <i class="fas fa-info"></i>
                            </button>
                            <div id="deal1-info" class="hidden bg-white text-gray-800 p-4 rounded-lg shadow-lg absolute bottom-12 left-0 w-full z-10">
                                <p>Limited time offer on all summer items. Includes t-shirts, shorts, dresses, and beachwear. Valid until August 31st.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Deal Card 2 -->
                <div class="deal-card rounded-lg overflow-hidden shadow-lg bg-base-100 relative">
                    <img src="https://th.bing.com/th/id/R.61a300318351cda0db0e69c7130d5661?rik=OvYRgrbuTj%2f5cw&riu=http%3a%2f%2fwww.thebeachcompany.in%2fcdn%2fshop%2fcollections%2f2015-12_PP_WhatsNew_1200x1200.jpg%3fv%3d1681794565&ehk=2ufOeDg%2fV9uQC81YPgFoid7sBrMv%2fYu1VGU8WQr6Sd0%3d&risl=&pid=ImgRaw&r=0" alt="New Arrivals" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">New Arrivals</h3>
                        <p class="mb-2">Check out our latest collections</p>
                        <div class="relative">
                            <button class="btn btn-sm btn-circle btn-info absolute -top-10 right-0" onclick="toggleDealInfo('deal2')">
                                <i class="fas fa-info"></i>
                            </button>
                            <div id="deal2-info" class="hidden bg-white text-gray-800 p-4 rounded-lg shadow-lg absolute bottom-12 left-0 w-full z-10">
                                <p>Fresh styles just landed! Our new arrivals feature the latest fashion trends from top designers and exclusive in-house collections.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Deal Card 3 -->
                <div class="deal-card rounded-lg overflow-hidden shadow-lg bg-base-100 relative">
                    <img src="https://th.bing.com/th/id/R.81a524d84cd06d31404d5ff3e598208d?rik=v8WDv6VTFpAEKA&riu=http%3a%2f%2fpremiumcollections.in%2fcdn%2fshop%2ffiles%2fpremium_collections.png%3fheight%3d628%26pad_color%3dffffff%26v%3d1709701185%26width%3d1200&ehk=OdN9zVIZFlnUSKCSliFSaVri%2fcONFG%2bUf%2bMv0qkRSEk%3d&risl=&pid=ImgRaw&r=0" alt="Premium Collection" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Premium Collection</h3>
                        <p class="mb-2">Luxury items at special prices</p>
                        <div class="relative">
                            <button class="btn btn-sm btn-circle btn-info absolute -top-10 right-0" onclick="toggleDealInfo('deal3')">
                                <i class="fas fa-info"></i>
                            </button>
                            <div id="deal3-info" class="hidden bg-white text-gray-800 p-4 rounded-lg shadow-lg absolute bottom-12 left-0 w-full z-10">
                                <p>Discover our exclusive premium collection featuring high-quality fabrics and exceptional craftsmanship. Limited quantities available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once 'models/Product.php';
            $product = new Product();
            $topRatedProducts = $product->getTopRatedProducts(7);
            $getNewProducts = $product->getNewProducts(7);
        ?>

        <!-- Bestsellers Carousel -->
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-3xl font-bold mb-6">Bestsellers</h2>
            <div class="carousel-container">
                <div class="carousel">
                    <?php foreach ($topRatedProducts as $prod): ?>
                        <div class="carousel-item w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-2">
                            <div class="product-card card bg-base-100 shadow-xl">
                                <figure class="px-4 pt-4 relative">
                                    <img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="rounded-xl h-48 w-full object-cover" />
                                    <?php if ($prod['stock_quantity'] < 10): ?>
                                        <div class="badge badge-secondary absolute top-2 left-2">LOW STOCK</div>
                                    <?php endif; ?>
                                </figure>
                                <div class="card-body">
                                    <h3 class="card-title text-lg"><?= htmlspecialchars($prod['name']) ?></h3>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="font-bold">$<?= number_format($prod['price'], 2) ?></span>
                                        </div>
                                        <div class="rating rating-sm">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <input type="radio" name="rating-<?= $prod['id'] ?>" class="mask mask-star-2 bg-orange-400" <?= ($i <= $prod['rating']) ? 'checked' : '' ?> />
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="card-actions justify-between mt-2">
                                        <button class="btn btn-sm btn-outline" onclick="addToWishlist(<?= $prod['id'] ?>)">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="addToCart(<?= $prod['id'] ?>)">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="flex justify-center mt-4">
                    <button class="btn btn-circle mr-2" onclick="moveCarousel('prev')">❮</button>
                    <button class="btn btn-circle" onclick="moveCarousel('next')">❯</button>
                </div>
            </div>
        </div>
        
        <!-- New Arrivals Carousel -->
        <div class="container mx-auto px-4 py-8 bg-base-200">
            <h2 class="text-3xl font-bold mb-6">New Arrivals</h2>
            <div class="carousel-container">
                <div class="carousel">
                <?php foreach ($getNewProducts as $product): ?>
                    <div class="carousel-item w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-2">
                        <div class="product-card card bg-base-100 shadow-xl">
                            <figure class="px-4 pt-4">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded-xl h-48 w-full object-cover" />
                                <div class="badge badge-secondary absolute top-2 left-2">NEW</div>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title text-lg"><?= htmlspecialchars($product['name']) ?></h3>
                                
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-bold">$<?= number_format($product['price'], 2) ?></span>
                                    </div>
                                    <div class="rating rating-sm">
                                        <?php for($i = 1; $i <= 5; $i++) : ?>
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
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="flex justify-center mt-4">
                    <button class="btn btn-circle mr-2" onclick="moveCarousel('prev')">❮</button>
                    <button class="btn btn-circle" onclick="moveCarousel('next')">❯</button>
                </div>
            </div>
        </div>
        
    </main>
<?php include 'component/footer.php'; ?>

<script>  
    
    function moveCarousel(direction) {
        const carousels = document.querySelectorAll('.carousel');
        carousels.forEach(carousel => {
            const items = carousel.querySelectorAll('.carousel-item');
            const itemWidth = items[0].offsetWidth + 16; // Including padding
            let scrollPosition = carousel.scrollLeft;

            if (direction === 'next') {
                scrollPosition += itemWidth * 1; // Move 1 items
            } else {
                scrollPosition -= itemWidth * 1; // Move 1 items back
            }

            carousel.scrollTo({ left: scrollPosition, behavior: 'smooth' });
        });
    }

    function toggleDealInfo(dealId) {
        const infoDiv = document.getElementById(`${dealId}-info`);
        infoDiv.classList.toggle('hidden');
    }
</script>
</body>
</html>