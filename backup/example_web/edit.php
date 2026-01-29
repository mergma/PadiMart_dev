<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: adminmain.php");
    exit();
}

include("connect.php");  // Changed from config.php to connect.php to match your existing setup

$id = $_GET['id'];
$stmt = $con->prepare("SELECT * FROM produk WHERE id = ?");  // Changed to use PDO and your table name 'produk'
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: adminmain.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle - Bima's Garage</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/edit.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    ::-webkit-scrollbar {
  width: 40px;
}

/* Track */
::-webkit-scrollbar-track {
  background: url(img/scrollbar-bg.png); 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: url(img/scrollbar.png);
  height: 150px;
  background-repeat: no-repeat;
  background-position: center;
}
</style>
<body>
    <div class="base">
        <!-- Navigation Bar -->
        <nav class="mainnavv">
            <div class="nav-left">
                <span class="menu-icon" onclick="openNav()">&#9776;</span>
                <a href="adminmain.php">
                    <img src="img/logo.png" alt="Logo" class="nav-logo">
                </a>
            </div>
            <div class="nav-center">
                <h2>Edit Vehicle</h2>
            </div>
            <div class="nav-right">
                <a href="adminupload.php" class="back-btn">Add New Vehicle</a>
                <a href="logout.php" class="back-btn">Logout</a>
            </div>
        </nav>

        <!-- Sidebar Overlay -->
        <div id="mySidenav" class="overlay">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                <img src="img/logo.png" alt="Logo" class="sidelogo">
                <a href="adminmain.php">Dashboard</a>
                <a href="adminupload.php">Add New Vehicle</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <div class="edit-container">
            <div class="edit-content">
                <!-- Error Messages -->
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="error-message">
                        <?php 
                            echo $_SESSION['error_message'];
                            unset($_SESSION['error_message']);
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Edit Form -->
                <form class="edit-form" action="editp.php" method="post" enctype="multipart/form-data">
                    <h2 class="edit-title">Edit Vehicle Details</h2>
                    
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Vehicle Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($data['nm_prd']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price (Dollars)</label>
                        <input type="number" name="price" value="<?php echo htmlspecialchars($data['hrg_prd']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="code">Vehicle Code</label>
                        <input type="text" name="code" value="<?php echo htmlspecialchars($data['kd_prd']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" name="category" value="<?php echo htmlspecialchars($data['cat']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($data['qty']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="image">Vehicle Image</label>
                        <input type="file" name="image" class="file-input">
                        <small>Leave empty to keep the current image</small>
                    </div>

                    <div class="button-group">
                        <a href="adminmain.php" class="cancel-btn">Cancel</a>
                        <button type="submit" class="save-btn">Save Changes</button>
                    </div>
                </form>

                <!-- Preview Card -->
                <div class="preview-card">
                    <h2>Current Vehicle Preview</h2>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($data['prd_img']); ?>" alt="<?php echo htmlspecialchars($data['nm_prd']); ?>" class="preview-img">
                        <div class="preview-details">
                            <h3><?php echo htmlspecialchars($data['nm_prd']); ?></h3>
                            <p>Code: <?php echo htmlspecialchars($data['kd_prd']); ?></p>
                            <p>Category: <?php echo htmlspecialchars($data['cat']); ?></p>
                            <p>Stock: <?php echo htmlspecialchars($data['qty']); ?> units</p>
                            <p class="price">$   <?php echo number_format($data['hrg_prd'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div style="text-align: center;">
                <img src="img/logo.png" alt="Logo" class="footerimg">
                <div class="socials">
                    <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="img/twitter.png" alt="Twitter"></a>
                    <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
                </div>
                <p class="copyright">&copy; 2024 Bima's Garage. All rights reserved.</p>
                <p class="contact-info">
                    Contact us: info@bimasgarage.com<br>
                    Phone: (021) 123-4567
                </p>
            </div>
        </footer>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "100%";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>
</body>
</html>