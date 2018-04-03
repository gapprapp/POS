<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos"); 
  
    $result = $mysql->query("select * from product");
    $rows = array();
    while($row = $result->fetch_array(MYSQL_ASSOC)) {
        $rows[] = array_map("utf8_encode", $row);
    }
    echo json_encode($rows);
    $result->close();
    $mysql->close();

?>