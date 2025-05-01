<footer class="bg-base-200 text-base-content mt-2">
      <div class="container mx-auto px-4 py-10">
          <div class="footer">
              <div>
                  <span class="footer-title">FASHIONHUB</span>
                  <p class="max-w-xs mt-2">Your one-stop destination for trendy, high-quality clothing and accessories for men, women, and kids.</p>
                  <div class="flex mt-4 gap-4">
                      <a class="btn btn-circle btn-outline">
                          <i class="fab fa-facebook-f"></i>
                      </a>
                      <a class="btn btn-circle btn-outline">
                          <i class="fab fa-twitter"></i>
                      </a>
                      <a class="btn btn-circle btn-outline">
                          <i class="fab fa-instagram"></i>
                      </a>
                      <a class="btn btn-circle btn-outline">
                          <i class="fab fa-pinterest"></i>
                      </a>
                  </div>
              </div>
              
              <div>
                  <span class="footer-title">Company</span>
                  <a class="link link-hover">About Us</a>
                  <a class="link link-hover">Careers</a>
                  <a class="link link-hover">Store Locations</a>
                  <a class="link link-hover">Our Blog</a>
                  <a class="link link-hover">Reviews</a>
              </div>
              
              <div>
                  <span class="footer-title">Help & Support</span>
                  <a class="link link-hover">FAQ</a>
                  <a class="link link-hover">Shipping Info</a>
                  <a class="link link-hover">Returns Policy</a>
                  <a class="link link-hover">Track Order</a>
                  <a class="link link-hover">Contact Us</a>
              </div>
              
              <div>
                  <span class="footer-title">Newsletter</span>
                  <div class="form-control w-80">
                      <label class="label">
                          <span class="label-text">Stay updated with new arrivals and exclusive offers</span>
                      </label>
                      <div class="relative">
                          <input type="text" placeholder="Your email address" class="input input-bordered w-full pr-16" />
                          <button class="btn btn-primary absolute top-0 right-0 rounded-l-none">Subscribe</button>
                      </div>
                  </div>
              </div>
          </div>
          
          <div class="border-t border-base-300 mt-10 pt-6">
              <div class="flex flex-col md:flex-row justify-between items-center">
                  <p>© 2023 FashionHub. All rights reserved.</p>
                  <div class="flex gap-6 mt-4 md:mt-0">
                      <a class="link link-hover">Privacy Policy</a>
                      <a class="link link-hover">Terms of Service</a>
                      <a class="link link-hover">Cookie Policy</a>
                  </div>
              </div>
          </div>
      </div>
</footer>
<!-- Cart and Wishlist Modal (handled by JavaScript) -->
<div id="loader" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="loading loading-spinner loading-lg"></div>
</div>

<!-- Login Popup Modal -->
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Login Required</h3>
        <p class="mb-6">You need to be logged in to add items to your wishlist or cart. Please log in or create an account to continue.</p>
        <div class="flex justify-end gap-2">
            <button class="btn btn-sm btn-outline" onclick="closeLoginModal()">Close</button>
            <a href="login.php" class="btn btn-sm btn-primary">Log In</a>
            <a href="register.php" class="btn btn-sm btn-secondary">Sign Up</a>
        </div>
    </div>
</div>

<!--                           theme                           -->
<script>
const BASE_URL = '<?php echo $baseUrl; ?>';

// Theme switcher
function changeTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

// Font switcher
function changeFont(fontFamily) {
    document.body.style.fontFamily = `'${fontFamily}', sans-serif`;
    localStorage.setItem('font', fontFamily);
}

// Apply saved theme and font on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    const savedFont = localStorage.getItem('font') || 'Poppins';
    changeTheme(savedTheme);
    changeFont(savedFont);
});    
</script>

<!--                           products                           -->
<script>
// Helper function to fetch product details for logged-in users
async function fetchProductDetails(productId) {
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    if (userId) {
        try {
            const response = await fetch(`${BASE_URL}product.php?id=${productId}`);
            const result = await response.json();
            if (result.success && result.data) {
                return result.data;
            }
            return { name: 'Unknown Product', price: 0, image: '' };
        } catch (error) {
            console.error('Error fetching product details:', error);
            return { name: 'Unknown Product', price: 0, image: '' };
        }
    } else {
        return { name: 'Unknown Product', price: 0, image: '' };
    }
}    
</script>

<!--                           cart                           -->
<script>


