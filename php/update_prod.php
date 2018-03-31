<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];		
    $barcode = $_POST['barcode'];	
    $name = $_POST['name'];		
    $type = $_POST['type'];		
    $amt1 = $_POST['amt1'];		
    $amt2 = $_POST['amt2'];		
    $unit = $_POST['unit'];		
    $fund = $_POST['fund'];		
    $price = $_POST['price'];   
    $last_type = "";
    $last_unit = "";   

    //--> update or insert amount to product branch
    mysqli_autocommit($conn,FALSE); 
    $query = "SELECT amount FROM product_branch WHERE prod_id = '$prod_id'";
    $result_q = mysqli_query($conn, $query);
    if(mysqli_num_rows($result_q) > 0){ 
        $sql_up = "UPDATE product_branch SET amount = '$amt1' WHERE branch_id = 1 AND prod_id = '$prod_id'"; 
        $result_up = mysqli_query($conn, $sql_up);
        $sql_up = "UPDATE product_branch SET amount = '$amt2' WHERE branch_id = 2 AND prod_id = '$prod_id'"; 
        $result_up = mysqli_query($conn, $sql_up);
    }else{
        $sql2 = "INSERT INTO product_branch(prod_id,branch_id,amount) VALUES ('$prod_id',1,'$amt1')";
        $result = mysqli_query($conn, $sql2);
        $sql3 = "INSERT INTO product_branch(prod_id,branch_id,amount) VALUES ('$prod_id',2,'$amt2')";
        $result = mysqli_query($conn, $sql3);
    }//--> end

    //--> get id type and unit or insert new type and new unit
    $sql = "SELECT prod_type FROM prod_type WHERE prod_type_name = '$type'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){  
        while($row = mysqli_fetch_array($result)){      
            $last_type =  $row['prod_type'];
        }    
    }else{
        $sql = "INSERT INTO prod_type(prod_type_name) VALUE('$type')";
        $result = mysqli_query($conn, $sql);
        $last_type = mysqli_insert_id($conn);	
    }

    $sql = "SELECT unit_id FROM unit WHERE unit_name = '$unit'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){      
            $last_unit = $row['unit_id'];
        }   
    }else{
        $sql = "INSERT INTO unit(unit_name) VALUE('$unit')";
        $result = mysqli_query($conn, $sql);
        $last_unit = mysqli_insert_id($conn);	
    }//--> end
   
    //--> update product
    $sql1 = "UPDATE product SET barcode = '$barcode', prod_name = '$name', prod_type = '$last_type',
    unit_id = '$last_unit', prod_cost = '$fund', prod_price = '$price' WHERE prod_id = '$prod_id'"; 
    $result = mysqli_query($conn, $sql1);  

    if($result){
        echo "success";
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }

    mysqli_close($conn);	
?>