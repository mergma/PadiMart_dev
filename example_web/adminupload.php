<?php
include "./connect.php"; 
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
<div class="base" id="body">
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
    <div class="formstyle">
        <div class="formcontainer">
            <?php
                function autocodegeneration($con){
                    $stmt = $con -> query("SELECT MAX(RIGHT(kd_prd,3)) AS last_number FROM produk");
                    $row = $stmt -> fetch();

                    $lastnum = $row['last_number']? $row['last_number'] : 0;
                    $newnum = $lastnum + 1;
                    $newnum = sprintf("%03d", $newnum);

                    $itemcode = "ITEM" . $newnum;

                    return $itemcode;
                }
                
                // Add this line to generate the code before the form
                $itemcode = autocodegeneration($con);
            ?>
            <form class="h2" action="<?= $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                <label for="kd_prd">Item Code:</label>
                <input type="text" id="kd_prd" name="kd_prd" placeholder="Product code" required value="<?php echo $itemcode; ?>">
                <br>
                <label for="nm_prd">Item Name:</label>
                <input type="text" id="nm_prd" name="nm_prd" placeholder="Product name" required>
                <br><br>
                <label for="hrg_prd"> Item Cost : </label>
                <input type="text" id="hrg_prd" name="hrg_prd" placeholder="Product Price" required>
                <br><br>
                <label for="cat"> Item Category : </label>
                <input type="text" id="cat" name="cat" placeholder="Product Category" required>
                <br><br>
                <label for="qtprd_datay">item Description : </label>
                <input type="number" id="prd_data" name="prd_data" placeholder="Product Description" required>
                <br><br>
                <label for="qty">item Quantity : </label>
                <input type="number" id="qty" name="qty" placeholder="Product Quantity" required>
                <br><br>
                <label for="prd_img"> Product Image : </label>
                <input type="file" id="prd_img" name="prd_img" class="imgupload" required>
                <br><br>
                <input type="submit" name="ok" value="save" class="btndesign">
            </form>
        </div>
    </div>
    <center>
        <div class="formcontainer">
            <h2>Find an item</h2>
            <form class="h2" action="./srcprod.php" method="post">
                <label for="src"> input Item name : </label>
                <input type="text" id="src" name="src"><br>
                <input type="submit" name="search" id="search" value="Search" class="btndesign">
            </form>
        </div>
    </center>
    <br><br><br><br>
    <?php
        if (isset($_POST["ok"])) {
            autocodegeneration($con);
            $kd_prd = $itemcode;
            $nm_prd = $_POST['nm_prd'];
            $hrg_prd = $_POST['hrg_prd'];
            $cat = $_POST['cat'];
            $qty = $_POST['qty'];
            $prd_data = $_POST['prd_data'];

            // Check if the file was uploaded without errors    
            if (isset($_FILES['prd_img']) && $_FILES['prd_img']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['prd_img']['name'];
                $file_temp = $_FILES['prd_img']['tmp_name'];
                $dir = './media/';
                $prd_img = $dir . $file_name;

                // Ensure the directory exists
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                // Move the uploaded file to the specified directory
                if (move_uploaded_file($file_temp, $prd_img)) {
                    $save = $con->prepare("INSERT IGNORE INTO produk (kd_prd, nm_prd, hrg_prd, prd_img, cat, qty, prd_data) 
                                          VALUES (:kd_prd, :nm_prd, :hrg_prd, :prd_img, :cat, :qty, :prd_data)");

                    $save->bindParam(':kd_prd', $kd_prd);
                    $save->bindParam(':nm_prd', $nm_prd);
                    $save->bindParam(':hrg_prd', $hrg_prd);
                    $save->bindParam(':prd_img', $prd_img);
                    $save->bindParam(':cat', $cat);
                    $save->bindParam(':qty', $qty);
                    $save->bindParam(':prd_data', $prd_data);

                    // Execute the query and check for errors
                    if ($save->execute()) {
                        echo "New record created successfully";
                        // Generate new code for next item
                        $newItemCode = autocodegeneration($con);
                        ?>
                        <script>
                            // Update the product code input with the new code
                            document.getElementById('kd_prd').value = '<?php echo $newItemCode; ?>';
                        </script>
                        <?php   
                    } else {
                        $errorInfo = $save->errorInfo();
                        echo "Error: " . $errorInfo[2];
                    }
                } else {
                    echo "Failed to move uploaded file.";
                }
            } else {
                echo "File upload error: " . (isset($_FILES['prd_img']['error']) ? $_FILES['prd_img']['error'] : 'No file uploaded');
            }
        }
    ?>
    <center>
        <h3 class="h2">Daftar Produk</h3>
        <table class="h2">
            <tr>
                <th>id produk</th>
                <th>nama produk</th>
                <th>harga produk</th>
                <th>kode produk</th>
                <th>Kategori produk</th>
                <th>Gambar Produk</th>
                <th></th>
            </tr>
            <?php
                $produk = "SELECT * FROM produk";
                $tampilproduk = $con->query($produk);
                $tampilproduk->setFetchMode(PDO::FETCH_ASSOC);
                $daftarproduk = $tampilproduk->fetchAll();

                foreach ($daftarproduk as $row) {
            ?>
            <tr>
                <td><?=$row['id'];?></td>
                <td><?=$row['nm_prd'];?></td>
            <td>$<?= number_format($row["hrg_prd"], 2, '.', ','); ?></td>
                <td><?=$row['kd_prd'];?></td>
                <td><?=$row['cat'];?></td>
                <td><?=$row['prd_data'];?></td>
                <td><?=$row['qty'];?></td>
                <td><img src="<?= $row['prd_img']; ?>" alt="Gambar Produk Disini" class="prodimg"></td>
                <td style="display:list;"><a href="javascript:void(0)" onclick="confirmDelete('<?=$row['kd_prd'];?>')" style="text-decoration:none; border: solid 1px black; border-radius: 5px; margin: 10px; color:black;">  Delete  </a>
                    <a href="./edit.php?id=<?=$row['id'];?>" target="_self" style="text-decoration:none; border: solid 1px black; border-radius: 5px; margin: 10px; color:black;">   Edit   </a>
                </td>
            </tr>
            <?php
                }
            ?>
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

    function confirmDelete(kd_prd) {
        if (confirm("Are you sure you want to delete this product?")) {
            window.location.href = "./del.php?kd_prd=" + encodeURIComponent(kd_prd);
        }
    }
</script>
</body>
</html>
