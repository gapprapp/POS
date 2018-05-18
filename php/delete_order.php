<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $order_id = $_POST['order_id'];     
    $txt = "(cancel)";

    $query = "SELECT branch_id FROM sale_order WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){
            $branch_id = $row['branch_id'];               
        }   
    } 

    mysqli_begin_transaction($conn);
    $query = "SELECT prod_amount,prod_id FROM sale_order_item WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){
            $amt = $row['prod_amount']; 
            $prod_id = $row['prod_id']; 
            $query = "UPDATE product_branch SET amount=amount+'$amt' WHERE branch_id = '$branch_id' AND prod_id = '$prod_id'";  
            $result1 = mysqli_query($conn, $query); 
            if(!$result1){
                mysqli_rollback($conn);
                echo "fail";
                exit;
            }              
        }   
    } 
    $query = "UPDATE sale_order SET order_number = CONCAT(order_number,'".$txt."') WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query); 
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
    mysqli_commit($conn); 
    echo "success";
    mysqli_close($conn);
?>