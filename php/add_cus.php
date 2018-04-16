<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $name  = $_POST['name'];
    $addr  = $_POST['address'];
    $tel  = $_POST['tel'];   

    mysqli_autocommit($conn,FALSE); 
  
    $sql = "INSERT INTO customer(customer_name,address,tel) VALUES ('$name','$addr','$tel')";
    $result = mysqli_query($conn, $sql);
    $cus_id = mysqli_insert_id($conn);

    if($result){
        echo $cus_id;
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }

    mysqli_close($conn);	
?>