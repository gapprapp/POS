<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];    
    $old_cus = $_POST['old_cus'];     
    
    $query = "DELETE FROM detail_customer WHERE prod_id = '$prod_id' AND customer_id = '$old_cus'";  
    $result = mysqli_query($conn, $query); 

    if($result){
        echo "success";      
    }else{
        echo "fail";     
    }
    mysqli_close($conn);
?>