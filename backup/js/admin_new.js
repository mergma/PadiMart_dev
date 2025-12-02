/**
 * Admin Panel JavaScript - Simplified for Modal-Based System
 * This version works with the new admin.php that uses Bootstrap modals
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin panel loaded - Modal-based system');

    // Search product functionality
    const searchInput = document.getElementById('searchProduct');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productsTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Edit category - populate modal
    document.querySelectorAll('.edit-category').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_category_id').value = this.dataset.id;
            document.getElementById('edit_category_name').value = this.dataset.name;
            document.getElementById('edit_category_description').value = this.dataset.description;
        });
    });

    // Edit product - populate modal
    document.querySelectorAll('.edit-product').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_product_id').value = this.dataset.id;
            document.getElementById('edit_product_code').value = this.dataset.code;
            document.getElementById('edit_title').value = this.dataset.title;
            document.getElementById('edit_category_id').value = this.dataset.category;
            document.getElementById('edit_price').value = this.dataset.price;
            document.getElementById('edit_weight').value = this.dataset.weight;
            document.getElementById('edit_seller').value = this.dataset.seller;
            document.getElementById('edit_phone').value = this.dataset.phone;
            document.getElementById('edit_origin').value = this.dataset.origin;
            document.getElementById('edit_condition').value = this.dataset.condition;
            document.getElementById('edit_stock').value = this.dataset.stock;
            document.getElementById('edit_popular').checked = this.dataset.popular == '1';
        });
    });

    // Image preview for file inputs
    const fileInputs = document.querySelectorAll('input[type="file"][accept="image/*"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB');
                    this.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar');
                    this.value = '';
                    return;
                }

                // Show preview (optional enhancement)
                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('Image loaded:', file.name);
                    // You can add preview functionality here if needed
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Auto-format phone number
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.startsWith('08')) {
                value = '628' + value.substring(2); // Convert 08xx to 628xx
            }
            if (value && !value.startsWith('+')) {
                value = '+' + value; // Add + prefix
            }
            e.target.value = value;
        });
    });

    // Confirm delete actions
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Yakin ingin menghapus item ini?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    console.log('Admin panel initialized successfully');
});

