<?php
    include "./connect.php";
    
    if (!isset($_GET["kd_prd"])) {
        header("location: ./adminupload.php");
        exit;
    }

    try {
        // Prepare statement to prevent SQL injection
        $stmt = $con->prepare("DELETE FROM produk WHERE kd_prd = ?");
        $stmt->execute([$_GET["kd_prd"]]);

        if ($stmt->rowCount() > 0) {
            header("location: ./adminupload.php");
        } else {
            // No rows were deleted - product didn't exist
            header("location: ./adminupload.php?error=notfound");
        }
    } catch (PDOException $e) {
        // Log error (in production, don't expose error details to users)
        error_log("Delete error: " . $e->getMessage());
        header("location: ./adminupload.php?error=failed");
    }
    exit;
?>