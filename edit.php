<?php
	//get constants
	require_once('appvars.php');
	require_once('connectvars.php');
			
	require_once('authorize.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="images/favicon.ico"/>

<title>[ - FLA Discussion Paper Series in Business and Economics - ]</title>

<link href="styles/site.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="scripts/site.js"> </script>

<script type="text/javascript">
//preload images
editalt = new Image();
editalt.src = "images/edit_16r.png";
deletealt = new Image();
deletealt.src = "images/delete_16r.png";


function deleteRow(rowNum){
	var msg = "Are you sure you want to delete Paper No. " + rowNum + "?";
	
	if(confirm(msg)){
		var locString = rowNum + "fileLocation",
		fLoc = document.getElementById(locString).value;
		
		var locString2 = rowNum + "fileLocation2",
		fLoc2 = document.getElementById(locString2).value;
		
		xmlhttp = new XMLHttpRequest();
		
		if (xmlhttp==null) {
			alert ("Browser does not support HTTP Request");
			return;
		}
		var url = "delete_engine.php";
		url = url+"?num="+rowNum;
		url = url+"&file="+fLoc;
		url = url+"&file2="+fLoc2;
		url = url+"&sid="+Math.random();
		xmlhttp.onreadystatechange = stateChanged;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
	}
}

function stateChanged() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		alert("Entry deleted");
		
		location.reload(true);
	}
}

function openEdit(num) {
	var url = "<?php echo EDITENTRY_PATH;?>";
	url = url+"?num="+num;
	
	window.open(url, '_self');
}

</script>

</head>

<body>

<div class="container">
<br />
    <div class="header" id="headerDiv"></div>
    <div class="nav">
        <?php 
			echo generate_nav_links(1);
		?>
    </div>    
    <div class="pageTitle">    
        <h3>FLA: DPS Database Administration</h3>
        <p class="content">Edit or delete existing discussion paper entries.</p>
    </div>
    <div class="main"><br />
    <table align="center" class="paperlist">
        <?php
						
			$sort = $_GET['sort']; // get sort parameter
			
			//calculate pagination
			$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
			$results_per_page = 10;
			$skip = ($cur_page - 1) * $results_per_page;
					  
			$query_string = "SELECT id, dateMonth, dateYear, paperNumber, title, authorGiven, authorFamily, author2Given, author2Family, author3Given, author3Family,
							author4Given, author4Family, author5Given, author5Family, fileLoc, fileLoc2  FROM " . TABLE_NAME;
			
			$query = build_query($query_string, $sort);
			
			$result = mysql_query($query, $dbc)
  					 or die('Error querying database.');
			
			$total = mysql_num_rows($result);
			$num_pages = ceil($total / $results_per_page);			
						
			echo build_sort_links($sort, 1, 0, 0);
			
			if ($num_pages>1){$query = $query . " LIMIT $skip, $results_per_page";}
			
			$result = mysql_query($query, $dbc)
     				or die('Error querying database.');
			
			while($row=mysql_fetch_array($result)) {
				echo '<tr><td>' . $row[dateMonth] . ' ' . $row[dateYear] . '</td><td>' . $row[paperNumber] . '</td><td><a href="' . ABSTRACT_PATH . '?num=' . $row[paperNumber] . '">' . $row[title] .
				'</a></td><td><a href="' . AUTHOR_PATH . '?given=' . $row[authorGiven] . '&family=' . $row[authorFamily] . '">' . $row[authorGiven] . ' ' . $row[authorFamily] . "</a>";
							
				if ( $row[author2Given] ){
					echo ', <a href="' . AUTHOR_PATH . '?given=' . $row[author2Given] . '&family=' . $row[author2Family] . '">' . $row[author2Given] . ' ' . $row[author2Family] . "</a>";		
				}
				if ( $row[author3Given] ){
					echo ', <a href="' . AUTHOR_PATH . '?given=' . $row[author3Given] . '&family=' . $row[author3Family] . '">' . $row[author3Given] . ' ' . $row[author3Family] . "</a>";
				}
				if ( $row[author4Given] ){
					echo ', <a href="' . AUTHOR_PATH . '?given=' . $row[author4Given] . '&family=' . $row[author4Family] . '">' . $row[author4Given] . ' ' . $row[author4Family] . '</a>';		
				}
				if ( $row[author5Given] ){
					echo ', <a href="' . AUTHOR_PATH . '?given=' . $row[author5Given] . '&family=' . $row[author5Family] . '">' . $row[author5Given] . ' ' . $row[author5Family] . '</a>';
				}
				
				echo '</td><td>' . 
				'<img src="images/edit_16.png" class="editbtn" onmouseover="this.src=\'images/edit_16r.png\'" onmouseout="this.src=\'images/edit_16.png\'" onclick="openEdit(' .
																																									 $row[paperNumber] . ')"  />' .
				' <img src="images/delete_16.png" class="editbtn" onmouseover="this.src=\'images/delete_16r.png\'" onmouseout="this.src=\'images/delete_16.png\'" onclick="deleteRow(' .
																																									 $row[paperNumber] . ')"  /></td>';
				echo '<input type="hidden" id="' . $row[paperNumber] . 'fileLocation" value="' . $row[fileLoc] . '" />';
				echo '<input type="hidden" id="' . $row[paperNumber] . 'fileLocation2" value="' . $row[fileLoc2] . '" />';
				
				echo '</tr>' . PHP_EOL;
			}
        
		mysql_close($dbc);
		
    	?>
	</table>
    <?php if($num_pages>1){echo generate_page_links($sort, 0, 0, $cur_page, $num_pages); echo '<br />';}?>
    <br />
    </div>
    <div class="footer">
    <?php echo credits(); ?>
    </div>
</div>

</body>
</html>