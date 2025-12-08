<?php
    error_reporting(E_ALL);
    include "./connect.php";

    // Initialize shopping cart session if not exists
    session_start();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add to cart functionality
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        
        // Check product quantity in database
        $stmt = $con->prepare("SELECT qty FROM produk WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product && $product['qty'] > 0) {
            // Add to cart
            if (!isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = 1;
            } else {
                $_SESSION['cart'][$product_id]++;
            }
            
            // Reduce quantity in database
            $stmt = $con->prepare("UPDATE produk SET qty = qty - 1 WHERE id = ?");
            $stmt->execute([$product_id]);
        } else {
            // Product is out of stock
            echo "<script>alert('Sorry, this product is out of stock!');</script>";
        }
    }

    // Remove from cart functionality
    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bima's Garage - User Main</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/usermain.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="base" id="body">
        <div id="Navbar" class="overlay">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                <img class="sidelogo" src="img/logo4-w.png" alt="Logo">
                <a href="./cart.php" target="_self">View Cart (<?php echo count($_SESSION['cart']); ?>)</a>
                <a href="./logout.php" id="logout">Logout</a>
            </div>
        </div>
        <div class="mainnavv">
            <div class="nav-left">
                <span onclick="openNav()" class="menu-icon">&#9776;</span>
            </div>
            <div class="nav-center">
                <img class="nav-logo" src="img/logo-b.png" alt="Logo">
            </div>
            <div class="nav-right">
                <div class="othernav">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16" onclick="window.location.href='./cart.php'">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                </div>
            </div>
        </div>
        
        <img class="titleimg" src="img/logo-b.png" alt="Logo">
        <br>
        <center>
            <div class="formcontainer">
                <h2>Find a Car</h2>
                <form class="h2" action="./srcprod.php" method="post">
                    <label for="src">Input car name:</label>
                    <input type="text" id="src" name="src" required>
                    <input type="submit" name="search" value="Search" class="btndesign">
                </form>
            </div>
        </center>

        <div class="prod">
            <?php
                $produk = "SELECT * FROM produk";
                $tampilproduk = $con->query($produk);
                $tampilproduk->setFetchMode(PDO::FETCH_ASSOC);
                $daftarproduk = $tampilproduk->fetchAll();

                foreach ($daftarproduk as $row) {
            ?>
            <center>
                <div id="prod-<?= htmlspecialchars($row['id']); ?>" class="product-card">
                    <img src="<?= htmlspecialchars($row['prd_img']); ?>" alt="Product Image" class="prodsimg">
                    <div class="desc">
                        <h1><?= htmlspecialchars($row['nm_prd']); ?></h1>
                        <p><?= htmlspecialchars($row['prd_data']); ?></p>
                        <h3>Item type: <?= htmlspecialchars($row['cat']); ?></h3>
                        <p>Available: <?= htmlspecialchars($row['qty']); ?></p>
                        <h2>Rp.<?= number_format($row['hrg_prd'], 2, '.', ','); ?></h2>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="add_to_cart" class="btndesign">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </center>
            <?php
                }
            ?>
        </div>

        <div class="footer">
            <center>
                <img class="footerimg" src="img/logo2-w.png" alt="Logo">
                <p class="copyright">Ⓒ Bima's Garage LLC Copyright 2024</p>
                <div class="socials">
                    <a href="http://instagram.com"><img src="img/instagram.png" alt="Instagram"></a>
                    <a href="http://discord.com"><img src="img/discord.png" alt="Discord"></a>
                    <a href="http://youtube.com"><img src="img/youtube.png" alt="YouTube"></a>
                </div>
                <div class="contact-info">
                    <p>+62 123 4567 890</p>
                    <p>contact@bimasgarage.com</p>
                </div>
            </center>
        </div>
    </div>

    <script>
        function openNav() {
            document.getElementById("Navbar").style.width = "30%";
        }
        
        function closeNav() {
            document.getElementById("Navbar").style.width = "0%";
        }

        window.onscroll = function() {
            var body = document.getElementById('body');
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            var scrollFactor = 0.2;
            body.style.backgroundPositionY = "-" + (scrollTop * scrollFactor) + "px";
        }
    </script>
</body>
</html> 