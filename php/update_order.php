<?php
	$conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");   
    $b_id = $_POST['branch_id']; 
    $pay = $_POST['pay'];  
    $order_id = $_POST['order_id'];
    $old_b;   

    $query = "SELECT branch_id FROM sale_order WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){      
          $old_b = $row['branch_id'];        
        }   
    }  

    mysqli_autocommit($conn,FALSE); 
    if($b_id){
        if($b_id != $old_b){
            $query = "SELECT prod_id,prod_amount FROM sale_order_item WHERE order_id = '$order_id'";  
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) > 0){    
                while($row = mysqli_fetch_array($result)){
                    $amt = $row['prod_amount'];    
                    $prod_id = $row['prod_id'];      
                    $query = "UPDATE product_branch SET amount=amount-'$amt' WHERE branch_id = '$old_b' AND prod_id = '$prod_id'";  
                    $result1 = mysqli_query($conn, $query);

                    $query = "UPDATE product_branch SET amount=amount+'$amt' WHERE branch_id = '$b_id' AND prod_id = '$prod_id'";  
                    $result1 = mysqli_query($conn, $query);
                }   
            }  
        }
        $query = "INSERT INTO order_record(order_id,payment_type,branch_id) VALUES ('$order_id','$pay','$b_id')";  
        $result = mysqli_query($conn, $query);        
        
        $query = "UPDATE sale_order SET payment_type='$pay',branch_id='$b_id' WHERE order_id = '$order_id'";  
        $result = mysqli_query($conn, $query);
    }else{
        $query = "INSERT INTO order_record(order_id,payment_type) VALUES ('$order_id','$pay')";  
        $result = mysqli_query($conn, $query); 
        
        $query = "UPDATE sale_order SET payment_type='$pay' WHERE order_id = '$order_id'";  
        $result = mysqli_query($conn, $query);
    }

    if($result){
        echo "success";
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }
	
	mysqli_close($conn);
?>