// Initialize cart from localStorage
let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

// Update badge counts on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
});

// Check if user is logged in
function isLoggedIn() {
    <?php if (isset($_SESSION['user_id'])): ?>
    return true;
    <?php else: ?>
    return false;
    <?php endif; ?>
}

// Show login modal
function showLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
}

// Close login modal
function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
}

// Cart functions
async function addToCart(productId, quantity = 1) {
    if (!isLoggedIn()) {
        showLoginModal();
        return;
    }
    document.getElementById('loader').classList.remove('hidden');
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    try {
        const response = await fetch(`${BASE_URL}cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, product_id: productId, quantity })
        });
        const result = await response.json();
        if (result.success) {
            updateCartCount();
            const product = await fetchProductDetails(productId);
            showNotification(`${product.name} added to cart!`);
        } else {
            showNotification('Error adding to cart: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('An error occurred while adding to cart.', 'error');
    } finally {
        setTimeout(() => document.getElementById('loader').classList.add('hidden'), 500);
    }
}

async function removeFromCart(productId) {
    document.getElementById('loader').classList.remove('hidden');
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    try {
        const response = await fetch(`${BASE_URL}cart.php`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, product_id: productId })
        });
        const result = await response.json();
        if (result.success) {
            updateCartCount();
        } else {
            showNotification('Error removing from cart: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        showNotification('An error occurred while removing from cart.', 'error');
    } finally {
        setTimeout(() => {
            toggleCart();
            document.getElementById('loader').classList.add('hidden');
        }, 500);
    }
}

async function updateCartItemQuantity(productId, newQuantity) {
    document.getElementById('loader').classList.remove('hidden');
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    if (newQuantity < 1) return;
    try {
        if (userId) {
            const response = await fetch(`${BASE_URL}cart.php`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, product_id: productId, quantity: newQuantity })
            });
            const result = await response.json();
            if (result.success) {
                updateCartCount();
            } else {
                showNotification('Error updating cart: ' + result.message, 'error');
            }
        } else {
            const item = cartItems.find(item => item.id === productId);
            if (item) {
                item.quantity = newQuantity;
                saveCart();
                updateCartCount();
            }
        }
    } catch (error) {
        console.error('Error updating cart:', error);
        showNotification('An error occurred while updating cart.', 'error');
    } finally {
        setTimeout(() => {
            toggleCart();
            document.getElementById('loader').classList.add('hidden');
        }, 500);
    }
}

function saveCart() {
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
}

async function updateCartCount() {
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    let totalItems = 0;
    if (userId) {
        try {
            const response = await fetch(`${BASE_URL}cart.php?user_id=${userId}`);
            const result = await response.json();
            if (result.success) {
                totalItems = result.data.reduce((sum, item) => sum + item.quantity, 0);
            }
        } catch (error) {
            console.error('Error fetching cart count:', error);
        }
    } else {
        totalItems = cartItems.reduce((total, item) => total + item.quantity, 0);
    }
    const badge = document.getElementById('cart-count');
    badge.textContent = totalItems;
    badge.classList.add('badge-pulse');
    setTimeout(() => badge.classList.remove('badge-pulse'), 500);
}

function toggleCart() {
    const existingModal = document.getElementById('cart-modal');
    if (existingModal) {
        existingModal.remove();
    }
    createCartModal();
}

async function createCartModal() {
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    let items = [];
    if (userId) {
        try {
            const response = await fetch(`${BASE_URL}cart.php?user_id=${userId}`);
            const result = await response.json();
            if (result.success) {
                items = result.data;
            }
        } catch (error) {
            console.error('Error fetching cart items:', error);
        }
    } else {
        items = cartItems;
    }

    const modal = document.createElement('div');
    modal.id = 'cart-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-end';
    modal.innerHTML = `
        <div class="bg-base-100 w-full max-w-md h-full overflow-y-auto">
            <div class="p-4 border-b border-base-300 flex justify-between items-center">
                <h3 class="text-xl font-bold">Your Cart (${items.reduce((total, item) => total + item.quantity, 0)})</h3>
                <button onclick="document.getElementById('cart-modal').classList.add('hidden')" class="btn btn-sm btn-circle">
                    ×
                </button>
            </div>
            
            <div class="p-4">
                ${items.length === 0 ? 
                    '<p class="text-center py-8">Your cart is empty</p>' : 
                    items.map(item => `
                        <div class="flex items-center py-4 border-b border-base-200">
                            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                            <div class="ml-4 flex-1">
                                <h4 class="font-medium">${item.name}</h4>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="font-bold">$${(item.price * item.quantity).toFixed(2)}</span>
                                    <div class="join">
                                        <button class="join-item btn btn-xs" onclick="updateCartItemQuantity(${item.id}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                                        <button class="join-item btn btn-xs">${item.quantity}</button>
                                        <button class="join-item btn btn-xs" onclick="updateCartItemQuantity(${item.id}, ${item.quantity + 1})">+</button>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-ghost ml-2" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `).join('')}
            </div>
            
            ${items.length > 0 ? `
                <div class="p-4 border-t border-base-300 sticky bottom-0 bg-base-100">
                    <div class="flex justify-between mb-4">
                        <span class="font-bold">Total:</span>
                        <span class="font-bold">$${items.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2)}</span>
                    </div>
                    <button class="btn btn-primary w-full" onclick="checkout()">
                        Proceed to Checkout
                    </button>
                </div>
            ` : ''}
        </div>
    `;
    
    document.body.appendChild(modal);
}

function checkout() {
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    if (!userId) {
        if (confirm('Please log in to proceed to checkout. Log in now?')) {
            window.location.href = window.location.origin + window.location.pathname.replace(/[^/]*$/, 'login.php');
        }
    } else {
        window.location.href = window.location.origin + window.location.pathname.replace(/[^/]*$/, 'checkout.php');
    }
}

// Sync guest user cart on login
async function syncGuestCart(userId) {
    let syncSuccess = true;
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    for (const item of cartItems) {
        try {
            const response = await fetch(`${BASE_URL}cart.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, product_id: item.id, quantity: item.quantity })
            });
            const result = await response.json();
            if (!result.success) {
                syncSuccess = false;
                console.error('Failed to sync cart item:', item.id, result.message);
            }
        } catch (error) {
            syncSuccess = false;
            console.error('Error syncing cart item:', error);
        }
    }
    localStorage.removeItem('cartItems');
    updateCartCount();
    if (!syncSuccess) {
        showNotification('Some cart items could not be synced to your account.', 'error');
    }
}
</script>

