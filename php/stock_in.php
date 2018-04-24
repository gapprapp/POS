<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $input  = $_POST['JSON'];
    $obj = json_decode($input,true); 
    $sup  = $_POST['sup_id']; 	
    $b_id  = $_POST['b_id'];  
    $dt  = $_POST['date'];
    $user_id = $_POST['user_id'];
    $sup_name = $_POST['sup_name'];
    $sum = 0;
    $i = 0;   

    mysqli_autocommit($conn,FALSE); 
    if($sup == 0){
        $sql = "INSERT INTO supplier (sup_name) VALUE ('$sup_name')"; 
        $result = mysqli_query($conn, $sql);
        $sup = mysqli_insert_id($conn);	
    }
    foreach ($obj as $data)
    {
        $total = $data['total'];
        $sum += $total;         
    } 
    $sql = "SELECT stock_number,count FROM stock_in ORDER BY stock_id DESC LIMIT 1"; 
    $result = mysqli_query($conn, $sql);    
   
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){  
            $number = $row['stock_number'];
            $year_old = substr($number,1,2);
            $year_cur = date("y");
            if($year_cur == $year_old){
                $count = $row['count']+1;
                $year = "I" . $year_cur . "-";
                $stock_number = $year . str_pad($count, 5, "0",STR_PAD_LEFT);
                $sql2 = "INSERT INTO stock_in (stock_number,branch_id,date_time,sup_id,cost,user_id,count) 
                VALUE ('$stock_number','$b_id','$dt','$sup','$sum','$user_id','$count')"; 
                $result2 = mysqli_query($conn, $sql2);
                $last_id = mysqli_insert_id($conn);	
            }else{
                $year = "I" . $year_cur . "-";
                $stock_number = $year . str_pad(1, 5, "0",STR_PAD_LEFT);
                $sql3 = "INSERT INTO stock_in (stock_number,branch_id,date_time,sup_id,cost,user_id,count) 
                VALUE ('$stock_number','$b_id','$dt','$sup','$sum','$user_id',1)"; 
                $result3 = mysqli_query($conn, $sql3);
                $last_id = mysqli_insert_id($conn);	
            }           
        }
    }else{
        $year = "I" . date("y") . "-";
        $stock_number = $year . str_pad(1, 5, "0",STR_PAD_LEFT);
        $sql1 = "INSERT INTO stock_in (stock_number,branch_id,date_time,sup_id,cost,user_id,count) 
        VALUE ('$stock_number','$b_id','$dt','$sup','$sum','$user_id',1)"; 
        $result1 = mysqli_query($conn, $sql1);
        $last_id = mysqli_insert_id($conn);	
    }   
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
            $cus_id = $cus[$c];
            $price = $cus_price[$c];
            if($cus_id != "-"){
                $sql = "INSERT INTO detail_customer (prod_id,customer_id,special_prod_price) 
                VALUE ('$prod_id','$cus_id','$price')"; 
                $result = mysqli_query($conn, $sql);
            }
        }

        for($u = 0 ; $u < count($unit); $u++){
            $unit_amt = $unit[$u];
            $price = $unit_price[$u];
            //$item_id = $u+1;
            if($unit_amt != "-"){
                $sql = "INSERT INTO detail_customer (prod_id,/*item_id,*/amt_threshold,prod_price) 
                VALUE ('$prod_id',/*'$item_id',*/'$unit_amt','$price')"; 
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