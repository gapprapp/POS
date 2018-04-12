<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];    
    $old_unit = $_POST['old_unit'];  
    
    mysqli_autocommit($conn,FALSE);
    $query = "DELETE FROM detail_customer WHERE prod_id = '$prod_id' AND amt_threshold = '$old_unit'";  
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