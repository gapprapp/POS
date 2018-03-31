<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $data = $_POST['phrase'];   
      
    $query = "SELECT sup_id,sup_name FROM supplier WHERE sup_name LIKE '%$data%'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){    
        while($row = mysqli_fetch_array($result)){      
            $output[] = $row;
        }   
    }else{    
      $output = array('sup_name' => 'Not Found');
    }   

    if($result){
        echo json_encode($output);		   
    }else{
        echo "fail";
    }

    mysqli_close($conn);

?>