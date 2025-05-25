
<?php 
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $database ='ql_mamnon_nhom11';

    $conn= new mysqli($server,$user,$pass,$database);
    if($conn){
        mysqli_query($conn, " SET NAMES 'utf8mb4' ");
    }else{
        echo "ket noi khong thnah coong";
    }

