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

function deleteFile(rowNum, fileNum){
	var msg = "Are you sure you want to delete this file?";
	
	var locString = rowNum + "fileLocation";
	if(fileNum==2){locString = locString+"2";}
	var fLoc = document.getElementById(locString).value;
	
	if(confirm(msg)){
		
		xmlhttp = new XMLHttpRequest();
		
		if (xmlhttp==null) {
			alert ("Browser does not support HTTP Request");
			return;
		}
		var url = "delete_engine.php";
		url = url+"?file";
		if(fileNum==2){url = url+"2";}
		url = url+"="+fLoc;
		url = url+"&switch="+fileNum;
		url = url+"&sid="+Math.random();
		xmlhttp.onreadystatechange = stateChanged;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
	}
}

function stateChanged() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		alert("File deleted");
		
		location.reload(true);
	}
}

function clearMain(){
	var cT = document.getElementById('clearToggle').value;
	
	if (cT==1) {
		var divMain = document.getElementById('divMain');
		while(divMain.firstChild){divMain.removeChild(divMain.firstChild);}
	}
}

function selectedDates(){
	
	var selMonth = document.getElementById('selectedMonth').value;
	var selYear = document.getElementById('selectedYear').value;
		
	var monthValues = [];
	var monthSel = document.getElementById('monthSelect');
	for (var i=0, n=monthSel.options.length;i<n;i++) {
	  	if (monthSel.options[i].value == selMonth){
			monthSel.options[i].selected = true
		}
	}
	
	var yearValues = [];
	var yearSel = document.getElementById('yearSelect');
	for (var i=0, n=yearSel.options.length;i<n;i++) {
	  	if (yearSel.options[i].value == selYear){
			yearSel.options[i].selected = true;
		}
	}
}

function validateForm(){
	
	var month = document.forms["updateForm"]["monthSelect"].value;
	var year = document.forms["updateForm"]["yearSelect"].value;
	/*var x = document.forms["updateForm"]["monthSelect"].value;
	var x = document.forms["updateForm"]["monthSelect"].value;
	var x = document.forms["updateForm"]["monthSelect"].value;
	var x = document.forms["updateForm"]["monthSelect"].value;*/
	
	if (month=="Month" || year=="Year"){
	alert("Please enter a date of publication.");
	return false;
	}
}

function addAuthor(next){
	
	var nTd = document.getElementById('namesTd');
	
	var givenId = "author" + next + "GivenText",
	familyId = "author" + next + "FamilyText";	
		
	var txtGiven = document.createTextNode("Given:"),
	txtFamily = document.createTextNode(" Family:")
	
	var givenInput = document.createElement('input');
	givenInput.setAttribute('type', 'text');
	givenInput.setAttribute('size', '30');
	givenInput.setAttribute('id', givenId);
	givenInput.setAttribute('name', givenId);
	
	var familyInput = document.createElement('input');
	familyInput.setAttribute('type', 'text');
	familyInput.setAttribute('size', '30');
	familyInput.setAttribute('id', familyId);
	familyInput.setAttribute('name', familyId);
	
	var x = document.getElementById('addAuthLink');
	x.parentNode.removeChild(x);
	
	nTd.appendChild(txtGiven);
	nTd.appendChild(givenInput);
	nTd.appendChild(txtFamily);
	nTd.appendChild(familyInput);
	nTd.appendChild(document.createElement('br'));
	
	if(next<5){
		var addLink = document.createElement('span');
		addLink.setAttribute('id', 'addAuthLink');
		addLink.setAttribute('class', 'optionText');
		var onclickText = "addAuthor("+ (next+1) +")"
		addLink.setAttribute('onclick', onclickText);
		var txtA = document.createElement('a');
		var txtLink = document.createTextNode("Add author?");
		addLink.appendChild(txtA);
		txtA.appendChild(txtLink);
		
		nTd.appendChild(addLink);
	}
	
	document.getElementById(givenId).focus();
	
}

function addFileBox(x){
	
	var fileTd = document.getElementById('fileBox');
	var addfilebox = document.createElement('input');

	var y = document.getElementById('addText');
	y.parentNode.removeChild(y);
	
	if(x==1){
		addfilebox.setAttribute('type', 'file');
		addfilebox.setAttribute('id', 'mainFile');
		addfilebox.setAttribute('name', 'mainFile');
		
		fileTd.insertBefore(addfilebox, fileTd.firstChild);
	}
	
	if(x==2){
		addfilebox.setAttribute('type', 'file');
		addfilebox.setAttribute('id', 'supFile');
		addfilebox.setAttribute('name', 'supFile');
		
		fileTd.appendChild(addfilebox);
	
	}
	
}

