<?php
include 'component/header.php';
include 'component/navbar.php';
?>

<main class="page-section active min-h-[93vh]">
      <div class="container mx-auto px-4 py-10">
    <!-- Genre Section -->
    <div class="card bg-base-100 shadow-xl mb-10">
      <div class="card-body">
        <h2 class="card-title text-3xl font-bold mb-6">Manage Genres</h2>
        <form id="genre-form" class="space-y-4">
          <input type="hidden" id="genre-id">
          <div class="form-control">
            <label class="label">
              <span class="label-text">Genre Name</span>
            </label>
            <input type="text" id="genre-name" placeholder="Enter genre name" class="input input-bordered w-full" required>
          </div>
          <button type="submit" class="btn btn-primary w-full">Save Genre</button>
        </form>
      </div>
    </div>

    <div class="card bg-base-100 shadow-xl mb-10">
      <div class="card-body">
        <h3 class="text-2xl font-bold mb-4">Genre List</h3>
        <div class="overflow-x-auto">
          <table id="genre-table" class="table table-zebra w-full">
            <thead>
              <tr>
                <th class="text-left">ID</th>
                <th class="text-left">Name</th>
                <th class="text-left">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Product Section -->
    <div class="card bg-base-100 shadow-xl mb-10">
      <div class="card-body">
        <h2 class="card-title text-3xl font-bold mb-6">Manage Products</h2>
        <form id="product-form" enctype="multipart/form-data" class="space-y-4">
          <input type="hidden" id="product-id">
          <div class="form-control">
            <label class="label">
              <span class="label-text">Product Name</span>
            </label>
            <input type="text" id="product-name" placeholder="Enter product name" class="input input-bordered w-full" required>
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Description</span>
            </label>
            <input type="text" id="product-description" placeholder="Enter description" class="input input-bordered w-full">
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Stock Quantity</span>
            </label>
            <input type="number" id="product-quantity" placeholder="Enter stock quantity" class="input input-bordered w-full">
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Price</span>
            </label>
            <input type="number" step="0.01" id="product-price" placeholder="Enter price" class="input input-bordered w-full">
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Rating (1-5)</span>
            </label>
            <input type="number" id="product-rating" placeholder="Enter rating" class="input input-bordered w-full">
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Genre</span>
            </label>
            <select id="product-genre" class="select select-bordered w-full"></select>
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Product Image</span>
            </label>
            <input type="file" id="product-image" class="file-input file-input-bordered w-full">
          </div>
          <button type="submit" class="btn btn-primary w-full">Save Product</button>
        </form>
      </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
      <div class="card-body">
        <h3 class="text-2xl font-bold mb-4">Product List</h3>
        <div class="overflow-x-auto">
          <table id="product-table" class="table table-zebra w-full">
            <thead>
              <tr>
                <th class="text-left">ID</th>
                <th class="text-left">Name</th>
                <th class="text-left">Image</th>
                <th class="text-left">Genre</th>
                <th class="text-left">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Image Popup Modal -->
  <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-4 rounded-lg max-w-3xl w-full">
      <div class="flex justify-end">
        <button id="closeModal" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      <img id="modalImage" class="w-full h-auto max-h-[80vh] object-contain" src="" alt="Product Image">
    </div>
  </div>
</main>

<?php include 'component/footer.php'; ?>

