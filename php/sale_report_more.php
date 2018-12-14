<?php
    $conn = mysqli_connect("localhost", "root", "pkl2468GG", "pos");
    $prod_id = $_POST['prod_id']; // Ex: "1247";
    //$prod_id = "1247";
    $date_from  = $_POST['date_from']; // Ex: "2018-09-01";
    $date_to  = $_POST['date_to']; // Ex: "2018-09-19";
    //$date_from  = "2018-11-01";
    //$date_to  = "2018-11-19";
    $report_mode = $_POST['report_mode'];  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "alldate_daterange";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "alldate_selectedmonth";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "allmonth";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    //$report_mode = "allyear";  //{allbranch_daterange|alldate_daterange|alldate_selectedmonth|allmonth|allyear}
    $year = $_POST['year'];           //selected year for alldate_selectedmonth|allmonth;
    //$year = 2018;
    $month = $_POST['month'];         //selected month for alldate_selectedmonth;
    //$month = 11;

	date_default_timezone_set('Asia/Bangkok');
	
	function thaimonth($arg){
		$date = explode("/", $arg);
		//$date[0] >> date
		//$date[1] >> month

		switch ($date[1]) {
		case 1: case "1":  
			$date[1] =  'ม.ค.';
			return (string)$date[0] . " " . (string)$date[1];
		case 2: case "2":  
			$date[1] =  'ก.พ.';
			return (string)$date[0] . " " . (string)$date[1];
		case 3: case "3":  
			$date[1] =  'มี.ค.';
			return (string)$date[0] . " " . (string)$date[1];
		case 4: case "4":  
			$date[1] =  'เม.ย.';
			return (string)$date[0] . " " . (string)$date[1];
		case 5: case "5":  
			$date[1] =  'พ.ค.';
			return (string)$date[0] . " " . (string)$date[1];
		case 6: case "6":  
			$date[1] =  'มิ.ย.';
			return (string)$date[0] . " " . (string)$date[1];
		case 7: case "7":  
			$date[1] =  'ก.ค.';
			return (string)$date[0] . " " . (string)$date[1];
		case 8: case "8":  
			$date[1] =  'ส.ค.';
			return (string)$date[0] . " " . (string)$date[1];
		case 9: case "9":  
			$date[1] =  'ก.ย.';
			return (string)$date[0] . " " . (string)$date[1];
		case 10: case "10":  
			$date[1] =  'ต.ค.';
			return (string)$date[0] . " " . (string)$date[1];
		case 11: case "11":  
			$date[1] =  'พ.ย.';
			return (string)$date[0] . " " . (string)$date[1];
		case 12: case "12":  
			$date[1] =  'ธ.ค.';
			return (string)$date[0] . " " . (string)$date[1];
      }
	}
		
   if($report_mode == "alldate_daterange" && $date_from != "none" && $date_to != "none" && $date_from != NULL && $date_to != NULL){
            $d_count=1;
	    $pre_sql = "SELECT DISTINCT DATE_FORMAT(date_time, '%Y%m%d') FROM sale_order WHERE DATE(date_time) >= '".$date_from."' AND DATE(date_time) <= '".$date_to."' ";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($d_row = mysqli_fetch_array($pre_result)){   
		      $sql = "SELECT p.prod_id, IFNULL(SUM(si.prod_amount),0) as amt, IFNULL(SUM((si.prod_price*si.prod_amount)-si.prod_discount),0) as sale_value 
		              FROM product p LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' 
		              AND p.prod_id = ".$prod_id." AND DATE_FORMAT(s.date_time, '%Y%m%d') = '".$d_row[0]."' GROUP BY p.prod_id ";
		      $result = mysqli_query($conn, $sql);
	              if(mysqli_num_rows($result) > 0){
	       		  while($dd_row = mysqli_fetch_array($result)){
					$output[$d_count]["amt"] = $dd_row[1];
					$output[$d_count]["sale_value"] = $dd_row[2];
					$originalDate = $d_row[0];
					$newDate = (string)date("d/m", strtotime($originalDate));
					$output[$d_count]["date"] = thaimonth($newDate);
					if($d_count == 1){
						$day = (string)date("D", strtotime($originalDate));
						$output[$d_count]["day"] = $day;
					}
			  }
	              }else{
					$output[$d_count]["amt"] = 0;
					$output[$d_count]["sale_value"] = 0;
					$originalDate = $d_row[0];
					$newDate = (string)date("d/m", strtotime($originalDate));
					$output[$d_count]["date"] = thaimonth($newDate);
					if($d_count == 1){
						$day = (string)date("D", strtotime($originalDate));
						$output[$d_count]["day"] = $day;
					}
		     	}
		      $d_count++;
       	    	}
    	    }
    }
    if($report_mode == "alldate_selectedmonth"){
	    if($year == NULL || $year == "none"){
		$year = "YEAR(CURDATE())";
	    }else{
		$year = "'".$year."'";
	    }
	    if($month == NULL || $month == "none"){
		$month = "MONTH(CURDATE())";
	    }else{
		$month = "'".$month."'";
	    }
    	    $d_count=1;

            $pre_sql = "SELECT DISTINCT DATE_FORMAT(date_time, '%Y%m%d') FROM sale_order WHERE YEAR(date_time)=".$year." AND MONTH(date_time)=".$month." ";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){
		while($d_row = mysqli_fetch_array($pre_result)){		
		      $sql = "SELECT p.prod_id, IFNULL(SUM(si.prod_amount),0) as amt, IFNULL(SUM((si.prod_price*si.prod_amount)-si.prod_discount),0) as sale_value 
		      FROM product p LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' 
		      AND p.prod_id = ".$prod_id." AND DATE_FORMAT(s.date_time, '%Y%m%d') = '".$d_row[0]."' GROUP BY p.prod_id ";
       	    	      $result = mysqli_query($conn, $sql);
	              if(mysqli_num_rows($result) > 0){
	       		while($dd_row = mysqli_fetch_array($result)){
					$output[$d_count]["amt"] = $dd_row[1];
					$output[$d_count]["sale_value"] = $dd_row[2];
					$originalDate = $d_row[0];
					$newDate = (string)date("d/m", strtotime($originalDate));
					$output[$d_count]["date"] = thaimonth($newDate);

					if($d_count == 1){
						$day = (string)date("D", strtotime($originalDate));
						$output[$d_count]["day"] = $day;
					}
				}
	              }else{
			$output[$d_count]["amt"] = 0;
			$output[$d_count]["sale_value"] = 0;
			$originalDate = $d_row[0];
			$newDate = (string)date("d/m", strtotime($originalDate));
			$output[$d_count]["date"] = thaimonth($newDate);
			if($d_count == 1){
						$day = (string)date("D", strtotime($originalDate));
						$output[$d_count]["day"] = $day;
					}
		      }
		      $d_count++;
    	    	}
	    }
    }
    if($report_mode == "allmonth"){
	    if($year == NULL || $year == "none"){
		$year = "YEAR(CURDATE())";
	    }else{
		$year = "'".$year."'";
	    }
	    $sql_where = " AND YEAR(s.date_time) = ".$year." ";

	    for($m_count=1; $m_count<=12; $m_count++){
	        $sql = "SELECT p.prod_id, IFNULL(SUM(si.prod_amount),0) as amt, IFNULL(SUM((si.prod_price*si.prod_amount)-si.prod_discount),0) as sale_value 
      		        FROM product p LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' 
		        AND p.prod_id = ".$prod_id." AND MONTH(s.date_time) = '".$m_count."' ".$sql_where." GROUP BY p.prod_id";
	    	$result = mysqli_query($conn, $sql);
	        if(mysqli_num_rows($result) > 0){
	       		while($m_row = mysqli_fetch_array($result)){
			    $output[$m_count]["amt".$m_count] = $m_row[1];
			    $output[$m_count]["sale_value".$m_count] = $m_row[2];
			}
		}else{
			$output[$m_count]["amt".$m_count] = 0;
			$output[$m_count]["sale_value".$m_count] = 0;
		}
    	    }
    }
    if($report_mode == "allyear"){
	    $sql_where = "";
	    $y_count=1;
            $pre_sql = "SELECT DISTINCT YEAR(s.date_time) FROM sale_order s WHERE 1";
	    $pre_result = mysqli_query($conn, $pre_sql);
	    if(mysqli_num_rows($pre_result) > 0){    
       		while($y_row = mysqli_fetch_array($pre_result)){      
		    $sql = "SELECT p.prod_id, IFNULL(SUM(si.prod_amount),0) as amt, IFNULL(SUM((si.prod_price*si.prod_amount)-si.prod_discount),0) as sale_value 
		            FROM product p LEFT JOIN sale_order_item si ON p.prod_id = si.prod_id LEFT JOIN sale_order s ON si.order_id = s.order_id WHERE s.order_number NOT LIKE '%can%' AND si.order_id NOT LIKE '%refund%' 
		            AND p.prod_id = ".$prod_id." AND YEAR(s.date_time) = '".$y_row[0]."' ".$sql_where." GROUP BY p.prod_id ";
	    	    $result = mysqli_query($conn, $sql);
	            if(mysqli_num_rows($result) > 0){
	       		    while($yy_row = mysqli_fetch_array($result)){
			        $output[$y_count]["amt"] = $yy_row[1];
			        $output[$y_count]["sale_value"] = $yy_row[2];
			        $output[$y_count]["year"] = $y_row[0]+543;
			    }
		    }else{
			$output[$y_count]["amt"] = 0;
			$output[$y_count]["sale_value"] = 0;
			$output[$y_count]["year"] = 0;
		    }
		    $y_count++;
		}
    	    }
    }

    //echo $sql;
    //var_dump($output);
    //die();
	
    if($d_count>1 || $m_count==13 || $y_count>1){
	echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }else{
	echo "fail";
    }
    mysqli_close($conn);
?>