</script>

</head>

<body onload="clearMain();selectedDates()">

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
        <?php if (!isset($_POST['submit'])) { echo '<p class="content">Use the form below to add entries to the database. Please note that some fields are required<span class="asterisk">*</span></p>
        <p class="content">Acceptable file types are: .pdf, .xls, .doc, &amp; .zip.</p>';} ?>
    <?php
	
	rem
	
	//get parameters
	$num = $_GET['num'];
	
	if (isset($_POST['submit'])) {
		// get form values
		$paperid = $_POST['idinput'];
		$date_month = $_POST['monthSelect'];
		$date_year = $_POST['yearSelect'];
		$paperNum = mysql_real_escape_string($dbc, trim($_POST['paperNumberText']));
		$title = mysql_real_escape_string($dbc, trim($_POST['titleText']));
		$authorGiven = mysql_real_escape_string($dbc, trim($_POST['authorGivenText']));
		$authorFamily = mysql_real_escape_string($dbc, trim($_POST['authorFamilyText']));
		$author2Given = mysql_real_escape_string($dbc, trim($_POST['author2GivenText']));
		$author2Family = mysql_real_escape_string($dbc, trim($_POST['author2FamilyText']));
		$author3Given = mysql_real_escape_string($dbc, trim($_POST['author3GivenText']));
		$author3Family = mysql_real_escape_string($dbc, trim($_POST['author3FamilyText']));
		$author4Given = mysql_real_escape_string($dbc, trim($_POST['author4GivenText']));
		$author4Family = mysql_real_escape_string($dbc, trim($_POST['author4FamilyText']));
		$author5Given = mysql_real_escape_string($dbc, trim($_POST['author5GivenText']));
		$author5Family = mysql_real_escape_string($dbc, trim($_POST['author5FamilyText']));
		$abstractText = mysql_real_escape_string($dbc, trim($_POST['abstractText']));
		
		// get file information and relocate
		if ($_FILES['mainFile']['name']){
			$fileName = mysql_real_escape_string($dbc, trim($_FILES['mainFile']['name']));
			$fileSizeBytes = $_FILES['mainFile']['size'];
			$fileSize = floor($fileSizeBytes / 1024);
			
			if (!empty($fileName)) {
				$fileLocation = FILE_UPLOADPATH . $fileName;
				move_uploaded_file($_FILES['mainFile']['tmp_name'], $fileLocation);
			}
			
			$fileType = pathinfo($fileLocation, PATHINFO_EXTENSION);
		}
		
		if ($_FILES['supFile']['name']){
			$fileName2 = mysql_real_escape_string($dbc, trim($_FILES['supFile']['name']));
			$fileSizeBytes2 = $_FILES['supFile']['size'];
			$fileSize2 = floor($fileSizeBytes2 / 1024);
			
			if (!empty($fileName2)) {
				$fileLocation2 = FILE_UPLOADPATH . $fileName2;
				move_uploaded_file($_FILES['supFile']['tmp_name'], $fileLocation2);
			}
			
			$fileType2 = pathinfo($fileLocation2, PATHINFO_EXTENSION);
		}
		
		$query = "UPDATE " . TABLE_NAME . " SET dateMonth='$date_month', dateYear='$date_year', paperNumber='$paperNum', title='$title', authorGiven='$authorGiven', authorFamily='$authorFamily',
						author2Given='$author2Given', author2Family='$author2Family', author3Given='$author3Given', author3Family='$author3Family', author4Given='$author4Given', author4Family='$author4Family',
						author5Given='$author5Given', author5Family='$author5Family', abstractText='$abstractText'";
						if($fileLocation){$query.= ", fileLoc='$fileLocation', fileSize='$fileSize', fileType='$fileType'";}
						if($fileLocation2){$query.= ", fileLoc2='$fileLocation2', fileSize2='$fileSize2', fileType2='$fileType2'";}
						$query.= " WHERE id='$paperid'";
		
		$result = mysql_query($query, $dbc)
    			or die('Error querying database.');
		
		// success?
		echo '<p class="content">You have successfully edited Discussion Paper No. ' . $paperNum . ' titled "' . $title . '"</p>';
		if($fileLocation){echo '<p class="content">You uploaded file ' . $fileName . ' of size ' . $fileSize . 'KB and type "' . $fileType . '"</p>';}
		if($fileLocation2){echo '<p class="content">You uploaded file ' . $fileName2 . ' of size ' . $fileSize2 . 'KB and type "' . $fileType2 . '"</p>';}
		echo '<p class="content">&lt;&lt; <a href="editdev.php">Back to the Edit page</a></p>';

		// clear vars
		$paperid = "";
		$date_month = "";
		$date_year = "";
		$paperNum = "";
		$title = "";
		$authorGiven = "";
		$authorFamily = "";
		$author2Given = "";
		$author2Family = "";
		$author3Given = "";
		$author3Family = "";
		$author4Given = "";
		$author4Family = "";
		$author5Given = "";
		$author5Family = "";
		$abstractText = "";
		
		$fileLocation = "";
		$fileSize = "";
		
		mysql_close($dbc);
		
	} else{
		
		rem
					  
		$query = "SELECT id, dateMonth, dateYear, paperNumber, title, authorGiven, authorFamily, author2Given, author2Family, author3Given, author3Family,
						author4Given, author4Family, author5Given, author5Family, abstractText, fileLoc, fileType, fileSize, fileLoc2, fileType2, fileSize2 FROM " . TABLE_NAME . " WHERE paperNumber='$num'";
		
		$result = mysql_query($query, $dbc)
   				or die('Error querying database.');
			
			while($row=mysql_fetch_array($result)) {
				$id = $row[id];
				$dm = $row[dateMonth];
				$dy = $row[dateYear];
				$pn = $row[paperNumber];
				$title = $row[title];
				$ag = $row[authorGiven];
				$af = $row[authorFamily];
				
				if ( $row[author2Given] ){
					$ag2 = $row[author2Given];
					$af2 = $row[author2Family];
				}
				if ( $row[author3Given] ){
					$ag3 = $row[author3Given];
					$af3 = $row[author3Family];
				}
				if ( $row[author4Given] ){
					$ag4 = $row[author4Given];
					$af4 = $row[author4Family];		
				}
				if ( $row[author5Given] ){
					$ag5 = $row[author5Given];
					$af5 = $row[author5Family];
				}
				
				$at = $row[abstractText];
				$fl = $row[fileLoc];
				$ft = $row[fileType];
				$fs = $row[fileSize];
				$fl2 = $row[fileLoc2];
				$ft2 = $row[fileType2];
				$fs2 = $row[fileSize2];
			}
        
		mysql_close($dbc);
		
	}
	
	?>
    </div>
    <div id="divMain" class="main">
        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="updateForm" onsubmit="return validateForm()">
        <input type="hidden" name="MAX_FILE_SIZE" value="1536000" />
        <input type="hidden" name="idinput" value="<?php echo $id; ?>" />
            <table align="center" class="formTable">
            <tr><th>
            Date of Publication<span class="asterisk">*</span>:
            </th><td>
            <input type="hidden" id="selectedMonth" value="<?php echo $dm; ?>" />
            <select id="monthSelect" name="monthSelect">
                <option>Month</option>
                <option>January</option>
                <option>February</option>
                <option>March</option>
                <option>April</option>
                <option>May</option>
                <option>June</option>
                <option>July</option>
                <option>August</option>
                <option>September</option>
                <option>October</option>
                <option>November</option>
                <option>December</option>
            </select>
            <input type="hidden" id="selectedYear" value="<?php echo $dy; ?>" />
            <select id="yearSelect" name="yearSelect">
            	<option >Year</option>
                <?php
					$curYear = date('Y');
					$i = $curYear - 2;
					
					for ($i; $i<=$curYear; $i++){
						echo '<option>' . $i . '</option>';
					}
                ?>
            </select>
            </td></tr><tr><th>
            Paper No.<span class="asterisk">*</span>:
            </th><td>
            <input type="text" size="10" id="paperNumberText" name="paperNumberText" value="<?php echo $pn; ?>" required />
            </td></tr><tr><th>
            Title<span class="asterisk">*</span>:
            </th><td>
            <textarea cols="80" rows="3" maxlength="500" id="titleText" name="titleText" required><?php echo $title; ?></textarea>
            </td></tr><tr><th>
            Author(s):
            </th><td id="namesTd">
            Given:<input type="text" size="30" id="authorGivenText" name="authorGivenText" value="<?php echo $ag; ?>" required />
            Family:<input type="text" size="30" id="authorFamilyText" name="authorFamilyText" value="<?php echo $af; ?>" required />
            <span class="asterisk">*</span><br />
			<?php
				
				if($ag2){ echo '
				Given:<input type="text" size="30" id="author2GivenText" name="author2GivenText" value="' . $ag2 . '" />
				Family:<input type="text" size="30" id="author2FamilyText" name="author2FamilyText" value="' . $af2 . '" />
				<br />'; if(!$ag3){ echo '<span id="addAuthLink" class="optionText" onclick="addAuthor(3)"><a>Add author?</a></span>';}}
					else {echo '<span id="addAuthLink" class="optionText" onclick="addAuthor(2)"><a>Add author?</a></span>';}
				if ($ag3){ echo '
				Given:<input type="text" size="30" id="author3GivenText" name="author3GivenText" value="' . $ag3 . '" />
				Family:<input type="text" size="30" id="author3FamilyText" name="author3FamilyText" value="' . $af3 . '" />
				<br />'; if(!$ag4){ echo '<span id="addAuthLink" class="optionText" onclick="addAuthor(4)"><a>Add author?</a></span>';}}
				if($ag4){ echo '
				Given:<input type="text" size="30" id="author2GivenText" name="author4GivenText" value="' . $ag4 . '" />
				Family:<input type="text" size="30" id="author2FamilyText" name="author4FamilyText" value="' . $af4 . '" />
				<br />'; if(!$ag5){ echo '<span id="addAuthLink" class="optionText" onclick="addAuthor(5)"><a>Add author?</a></span>';}}
				if ($ag5){ echo '
				Given:<input type="text" size="30" id="author3GivenText" name="author5GivenText" value="' . $ag5 . '" />
				Family:<input type="text" size="30" id="author3FamilyText" name="author5FamilyText" value="' . $af5 . '" />
				<br />';}
				
			
			
			?>
            </td></tr><tr><th>
            Abstract:
            </th><td>
            <textarea cols="80" rows="10" maxlength="5000" id="abstractText" name ="abstractText"><?php echo $at; ?></textarea>
            </td></tr>
            <tr><th>File(s):</th><td id="fileBox">
            <?php
				if ($fl || $fl2){
					echo '';
					if ($fl) {
					echo '<a href="' . $fl . '" class="';
					echo fileClass($ft);
					echo '" target="_blank">' . $pn . '</a> (' . $fs . 'KB) ' . 
						'<span class="optionText" onclick="deleteFile(' . $pn . ', 1)"><a>Delete file?</a></span> ' .
						'<input type="hidden" id="' . $pn . 'fileLocation" value="' . $fl . '" />';
					}
					if ($fl2) {
					echo '<a href="' . $fl2 . '" class="';
					echo fileClass($ft2);
					echo '" target="_blank">' . $pn . '</a> (' . $fs2 . 'KB) ' . 
						'<span class="optionText" onclick="deleteFile(' . $pn . ', 2)"><a>Delete file?</a></span> ' .
						'<input type="hidden" id="' . $pn . 'fileLocation2" value="' . $fl2 . '" />';
					}
					
					
					if ($fl && !$fl2){
						echo ' <span class="optionText" id="addText" onclick="addFileBox(2)"><a>Add additional file?</a></span> ';
					}
					if (!$fl && $fl2){
						echo ' <span class="optionText" id="addText" onclick="addFileBox(1)"><a>Add additional file?</a></span> ';
					}
					echo '</td></tr>';
					
				} else {echo '<input type="file" id="mainFile" name="mainFile"  />
									<span class="optionText" id="addText" onclick="addFileBox(2)"><a>Add additional file?</a></span>';}
			?>
            </td></tr>
            <tr><th colspan="2">
            <input type="submit" name="submit" value="...send to database..."  />
            </th></tr>
            </table>
            
        <input type="hidden" id="clearToggle" value="<?php if(isset($_POST['submit'])){echo 1;}else{echo 0;} ?>" />    
        </form>
        <br />
    </div>
    <div class="footer">
    <?php echo credits(); ?>
    </div>
</div>

</body>
</html>