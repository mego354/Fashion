<?php
include 'component/header.php';
include 'component/navbar.php';
?>
<main id="orders-page" class="page-section active min-h-[93vh] p-4 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-center">My Orders</h1>
        <div id="orders-container" class="space-y-6"></div>
    </div>
</main>
<?php include 'component/footer.php'; ?>

<script>
// Function to format date to dd/mm/yyyy hh:mm
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${day}/${month}/${year} ${hours}:${minutes}`;
}

document.addEventListener('DOMContentLoaded', function () {
    fetch('api/order.php?action=get_orders')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('orders-container');
            if (!data.success || !data.orders || data.orders.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500">You don\'t have any orders yet.</p>';
                return;
            }

            data.orders.forEach(order => {
                const orderDiv = document.createElement('div');
                orderDiv.className = 'bg-white shadow rounded-xl p-4 border border-gray-200';

                // Format the date fields
                const createdDate = formatDate(order.created_at);
                const updatedDate = formatDate(order.updated_at);

                // Displaying address if available
                const address = order.address_id ? `<p class="text-sm text-gray-600">Address: ${order.address_id}</p>` : '';

                const orderHeader = `
                    <div class="mb-4">
                        <h2 class="font-semibold text-lg text-primary">Order Number: ORD-${order.created_at.split(" ")[0]}-${order.id.toString().padStart(5, '0')}</h2>
                        <p class="text-sm text-gray-500">Order Date: ${createdDate}</p>
                        <p class="text-sm text-gray-500">Last Updated: ${updatedDate}</p>
                        ${address}
                        <p class="text-sm text-gray-600">Total Amount: ${order.total_amount} $</p>
                        <p class="text-sm text-gray-600">Payment Method: ${order.payment_method}</p>
                        <p class="text-sm text-gray-600">Status: <span class="badge badge-warning">${order.status}</span></p>
                    </div>
                `;

                const itemsList = order.items.map(item => `
                    <div class="flex items-center justify-between border-t py-2">
                        <div class="flex items-center gap-4">
                            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 rounded object-cover">
                            <span class="text-lg font-medium">${item.name}</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            ${item.quantity} × ${item.unit_price} $
                        </div>
                    </div>
                `).join('');

                orderDiv.innerHTML = orderHeader + itemsList;
                container.appendChild(orderDiv);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('orders-container').innerHTML = '<p class="text-center text-red-500">An error occurred while fetching orders.</p>';
        });
});
</script>
</body>
</html>
