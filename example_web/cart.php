<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = max(1, intval($_POST['quantity']));
    $old_quantity = $_SESSION['cart'][$product_id];
    
    // Calculate quantity difference
    $quantity_diff = $old_quantity - $new_quantity;
    
    if ($quantity_diff != 0) {
        // Update database quantity
        $stmt = $con->prepare("UPDATE produk SET qty = qty + ? WHERE id = ?");
        $stmt->execute([$quantity_diff, $product_id]);
        
        // Update cart quantity
        $_SESSION['cart'][$product_id] = $new_quantity;
    }
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        $quantity = $_SESSION['cart'][$product_id];
        
        // Return quantity to database
        $stmt = $con->prepare("UPDATE produk SET qty = qty + ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);
        
        // Remove from cart
        unset($_SESSION['cart'][$product_id]);
    }
}

// Get cart items details from database
$cart_items = array();
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    $stmt = $con->prepare("SELECT * FROM produk WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$current_date = date("F j, Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Bima's Garage</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/cart.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="base" id="body">
        <!-- Navigation -->
        <nav class="mainnavv">
            <div class="nav-left">
                <span onclick="openNav()" class="menu-icon">&#9776;</span>
            </div>
            <div class="nav-center">
                <img class="nav-logo" src="img/logo-b.png" alt="Logo">
            </div>
            <div class="nav-right">
                <a href="./usermain.php" class="continue-shopping">Continue Shopping</a>
            </div>
        </nav>

        <!-- Sidebar -->
        <div id="Navbar" class="overlay">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                <img class="sidelogo" src="img/logo4-w.png" alt="Logo">
                <a href="./usermain.php">Back to Shop</a>
                <a href="./logout.php" id="logout">Logout</a>
            </div>
        </div>

        <div class="cart-container">
            <div class="cart-header">
                <h1>Bimer's Basement</h1>
                <h3>Receipt</h3>
                <form class="cart-info">
                    <label for="customer">Customer:</label>
                    <input type="text" id="customer" name="customer" required>
                    <label for="date">Date:</label>
                    <input type="text" id="date" name="date" value="<?php echo $current_date; ?>" readonly>
                </form>
            </div>

            <?php if (!empty($cart_items)): ?>
            <div class="cart-grid">
                <div class="cart-items">
                    <?php 
                    $subtotal = 0;
                    foreach ($cart_items as $item): 
                        $quantity = $_SESSION['cart'][$item['id']];
                        $item_total = $item['hrg_prd'] * $quantity;
                        $subtotal += $item_total;
                    ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['prd_img']); ?>" alt="<?= htmlspecialchars($item['nm_prd']); ?>">
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['nm_prd']); ?></h3>
                            <p>Item type: <?= htmlspecialchars($item['cat']); ?></p>
                            <div class="item-price">Rp.<?= number_format($item['hrg_prd'], 2); ?></div>
                            <form method="post" class="quantity-controls">
                                <input type="hidden" name="product_id" value="<?= $item['id']; ?>">
                                <input type="number" name="quantity" value="<?= $quantity; ?>" min="1" class="quantity-input">
                                <button type="submit" name="update_quantity" class="update-btn">Update</button>
                                <button type="submit" name="remove_item" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>Rp.<?= number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-item">
                        <span>Cash Given</span>
                        <input type="number" id="cash-given" name="cash-given" required>
                    </div>
                    <?php
                    if (isset($_POST['cash-given'])) {
                        $cash_given = $_POST['cash-given'];
                        if ($cash_given > $subtotal) {
                            $change = $cash_given - $subtotal;
                            echo "<div class='summary-item'><span>Change</span><span>$" . number_format($change, 2) . "</span></div>";
                        }
                    }
                    ?>
                    <div class="summary-total">
                        <span>Total</span>
                        <span>Rp.<?= number_format($subtotal, 2); ?></span>
                    </div>
                    <button class="checkout-btn" onclick="handleCheckout()">Proceed to Checkout</button>
                </div>
            </div>
            <?php else: ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <a href="./usermain.php" class="continue-shopping">Continue Shopping</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function openNav() {
            document.getElementById("Navbar").style.width = "30%";
        }
        
        function closeNav() {
            document.getElementById("Navbar").style.width = "0%";
        }

        function handleCheckout() {
            // Hide elements before printing
            document.querySelectorAll('.continue-shopping, .quantity-controls, .checkout-btn').forEach(el => {
                el.style.display = 'none';
            });
            
            // Trigger print
            window.print();
            
            // Show elements again after print dialog is closed
            window.onafterprint = function() {
                document.querySelectorAll('.continue-shopping, .quantity-controls').forEach(el => {
                    el.style.display = 'block';
                });
            };
        }
    </script>
</body>
</html> 