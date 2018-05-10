<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];		
    $img = $_POST['img'];  
   
    $sql = "UPDATE product SET img_string = '$img' WHERE prod_id = '$prod_id'"; 
    $result = mysqli_query($conn, $sql);  

    if($result){
        echo "success";             
    }else{
        echo "fail";      
    }
    mysqli_close($conn);	
?>