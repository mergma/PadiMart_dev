<?php
    include "./connect.php";




    if(isset($_POST['search'])){
        $srcc = $_POST["src"];

        $find = "SELECT * FROM produk WHERE nm_prd like'%$srcc%' OR hrg_prd like'%$srcc%' ORDER BY kd_prd DESC";
        $findprod = $con->query($find);
        $findprod->setFetchMode(PDO::FETCH_ASSOC);
        $findlist=$findprod->fetchAll();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>prod-src</title>
    <link rel="stylesheet" href="adminupload.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="base">
    <div id="Navbar" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content">
        <img class="sidelogo" src="img/logo4-w.png" alt="Logo">
        <a href="./adminmain.php" target="_self" >Main page</a>
        </div>
    </div>
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
<br>
<br>
<br>
<br>

    <center><h3 class="h2">Daftar Produk</h3>
    <table class="h2">
        <tr>
            <th>id produk</th>
            <th>nama produk</th>
            <th>harga produk</th>
            <th>kode produk</th>
            <th>Gambar Produk</th>
        </tr>



    <?php
       foreach ($findlist as $row) {
    ?>
        <tr>
            <td><?=$row['id'];?></td>
            <td><?=$row['nm_prd'];?></td>
            <td><?=$row['hrg_prd'];?></td>
            <td><?=$row['kd_prd'];?></td>
            <td><img src="img/<?= $row['prd_img']; ?>" alt="Gambar Produk Disini" class="prodimg"></td>
            <td><a href="./del.php?kd_prd=<?=$row['kd_prd'];?>" target="_self" style="text-decoration:none">Delete</a></td>
            <td><a href="./edit.php?id=<?=$row['id'];?>" target="_self" style="text-decoration:none">Edit</a></td>
        </tr>
        
    <?php
        }
    ?>
    </table>
    <a href="./adminupload.php" target="_self" >Back</a>
    </center>
    <br>
    </div>
</body>
<script>
    function openNav() {
    document.getElementById("Navbar").style.width = "30%";
  }
  
  function closeNav() {
    document.getElementById("Navbar").style.width = "0%";
  }
</script>
</html>