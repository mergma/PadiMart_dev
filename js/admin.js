document.addEventListener('DOMContentLoaded', () => {
  // Use shared ProductsManager for data persistence
  let products = ProductsManager.getProducts();
  let deleteTargetId = null;

  // DOM Elements
  const addProductForm = document.getElementById('addProductForm');
  const productsList = document.getElementById('productsList');
  const searchProducts = document.getElementById('searchProducts');
  const filterCategory = document.getElementById('filterCategory');
  const deleteModal = document.getElementById('deleteModal');
  const confirmDelete = document.getElementById('confirmDelete');
  const cancelDelete = document.getElementById('cancelDelete');

  // Format price
  const formatPrice = (value) => {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  };

  // Render products list
  function renderProducts(list = products) {
    productsList.innerHTML = '';

    if (list.length === 0) {
      productsList.innerHTML = '<div class="empty-state"><p>Tidak ada produk. Tambahkan produk baru untuk memulai.</p></div>';
      return;
    }

    list.forEach((product) => {
      const productItem = document.createElement('div');
      productItem.className = 'product-item';
      productItem.dataset.id = product.id;

      const badge = product.popular ? '<span class="product-badge">POPULER</span>' : '';

      productItem.innerHTML = `
        <div class="product-image" style="background-image: url('${product.image}')"></div>
        <div class="product-info">
          <h3>${product.title}</h3>
          <p><strong>Kategori:</strong> ${product.category}</p>
          <p><strong>Harga:</strong> <span class="product-price">Rp ${formatPrice(product.price)}</span></p>
          <p><strong>WhatsApp:</strong> ${product.phone}</p>
          ${badge}
        </div>
        <div class="product-actions">
          <button class="btn-edit" onclick="editProduct(${product.id})">Edit</button>
          <button class="btn-danger" onclick="openDeleteModal(${product.id})">Hapus</button>
        </div>
      `;

      productsList.appendChild(productItem);
    });
  }

  // Add product
  addProductForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const newProduct = {
      title: document.getElementById('productTitle').value,
      category: document.getElementById('productCategory').value,
      price: parseInt(document.getElementById('productPrice').value),
      image: document.getElementById('productImage').value || 'https://via.placeholder.com/600x420?text=Produk',
      popular: document.getElementById('productPopular').checked,
      phone: document.getElementById('productPhone').value,
    };

    ProductsManager.addProduct(newProduct);
    products = ProductsManager.getProducts();
    addProductForm.reset();
    renderProducts();

    // Show success message
    showNotification('Produk berhasil ditambahkan!', 'success');
  });

  // Search and filter
  function applyFilters() {
    const searchTerm = searchProducts.value.toLowerCase();
    const selectedCategory = filterCategory.value;

    const filtered = products.filter((product) => {
      const matchSearch = product.title.toLowerCase().includes(searchTerm) || product.category.toLowerCase().includes(searchTerm);
      const matchCategory = !selectedCategory || product.category === selectedCategory;
      return matchSearch && matchCategory;
    });

    renderProducts(filtered);
  }

  searchProducts.addEventListener('input', applyFilters);
  filterCategory.addEventListener('change', applyFilters);

  // Delete modal
  function openDeleteModal(id) {
    deleteTargetId = id;
    const product = products.find((p) => p.id === id);
    document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus "${product.title}"?`;
    deleteModal.classList.add('active');
  }

  confirmDelete.addEventListener('click', () => {
    ProductsManager.deleteProduct(deleteTargetId);
    products = ProductsManager.getProducts();
    deleteModal.classList.remove('active');
    renderProducts();
    showNotification('Produk berhasil dihapus!', 'success');
  });

  cancelDelete.addEventListener('click', () => {
    deleteModal.classList.remove('active');
    deleteTargetId = null;
  });

  // Close modal on background click
  deleteModal.addEventListener('click', (e) => {
    if (e.target === deleteModal) {
      deleteModal.classList.remove('active');
    }
  });

  // Edit product (placeholder - can be expanded)
  window.editProduct = (id) => {
    const product = products.find((p) => p.id === id);
    if (product) {
      document.getElementById('productTitle').value = product.title;
      document.getElementById('productCategory').value = product.category;
      document.getElementById('productPrice').value = product.price;
      document.getElementById('productImage').value = product.image;
      document.getElementById('productPopular').checked = product.popular;
      document.getElementById('productPhone').value = product.phone;

      // Scroll to form
      document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });

      // Show message
      showNotification(`Editing: ${product.title}. Update dan submit untuk menyimpan perubahan.`, 'info');
    }
  };

  // Delete product (exposed to global scope)
  window.openDeleteModal = openDeleteModal;

  // Notification system
  function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
      color: white;
      padding: 1rem 1.5rem;
      border-radius: 6px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      z-index: 2000;
      animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.style.animation = 'slideOut 0.3s ease';
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  // Add animation styles
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(400px);
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(style);

  // Initial render
  renderProducts();
});

