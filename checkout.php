<?php
include 'component/header.php';
include 'component/navbar.php';
?>

<main class="page-section active min-h-[93vh]">
    <!-- Checkout Page -->
    <section id="checkout-page" class="page-section">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-6">Checkout</h1>
            
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="w-full lg:w-2/3">
                    <!-- Checkout Steps -->
                    <ul class="steps steps-horizontal lg:steps-horizontal w-full mb-8">
                        <li class="step step-primary">Address</li>
                        <li class="step">Payment</li>
                        <li class="step">Confirmation</li>
                    </ul>
                    
                    <!-- Address Selection -->
                    <div id="checkout-address" class="bg-base-100 p-6 rounded-lg shadow-md mb-6">
                        <h2 class="text-xl font-bold mb-4">Delivery Address</h2>
                        
                        <div class="tabs mb-4">
                            <a class="tab tab-lifted tab-active" data-tab="saved-addresses">Saved Addresses</a>
                            <a class="tab tab-lifted" data-tab="new-address">Add New Address</a>
                            <a class="tab tab-lifted" data-tab="store-pickup">Store Pickup</a>
                        </div>
                        
                        <!-- Saved Addresses -->
                        <div id="saved-addresses-content" class="tab-content">
                            <div id="address-list" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- Addresses will be loaded dynamically -->
                            </div>
                            <div class="flex justify-between mt-8">
                                <button class="btn btn-outline" onclick="navigateTo('home')">Continue Shopping</button>
                                <button class="btn btn-primary" onclick="proceedToPayment()">Proceed to Payment</button>
                            </div>
                        </div>
                        
                        <!-- New Address Form -->
                        <div id="new-address-content" class="tab-content hidden">
                            <form id="new-address-form" class="space-y-4">
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Title (e.g., Home, Office)</span></label>
                                    <input type="text" name="title" class="input input-bordered" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Street</span></label>
                                    <input type="text" name="street" class="input input-bordered" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Apartment Number</span></label>
                                    <input type="text" name="apartment_number" class="input input-bordered" />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text">City</span></label>
                                    <input type="text" name="city" class="input input-bordered" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Country</span></label>
                                    <input type="text" name="country" class="input input-bordered" required />
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" class="btn btn-outline" onclick="showTab('saved-addresses')">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Address</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Store Pickup -->
                        <div id="store-pickup-content" class="tab-content hidden">
                            <p>Store pickup is available at our main store:</p>
                            <p class="mt-2">789 Retail Street, New York, NY 10003</p>
                            <p>Pickup Hours: Mon-Fri, 9 AM - 6 PM</p>
                            <div class="flex justify-end mt-4">
                                <button class="btn btn-primary" onclick="selectStorePickup()">Select Store Pickup</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div id="checkout-payment" class="bg-base-100 p-6 rounded-lg shadow-md mb-6 hidden">
                        <h2 class="text-xl font-bold mb-4">Payment Method</h2>
                        
                        <div class="tabs mb-4">
                            <a class="tab tab-lifted tab-active" data-tab="credit-card">Credit/Debit Card</a>
                            <a class="tab tab-lifted" data-tab="cash-on-delivery">Cash on Delivery</a>
                        </div>
                        
                        <!-- Credit Card Payment -->
                        <div id="credit-card-content" class="tab-content">
                            <div id="card-list" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Cards will be loaded dynamically -->
                            </div>
                            <button class="btn btn-outline mb-4" onclick="showTab('new-card')">Add New Card</button>
                            <div class="flex justify-between mt-8">
                                <button class="btn btn-outline" onclick="checkoutStep('address')">Back to Address</button>
                                <button class="btn btn-primary" onclick="proceedToConfirmation()">Review Order</button>
                            </div>
                        </div>
                        
                        <!-- New Card Form -->
                        <div id="new-card-content" class="tab-content hidden">
                            <form id="new-card-form" class="space-y-4">
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Cardholder Name</span></label>
                                    <input type="text" name="cardholder_name" class="input input-bordered" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text">Card Number</span></label>
                                    <input type="text" name="card_number" class="input input-bordered" placeholder="1234 5678 9012 3456" required />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label"><span class="label-text">Expiry Date</span></label>
                                        <input type="text" name="expiry_date" class="input input-bordered" placeholder="MM/YY" required />
                                    </div>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text">CVV</span></label>
                                        <input type="text" name="cvv" class="input input-bordered" placeholder="123" required />
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="cursor-pointer flex items-center gap-2 mt-4">
                                        <input type="checkbox" name="save_card" class="checkbox checkbox-primary" checked />
                                        <span>Save card for future payments</span>
                                    </label>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" class="btn btn-outline" onclick="showTab('credit-card')">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Card</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Cash on Delivery -->
                        <div id="cash-on-delivery-content" class="tab-content hidden">
                            <p>Pay with cash upon delivery. Please ensure you have the exact amount ready.</p>
                            <div class="flex justify-between mt-8">
                                <button class="btn btn-outline" onclick="checkoutStep('address')">Back to Address</button>
                                <button class="btn btn-primary" onclick="selectCashOnDelivery()">Proceed with Cash on Delivery</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Confirmation -->
                    <div id="checkout-confirmation" class="bg-base-100 p-6 rounded-lg shadow-md mb-6 hidden">
                        <h2 class="text-xl font-bold mb-4">Order Confirmation</h2>
                        
                        <div class="space-y-6">
                            <div class="bg-base-200 p-4 rounded-lg" id="confirmation-address">
                                <h3 class="font-bold mb-2">Delivery Address</h3>
                                <!-- Address details will be updated dynamically -->
                            </div>
                            
                            <div class="bg-base-200 p-4 rounded-lg" id="confirmation-payment">
                                <h3 class="font-bold mb-2">Payment Method</h3>
                                <!-- Payment details will be updated dynamically -->
                            </div>
                            
                            <div class="bg-base-200 p-4 rounded-lg">
                                <h3 class="font-bold mb-2">Order Items</h3>
                                <div id="confirmation-items" class="space-y-3">
                                    <!-- Order items will be loaded dynamically -->
                                </div>
                            </div>
                            
                            <div class="bg-base-200 p-4 rounded-lg" id="confirmation-summary">
                                <h3 class="font-bold mb-2">Order Summary</h3>
                                <div class="flex justify-between mb-1">
                                    <span>Subtotal</span>
                                    <span id="subtotal">$0.00</span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span>Shipping</span>
                                    <span id="shipping">$7.99</span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span>Discount</span>
                                    <span id="discount" class="text-success">-$0.00</span>
                                </div>
                                <div class="divider my-2"></div>
                                <div class="flex justify-between font-bold">
                                    <span>Total</span>
                                    <span id="total">$7.99</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-control mt-6">
                            <label class="cursor-pointer flex items-center gap-2">
                                <input type="checkbox" class="checkbox checkbox-primary" id="terms-checkbox" />
                                <span>I agree to the Terms & Conditions and Privacy Policy</span>
                            </label>
                        </div>
                        
                        <div class="flex justify-between mt-8">
                            <button class="btn btn-outline" onclick="checkoutStep('payment')">Back to Payment</button>
                            <button class="btn btn-primary" onclick="placeOrder()">Place Order</button>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary Sidebar -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-base-200 p-6 rounded-lg shadow-md sticky top-24">
                        <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                        
                        <!-- Order Items -->
                        <div id="order-summary-items" class="space-y-3 mb-6">
                            <!-- Order items will be loaded dynamically -->
                        </div>
                        
                        <!-- Price Calculation -->
                        <div class="border-t pt-4">
                            <div class="flex justify-between mb-1">
                                <span>Subtotal</span>
                                <span id="summary-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>Shipping</span>
                                <span id="summary-shipping">$7.99</span>
                            </div>
                            <div class="flex justify-between mb-3">
                                <span>Discount</span>
                                <span id="summary-discount" class="text-success">-$0.00</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span id="summary-total">$7.99</span>
                            </div>
                        </div>
                        
                        <!-- Promo Code -->
                        <div class="form-control mt-6">
                            <div class="flex gap-2">
                                <input type="text" id="promo-code" placeholder="Enter promo code" class="input input-bordered w-full" />
                                <button class="btn btn-primary" onclick="applyPromoCode()">Apply</button>
                            </div>
                        </div>
                        
                        <!-- Shipping Notes -->
                        <div class="mt-6 text-sm">
                            <p class="flex items-center gap-2">
                                <i class="fas fa-truck"></i>
                                <span>Standard Shipping: 3-5 business days</span>
                            </p>
                            <p class="flex items-center gap-2 mt-2">
                                <i class="fas fa-store"></i>
                                <span>Pick-up from store: Free shipping</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Confirmation Message -->
    <div id="order-confirmation" class="fixed inset-0 z-50 bg-black bg-opacity-70 hidden flex items-center justify-center">
        <div class="bg-base-100 p-8 rounded-lg max-w-md w-full shadow-2xl">
            <div class="flex flex-col items-center">
                <div class="text-success mb-6 text-6xl">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Order Placed Successfully!</h3>
                <p class="text-center mb-6">Your order has been placed successfully. You'll receive a confirmation email shortly.</p>
                <p class="font-semibold mb-6" id="order-number">Order #: ORD-2023-XXXXX</p>
                <div class="flex gap-4">
                    <button class="btn btn-outline" onclick="navigateTo('profile')">Order Details</button>
                    <button class="btn btn-primary" onclick="closeOrderConfirmation()">Continue Shopping</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div id="edit-address-modal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Edit Address</h3>
            <form id="edit-address-form" class="space-y-4 mt-4">
                <input type="hidden" name="id" id="edit-address-id" />
                <div class="form-control">
                    <label class="label"><span class="label-text">Title (e.g., Home, Office)</span></label>
                    <input type="text" name="title" id="edit-title" class="input input-bordered" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Street</span></label>
                    <input type="text" name="street" id="edit-street" class="input input-bordered" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Apartment Number</span></label>
                    <input type="text" name="apartment_number" id="edit-apartment_number" class="input input-bordered" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">City</span></label>
                    <input type="text" name="city" id="edit-city" class="input input-bordered" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Country</span></label>
                    <input type="text" name="country" id="edit-country" class="input input-bordered" required />
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-outline" onclick="closeEditAddressModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</main>

