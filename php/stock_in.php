<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $input  = $_POST['JSON'];
    $obj = json_decode($input,true);  	
    $b_id  = $_POST['b_id'];  
    $dt  = $_POST['date'];
    $sum = 0;
    $i = 0;   

    foreach ($obj as $data)
    {
        $total = $data['total'];
        $sum += $total;         
    }  

    mysqli_autocommit($conn,FALSE); 
    $sql = "INSERT INTO stock_in (branch_id,date_time,cost) VALUE ('$b_id','$dt','$sum')"; 
    $result = mysqli_query($conn, $sql);
    $last_id = mysqli_insert_id($conn);
    foreach ($obj as $data)
    {
        $i++;
        $item = $i;
        $prod_id = $data['prod_id'];
        $cost = $data['retail'];    
        $amt = $data['amount']; 
        $cus = $data['cus'];    
        $cus_price = $data['cus_price'];       
        $unit = $data['unit'];       
        $unit_price = $data['unit_price'];            
      
        $sql1 = "INSERT INTO stock_in_item (stock_id,item_id,prod_id,prod_cost,prod_amount)
        VALUE ('$last_id','$item','$prod_id','$cost','$amt')"; 
        $result1 = mysqli_query($conn, $sql1);  
        
        $query = "SELECT prod_id FROM product_branch WHERE branch_id = '$b_id' AND prod_id = '$prod_id'";
        $result_q = mysqli_query($conn, $query);
        if(mysqli_num_rows($result_q) > 0){ 
            $sql_up = "UPDATE product_branch SET amount = amount+'$amt' WHERE branch_id = '$b_id' AND prod_id = '$prod_id'"; 
            $result_up = mysqli_query($conn, $sql_up);
        }else{
            $sql_in = "INSERT INTO product_branch (branch_id,prod_id,amount) VALUE ('$b_id','$prod_id','$amt')"; 
            $result_in = mysqli_query($conn, $sql_in);
        }  
        
        for($c = 0 ; $c < count($cus); $c++){
            $cus_name = $cus[$c];
            $price = $cus_price[$c];
            if($cus_name != "-"){
                $sql = "INSERT INTO detail_customer (prod_id,customer_id,special_prod_price) 
                VALUE ('$prod_id',(SELECT customer_id FROM customer WHERE customer_name = '$cus_name'),'$price')"; 
                $result = mysqli_query($conn, $sql);
            }
        }

        for($u = 0 ; $u < count($unit); $u++){
            $unit_amt = $unit[$u];
            $price = $unit_price[$u];
            $item_id = $u+1;
            if($unit_amt != "-"){
                $sql = "INSERT INTO detail_customer (prod_id,item_id,amt_threshold,prod_price) 
                VALUE ('$prod_id','$item_id','$unit_amt','$price')"; 
                $result = mysqli_query($conn, $sql);
            }
        }
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