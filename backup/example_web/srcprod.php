<?php
    include "./connect.php";

    if(isset($_POST['search'])){
        $src = $_POST["src"];

        $stmt = $con->prepare("SELECT * FROM produk WHERE nm_prd LIKE :search OR hrg_prd LIKE :search");
        $searchParam = "%$src%";
        $stmt->execute(['search' => $searchParam]);
        $daftarproduk = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($daftarproduk) > 0) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>prod</title>
    <link rel="stylesheet" href="css/adminupload.css?v=<?php echo time(); ?>">
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
    <div id="Navbar" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content">
            <img class="sidelogo" src="img/logo4-w.png" alt="Logo">
            <a href="./adminmain.php" target="_self">Main page</a>
            <a onclick="logoutConfirm()" id="logout" style="cursor:pointer">Logout</a>
            <a href="./logout.php" target="_self" id="confirm" style="cursor:pointer">Confirm</a>
            <a onclick="logoutCancel()" id="cancel" style="cursor:pointer">Cancel</a>
        </div>
    </div>
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <center>
        <div class="formcontainer">
            <h2>Find a Car</h2>
            <form class="h2" action="./srcprod.php" method="post">
                <label for="src">Input car name:</label>
                <input type="text" id="src" name="src" required><br>
                <input type="submit" name="search" value="Search" class="btndesign">
            </form>
        </div>
    </center>    <center>
    <a href="./adminupload.php" style="text-decoration: none; color:black;"><h3>Remove search filter</h3></a>
    <br><br><br><br>


        <h3 class="h2">Product List</h3>
        <table class="h2">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Code</th>
                <th>Category</th>
                <th>Image</th>
                <th>Actions</th>
                <th></th>
            </tr>
            <?php foreach ($daftarproduk as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['nm_prd']) ?></td>
                <td><?= htmlspecialchars($row['hrg_prd']) ?></td>
                <td><?= htmlspecialchars($row['kd_prd']) ?></td>
                <td><?= htmlspecialchars($row['cat']) ?></td>
                <td><img src="<?= htmlspecialchars($row['prd_img']) ?>" alt="Product Image" class="prodimg"></td>
                <td><a href="./del.php?kd_prd=<?= htmlspecialchars($row['kd_prd']) ?>">Delete</a></td>
                <td><a href="./edit.php?id=<?= htmlspecialchars($row['id']) ?>">Edit</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </center>
    <br>
</div>
<script>
    function openNav() {
        document.getElementById("Navbar").style.width = "30%";
    }

    function closeNav() {
        document.getElementById("Navbar").style.width = "0%";
    }

    function logoutConfirm() {
        document.getElementById("confirm").style.opacity = "100%";
        document.getElementById("cancel").style.opacity = "100%";
    }

    function logoutCancel() {
        document.getElementById("confirm").style.opacity = "0%";
        document.getElementById("cancel").style.opacity = "0%";
    }
</script>
</body>
</html>
<?php
        } else {
            echo "<script>alert('No products found'); window.location='adminupload.php';</script>";
        }
    }
?>

