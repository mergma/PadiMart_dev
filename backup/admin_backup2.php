<?php
require_once 'api/config.php';

// Handle product operations
$message = '';
$messageType = '';

// Add new product
if (isset($_POST['add_product'])) {
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $price = intval($_POST['price'] ?? 0);
    $weight = $conn->real_escape_string($_POST['weight'] ?? '');
    $seller = $conn->real_escape_string($_POST['seller'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $origin = $conn->real_escape_string($_POST['origin'] ?? '');
    $condition = $conn->real_escape_string($_POST['condition'] ?? 'Baru');
    $popular = isset($_POST['popular']) ? 1 : 0;
    $stock = intval($_POST['stock'] ?? 0);
    
    // Get category name
    $category = '';
    if ($category_id) {
        $catSql = "SELECT name FROM categories WHERE id = $category_id";
        $catResult = $conn->query($catSql);
        if ($catResult && $catRow = $catResult->fetch_assoc()) {
            $category = $catRow['name'];
        }
    }
    
    // Generate product code
    $codeSql = "SELECT product_code FROM products WHERE product_code LIKE 'KD_%' ORDER BY product_code DESC LIMIT 1";
    $codeResult = $conn->query($codeSql);
    if ($codeResult && $codeRow = $codeResult->fetch_assoc()) {
        $lastCode = $codeRow['product_code'];
        $lastNumber = intval(substr($lastCode, 3));
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    $productCode = 'KD_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
    // Handle image upload
    $imagePath = 'uploads/default.jpg';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = 'uploads/' . $filename;
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $filepath)) {
            $imagePath = $filepath;
        }
    }
    
    $categoryIdSql = $category_id ? $category_id : 'NULL';
    $sql = "INSERT INTO products (product_code, title, category, category_id, price, weight, seller, phone, origin, `condition`, image, popular, stock) 
            VALUES ('$productCode', '$title', '$category', $categoryIdSql, $price, '$weight', '$seller', '$phone', '$origin', '$condition', '$imagePath', $popular, $stock)";
    
    if ($conn->query($sql)) {
        $message = 'Produk berhasil ditambahkan';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'danger';
    }
}

