<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");    
    $input  = $_POST['JSON'];
    $obj = json_decode($input,true); 
    $order_id  = $_POST['order_id'];
    $sum  = $_POST['sum']; 	
    $sum_refund  = $_POST['sum_refund'];    
    $branch_id = $_POST['branch_id'];
    $comment = $_POST['comment'];
    $dt = $_POST['dt'];
    $txt = "(refund)";

    mysqli_begin_transaction($conn);
    $query = "INSERT INTO return_order(order_id,datetime,sum_price,note) 
    VALUES ('$order_id','$dt','$sum_refund','$comment')";  
    $result = mysqli_query($conn, $query);    
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }   
    $return_id = mysqli_insert_id($conn);	   
    
    $query = "UPDATE sale_order SET sum_price='$sum' WHERE order_id = '$order_id'";
    $result = mysqli_query($conn, $query);
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
    $no = 0;
    foreach ($obj as $data){
        $no++;
        $amt = $data['amt'];  
        $price = $data['price'];  
        $prod_id = $data['prod_id'];       
           
        $query = "UPDATE product_branch SET amount=amount+'$amt' WHERE branch_id = '$branch_id' AND prod_id = '$prod_id'";  
        $result = mysqli_query($conn, $query);
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }

        $query = "INSERT INTO return_order_item(return_id,item_id,prod_id,prod_price,prod_amount) 
        VALUES ('$return_id','$no','$prod_id','$price','$amt')";  
        $result = mysqli_query($conn, $query); 
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }  

        $query = "UPDATE sale_order_item SET order_id = CONCAT(order_id,'".$txt."')  WHERE order_id = '$order_id' AND prod_id = '$prod_id' AND prod_amount = '$amt'";  
        $result = mysqli_query($conn, $query);
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }
    }
    mysqli_commit($conn); 
    echo "success";
    mysqli_close($conn);
?>