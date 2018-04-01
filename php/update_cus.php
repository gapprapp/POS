<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $cus_id = $_POST['cus_id'];		
    $tel = $_POST['tel'];	
    $name = $_POST['name'];		
    $addr = $_POST['address'];

    mysqli_autocommit($conn,FALSE); 
    $sql = "UPDATE customer SET customer_name = '$name', address = '$addr', tel = '$tel'
    WHERE customer_id = '$cus_id'"; 
    $result = mysqli_query($conn, $sql);  

    if($result){
        echo "success";
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }

    mysqli_close($conn);	
?>