// Edit product
if (isset($_POST['edit_product'])) {
    $id = intval($_POST['product_id']);
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $price = intval($_POST['price'] ?? 0);
    $weight = $conn->real_escape_string($_POST['weight'] ?? '');
    $seller = $conn->real_escape_string($_POST['seller'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $origin = $conn->real_escape_string($_POST['origin'] ?? '');
    $condition = $conn->real_escape_string($_POST['condition'] ?? 'Baru');
    $popular = isset($_POST['popular']) ? 1 : 0;
    $stock = intval($_POST['stock'] ?? 0);
    
    // Get category name
    $category = '';
    if ($category_id) {
        $catSql = "SELECT name FROM categories WHERE id = $category_id";
        $catResult = $conn->query($catSql);
        if ($catResult && $catRow = $catResult->fetch_assoc()) {
            $category = $catRow['name'];
        }
    }
    
    // Get existing image
    $imageSql = "SELECT image FROM products WHERE id = $id";
    $imageResult = $conn->query($imageSql);
    $imagePath = 'uploads/default.jpg';
    if ($imageResult && $imageRow = $imageResult->fetch_assoc()) {
        $imagePath = $imageRow['image'];
    }
    
    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        // Delete old image if not default
        if ($imagePath !== 'uploads/default.jpg' && file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = 'uploads/' . $filename;
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $filepath)) {
            $imagePath = $filepath;
        }
    }
    
    $categoryIdSql = $category_id ? $category_id : 'NULL';
    $sql = "UPDATE products SET title = '$title', category = '$category', category_id = $categoryIdSql, price = $price, 
            weight = '$weight', seller = '$seller', phone = '$phone', origin = '$origin', `condition` = '$condition', 
            image = '$imagePath', popular = $popular, stock = $stock WHERE id = $id";
    
    if ($conn->query($sql)) {
        $message = 'Produk berhasil diupdate';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'danger';
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get image path
    $imageSql = "SELECT image FROM products WHERE id = $id";
    $imageResult = $conn->query($imageSql);
    if ($imageResult && $imageRow = $imageResult->fetch_assoc()) {
        $imagePath = $imageRow['image'];
        if ($imagePath !== 'uploads/default.jpg' && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql)) {
        $message = 'Produk berhasil dihapus';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'danger';
    }
}

// Add new category
if (isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['category_name'] ?? '');
    $description = $conn->real_escape_string($_POST['category_description'] ?? '');

    if (empty($name)) {
        $message = 'Nama kategori harus diisi';
        $messageType = 'danger';
    } else {
        $checkSql = "SELECT id FROM categories WHERE name = '$name'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            $message = 'Nama kategori sudah ada';
            $messageType = 'danger';
        } else {
            $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";
            if ($conn->query($sql)) {
                $message = 'Kategori berhasil ditambahkan';
                $messageType = 'success';
            } else {
                $message = 'Error: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

// Edit category
if (isset($_POST['edit_category'])) {
    $id = intval($_POST['category_id']);
    $name = $conn->real_escape_string($_POST['category_name'] ?? '');
    $description = $conn->real_escape_string($_POST['category_description'] ?? '');

    if (empty($name)) {
        $message = 'Nama kategori harus diisi';
        $messageType = 'danger';
    } else {
        $checkSql = "SELECT id FROM categories WHERE name = '$name' AND id != $id";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            $message = 'Nama kategori sudah ada';
            $messageType = 'danger';
        } else {
            $sql = "UPDATE categories SET name = '$name', description = '$description' WHERE id = $id";
            if ($conn->query($sql)) {
                $message = 'Kategori berhasil diupdate';
                $messageType = 'success';
            } else {
                $message = 'Error: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

// Delete category
if (isset($_GET['delete_category'])) {
    $id = intval($_GET['delete_category']);

    $checkSql = "SELECT COUNT(*) as count FROM products WHERE category_id = $id";
    $checkResult = $conn->query($checkSql);
    $row = $checkResult->fetch_assoc();

    if ($row['count'] > 0) {
        $message = 'Tidak dapat menghapus kategori: Digunakan oleh ' . $row['count'] . ' produk';
        $messageType = 'warning';
    } else {
        $sql = "DELETE FROM categories WHERE id = $id";
        if ($conn->query($sql)) {
            $message = 'Kategori berhasil dihapus';
            $messageType = 'success';
        } else {
            $message = 'Error: ' . $conn->error;
            $messageType = 'danger';
        }
    }
}

// Fetch categories
$categoriesSql = "SELECT * FROM categories ORDER BY name";
$categoriesResult = $conn->query($categoriesSql);
$categories = [];
if ($categoriesResult) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch products with category information
$productsSql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC";
$productsResult = $conn->query($productsSql);
$products = [];
if ($productsResult) {
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }
}

// Generate next product code for display
$codeSql = "SELECT product_code FROM products WHERE product_code LIKE 'KD_%' ORDER BY product_code DESC LIMIT 1";
$codeResult = $conn->query($codeSql);
if ($codeResult && $codeRow = $codeResult->fetch_assoc()) {
    $lastCode = $codeRow['product_code'];
    $lastNumber = intval(substr($lastCode, 3));
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 1;
}
$nextProductCode = 'KD_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - PADI MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .nav-link {
            color: white !important;
        }
        .table-responsive {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .badge-popular {
            background-color: #58c234;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">PadiMart Admin</a>
            <div class="navbar-nav ms-auto">
                <a href="index.php" class="nav-link"><i class="fas fa-arrow-left"></i> Kembali ke Katalog</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">Manajemen Produk</h1>
                <p class="text-muted">Kelola produk dan kategori PADI MART</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-folder-plus"></i> Tambah Kategori
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Categories Section -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-folder"></i> Kategori</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-category"
                                                data-id="<?php echo $category['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($category['name']); ?>"
                                                data-description="<?php echo htmlspecialchars($category['description']); ?>"
                                                data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?delete_category=<?php echo $category['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada kategori</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-box"></i> Daftar Produk</h5>
                <div class="input-group w-50">
                    <input type="text" id="searchProduct" class="form-control" placeholder="Cari produk...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Penjual</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($products) > 0): ?>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['product_code']); ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                             alt="<?php echo htmlspecialchars($product['title']); ?>"
                                             class="product-image">
                                    </td>
                                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? $product['category'] ?? 'N/A'); ?></td>
                                    <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                    <td><?php echo $product['stock']; ?></td>
                                    <td><?php echo htmlspecialchars($product['seller'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if ($product['popular']): ?>
                                            <span class="badge badge-popular">Populer</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-product"
                                                data-id="<?php echo $product['id']; ?>"
                                                data-code="<?php echo htmlspecialchars($product['product_code']); ?>"
                                                data-title="<?php echo htmlspecialchars($product['title']); ?>"
                                                data-category="<?php echo $product['category_id'] ?? ''; ?>"
                                                data-price="<?php echo $product['price']; ?>"
                                                data-weight="<?php echo htmlspecialchars($product['weight']); ?>"
                                                data-seller="<?php echo htmlspecialchars($product['seller']); ?>"
                                                data-phone="<?php echo htmlspecialchars($product['phone']); ?>"
                                                data-origin="<?php echo htmlspecialchars($product['origin']); ?>"
                                                data-condition="<?php echo htmlspecialchars($product['condition']); ?>"
                                                data-stock="<?php echo $product['stock']; ?>"
                                                data-popular="<?php echo $product['popular']; ?>"
                                                data-bs-toggle="modal" data-bs-target="#editProductModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?delete=<?php echo $product['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada produk</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="category_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="category_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="add_category" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_category_id" name="category_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_category_name" name="category_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_category_description" name="category_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_category" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kode Produk</label>
                                <input type="text" class="form-control" value="<?php echo $nextProductCode; ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="price" min="0" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Berat/Volume</label>
                                <input type="text" class="form-control" name="weight" placeholder="Contoh: 1 kg">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stock" value="0" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Penjual</label>
                                <input type="text" class="form-control" name="seller">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. WhatsApp</label>
                                <input type="tel" class="form-control" name="phone" placeholder="+628xxx">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Asal Daerah</label>
                                <input type="text" class="form-control" name="origin" placeholder="Tabalong, Kalimantan Selatan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kondisi</label>
                                <select class="form-select" name="condition">
                                    <option value="Baru">Baru</option>
                                    <option value="Bekas - Seperti Baru">Bekas - Seperti Baru</option>
                                    <option value="Bekas - Baik">Bekas - Baik</option>
                                    <option value="Refurbished">Refurbished</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" class="form-control" name="product_image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="popular" id="add_popular">
                                <label class="form-check-label" for="add_popular">Tandai sebagai Produk Populer</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Tambah Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_product_id" name="product_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kode Produk</label>
                                <input type="text" class="form-control" id="edit_product_code" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_category_id" name="category_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_price" name="price" min="0" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Berat/Volume</label>
                                <input type="text" class="form-control" id="edit_weight" name="weight">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" id="edit_stock" name="stock" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Penjual</label>
                                <input type="text" class="form-control" id="edit_seller" name="seller">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. WhatsApp</label>
                                <input type="tel" class="form-control" id="edit_phone" name="phone">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Asal Daerah</label>
                                <input type="text" class="form-control" id="edit_origin" name="origin">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kondisi</label>
                                <select class="form-select" id="edit_condition" name="condition">
                                    <option value="Baru">Baru</option>
                                    <option value="Bekas - Seperti Baru">Bekas - Seperti Baru</option>
                                    <option value="Bekas - Baik">Bekas - Baik</option>
                                    <option value="Refurbished">Refurbished</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" class="form-control" name="product_image" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="popular" id="edit_popular">
                                <label class="form-check-label" for="edit_popular">Tandai sebagai Produk Populer</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_product" class="btn btn-primary">Update Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search product functionality
        document.getElementById('searchProduct').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productsTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

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
    </script>
</body>
</html>