<!--                           wishlist                           -->
<script>

// Initialize wishlist from localStorage
let wishlistItems = JSON.parse(localStorage.getItem('wishlistItems')) || [];

// Update badge counts on page load
document.addEventListener('DOMContentLoaded', () => {
    updateWishlistCount();
});

// Wishlist functions
async function addToWishlist(productId) {
    if (!isLoggedIn()) {
        showLoginModal();
        return;
    }
    document.getElementById('loader').classList.remove('hidden');
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    try {
        const response = await fetch(`${BASE_URL}wishlist.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, product_id: productId })
        });
        const result = await response.json();
        if (result.success) {
            updateWishlistCount();
            const product = await fetchProductDetails(productId);
            showNotification(`${product.name} added to wishlist!`);
        } else {
            showNotification('Failed to add to wishlist: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error adding to wishlist:', error);
        showNotification('An error occurred while adding to wishlist.', 'error');
    } finally {
        setTimeout(() => document.getElementById('loader').classList.add('hidden'), 500);
    }
}

async function removeFromWishlist(productId) {
    document.getElementById('loader').classList.remove('hidden');
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    try {
        if (userId) {
            const response = await fetch(`${BASE_URL}wishlist.php`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, product_id: productId })
            });
            const result = await response.json();
            if (result.success) {
                updateWishlistCount();
            } else {
                showNotification('Error removing from wishlist: ' + result.message, 'error');
            }
        } else {
            wishlistItems = wishlistItems.filter(item => item.id !== productId);
            saveWishlist();
            updateWishlistCount();
        }
    } catch (error) {
        console.error('Error removing from wishlist:', error);
        showNotification('An error occurred while removing from wishlist.', 'error');
    } finally {
        setTimeout(() => {
            toggleWishlist();
            document.getElementById('loader').classList.add('hidden');
        }, 500);
    }
}

function saveWishlist() {
    localStorage.setItem('wishlistItems', JSON.stringify(wishlistItems));
}

async function updateWishlistCount() {
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    let totalItems = 0;
    if (userId) {
        try {
            const response = await fetch(`${BASE_URL}wishlist.php?user_id=${userId}`);
            const result = await response.json();
            if (result.success) {
                totalItems = result.data.length;
            }
        } catch (error) {
            console.error('Error fetching wishlist count:', error);
        }
    } else {
        totalItems = wishlistItems.length;
    }
    const badge = document.getElementById('wishlist-count');
    badge.textContent = totalItems;
    badge.classList.add('badge-pulse');
    setTimeout(() => badge.classList.remove('badge-pulse'), 500);
}

function toggleWishlist() {
    const existingModal = document.getElementById('wishlist-modal');
    if (existingModal) {
        existingModal.remove();
    }
    createWishlistModal();
}

async function createWishlistModal() {
    const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';
    let items = [];
    if (userId) {
        try {
            const response = await fetch(`${BASE_URL}wishlist.php?user_id=${userId}`);
            const result = await response.json();
            if (result.success) {
                items = result.data;
            }
        } catch (error) {
            console.error('Error fetching wishlist items:', error);
        }
    } else {
        items = wishlistItems;
    }

    const modal = document.createElement('div');
    modal.id = 'wishlist-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-end';
    modal.innerHTML = `
        <div class="bg-base-100 w-full max-w-md h-full overflow-y-auto">
            <div class="p-4 border-b border-base-300 flex justify-between items-center">
                <h3 class="text-xl font-bold">Your Wishlist (${items.length})</h3>
                <button onclick="document.getElementById('wishlist-modal').classList.add('hidden')" class="btn btn-sm btn-circle">
                    ×
                </button>
            </div>
            
            <div class="p-4">
                ${items.length === 0 ? 
                    '<p class="text-center py-8">Your wishlist is empty</p>' : 
                    items.map(item => `
                        <div class="flex items-center py-4 border-b border-base-200">
                            <img src="${item.image || ''}" alt="${item.name || 'Unknown Product'}" class="w-16 h-16 object-cover rounded">
                            <div class="ml-4 flex-1">
                                <h4 class="font-medium">${item.name || 'Unknown Product'}</h4>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="font-bold">$${parseFloat(item.price || 0).toFixed(2)}</span>
                                    <button class="btn btn-sm btn-primary" onclick="addToCart(${item.product_id || item.id})">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-ghost ml-2" onclick="removeFromWishlist(${item.product_id || item.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `).join('')}
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Sync guest user wishlist on login
async function syncGuestWishlist(userId) {
    let syncSuccess = true;
    const wishlistItems = JSON.parse(localStorage.getItem('wishlistItems')) || [];
    for (const item of wishlistItems) {
        try {
            const response = await fetch(`${BASE_URL}wishlist.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, product_id: item.id })
            });
            const result = await response.json();
            if (!result.success) {
                syncSuccess = false;
                console.error('Failed to sync wishlist item:', item.id, result.message);
            }
        } catch (error) {
            syncSuccess = false;
            console.error('Error syncing wishlist item:', error);
        }
    }
    localStorage.removeItem('wishlistItems');
    updateWishlistCount();
    if (!syncSuccess) {
        showNotification('Some wishlist items could not be synced to your account.', 'error');
    }
}
</script>

<!--                           notifications                           -->
<script>
function showNotification(message, type = 'success') {
    let container = document.querySelector('.notification-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'notification-container fixed top-16 right-4 flex flex-col gap-2 z-[1000]';
        document.body.appendChild(container);
    }

    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'error'} shadow-lg flex items-center max-w-sm w-full opacity-0 transition-opacity duration-300`;
    notification.setAttribute('role', 'alert');
    notification.setAttribute('aria-live', 'assertive');
    notification.innerHTML = `
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'}" />
            </svg>
            <span class="ml-2">${message}</span>
        </div>
        <button class="btn btn-sm btn-ghost ml-auto" onclick="this.closest('.alert').remove()" aria-label="Close notification">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;

    container.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('opacity-0');
        notification.classList.add('opacity-100');
    }, 10);

    setTimeout(() => {
        notification.classList.remove('opacity-100');
        notification.classList.add('opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

<!--                           Logout                           -->
<script>
document.getElementById('logout-link').addEventListener('click', function (e) {
    e.preventDefault();

    fetch('api/auth.php?action=logout')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show notification
                showNotification(data.message);

                // Redirect to login page after a short delay (to allow the notification to show)
                setTimeout(function () {
                    window.location.href = 'login.php'; 
                }, 1000);  
            } else {
                // If logout failed
                showNotification('Logout failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while logging out.');
        });
});

</script>



