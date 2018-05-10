<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];    
    
    mysqli_begin_transaction($conn); 
    $query = "DELETE FROM product WHERE prod_id = '$prod_id'";  
    $result = mysqli_query($conn, $query); 
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }   

    $query = "DELETE FROM product_branch WHERE prod_id = '$prod_id'";  
    $result = mysqli_query($conn, $query);   
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    } 

    $query = "DELETE FROM detail_customer WHERE prod_id = '$prod_id'";  
    $result = mysqli_query($conn, $query); 
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
    mysqli_commit($conn); 
    echo "success";
    mysqli_close($conn);
?>