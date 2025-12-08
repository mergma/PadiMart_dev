<?php
    error_reporting(E_ALL);
    include "./connect.php";
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bima's Garage - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/adminmain.css?v=<?php echo time(); ?>">
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
    <div class="base" id="body">
        <div id="Navbar" class="overlay">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                <img class="sidelogo" src="img/logo4-w.png" alt="Logo">
                <a href="./adminupload.php" target="_self">Add New Vehicle</a>
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
        </div>
        
        <img class="titleimg" src="img/logo-b.png" alt="Logo">
        <br>
        <center>
            <div class="formcontainer">
                <h2>item filter</h2>
                <form class="h2" action="./srcprod.php" method="post">
                    <label for="src">Input item name:</label>
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
                        <h3>Product category: <?= htmlspecialchars($row['cat']); ?></h3>
                        <p>Available: <?= htmlspecialchars($row['qty']); ?></p>
                        <h2>Rp.<?= number_format($row['hrg_prd'], 2, '.', ','); ?></h2>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= htmlspecialchars($row['id']); ?>" class="edit-btn">Edit</a>
                            <a href="del.php?id=<?= htmlspecialchars($row['id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</a>
                        </div>
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
