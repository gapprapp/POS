<?php
    $connect = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $data = $_POST['phrase'];       
  
    $query = "SELECT unit_name FROM unit WHERE unit_name LIKE '%".$data."%'";  
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){      
       $output[] = $row;
      }   
    }else{    
      $output = array('unit_name' => 'Not Found');
    }   

    if($result){
        echo json_encode($output);		   
    }else{
        echo "fail";
    }

    mysqli_close($connect);
?>