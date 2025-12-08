<?php
    
    $dsn = "mysql:dbname=bimersbasement;host=localhost";
    $username = "root";
    $password = "";
    $con = new PDO ($dsn, $username, $password);


    if ($con) {
        $produk = "CREATE TABLE IF NOT EXISTS produk (
        id int not null auto_increment primary key,
        kd_prd text (10) not null unique,
        nm_prd text (1000) not null unique,
        hrg_prd int not null,
        prd_data text not null,
        cat text not null,
        qty int not null,
        prd_img varchar(255) not null
        )";
        $con->query($produk);

        $users = "CREATE TABLE IF NOT EXISTS users (
        id int not null auto_increment primary key,
        username varchar(50) not null unique,
        email varchar(100) not null unique,
        password varchar(255) not null,
        created_at timestamp default current_timestamp
        )";
        $con->query($users);
    } else {
        print "failed";
    }
?>