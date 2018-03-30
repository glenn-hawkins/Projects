<?php
    
	require_once('connectvars.php');
	
    $num = $_GET['num']; // get parameters
    $fLoc = $_GET['file'];
	$fLoc2 = $_GET['file2'];
	$switch = $_GET['switch'];
	
	if ($num){
		
		if($fLoc){unlink($fLoc);}
		if($fLoc2){unlink($fLoc2);}
		
		$query = "DELETE FROM FLAworkingpaper WHERE paperNumber = '";
		$query .= $num;
		$query .= "' LIMIT 1";
			
	} else {
		if($switch==1){
			
			unlink($fLoc);
			
			$query = "UPDATE FLAworkingpaper SET fileLoc='', fileSize='0', fileType='' WHERE fileLoc = '";
			$query .= $fLoc;
			$query .= "' LIMIT 1";
		}
		if($switch==2){
			
			unlink($fLoc2);
			
			$query = "UPDATE FLAworkingpaper SET fileLoc2='', fileSize2='0', fileType2='' WHERE fileLoc2 = '";
			$query .= $fLoc2;
			$query .= "' LIMIT 1";
		}
	}

	mysql_query($query, $dbc)
			or die('Error querying database.');
        
?>