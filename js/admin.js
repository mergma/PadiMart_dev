document.addEventListener('DOMContentLoaded', () => {
  // Load products from database via PHP
  let products = [];
  let deleteTargetId = null;
  let currentImageData = null; // Store current image data

  // Load products from database
  async function loadProductsFromDatabase() {
    try {
      const apiUrl = window.CONFIG?.API_BASE_URL || '/padi/api';
      const response = await fetch(apiUrl + '/get-products.php');
      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const result = await response.json();
      if (!result.success) throw new Error(result.message || 'Failed to load products');

      products = result.data || [];
      console.log('Loaded', products.length, 'products from database');
      renderProducts();
      updateStatistics();
    } catch (error) {
      console.error('Error loading products from database:', error);
      // Fallback to localStorage if database fails
      products = ProductsManager.getProducts();
      renderProducts();
      updateStatistics();
    }
  }

  // DOM Elements
  const addProductForm = document.getElementById('addProductForm');
  const productsList = document.getElementById('productsList');
  const searchProducts = document.getElementById('searchProducts');
  const filterCategory = document.getElementById('filterCategory');
  const deleteModal = document.getElementById('deleteModal');
  const confirmDelete = document.getElementById('confirmDelete');
  const cancelDelete = document.getElementById('cancelDelete');
  const productImageInput = document.getElementById('productImage');
  const imagePreview = document.getElementById('imagePreview');
  const previewImg = document.getElementById('previewImg');
  const removeImageBtn = document.getElementById('removeImage');

  // New field elements
  const productOrigin = document.getElementById('productOrigin');
  const productPhone = document.getElementById('productPhone');

  // Format price
  const formatPrice = (value) => {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  };

  // Image handling functions
  const handleImageUpload = (file) => {
    return new Promise((resolve, reject) => {
      if (!file) {
        resolve(null);
        return;
      }

      // Check file type
      if (!file.type.startsWith('image/')) {
        reject(new Error('File harus berupa gambar'));
        return;
      }

      // Check file size (max 5MB)
      if (file.size > 5 * 1024 * 1024) {
        reject(new Error('Ukuran file maksimal 5MB'));
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        resolve(e.target.result);
      };
      reader.onerror = () => {
        reject(new Error('Gagal membaca file'));
      };
      reader.readAsDataURL(file);
    });
  };

  const showImagePreview = (imageSrc) => {
    if (imageSrc) {
      previewImg.src = imageSrc;
      imagePreview.style.display = 'block';
      currentImageData = imageSrc;
    } else {
      hideImagePreview();
    }
  };

  const hideImagePreview = () => {
    imagePreview.style.display = 'none';
    previewImg.src = '';
    currentImageData = null;
  };

  // Image input event listeners
  productImageInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (file) {
      try {
        const imageData = await handleImageUpload(file);
        showImagePreview(imageData);
      } catch (error) {
        showNotification(error.message, 'error');
        productImageInput.value = '';
      }
    } else {
      hideImagePreview();
    }
  });

  removeImageBtn.addEventListener('click', () => {
    productImageInput.value = '';
    hideImagePreview();
  });

  // Auto-format phone number
  productPhone.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
    if (value.startsWith('08')) {
      value = '628' + value.substring(2); // Convert 08xx to 628xx
    }
    if (value && !value.startsWith('+')) {
      value = '+' + value; // Add + prefix
    }
    e.target.value = value;
  });

  // Set default origin if empty
  productOrigin.addEventListener('blur', (e) => {
    if (!e.target.value.trim()) {
      e.target.value = 'Tabalong, Kalimantan Selatan';
    }
  });

  // Update statistics
  function updateStatistics() {
    const totalProducts = products.length;
    const popularProducts = products.filter(p => p.popular).length;
    const categories = [...new Set(products.map(p => p.category))].length;
    const sellers = [...new Set(products.map(p => p.seller).filter(Boolean))].length;

    document.getElementById('totalProducts').textContent = totalProducts;
    document.getElementById('popularProducts').textContent = popularProducts;
    document.getElementById('totalCategories').textContent = categories;
    document.getElementById('totalSellers').textContent = sellers;
  }

  // Render products list with expandable edit cards
  function renderProducts(list = products) {
    productsList.innerHTML = '';

    if (list.length === 0) {
      productsList.innerHTML = '<div class="empty-state"><p>Tidak ada produk. Tambahkan produk baru untuk memulai.</p></div>';
      return;
    }

    list.forEach((product, index) => {
      const productCard = document.createElement('div');
      productCard.className = 'admin-product-card';
      productCard.dataset.id = product.id;
      productCard.style.animationDelay = `${index * 60}ms`;

      const badge = product.popular ? '<div class="admin-card__badge">POPULER</div>' : '';

      productCard.innerHTML = `
        <div class="admin-card__shine"></div>
        <div class="admin-card__glow"></div>
        ${badge}
        <div class="admin-card__content">
          <div class="admin-card__image" style="background-image:url('${product.image}');"></div>
          <div class="admin-card__text">
            <h3 class="admin-card__title">${product.title}</h3>
            <p class="admin-card__category">${product.category}</p>
            <p class="admin-card__price">Rp ${formatPrice(product.price)}</p>
          </div>
          <div class="admin-card__footer">
            <div class="admin-card__info">
              <span class="info-item">📦 ${product.weight || 'N/A'}</span>
              <span class="info-item">👤 ${product.seller || 'N/A'}</span>
            </div>
            <div class="admin-card__actions">
              <button class="admin-btn admin-btn-edit" data-product-id="${product.id}" aria-label="Edit ${product.title}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </button>
              <button class="admin-btn admin-btn-delete" data-product-id="${product.id}" aria-label="Hapus ${product.title}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3,6 5,6 21,6"></polyline>
                  <path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"></path>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div class="admin-card__edit-form" style="display: none;">
          <div class="admin-edit-form-header">
            <h3>Edit Produk</h3>
            <button class="admin-edit-form-close" aria-label="Tutup form edit">×</button>
          </div>
          <form class="admin-edit-form">
            <div class="admin-form-row">
              <div class="admin-form-group">
                <label for="admin-edit-title-${product.id}">Nama Produk</label>
                <input type="text" id="admin-edit-title-${product.id}" name="title" value="${product.title}" required>
              </div>
              <div class="admin-form-group">
                <label for="admin-edit-category-${product.id}">Kategori</label>
                <select id="admin-edit-category-${product.id}" name="category" required>
                  <option value="Beras" ${product.category === 'Beras' ? 'selected' : ''}>Beras</option>
                  <option value="Camilan & Olahan" ${product.category === 'Camilan & Olahan' ? 'selected' : ''}>Camilan & Olahan</option>
                  <option value="Kerajinan & Oleh-oleh" ${product.category === 'Kerajinan & Oleh-oleh' ? 'selected' : ''}>Kerajinan & Oleh-oleh</option>
                  <option value="Pupuk" ${product.category === 'Pupuk' ? 'selected' : ''}>Pupuk</option>
                  <option value="Benih" ${product.category === 'Benih' ? 'selected' : ''}>Benih</option>
                  <option value="Alat" ${product.category === 'Alat' ? 'selected' : ''}>Alat</option>
                  <option value="Edukasi" ${product.category === 'Edukasi' ? 'selected' : ''}>Edukasi</option>
                </select>
              </div>
            </div>
            <div class="admin-form-row">
              <div class="admin-form-group">
                <label for="admin-edit-price-${product.id}">Harga (Rp)</label>
                <input type="number" id="admin-edit-price-${product.id}" name="price" value="${product.price}" min="0" required>
              </div>
              <div class="admin-form-group">
                <label for="admin-edit-weight-${product.id}">Berat</label>
                <input type="text" id="admin-edit-weight-${product.id}" name="weight" value="${product.weight || ''}">
              </div>
            </div>
            <div class="admin-form-row">
              <div class="admin-form-group">
                <label for="admin-edit-seller-${product.id}">Nama Penjual</label>
                <input type="text" id="admin-edit-seller-${product.id}" name="seller" value="${product.seller || ''}">
              </div>
              <div class="admin-form-group">
                <label for="admin-edit-phone-${product.id}">No. WhatsApp</label>
                <input type="tel" id="admin-edit-phone-${product.id}" name="phone" value="${product.phone || ''}">
              </div>
            </div>
            <div class="admin-form-row">
              <div class="admin-form-group">
                <label for="admin-edit-origin-${product.id}">Asal Daerah</label>
                <input type="text" id="admin-edit-origin-${product.id}" name="origin" value="${product.origin || ''}">
              </div>
              <div class="admin-form-group">
                <label for="admin-edit-condition-${product.id}">Kondisi</label>
                <select id="admin-edit-condition-${product.id}" name="condition">
                  <option value="Baru" ${(product.condition || 'Baru') === 'Baru' ? 'selected' : ''}>Baru</option>
                  <option value="Bekas - Seperti Baru" ${product.condition === 'Bekas - Seperti Baru' ? 'selected' : ''}>Bekas - Seperti Baru</option>
                  <option value="Bekas - Baik" ${product.condition === 'Bekas - Baik' ? 'selected' : ''}>Bekas - Baik</option>
                  <option value="Refurbished" ${product.condition === 'Refurbished' ? 'selected' : ''}>Refurbished</option>
                </select>
              </div>
            </div>
            <div class="admin-form-group">
              <label for="admin-edit-image-${product.id}">Gambar Produk</label>
              <input type="file" id="admin-edit-image-${product.id}" name="image" accept="image/*">
              <div class="admin-image-preview" id="admin-preview-${product.id}">
                <img src="${product.image || ''}" alt="Preview" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 4px;">
              </div>
            </div>
            <div class="admin-form-group admin-checkbox-group">
              <label>
                <input type="checkbox" name="popular" ${product.popular ? 'checked' : ''}> Produk Populer
              </label>
            </div>
            <div class="admin-form-actions">
              <button type="button" class="admin-btn-cancel">Batal</button>
              <button type="submit" class="admin-btn-save">Simpan</button>
            </div>
          </form>
        </div>
      `;

      // Add event handlers
      setupCardEventHandlers(productCard, product);
      productsList.appendChild(productCard);
    });
  }

  // Add product
  addProductForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('title', document.getElementById('productTitle').value);
    formData.append('category', document.getElementById('productCategory').value);
    formData.append('price', parseInt(document.getElementById('productPrice').value));
    formData.append('weight', document.getElementById('productWeight').value);
    formData.append('seller', document.getElementById('productSeller').value);
    formData.append('origin', document.getElementById('productOrigin').value || 'Tabalong, Kalimantan Selatan');
    formData.append('condition', document.getElementById('productCondition').value || 'Baru');
    formData.append('phone', document.getElementById('productPhone').value);
    if (document.getElementById('productPopular').checked) {
      formData.append('popular', '1');
    }

    // Add image file if selected
    const imageInput = document.getElementById('productImage');
    if (imageInput.files.length > 0) {
      formData.append('image', imageInput.files[0]);
    }

    try {
      const apiUrl = window.CONFIG?.API_BASE_URL || '/padi/api';
      const response = await fetch(apiUrl + '/manage-products.php', {
        method: 'POST',
        body: formData
      });

      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const result = await response.json();
      if (!result.success) throw new Error(result.message || 'Failed to add product');

      addProductForm.reset();
      hideImagePreview();
      await loadProductsFromDatabase();
      showNotification('Produk berhasil ditambahkan!', 'success');
    } catch (error) {
      console.error('Error adding product:', error);
      showNotification('Gagal menambahkan produk: ' + error.message, 'error');
    }
  });

  // Search and filter
  function applyFilters() {
    const searchTerm = searchProducts.value.toLowerCase();
    const selectedCategory = filterCategory.value;

    const filtered = products.filter((product) => {
      const matchSearch = product.title.toLowerCase().includes(searchTerm) ||
                         product.category.toLowerCase().includes(searchTerm) ||
                         (product.seller && product.seller.toLowerCase().includes(searchTerm)) ||
                         (product.origin && product.origin.toLowerCase().includes(searchTerm)) ||
                         (product.weight && product.weight.toLowerCase().includes(searchTerm));
      const matchCategory = !selectedCategory || product.category === selectedCategory;
      return matchSearch && matchCategory;
    });

    renderProducts(filtered);
  }

  searchProducts.addEventListener('input', applyFilters);
  filterCategory.addEventListener('change', applyFilters);

  // Setup event handlers for admin cards
  function setupCardEventHandlers(card, product) {
    const editBtn = card.querySelector('.admin-btn-edit');
    const deleteBtn = card.querySelector('.admin-btn-delete');
    const editForm = card.querySelector('.admin-edit-form');
    const editFormClose = card.querySelector('.admin-edit-form-close');
    const cancelBtn = card.querySelector('.admin-btn-cancel');

    // Edit button handler
    if (editBtn) {
      editBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleAdminEditForm(card, product);
      });
    }

    // Delete button handler
    if (deleteBtn) {
      deleteBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const productId = parseInt(e.currentTarget.getAttribute('data-product-id'));
        openDeleteModal(productId);
      });
    }

    // Edit form close handlers
    if (editFormClose) {
      editFormClose.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAdminEditForm(card);
      });
    }

    if (cancelBtn) {
      cancelBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAdminEditForm(card);
      });
    }

    // Edit form submit handler
    if (editForm) {
      editForm.addEventListener('submit', (e) => {
        e.preventDefault();
        e.stopPropagation();
        handleAdminEditSubmit(card, product);
      });
    }

    // Image file input handler
    const imageInput = card.querySelector(`#admin-edit-image-${product.id}`);
    if (imageInput) {
      imageInput.addEventListener('change', (e) => {
        handleImagePreview(e.target, product.id);
      });
    }
  }

  // Admin edit form functions
  function toggleAdminEditForm(card, product) {
    try {
      const editForm = card.querySelector('.admin-card__edit-form');
      const cardContent = card.querySelector('.admin-card__content');

      if (!editForm || !cardContent) {
        console.error('Edit form or card content not found');
        return;
      }

      const isExpanded = card.classList.contains('expanded');

      if (isExpanded) {
        closeAdminEditForm(card);
      } else {
        openAdminEditForm(card);
      }
    } catch (error) {
      console.error('Error toggling admin edit form:', error);
    }
  }

  function openAdminEditForm(card) {
    const editForm = card.querySelector('.admin-card__edit-form');
    const cardContent = card.querySelector('.admin-card__content');

    if (!editForm || !cardContent) return;

    // Close any other open edit forms
    document.querySelectorAll('.admin-product-card.expanded').forEach(otherCard => {
      if (otherCard !== card) {
        closeAdminEditForm(otherCard);
      }
    });

    card.classList.add('expanded');
    editForm.style.display = 'block';

    // Animate the expansion
    setTimeout(() => {
      editForm.classList.add('active');
    }, 10);

    // Focus on first input
    const firstInput = editForm.querySelector('input');
    if (firstInput) {
      setTimeout(() => firstInput.focus(), 300);
    }
  }

  function closeAdminEditForm(card) {
    const editForm = card.querySelector('.admin-card__edit-form');

    if (!editForm) return;

    editForm.classList.remove('active');

    setTimeout(() => {
      card.classList.remove('expanded');
      editForm.style.display = 'none';
    }, 300);
  }

  function handleAdminEditSubmit(card, product) {
    const editForm = card.querySelector('.admin-edit-form');
    if (!editForm) return;

    const formData = new FormData(editForm);
    const imageFile = formData.get('image');

    // Handle image upload
    if (imageFile && imageFile.size > 0) {
      // Convert image file to base64 data URL
      const reader = new FileReader();
      reader.onload = function(e) {
        const imageDataUrl = e.target.result;
        updateProductWithImage(product, formData, imageDataUrl, card);
      };
      reader.readAsDataURL(imageFile);
    } else {
      // No new image uploaded, keep existing image
      updateProductWithImage(product, formData, product.image, card);
    }
  }

  async function updateProductWithImage(product, formData, imageUrl, card) {
    try {
      const apiUrl = window.CONFIG?.API_BASE_URL || '/padi/api';
      const updateData = new FormData();
      updateData.append('id', product.id);
      updateData.append('title', formData.get('title'));
      updateData.append('category', formData.get('category'));
      updateData.append('price', parseInt(formData.get('price')));
      updateData.append('weight', formData.get('weight'));
      updateData.append('seller', formData.get('seller'));
      updateData.append('phone', formData.get('phone'));
      updateData.append('origin', formData.get('origin'));
      updateData.append('condition', formData.get('condition'));
      if (formData.has('popular')) {
        updateData.append('popular', '1');
      }

      const response = await fetch(apiUrl + '/manage-products.php', {
        method: 'PUT',
        body: updateData
      });

      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const result = await response.json();
      if (!result.success) throw new Error(result.message || 'Failed to update product');

      // Close edit form and refresh display
      closeAdminEditForm(card);
      await loadProductsFromDatabase();
      showNotification('Produk berhasil diperbarui!', 'success');
    } catch (error) {
      console.error('Error updating product:', error);
      showNotification('Gagal memperbarui produk: ' + error.message, 'error');
    }
  }

  function handleImagePreview(input, productId) {
    const file = input.files[0];
    const previewContainer = document.getElementById(`admin-preview-${productId}`);

    if (file && previewContainer) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = previewContainer.querySelector('img');
        if (img) {
          img.src = e.target.result;
        }
      };
      reader.readAsDataURL(file);
    }
  }

  // Delete modal
  function openDeleteModal(id) {
    deleteTargetId = id;
    const product = products.find((p) => p.id === id);
    document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus "${product.title}"?`;
    deleteModal.classList.add('active');
  }

  confirmDelete.addEventListener('click', async () => {
    try {
      const apiUrl = window.CONFIG?.API_BASE_URL || '/padi/api';
      const formData = new FormData();
      formData.append('id', deleteTargetId);

      const response = await fetch(apiUrl + '/manage-products.php', {
        method: 'DELETE',
        body: formData
      });

      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const result = await response.json();
      if (!result.success) throw new Error(result.message || 'Failed to delete product');

      deleteModal.classList.remove('active');
      await loadProductsFromDatabase();
      showNotification('Produk berhasil dihapus!', 'success');
    } catch (error) {
      console.error('Error deleting product:', error);
      showNotification('Gagal menghapus produk: ' + error.message, 'error');
    }
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

  // Initial render - load from database
  loadProductsFromDatabase();
});

