<?php
    $connect = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $data = $_POST['phrase'];       
  
    $query = "SELECT prod_type_name FROM prod_type WHERE prod_type_name LIKE '%".$data."%'";  
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result) > 0){    
      while($row = mysqli_fetch_array($result)){      
       $output[] = $row;
      }   
    }else{    
      $output = array('prod_type_name' => 'Not Found');
    }   

    if($result){
        echo json_encode($output);		   
    }else{
        echo "fail";
    }

    mysqli_close($connect);
?>