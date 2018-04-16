<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $input_get  = $_POST['JSON_cget'];
    $obj_get = json_decode($input_get,true);    
    $input_add  = $_POST['JSON_cadd'];
    $obj_add = json_decode($input_add,true);    
    $prod_id = $_POST['prod_id'];  

    mysqli_autocommit($conn,FALSE);  
    foreach ($obj_get as $data)
    {        
        $old_cus = $data['old_cus'];
        $cus_id = $data['cus_id'];    
        $cus_price = $data['cus_price'];

        $sql = "UPDATE detail_customer SET customer_id = '$cus_id',special_prod_price = '$cus_price' 
        WHERE customer_id = '$old_cus' AND prod_id = '$prod_id'"; 
        $result = mysqli_query($conn, $sql);    
    }

    foreach ($obj_add as $data)
    {     
        $cus_id = $data['cus_id'];    
        $cus_price = $data['cus_price'];
    
        $sql = "INSERT INTO detail_customer (prod_id,customer_id,special_prod_price) 
        VALUE ('$prod_id','$cus_id','$cus_price')"; 
        $result = mysqli_query($conn, $sql); 
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