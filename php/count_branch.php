<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
   
    $sql = "SELECT branch_name b_name FROM branch";
    $result = mysqli_query($conn, $sql); 
    


    while($row = mysqli_fetch_assoc($result)) {
        $output[] = $row;
    }echo json_encode($output, JSON_UNESCAPED_UNICODE);

    mysqli_close($conn);	
?>