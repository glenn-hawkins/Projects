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

function authorNames(num){
	var nTd = document.getElementById('namesTd');
	
	while (nTd.firstChild) {nTd.removeChild(nTd.firstChild);}
	
	for (var i=1; i<=num; i++){
		if (i>1){
			var givenId = "author" + i + "GivenText",
			familyId = "author" + i + "FamilyText";	
		} else {
			var givenId = "authorGivenText",
			familyId = "authorFamilyText";	
		}
		
		var txtGiven = document.createTextNode("Given:"),
		txtFamily = document.createTextNode(" Family:")
		
		var givenInput = document.createElement('input');
		givenInput.setAttribute('type', 'text');
		givenInput.setAttribute('size', '30');
		givenInput.setAttribute('id', givenId);
		givenInput.setAttribute('name', givenId);
		givenInput.setAttribute('required', '');
		
		var familyInput = document.createElement('input');
		familyInput.setAttribute('type', 'text');
		familyInput.setAttribute('size', '30');
		familyInput.setAttribute('id', familyId);
		familyInput.setAttribute('name', familyId);
		familyInput.setAttribute('required', '');
		
		nTd.appendChild(txtGiven);
		nTd.appendChild(givenInput);
		nTd.appendChild(txtFamily);
		nTd.appendChild(familyInput);
		nTd.appendChild(document.createElement('br'));
		
	}
	
	document.getElementById('authorGivenText').focus();
	
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
        <?php if (!isset($_POST['submit'])) { echo '<p class="content">Use the form below to add entries to the database. Please note that some fields are required<span class="asterisk">*</span></p>
        <p class="content">Acceptable file types are: .pdf, .xls/x, .doc/x, &amp; .zip.</p>';} ?>
    <?php
	
	if (isset($_POST['submit'])) {
		// get form values
		
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
		
		$query = "INSERT INTO " . TABLE_NAME . " (dateMonth, dateYear, paperNumber, title, authorGiven, authorFamily, author2Given, author2Family, author3Given, author3Family,
							author4Given, author4Family, author5Given, author5Family, abstractText";
							if($fileLocation){$query.= ", fileLoc, fileSize, fileType";}
							if($fileLocation2){$query.= ", fileLoc2, fileSize2, fileType2";}
		$query .= ") " . "VALUES ('$date_month', '$date_year', '$paperNum', '$title', '$authorGiven', '$authorFamily', '$author2Given', '$author2Family', 
							'$author3Given', '$author3Family', '$author4Given', '$author4Family', '$author5Given', '$author5Family', '$abstractText'";
							if($fileLocation){$query.= ", '$fileLocation', '$fileSize', '$fileType'";}
							if($fileLocation2){$query.= ", '$fileLocation2', '$fileSize2', '$fileType2'";}
		$query .= ")";
		
		mysql_query($query, $dbc)
			 or die('Error querying database.');
		
		// success?
		echo '<p class="content">Your update was successful.</p>';
		echo '<p class="content">You added Discussion Paper No. ' . $paperNum . ' titled "' . $title . '"</p>';
		if($fileLocation){echo '<p class="content">You uploaded file ' . $fileName . ' of size ' . $fileSize . 'KB and with extention "' . $fileType . '"</p>';};
		if($fileLocation2){echo '<p class="content">You uploaded file ' . $fileName2 . ' of size ' . $fileSize2 . 'KB and with extention "' . $fileType2 . '"</p>';}; 
		echo '<p class="content">You may continue to update using the form.</p>';
		
		// clear vars
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
		$fileLocation2 = "";
		$fileSize2 = "";
		
		mysql_close($dbc);
		
	}
	
	?>
    </div>
    <div class="main">
        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="updateForm" onsubmit="return validateForm()">
        <input type="hidden" name="MAX_FILE_SIZE" value="1536000" />
            <table align="center" class="formTable">
            <tr><th>
            Date of Publication<span class="asterisk">*</span>:
            </th><td>
            <select id="monthSelect" name="monthSelect">
                <option selected="selected">Month</option>
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
            
            <select id="yearSelect" name="yearSelect">
            	<option selected="selected">Year</option>
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
            <input type="text" size="10" id="paperNumberText" name="paperNumberText" required />
            </td></tr><tr><th>
            Title<span class="asterisk">*</span>:
            </th><td>
            <textarea cols="80" rows="3" maxlength="500" id="titleText" name="titleText" required></textarea>
            </td></tr><tr><th>
            Author(s)<span class="asterisk">*</span>:
            </th><td id="namesTd">
            <select onchange="authorNames(this.value)">
            	<option selected="selected">How many?</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
            </td></tr><tr><th>
            Abstract:
            </th><td>
            <textarea cols="80" rows="10" maxlength="5000" id="abstractText" name ="abstractText"></textarea>
            </td></tr><tr><th>
            File:
            </th><td id="fileBox">
            <input type="file" id="mainFile" name="mainFile" />
            <span class="optionText" id="addText" onclick="addFileBox(2)"><a>Add additional file?</a></span>
            </td></tr><tr><th colspan="2">
            <input type="submit" name="submit" value="...send to database..."  />
            </th></tr>
            </table>
        </form>
        <br />
    </div>
    <div class="footer">
    <?php echo credits(); ?>
    </div>
</div>

</body>
</html>