<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - PADI MART</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/admin.css" />
    <link rel="stylesheet" href="css/responsive-utilities.css" />
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <span class="brand-text">PadiMart Admin</span>
            </div>
            <div class="nav-links">
                <a href="index.php" class="nav-link">← Kembali ke Katalog</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-container">
        <div class="container">
            <!-- Header -->
            <div class="admin-header">
                <h1>Kelola Produk</h1>
                <p class="subtitle">Tambah, edit, atau hapus produk dari katalog</p>
            </div>

            <!-- Add Product Form -->
            <div class="form-section">
                <h2>Tambah Produk Baru</h2>
                <form id="addProductForm" class="product-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="productTitle">Nama Produk *</label>
                            <input type="text" id="productTitle" name="title" placeholder="Contoh: Beras Organik Premium" required />
                        </div>
                        <div class="form-group">
                            <label for="productCategory">Kategori *</label>
                            <select id="productCategory" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Beras">Beras</option>
                                <option value="Camilan & Olahan">Camilan & Olahan</option>
                                <option value="Kerajinan & Oleh-oleh">Kerajinan & Oleh-oleh</option>
                                <option value="Pupuk">Pupuk</option>
                                <option value="Benih">Benih</option>
                                <option value="Alat">Alat</option>
                                <option value="Edukasi">Edukasi</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productPrice">Harga (Rp) *</label>
                            <input type="number" id="productPrice" name="price" placeholder="Contoh: 120000" min="0" required />
                        </div>
                        <div class="form-group">
                            <label for="productWeight">Berat/Volume *</label>
                            <input type="text" id="productWeight" name="weight" placeholder="Contoh: 1 kg, 500 gram, 2 liter" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productPhone">Nomor WhatsApp Penjual *</label>
                            <input type="tel" id="productPhone" name="phone" placeholder="Contoh: +628123456789" required />
                        </div>
                        <div class="form-group">
                            <label for="productSeller">Nama Penjual *</label>
                            <input type="text" id="productSeller" name="seller" placeholder="Contoh: Petani Organik Sejahtera" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productOrigin">Asal Daerah</label>
                            <input type="text" id="productOrigin" name="origin" placeholder="Contoh: Tabalong, Kalimantan Selatan" />
                        </div>
                        <div class="form-group">
                            <label for="productCondition">Kondisi Produk</label>
                            <select id="productCondition" name="condition">
                                <option value="Baru">Baru</option>
                                <option value="Bekas - Seperti Baru">Bekas - Seperti Baru</option>
                                <option value="Bekas - Baik">Bekas - Baik</option>
                                <option value="Refurbished">Refurbished</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productImage">Gambar Produk</label>
                        <input type="file" id="productImage" name="image" accept="image/*" />
                        <small class="form-help">Pilih file gambar (JPG, PNG, GIF, dll.)</small>
                        <div id="imagePreview" class="image-preview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" />
                            <button type="button" id="removeImage" class="btn-remove">×</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productPopular">
                            <input type="checkbox" id="productPopular" name="popular" />
                            Tandai sebagai Produk Populer
                        </label>
                    </div>

                    <button type="submit" class="btn-primary">+ Tambah Produk</button>
                </form>
            </div>

            <!-- Products Statistics -->
            <div class="stats-section">
                <h2>Statistik Produk</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number" id="totalProducts">0</div>
                        <div class="stat-label">Total Produk</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="popularProducts">0</div>
                        <div class="stat-label">Produk Populer</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="totalCategories">0</div>
                        <div class="stat-label">Kategori</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="totalSellers">0</div>
                        <div class="stat-label">Penjual</div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="products-section">
                <h2>Daftar Produk</h2>
                
                <div class="products-controls">
                    <input type="search" id="searchProducts" placeholder="Cari produk..." />
                    <select id="filterCategory">
                        <option value="">Semua Kategori</option>
                        <option value="Beras">Beras</option>
                        <option value="Camilan & Olahan">Camilan & Olahan</option>
                        <option value="Kerajinan & Oleh-oleh">Kerajinan & Oleh-oleh</option>
                        <option value="Pupuk">Pupuk</option>
                        <option value="Benih">Benih</option>
                        <option value="Alat">Alat</option>
                        <option value="Edukasi">Edukasi</option>
                    </select>
                </div>

                <div id="productsList" class="products-list">
                    <!-- Products will be rendered here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Hapus Produk?</h3>
            <p id="deleteMessage">Apakah Anda yakin ingin menghapus produk ini?</p>
            <div class="modal-buttons">
                <button id="confirmDelete" class="btn-danger">Hapus</button>
                <button id="cancelDelete" class="btn-secondary">Batal</button>
            </div>
        </div>
    </div>

    <script src="js/products-data.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>

