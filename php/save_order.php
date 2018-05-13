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
   
    $sql = "SELECT order_number,count FROM sale_order ORDER BY order_id DESC LIMIT 1"; 
    $result = mysqli_query($conn, $sql);    
   
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){  
            $number = $row['order_number'];
            $year_old = substr($number,1,2);
            $year_cur = date("y");
            if($year_cur == $year_old){
                $count = $row['count']+1;
                $year = "S" . $year_cur . "-";
                $order_number = $year . str_pad($count, 5, "0",STR_PAD_LEFT);
                $sql2 = "INSERT INTO sale_order (order_number,customer_id,date_time,sum_price,total_price,
                payment_type,note,user_id,branch_id,total_discount,get_money,change_money,count) 
                VALUE ('$order_number','$cus','$dt','$sum','$final_price','$pay','$comment','$user_id','$b','$dis','$get_money','$change_money','$count')"; 
                $result2 = mysqli_query($conn, $sql2);
                if(!$result2){
                    mysqli_rollback($conn);
                    echo "fail";
                    exit;
                }
                $last_id = mysqli_insert_id($conn);	
            }else{
                $year = "S" . $year_cur . "-";
                $order_number = $year . str_pad(1, 5, "0",STR_PAD_LEFT);
                $sql3 = "INSERT INTO sale_order (order_number,customer_id,date_time,sum_price,total_price,
                payment_type,note,user_id,branch_id,total_discount,get_money,change_money,count) 
                VALUE ('$order_number','$cus','$dt','$sum','$final_price','$pay','$comment','$user_id','$b','$dis','$get_money','$change_money',1)"; 
                $result3 = mysqli_query($conn, $sql3);
                if(!$result3){
                    mysqli_rollback($conn);
                    echo "fail";
                    exit;
                }
                $last_id = mysqli_insert_id($conn);	
            }           
        }
    }else{
        $year = "S" . date("y") . "-";
        $order_number = $year . str_pad(1, 5, "0",STR_PAD_LEFT);
        $sql1 = "INSERT INTO sale_order (order_number,customer_id,date_time,sum_price,total_price,
        payment_type,note,user_id,branch_id,total_discount,get_money,change_money,count) 
        VALUE ('$order_number','$cus','$dt','$sum','$final_price','$pay','$comment','$user_id','$b','$dis','$get_money','$change_money',1)"; 
        $result1 = mysqli_query($conn, $sql1);
        if(!$result1){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }
        $last_id = mysqli_insert_id($conn);	
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
         VALUE ('$last_id','$item','$prod_id','$price','$amt','$discount','$bonus')"; 
        $result1 = mysqli_query($conn, $sql1);  
        if(!$result1){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }     
       
        $sql_up = "UPDATE product_branch SET amount = amount-'$amt' WHERE branch_id = '$b' AND prod_id = '$prod_id'"; 
        $result_up = mysqli_query($conn, $sql_up);    
        if(!$result_up){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }    
    }
    mysqli_commit($conn); 
    echo $order_number;
    mysqli_close($conn);
?>