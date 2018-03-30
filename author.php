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
				
		$sort = $_GET['sort']; // get sort parameter
		
		$given = $_GET['given']; // get author's name
		$family = $_GET['family'];
        
		//calculate pagination
		$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
		$results_per_page = 10;
		$skip = ($cur_page - 1) * $results_per_page;
                  
        $query_string = "SELECT dateMonth, dateYear, paperNumber, title, authorGiven, authorFamily, author2Given, author2Family, author3Given, author3Family,
							author4Given, author4Family, author5Given, author5Family
					FROM " . TABLE_NAME . " 
					WHERE (authorGiven='$given' AND authorFamily='$family')
					OR (author2Given='$given' AND author2Family='$family')
					OR (author3Given='$given' AND author3Family='$family')
					OR (author4Given='$given' AND author4Family='$family')
					OR (author5Given='$given' AND author5Family='$family')";
        
		$query = build_query($query_string, $sort);
			
		$result = mysql_query($query, $dbc)
    		or die('Error querying database.');
			
		$total = mysql_num_rows($result);
		$num_pages = ceil($total / $results_per_page);
		
		if ($num_pages>1){$query = $query . " LIMIT $skip, $results_per_page";}
		
        $result = mysql_query($query, $dbc)
     			or die('Error querying database.');
			
		echo '<h3>Papers ' . $given . ' ' .  $family . ' has contributed to:</h3>' . PHP_EOL;	
		
 
	?>
    </div>
    <div class="main"><br />
    <table align="center" class="paperlist">      
        <?			
			echo build_sort_links($sort, 0, $given, $family);
						
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
				
				echo '</td></tr>' . PHP_EOL;
			}
        
			mysql_close($dbc);
		
    	?>
	</table>
    <?php if($num_pages>1){echo generate_page_links($sort, $given, $family, $cur_page, $num_pages); echo '<br />';}?>
    <br />
    </div>
    <div class="footer">
    <?php echo credits(); ?>
    </div>
</div>

</body>
</html>