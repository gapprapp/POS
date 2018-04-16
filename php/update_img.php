<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];		
    $img = $_POST['img'];
  
    mysqli_autocommit($conn,FALSE); 
    $sql = "UPDATE product SET img_string = '$img' WHERE prod_id = '$prod_id'"; 
    $result = mysqli_query($conn, $sql);  

    if($result){
        echo "success";
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }

    mysqli_close($conn);	
?>