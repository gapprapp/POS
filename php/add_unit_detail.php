<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $input_get  = $_POST['JSON_uget'];
    $obj_get = json_decode($input_get,true);    
    $input_add  = $_POST['JSON_uadd'];
    $obj_add = json_decode($input_add,true);    
    $prod_id = $_POST['prod_id'];  

    mysqli_autocommit($conn,FALSE);  
    foreach ($obj_get as $data)
    {        
        $old_unit = $data['old_unit'];
        $unit = $data['unit'];    
        $unit_price = $data['unit_price'];

        $sql = "UPDATE detail_customer SET amt_threshold = '$unit',prod_price = '$unit_price' 
        WHERE amt_threshold = '$old_unit' AND prod_id = '$prod_id'"; 
        $result = mysqli_query($conn, $sql);    
    }

    foreach ($obj_add as $data)
    {     
        $unit = $data['unit'];    
        $unit_price = $data['unit_price'];
    
        $sql = "INSERT INTO detail_customer (prod_id,amt_threshold,prod_price) 
        VALUE ('$prod_id','$unit','$unit_price')"; 
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