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
    $sql = "INSERT INTO transfer(branch_from,branch_to,datetime,user_id) VALUE ('$b_from','$b_to','$dt','$user_id')"; 
    $result = mysqli_query($conn, $sql);
    if(!$result){
        mysqli_rollback($conn);
        echo "fail";
        exit;
    }
    $last_id = mysqli_insert_id($conn);	

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
    echo "success";
    mysqli_close($conn);
?>