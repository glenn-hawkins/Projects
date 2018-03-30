<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="images/favicon.ico"/>

<title>[ - FLA Discussion Paper Series in Business and Economics - ]</title>

<link href="styles/site.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="scripts/site.js"> </script>

</head>

<body>

<div class="container">
<br />
    <div class="header" id="headerDiv"></div>
    <div class="nav">
		<?php 
			//get constants
			require_once('appvars.php');
			require_once('connectvars.php');
			
			echo generate_nav_links(0);
		?>
    </div>    
    <div class="pageTitle">

    <?php
		
        $num = $_GET['num']; // get paper number
		
        $query = "SELECT dateMonth, dateYear, paperNumber, title, authorGiven, authorFamily, author2Given, author2Family, author3Given, author3Family,
							author4Given, author4Family, author5Given, author5Family, abstractText, fileLoc, fileSize, fileType, fileLoc2, fileSize2, fileType2";
		$query .= " FROM " . TABLE_NAME;
		$query .= " WHERE paperNumber='$num' LIMIT 1";
        
        $result = mysql_query($query, $dbc)
     		or die('Error querying database.'); 
		
		while($row=mysql_fetch_array($result)) {
			echo '<h3>' . $row[title] . '</h3></div>
    		<div class="main">
			<p>Published: ' . $row[dateMonth]. ' ' . $row[dateYear] . '</p><p>No. ' . $row[paperNumber] . '</p><p>' .
			'<a href="' . AUTHOR_PATH . '?given=' . $row[authorGiven] . '&family=' . $row[authorFamily] . '">' . $row[authorGiven] . ' ' . $row[authorFamily] . "</a>";
							
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
			
			echo '</p>';
			if( $row[abstractText] ){echo '<p>' . $row[abstractText] . '</p>';}
			
			if( $row[fileLoc] || $row[fileLoc2] ){
				echo '<table align="left"><tr><th>File(s):</th><td>';
         		if ( $row[fileLoc] ){
					echo '<a href="' . $row[fileLoc] . '" class="';
					echo fileClass($row[fileType]);
					echo '" target="_blank">' . $row[paperNumber] . '</a> (' . $row[fileSize] . 'KB) ';
				}
				if ( $row[fileLoc2] ){
					echo '<a href="' . $row[fileLoc2] . '" class="';
					echo fileClass($row[fileType2]);
					echo '" target="_blank">Supplemental material</a> (' . $row[fileSize2] . 'KB)';
				}
				echo '</td></tr></table>';
			}
			echo '<br /><br />' . PHP_EOL;
		}
        
		mysql_close($dbc);
		
    ?>
    </div>
    <div class="footer">
    <?php echo credits(); ?>
    </div>
</div>

</body>
</html>