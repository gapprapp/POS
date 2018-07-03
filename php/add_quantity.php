<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id  = $_POST['prod_id'];
    $prod_id_sub  = $_POST['prod_id_sub'];
    $quantity  = $_POST['quantity'];   
   
    $sql = "INSERT INTO detail_quantity (prod_id, prod_id_sub, quantity) VALUES('$prod_id', '$prod_id_sub', '$quantity')";
    $result = mysqli_query($conn, $sql); 

    if($result){
        echo "success";      
    }else{
        echo "fail";       
    }
    mysqli_close($conn);	
?>