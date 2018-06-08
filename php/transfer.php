<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $input  = $_POST['JSON'];
    $obj = json_decode($input,true);   
    $b_from  = $_POST['b_id_from'];
    $b_to  = $_POST['b_id_to'];
    $dt  = $_POST['date'];
    $sum  = $_POST['sum'];  
    $user_id = $_POST['user_id'];   

    mysqli_begin_transaction($conn); 
    $sql = "SELECT transfer_number,count FROM transfer ORDER BY transfer_id DESC LIMIT 1"; 
    $result = mysqli_query($conn, $sql);    
   
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){  
            $number = $row['transfer_number'];
            $year_old = substr($number,1,2);
            $year_cur = date("y");
            if($year_cur == $year_old){
                $count = $row['count']+1;
                $year = "T" . $year_cur . "-";
                $order_number = $year . str_pad($count, 5, "0",STR_PAD_LEFT);
                $sql1 = "INSERT INTO transfer(transfer_number,branch_from,branch_to,datetime,user_id,count) VALUE ('$order_number','$b_from','$b_to','$dt','$user_id','$count')"; 
                $result1 = mysqli_query($conn, $sql1);
                if(!$result1){
                    mysqli_rollback($conn);
                    echo "fail";
                    exit;
                }
                $last_id = mysqli_insert_id($conn);	   
            }else{
                $year = "T" . $year_cur . "-";
                $order_number = $year . str_pad(1, 5, "0",STR_PAD_LEFT);
                $sql2 = "INSERT INTO transfer(transfer_number,branch_from,branch_to,datetime,user_id,count) VALUE ('$order_number','$b_from','$b_to','$dt','$user_id',1)"; 
                $result2 = mysqli_query($conn, $sql2);
                if(!$result2){
                    mysqli_rollback($conn);
                    echo "fail";
                    exit;
                }
                $last_id = mysqli_insert_id($conn);	   
            }           
        }
    }else{
        $year = "T" . date("y") . "-";
        $order_number = $year . str_pad(1, 5, "0",STR_PAD_LEFT);
        $sql3 = "INSERT INTO transfer(transfer_number,branch_from,branch_to,datetime,user_id,count) VALUE ('$order_number','$b_from','$b_to','$dt','$user_id',1)"; 
        $result3 = mysqli_query($conn, $sql3);
        if(!$result3){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }
        $last_id = mysqli_insert_id($conn);	       
    }
    $sql = "INSERT INTO transfer_record(transfer_id,branch_from,branch_to,datetime,user_id) VALUE ('$last_id','$b_from','$b_to','$dt','$user_id')"; 
    $result = mysqli_query($conn, $sql);
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
    $record_id = mysqli_insert_id($conn); 

    foreach ($obj as $data)
    {      
        $prod_id = $data['prod_id'];       
        $amt = $data['amount'];         
      
        $sql = "INSERT INTO transfer_item (transfer_id,prod_id,prod_amount) VALUE ('$last_id','$prod_id','$amt')"; 
        $result = mysqli_query($conn, $sql);  
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }     
        $sql = "INSERT INTO transfer_record_item (record_id,transfer_id,prod_id,prod_amount) VALUE ('$record_id','$last_id','$prod_id','$amt')"; 
        $result = mysqli_query($conn, $sql);  
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }  
        $sql = "UPDATE product_branch SET amount = amount+'$amt' WHERE branch_id = '$b_to' AND prod_id = '$prod_id'"; 
        $result = mysqli_query($conn, $sql);    
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }         
        $sql = "UPDATE product_branch SET amount = amount-'$amt' WHERE branch_id = '$b_from' AND prod_id = '$prod_id'"; 
        $result = mysqli_query($conn, $sql);    
        if(!$result){
            mysqli_rollback($conn);
            echo "fail";
            exit;
        }    
    }
    mysqli_commit($conn); 
    echo $order_number;
    mysqli_close($conn);
?>