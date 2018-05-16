<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $b_id  = $_POST['b_id'];
    $user_id  = $_POST['user_id'];
    $date  = $_POST['date'];
    $total  = $_POST['total'];    
   
    $sql = "INSERT INTO cash_record (branch_id, date_time, record_by, cash) VALUES('$b_id', '$date', '$user_id', '$total')";
    $result = mysqli_query($conn, $sql); 

    if($result){
        echo "success";      
    }else{
        echo "fail";       
    }
    mysqli_close($conn);	
?>