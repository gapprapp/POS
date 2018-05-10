<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $cus_id = $_POST['cus_id'];		
    $tel = $_POST['tel'];	
    $name = $_POST['name'];		
    $addr = $_POST['address'];
    
    $sql = "UPDATE customer SET customer_name = '$name', address = '$addr', tel = '$tel'
    WHERE customer_id = '$cus_id'"; 
    $result = mysqli_query($conn, $sql);  

    if($result){
        echo "success";     
    }else{
        echo "fail";      
    }
    mysqli_close($conn);	
?>