<?php include 'component/footer.php'; ?>

<script>
    let selectedAddressId = null;
    let selectedCardId = null;
    let paymentMethod = null;
    let cartItems2 = [];

    // Initialize page
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('checkout-page').classList.add('active');
        checkoutStep('address');
        loadAddresses();
        loadCartItems2();
    });

    // Handle checkout steps
    function checkoutStep(step) {
        document.getElementById('checkout-address').classList.add('hidden');
        document.getElementById('checkout-payment').classList.add('hidden');
        document.getElementById('checkout-confirmation').classList.add('hidden');

        document.getElementById(`checkout-${step}`).classList.remove('hidden');

        const steps = document.querySelectorAll('.steps .step');
        steps.forEach((stepEl, index) => {
            stepEl.classList.remove('step-primary');
            if (
                (step === 'address' && index === 0) ||
                (step === 'payment' && index <= 1) ||
                (step === 'confirmation' && index <= 2)
            ) {
                stepEl.classList.add('step-primary');
            }
        });

        if (step === 'payment') {
            loadCreditCards();
        } else if (step === 'confirmation') {
            updateConfirmationDetails();
        }
    }

    // Handle tab switching
    function showTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        document.getElementById(`${tabId}-content`).classList.remove('hidden');

        document.querySelectorAll('.tabs .tab').forEach(tab => tab.classList.remove('tab-active'));
        document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('tab-active');
    }

    document.querySelectorAll('.tabs .tab').forEach(tab => {
        tab.addEventListener('click', () => showTab(tab.dataset.tab));
    });

    // Load user addresses
    function loadAddresses() {
        fetch('api/order.php?action=get_addresses')
            .then(response => response.json())
            .then(data => {
                const addressList = document.getElementById('address-list');
                addressList.innerHTML = '';
                if (data.success && data.addresses.length > 0) {
                    data.addresses.forEach(address => {
                        const isSelected = selectedAddressId === address.id;
                        const addressCard = `
                            <div class="border ${isSelected ? 'border-primary' : 'border-base-300'} p-4 rounded-lg relative">
                                ${isSelected ? '<div class="badge badge-primary absolute right-2 top-2">Selected</div>' : ''}
                                <h3 class="font-bold">${address.title}</h3>
                                <p>${address.street}</p>
                                ${address.apartment_number ? `<p>${address.apartment_number}</p>` : ''}
                                <p>${address.city}, ${address.country}</p>
                                <div class="flex justify-end mt-2">
                                    <button class="btn btn-sm btn-outline mr-2" onclick="editAddress(${address.id})">Edit</button>
                                    <button class="btn btn-sm btn-primary" onclick="selectAddress(${address.id})">Select</button>
                                </div>
                            </div>
                        `;
                        addressList.insertAdjacentHTML('beforeend', addressCard);
                    });
                } else {
                    addressList.innerHTML = '<p>No saved addresses. Please add a new address or select store pickup.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading addresses:', error);
                showNotification('Failed to load addresses.', 'error');
            });
    }

    // Select address
    function selectAddress(addressId) {
        selectedAddressId = addressId;
        loadAddresses();
    }

    // Edit address
    function editAddress(addressId) {
        fetch(`api/order.php?action=get_address&id=${addressId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    document.getElementById('edit-address-id').value = addressId;
                    document.getElementById('edit-title').value = data.title;
                    document.getElementById('edit-street').value = data.street;
                    document.getElementById('edit-apartment_number').value = data.apartment_number || '';
                    document.getElementById('edit-city').value = data.city;
                    document.getElementById('edit-country').value = data.country;
                    document.getElementById('edit-address-modal').classList.remove('hidden');
                } else {
                    showNotification('Address not found.', 'error');
                }
            })
            .catch(error => {
                console.error('Error fetching address:', error);
                showNotification('Failed to load address.', 'error');
            });
    }

    // Close edit address modal
    function closeEditAddressModal() {
        document.getElementById('edit-address-modal').classList.add('hidden');
    }

    // Handle edit address form submission
    document.getElementById('edit-address-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('api/order.php?action=update_address', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadAddresses();
                    closeEditAddressModal();
                    showNotification('Address updated successfully!');
                } else {
                    showNotification(data.message || 'Failed to update address.', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating address:', error);
                showNotification('Failed to update address.', 'error');
            });
    });

    // Load credit cards
    function loadCreditCards() {
        fetch('api/order.php?action=get_credit_cards')
            .then(response => response.json())
            .then(data => {
                const cardList = document.getElementById('card-list');
                cardList.innerHTML = '';
                if (data.success && data.cards.length > 0) {
                    data.cards.forEach(card => {
                        const isSelected = selectedCardId === card.id;
                        const cardCard = `
                            <div class="border ${isSelected ? 'border-primary' : 'border-base-300'} p-4 rounded-lg relative">
                                ${isSelected ? '<div class="badge badge-primary absolute right-2 top-2">Selected</div>' : ''}
                                <div class="flex items-center mb-2">
                                    <i class="fab fa-cc-visa text-2xl mr-2"></i>
                                    <span>${card.card_number}</span>
                                </div>
                                <p>${card.cardholder_name}</p>
                                <p class="text-sm">Expires: ${card.expiry_date}</p>
                                <div class="flex justify-end mt-2">
                                    <button class="btn btn-sm btn-primary" onclick="selectCard(${card.id})">Select</button>
                                </div>
                            </div>
                        `;
                        cardList.insertAdjacentHTML('beforeend', cardCard);
                    });
                } else {
                    cardList.innerHTML = '<p>No saved cards. Please add a new card.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading credit cards:', error);
                showNotification('Failed to load credit cards.', 'error');
            });
    }

    // Select credit card
    function selectCard(cardId) {
        selectedCardId = cardId;
        paymentMethod = 'card';
        loadCreditCards();
    }

    // Select cash on delivery
    function selectCashOnDelivery() {
        paymentMethod = 'cash';
        selectedCardId = null;
        proceedToConfirmation();
    }

    // Select store pickup
    function selectStorePickup() {
        selectedAddressId = null; // No address needed for store pickup
        proceedToPayment();
    }

    // Proceed to payment
    function proceedToPayment() {
        if (!selectedAddressId && !document.getElementById('store-pickup-content').classList.contains('hidden')) {
            // Store pickup selected
            checkoutStep('payment');
        } else if (selectedAddressId) {
            checkoutStep('payment');
        } else {
            showNotification('Please select a delivery address or store pickup.', 'error');
        }
    }

    // Proceed to confirmation
    function proceedToConfirmation() {
        if (paymentMethod === 'cash' || selectedCardId) {
            checkoutStep('confirmation');
        } else {
            showNotification('Please select a payment method.', 'error');
        }
    }

    // Load cart items
    function loadCartItems2() {
        fetch('api/order.php?action=get_cart')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.items.length > 0) {
                    cartItems2 = data.items;
                    const orderSummary = document.getElementById('order-summary-items');
                    orderSummary.innerHTML = '';
                    let subtotal = 0;
                    cartItems2.forEach(item => {
                        subtotal += item.quantity * item.unit_price;
                        const itemHtml = `
                            <div class="flex justify-between">
                                <div class="flex">
                                    <div class="w-10 h-10 bg-gray-200 rounded-md overflow-hidden mr-2">
                                        <img src="${item.image}" alt="${item.name}" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">${item.name}</p>
                                        <p class="text-xs text-gray-500">${item.quantity} × $${parseFloat(item.unit_price || 0).toFixed(2)}</p>
                                    </div>
                                </div>
                                <p class="font-medium">$${(item.quantity * item.unit_price).toFixed(2)}</p>
                            </div>
                        `;
                        orderSummary.insertAdjacentHTML('beforeend', itemHtml);
                    });
                    updateSummary(subtotal);
                } else {
                    document.getElementById('order-summary-items').innerHTML = '<p>Your cart is empty.</p>';
                    document.getElementById('summary-subtotal').textContent = '$0.00';
                    document.getElementById('summary-shipping').textContent = '$0.00';
                    document.getElementById('summary-total').textContent = '$0.00';
                }
            })
            .catch(error => {
                console.error('Error loading cart items:', error);
                showNotification('Failed to load cart items.', 'error');
            });
    }

    // Update order summary
    function updateSummary(subtotal) {
        const shipping = document.getElementById('store-pickup-content').classList.contains('hidden') && selectedAddressId ? 7.99 : 0;
        const discount = 0; // Placeholder for promo code logic
        const total = subtotal + shipping - discount;

        document.getElementById('summary-subtotal').textContent = `$${(parseFloat(subtotal || 0)).toFixed(2)}`;
        document.getElementById('summary-shipping').textContent = `$${(parseFloat(shipping || 0)).toFixed(2)}`;
        document.getElementById('summary-discount').textContent = `-$${(parseFloat(discount || 0)).toFixed(2)}`;
        document.getElementById('summary-total').textContent = `$${(parseFloat(total || 0)).toFixed(2)}`;

        document.getElementById('subtotal').textContent = `$${(parseFloat(subtotal || 0)).toFixed(2)}`;
        document.getElementById('shipping').textContent = `$${(parseFloat(shipping || 0)).toFixed(2)}`;
        document.getElementById('discount').textContent = `-$${(parseFloat(discount || 0)).toFixed(2)}`;
        document.getElementById('total').textContent = `$${(parseFloat(total || 0)).toFixed(2)}`;
    }

    // Update confirmation details
    function updateConfirmationDetails() {
        // Update address
        if (selectedAddressId) {
            fetch(`api/order.php?action=get_address&id=${selectedAddressId}`)
                .then(response => response.json())
                .then(address => {
                    if (address.success !== false) {
                        document.getElementById('confirmation-address').innerHTML = `
                            <h3 class="font-bold mb-2">Delivery Address</h3>
                            <p>${address.title}</p>
                            <p>${address.street}${address.apartment_number ? ', ' + address.apartment_number : ''}</p>
                            <p>${address.city}, ${address.country}</p>
                        `;
                    } else {
                        showNotification('Failed to load address.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                    showNotification('Failed to load address.', 'error');
                });
        } else {
            document.getElementById('confirmation-address').innerHTML = `
                <h3 class="font-bold mb-2">Delivery Address</h3>
                <p>Store Pickup</p>
                <p>789 Retail Street, New York, NY 10003</p>
            `;
        }

        // Update payment
        if (paymentMethod === 'card') {
            fetch(`api/order.php?action=get_credit_card&id=${selectedCardId}`)
                .then(response => response.json())
                .then(card => {
                    if (card.success !== false) {
                        document.getElementById('confirmation-payment').innerHTML = `
                            <h3 class="font-bold mb-2">Payment Method</h3>
                            <div class="flex items-center">
                                <i class="fab fa-cc-visa text-2xl mr-2"></i>
                                <span>${card.card_number}</span>
                            </div>
                        `;
                    } else {
                        showNotification('Failed to load card.', fraud, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching card:', error);
                    showNotification('Failed to load card.', 'error');
                });
        } else {
            document.getElementById('confirmation-payment').innerHTML = `
                <h3 class="font-bold mb-2">Payment Method</h3>
                <p>Cash on Delivery</p>
            `;
        }

        // Update order items
        const confirmationItems = document.getElementById('confirmation-items');
        confirmationItems.innerHTML = '';
        cartItems2.forEach(item => {
            const itemHtml = `
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-200 rounded-md overflow-hidden mr-3">
                            <img src="${item.image}" alt="${item.name}" class="h-full w-full object-cover" />
                        </div>
                        <div>
                            <p class="font-medium">${item.name}</p>
                            <p class="text-xs text-gray-500">Qty: ${item.quantity}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p>${item.quantity} × $${parseFloat(item.unit_price || 0).toFixed(2)}</p>
                        <p class="font-bold">$${(item.quantity * item.unit_price).toFixed(2)}</p>
                    </div>
                </div>
            `;
            confirmationItems.insertAdjacentHTML('beforeend', itemHtml);
        });
    }

    // Handle new address form submission
    document.getElementById('new-address-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('api/order.php?action=add_address', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadAddresses();
                    showTab('saved-addresses');
                    e.target.reset();
                    showNotification('Address added successfully!');
                } else {
                    showNotification(data.message || 'Failed to add address.', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding address:', error);
                showNotification('Failed to add address.', 'error');
            });
    });

    // Handle new card form submission
    document.getElementById('new-card-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('api/order.php?action=add_credit_card', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCreditCards();
                    showTab('credit-card');
                    e.target.reset();
                    showNotification('Card added successfully!');
                } else {
                    showNotification(data.message || 'Failed to add card.', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding card:', error);
                showNotification('Failed to add card.', 'error');
            });
    });

    // Apply promo code (placeholder)
    function applyPromoCode() {
        const promoCode = document.getElementById('promo-code').value;
        if (promoCode) {
            showNotification(`Promo code "${promoCode}" applied! (Placeholder)`, 'success');
            // Add logic to apply discount
        } else {
            showNotification('Please enter a promo code.', 'error');
        }
    }

    // Place order
    function placeOrder() {
        if (!document.getElementById('terms-checkbox').checked) {
            showNotification('Please agree to the Terms & Conditions and Privacy Policy.', 'error');
            return;
        }
        if (cartItems2.length === 0) {
            showNotification('Your cart is empty.', 'error');
            return;
        }

        const orderData = {
            address_id: selectedAddressId,
            payment_method: paymentMethod,
            credit_card_id: paymentMethod === 'card' ? selectedCardId : null,
            items: cartItems2.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                unit_price: item.unit_price
            }))
        };

        fetch('api/order.php?action=place_order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(orderData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('order-number').textContent = `Order #: ${data.order_id}`;
                    document.getElementById('order-confirmation').classList.remove('hidden');
                    cartItems2 = []; // Clear local cart
                    updateSummary(0);
                } else {
                    showNotification(data.message || 'Failed to place order.', 'error');
                }
            })
            .catch(error => {
                console.error('Error placing order:', error);
                showNotification('Failed to place order.', 'error');
            });
    }

    // Close order confirmation
    function closeOrderConfirmation() {
        document.getElementById('order-confirmation').classList.add('hidden');
        navigateTo('home');
    }
</script>

</body>
</html>