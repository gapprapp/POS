<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");    
    
    if(isset($_POST['prod_id'])){
        $prod_id = $_POST['prod_id'];
        $query = "DELETE FROM detail_quantity WHERE prod_id = '$prod_id'";  
    }else if(isset($_POST['prod_id_sub'])){
        $prod_id_sub = $_POST['prod_id_sub'];
        $query = "DELETE FROM detail_quantity WHERE prod_id_sub = '$prod_id_sub'";  
    }
    $result = mysqli_query($conn, $query); 

    if($result){
        echo "success";     
    }else{
        echo "fail";     
    }
    mysqli_close($conn);
?>