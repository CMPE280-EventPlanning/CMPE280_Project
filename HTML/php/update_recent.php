<?php
	header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Max-Age: 1000');
        header("content-type: application/json");
        
  	$jsonobj = new stdClass();
        $jsonobj->operation = null;

	$conn = new mysqli('eclectic.ciwss8n8nsgm.us-west-1.rds.amazonaws.com', 'cmpe280', 'cmpe280project', 'eclectic');
	// Check connection
  	if ($conn->connect_error >0) {
    	$jsonobj->operation = "Error" ;
    	die("Connection failed: " . $conn->connect_error);
  	} 
//	through the global variable $_POST, like this:
        $user= isset($_POST['user']) ? $_POST['user'] : 'null';
        $loc_id= isset($_POST['loc_id']) ? $_POST['loc_id'] : 'null';
  	$sql = "SELECT recent FROM recently_viewed WHERE username = '$user'";
  	$result = $conn->query($sql);		
	 $row = $result->fetch_assoc();
         $recent = $row["recent"];
         $rec_array = explode(",", $recent);
    if (in_array($loc_id, $rec_array)) {
        $jsonobj->operation = 'Ok';
    } else {
         if (count($rec_array) == 3) {
		 array_shift($rec_array);
	 }
         array_push($rec_array,$loc_id);
         $rec_str = implode (",", $rec_array);
  	$sql = "UPDATE recently_viewed set recent = '$rec_str' where username = '$user'";
  	$result = $conn->query($sql);

      if ($result == TRUE) {
         $jsonobj->operation = 'Ok';
      } else {
         $jsonobj->operation = 'Error';
      }
   }
  $conn->close();
      echo json_encode($jsonobj);
?>
