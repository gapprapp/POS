<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $name  = $_POST['name'];
    $addr  = $_POST['address'];
    $tel  = $_POST['tel'];     
    $type = $_POST['type'];	

    $sql = "INSERT INTO customer(customer_name,address,tel,customer_type) VALUES ('$name','$addr','$tel','$type')";
    $result = mysqli_query($conn, $sql);
    $cus_id = mysqli_insert_id($conn);

    if($result){
        echo $cus_id;       
    }else{
        echo "fail";     
    }
    mysqli_close($conn);	
?>