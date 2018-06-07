<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $input  = $_POST['JSON'];
    $obj = json_decode($input,true); 
    $cus  = $_POST['cus_id'];
    $b  = $_POST['b_id'];
    $dt  = $_POST['date'];
    $sum  = $_POST['sum'];
    $pay  = $_POST['pay'];
    $dis  = $_POST['dis'];
    $get_money  = $_POST['get_money'];
    $change_money  = $_POST['change_money'];
    $final_price  = $_POST['final_price'];
    $comment  = $_POST['comment'];
    $name_cus = $_POST['name_cus'];
    $user_id = $_POST['user_id'];
    $order_id = $_POST['order_id'];
    $b_id_old  = $_POST['b_id_old'];

    mysqli_begin_transaction($conn); 
    if($cus == -1){
        $sql = "INSERT INTO customer (customer_name) VALUE ('$name_cus')"; 
        $result = mysqli_query($conn, $sql);
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }
        $cus = mysqli_insert_id($conn);	
        if($cus == 0){
            $sql = "SELECT customer_id FROM customer WHERE customer_name = '$name_cus'"; 
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){    
                while($row = mysqli_fetch_array($result)){  
                    $cus = $row['customer_id'];
                }
            }
        }
    }  

    $sql = "UPDATE sale_order SET customer_id = '$cus',date_time = '$dt',sum_price = '$sum',total_price = '$final_price',
    payment_type = '$pay',note = '$comment',user_id = '$user_id',branch_id = '$b',total_discount = '$dis',
    get_money = '$get_money',change_money = '$change_money' WHERE order_id = '$order_id'"; 
    $result = mysqli_query($conn, $sql);
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
    
    $query = "INSERT INTO order_record(order_id,customer_id,datetime,sum_price,total_price,payment_type,note,user_id,
    branch_id,total_discount,get_money,change_money) VALUES ('$order_id','$cus','$dt','$sum','$final_price','$pay','$comment',
    '$user_id','$b','$dis','$get_money','$change_money')";  
    $result = mysqli_query($conn, $query); 
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
    $record_id = mysqli_insert_id($conn);	  
    
    $query = "SELECT prod_amount,prod_id FROM sale_order_item WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){
            $amt = $row['prod_amount'];   
            $prod_id = $row['prod_id'];
            $sql = "UPDATE product_branch SET amount=amount+'$amt' WHERE branch_id = '$b_id_old' AND prod_id = '$prod_id'";  
            $result1 = mysqli_query($conn, $sql);
            if(!$result1){
                mysqli_rollback($conn);
                echo "fail";
                exit;
            }            
        }   
    } 
    $query = "DELETE FROM sale_order_item WHERE order_id = '$order_id'";  
    $result = mysqli_query($conn, $query);
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
            
    foreach ($obj as $data)
    {
        $item = $data['item_id'];
        $prod_id = $data['prod_id'];
        $price = $data['price'];    
        $amt = $data['amount'];    
        $discount = $data['discount'];       
        $bonus = $data['bonus'];          
      
        $sql1 = "INSERT INTO sale_order_item (order_id,item_id,prod_id,prod_price,prod_amount,prod_discount,bonus)
         VALUE ('$order_id','$item','$prod_id','$price','$amt','$discount','$bonus')"; 
        $result1 = mysqli_query($conn, $sql1);   
        if(!$result1){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        } 
        
        $query = "INSERT INTO order_record_item(order_id,item_id,prod_id,prod_price,prod_amount,prod_discount,bonus) 
        VALUES ('$record_id','$item','$prod_id','$price','$amt','$discount','$bonus')";  
        $result = mysqli_query($conn, $query); 
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }
       
        $sql_up = "UPDATE product_branch SET amount = amount-'$amt' WHERE branch_id = '$b' AND prod_id = '$prod_id'"; 
        $result = mysqli_query($conn, $sql_up);  
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