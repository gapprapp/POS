<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $cus_id = $_POST['cus_id'];    
    
    mysqli_autocommit($conn,FALSE); 
    $query = "DELETE FROM customer WHERE customer_id = '$cus_id'";  
    $result = mysqli_query($conn, $query);    

    $query = "DELETE FROM detail_customer WHERE customer_id = '$cus_id'";  
    $result = mysqli_query($conn, $query);    

    if($result){
        echo "success";
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }

    mysqli_close($conn);
?>