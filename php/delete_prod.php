<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id'];    
    
    mysqli_autocommit($conn,FALSE); 
    $query = "DELETE FROM product WHERE prod_id = '$prod_id'";  
    $result = mysqli_query($conn, $query);    

    $query = "DELETE FROM product_branch WHERE prod_id = '$prod_id'";  
    $result = mysqli_query($conn, $query);    

    if($result){
        echo "success";
        mysqli_commit($conn);          
    }else{
        echo "fail";
        mysqli_rollback($conn);
    }

    mysqli_close($conn);
?>