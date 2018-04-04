<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $order_id = $_POST['order_id'];    
    
    mysqli_autocommit($conn,FALSE); 
    $txt = "(cancle)";
    $query = "UPDATE sale_order SET order_number = CONCAT(order_number,'".$txt."') WHERE order_id = '$order_id'";  
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