<script>
    const genreForm = document.getElementById('genre-form');
    const productForm = document.getElementById('product-form');
    const genreTable = document.getElementById('genre-table').querySelector('tbody');
    const productTable = document.getElementById('product-table').querySelector('tbody');
    const genreSelect = document.getElementById('product-genre');
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');

    // -------- GENRES --------
    async function loadGenres() {
      const res = await fetch('api/genres.php');
      const data = await res.json();
      genreTable.innerHTML = '';
      genreSelect.innerHTML = '<option value="">Select Genre</option>';
      data.data.forEach(g => {
        genreTable.innerHTML += `
          <tr>
            <td>${g.id}</td>
            <td>${g.name}</td>
            <td>
              <div class="flex flex-col space-y-2 md:flex-row md:space-x-2 md:space-y-0">
                <button class="btn btn-sm btn-outline btn-info w-full md:w-auto" onclick='editGenre(${JSON.stringify(g)})'>
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline btn-error w-full md:w-auto" onclick='deleteGenre(${g.id})'>
                  <i class="fas fa-trash"></i> Delete
                </button>
              </div>
            </td>
          </tr>`;
        genreSelect.innerHTML += `<option value="${g.id}">${g.name}</option>`;
      });
    }

    genreForm.onsubmit = async e => {
      e.preventDefault();
      const id = document.getElementById('genre-id').value;
      const name = document.getElementById('genre-name').value;

      const method = id ? 'PUT' : 'POST';
      await fetch('api/genres.php', {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, name })
      });

      genreForm.reset();
      loadGenres();
    }

    function editGenre(g) {
      document.getElementById('genre-id').value = g.id;
      document.getElementById('genre-name').value = g.name;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function deleteGenre(id) {
      await fetch('api/genres.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      });
      loadGenres();
    }

    // -------- PRODUCTS --------
    async function loadProducts() {
      const res = await fetch('api/products.php');
      const data = await res.json();
      productTable.innerHTML = '';
      data.data.forEach(p => {
        productTable.innerHTML += `
          <tr>
            <td>${p.id}</td>
            <td>${p.name}</td>
            <td>
              <div class="relative inline-block">
                <img class="w-32 h-24 object-cover rounded-lg" src="${p.image}" alt="${p.name}">
                <button class="absolute top-2 right-2 bg-gray-800 bg-opacity-50 rounded-full p-2 hover:bg-opacity-75" onclick="showImage('${p.image}')">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
            </td>
            <td>${p.genre_name}</td>
            <td class="min-w-[150px]">
              <div class="flex flex-col space-y-2 md:flex-row md:space-x-2 md:space-y-0">
                <button class="btn btn-sm btn-outline btn-info w-full md:w-auto" onclick='editProduct(${JSON.stringify(p)})'>
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline btn-error w-full md:w-auto" onclick='deleteProduct(${p.id})'>
                  <i class="fas fa-trash"></i> Delete
                </button>
              </div>
            </td>
          </tr>`;
      });
    }

    // Image Popup Functions
    function showImage(imageSrc) {
      modalImage.src = imageSrc;
      imageModal.classList.remove('hidden');
    }

    closeModal.onclick = () => {
      imageModal.classList.add('hidden');
      modalImage.src = '';
    }

    // Close modal when clicking outside
    imageModal.onclick = (e) => {
      if (e.target === imageModal) {
        imageModal.classList.add('hidden');
        modalImage.src = '';
      }
    }

    productForm.onsubmit = async e => {
      e.preventDefault();
      
      const id = document.getElementById('product-id').value;
      const name = document.getElementById('product-name').value;
      const description = document.getElementById('product-description').value;
      const stock_quantity = document.getElementById('product-quantity').value;
      const price = document.getElementById('product-price').value;
      const rating = document.getElementById('product-rating').value;
      const genre_id = document.getElementById('product-genre').value;
      const image = document.getElementById('product-image').files[0];
    
      const formData = new FormData();
      formData.append('name', name);
      formData.append('description', description);
      formData.append('stock_quantity', stock_quantity);
      formData.append('price', price);
      formData.append('rating', rating);
      formData.append('genre_id', genre_id);
    
      if (image) {
        formData.append('image', image);
      }
    
      let url = 'api/products.php';
      let method = 'POST';
    
      if (id) {
        formData.append('id', id);
        formData.append('_method', 'PUT');
      }
    
      await fetch(url, {
        method: method,
        body: formData
      });
    
      productForm.reset();
      loadProducts();
    }
    
    function editProduct(p) {
      document.getElementById('product-id').value = p.id;
      document.getElementById('product-name').value = p.name;
      document.getElementById('product-description').value = p.description;
      document.getElementById('product-quantity').value = p.stock_quantity;
      document.getElementById('product-price').value = p.price;
      document.getElementById('product-rating').value = p.rating;
      document.getElementById('product-genre').value = p.genre_id;
      window.scrollTo({ top: document.getElementById('product-form').offsetTop, behavior: 'smooth' });
    }

    async function deleteProduct(id) {
      const formData = new FormData();
      formData.append('id', id);
      formData.append('_method', 'DELETE');
  
      await fetch('api/products.php', {
          method: 'POST',
          body: formData
      });
      
      loadProducts();
    }    
    // -------- INIT --------
    loadGenres();
    loadProducts();
</script>
  
</body>
</html>