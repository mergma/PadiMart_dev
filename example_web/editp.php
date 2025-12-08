<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include "./connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nm_prd = $_POST['name'];
    $hrg_prd = $_POST['price'];
    $kd_prd = $_POST['code'];
    $cat = $_POST['category'];
    $qty = $_POST['quantity'];

    try {
        // Start transaction
        $con->beginTransaction();

        if (!empty($_FILES['image']['name'])) {
            // Handle new image upload
            $file_name = $_FILES['image']['name'];
            $file_temp = $_FILES['image']['tmp_name'];
            $dir = './media/';
            $prd_img = $dir . $file_name;

            // Ensure the directory exists
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            // Move the uploaded file
            if (!move_uploaded_file($file_temp, $prd_img)) {
                throw new Exception("Failed to upload image");
            }

            // Update query with new image
            $stmt = $con->prepare("UPDATE produk SET 
                nm_prd = :nm_prd,
                hrg_prd = :hrg_prd,
                kd_prd = :kd_prd,
                cat = :cat,
                qty = :qty,
                prd_img = :prd_img
                WHERE id = :id");
            $stmt->bindParam(':prd_img', $prd_img);
        } else {
            // Update query without changing the image
            $stmt = $con->prepare("UPDATE produk SET 
                nm_prd = :nm_prd,
                hrg_prd = :hrg_prd,
                kd_prd = :kd_prd,
                cat = :cat,
                qty = :qty
                WHERE id = :id");
        }

        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nm_prd', $nm_prd);
        $stmt->bindParam(':hrg_prd', $hrg_prd);
        $stmt->bindParam(':kd_prd', $kd_prd);
        $stmt->bindParam(':cat', $cat);
        $stmt->bindParam(':qty', $qty);

        // Execute the query
        if ($stmt->execute()) {
            $con->commit();
            $_SESSION['success_message'] = "Vehicle updated successfully!";
            header("Location: adminmain.php");
            exit();
        } else {
            throw new Exception("Failed to update vehicle");
        }
    } catch (Exception $e) {
        $con->rollBack();
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: edit.php?id=" . $id);
        exit();
    }
} else {
    header("Location: adminmain.php");
    exit();
}
?>