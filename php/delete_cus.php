<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $cus_id = $_POST['cus_id'];    
    
    mysqli_begin_transaction($conn); 
    $query = "DELETE FROM customer WHERE customer_id = '$cus_id'";  
    $result = mysqli_query($conn, $query); 
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }   

    $query = "DELETE FROM detail_customer WHERE customer_id = '$cus_id'";  
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