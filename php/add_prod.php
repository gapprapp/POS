<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $input  = $_POST['JSON'];
    $obj = json_decode($input,true);   
    $last_type = "";
    $last_unit = "";
    $last_prod = "";  

    mysqli_autocommit($conn,FALSE); 
    foreach ($obj as $data)
    {
        $barcode = $data['barcode'];
        $name = $data['name'];
        $type = $data['type'];    
        $amt1 = $data['amt1'];    
        $amt2 = $data['amt2'];       
        $unit = $data['unit']; 
        $fund = $data['fund']; 
        $price = $data['price']; 
        $b64 = $data['b64'];     
       
        $sql = "SELECT prod_type FROM prod_type WHERE prod_type_name = '$type'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){  
            while($row = mysqli_fetch_array($result)){      
                $last_type =  $row['prod_type'];
            }    
        }else{
            $sql = "INSERT INTO prod_type(prod_type_name) VALUES ('$type')";
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
            $sql = "INSERT INTO unit(unit_name) VALUES ('$unit')";
            $result = mysqli_query($conn, $sql);
            $last_unit = mysqli_insert_id($conn);	
        }       

        $sql = "INSERT INTO product(prod_name,barcode,prod_type,unit_id,prod_cost,prod_price,img_string) 
        VALUES ('$name','$barcode','$last_type','$last_unit','$fund','$price','$b64')";
        $result = mysqli_query($conn, $sql);
        $last_prod = mysqli_insert_id($conn);
        
        $sql = "INSERT INTO product_branch(prod_id,branch_id,amount) VALUES ('$last_prod',1,'$amt1')";
        $result = mysqli_query($conn, $sql);
        $sql = "INSERT INTO product_branch(prod_id,branch_id,amount) VALUES ('$last_prod',2,'$amt2')";
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