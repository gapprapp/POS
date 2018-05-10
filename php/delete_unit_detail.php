<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];    
    $old_unit = $_POST['old_unit'];    
    
    $query = "DELETE FROM detail_customer WHERE prod_id = '$prod_id' AND amt_threshold = '$old_unit'";  
    $result = mysqli_query($conn, $query); 

    if($result){
        echo "success";     
    }else{
        echo "fail";     
    }
    mysqli_close($conn);
?>