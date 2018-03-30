<?php

	//Application constants
	
	define('ADMIN_PATH', 'admin.php');
	define('ABSTRACT_PATH', 'abstract.php');
	define('AUTHOR_PATH', 'author.php');
	define('EDIT_PATH', 'edit.php');
	define('EDITENTRY_PATH', 'editentry.php');
	define('HOME_PATH', 'home.php');
	define('FILE_UPLOADPATH', 'files/');
	
	//Application functions
	
	function build_query($query_string, $sort){
		
		$q = $query_string;
		
		switch ($sort){
			case 1:
				$q .= " ORDER BY paperNumber";
				break;
			case 2:
				$q .= " ORDER BY paperNumber DESC";
				break;
			case 3:
				$q .= " ORDER BY title";
				break;
			case 4:
				$q .= " ORDER BY title DESC";
				break;
			case 5:
				$q .= " ORDER BY authorFamily, authorGiven";
				break;
			case 6:
				$q .= " ORDER BY authorFamily DESC, authorGiven DESC";
				break;
			default: 
				$q .= " ORDER BY paperNumber DESC";
		}
		
		return $q;
	}
	
	function build_sort_links($sort, $edit, $given, $family){
		
		$sort_links = '';
		
		switch ($sort) {
			case 1:
				$sort_links .= '<tr><th class="date">Date</th><th class="number"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=2">No.</a></th>';
				$sort_links .= '<th class="title"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=3">Title</a></th>';
				$sort_links .= '<th class="author"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=5">Author(s)</a></th>';
				if($edit){$sort_links .= '<th><span class="content">Edit/Delete</th>';}
				$sort_links .= '</tr>';
				break;
			case 3:
				$sort_links .= '<tr><th class="date">Date</th><th class="number"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=1">No.</a></th>';
				$sort_links .= '<th class="title"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=4">Title</a></th>';
				$sort_links .= '<th class="author"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=5">Author(s)</a></th>';
				if($edit){$sort_links .= '<th><span class="content">Edit/Delete</th>';}
				$sort_links .= '</tr>';
				break;
			case 5:
				$sort_links .= '<tr><th class="date">Date</th><th class="number"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=1">No.</a></th>';
				$sort_links .= '<th class="title"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=3">Title</a></th>';
				$sort_links .= '<th class="author"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=6">Author(s)</a></th>';
				if($edit){$sort_links .= '<th><span class="content">Edit/Delete</th>';}
				$sort_links .= '</tr>';
				break;
			default:
				$sort_links .= '<tr><th class="date">Date</th><th class="number"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=1">No.</a></th>';
				$sort_links .= '<th class="title"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=3">Title</a></th>';
				$sort_links .= '<th class="author"><a class="sorter" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$sort_links .= 'given=' . $given . '&family=' . $family . '&';}
				$sort_links .= 'sort=5">Author(s)</a></th>';
				if($edit){$sort_links .= '<th><span class="content">Edit/Delete</th>';}
				$sort_links .= '</tr>';
		}
		
		return $sort_links;
	}
	
	function generate_page_links($sort, $given, $family, $cur_page, $num_pages){
		
		$page_links = 'Pages: ';
		//previous link if not the first
		if($cur_page > 1){
			$page_links .= '<a class="pageLink" href="' . $_SERVER['PHP_SELF'] . '?';
			if($given && $family){$page_links .= 'given=' . $given . '&family=' . $family . '&';}
			if($sort){$page_links .= 'sort=' . $sort . '&';}
			$page_links .= 'page=' . ($cur_page - 1) . '"><</a> ';
		} else {$page_links .= '< ';}
		//generate page number links
		for ($i = 1; $i <= $num_pages; $i++){	
			if ($cur_page == $i){$page_links .= ' ' . $i;}
			else {
				$page_links .= ' <a class="pageLink" href="' . $_SERVER['PHP_SELF'] . '?';
				if($given && $family){$page_links .= 'given=' . $given . '&family=' . $family . '&';}
				if($sort){$page_links .= 'sort=' . $sort . '&';}
				$page_links .= 'page=' . $i . '"> ' . $i . '</a>';
			}
		}
		//next link if not the last
		if($cur_page < $num_pages){
			$page_links .= ' <a class="pageLink" href="' . $_SERVER['PHP_SELF'] . '?';
			if($given && $family){$page_links .= 'given=' . $given . '&family=' . $family . '&';}
			if($sort){$page_links .= 'sort=' . $sort . '&';}
			$page_links .= 'page=' . ($cur_page + 1) . '">></a> ';
		} else {$page_links .= ' >';}
		
		return $page_links;
	}
	
	function fileClass($fileType){
		
		$file_type = '';
		//detect file extention and assign css class
		if ($fileType == "pdf"){$file_type .= 'pdfLink';}
		if ($fileType == "xls"){$file_type .= 'xlsLink';}
		if ($fileType == "xlsx"){$file_type .= 'xlsLink';}		
		if ($fileType == "doc"){$file_type .= 'docLink';}
		if ($fileType == "docx"){$file_type .= 'docLink';}
		if ($fileType == "zip"){$file_type .= 'zipLink';}
		
		return $file_type;
	}
	
	function generate_nav_links($add){
		 //standard links
		 $nav_string = 
		 '<ul>
            <li><a href="http://www.sophia.ac.jp/eng/e_top" title="Sophia University English Homepage">Sophia University</a></li>
            <li>&nbsp;&gt;&gt;&nbsp;<a href="http://www.fla.sophia.ac.jp/" title="Faculty of Liberal Arts Homepage">Faculty of Liberal Arts</a></li>
            <li>&nbsp;&gt;&gt;&nbsp;<a href="http://www.fla.sophia.ac.jp/professors/research" title="Research Related to FLA">Research</a></li>
            <li>&nbsp;&gt;&gt;&nbsp;<a href="' . HOME_PATH . '" title="FLA: Discussion Paper Series in Business and Economics">FLA:DPS Home</a></li>';
		//additional links
		if($add==1){$nav_string .= '
            <li>&nbsp;&gt;&gt;&nbsp;Admin: <a href="' . ADMIN_PATH . '" title="FLA:DPS Admin - Update">Update</a> / 
            <a href="' . EDIT_PATH . '" title="FLA:DPS Admin - Edit">Edit</a></li>';}	
        $nav_string .= '</ul>';
		
		return $nav_string;
	}
	
	function credits (){

		$credit_string = '<span class="content">compatible with Chrome 24, Firefox 19, &amp; Internet Explorer 9. coded by <a href="mailto:glenn@generator.herobo.com">Glenn Hawkins</a></span>';
		
		return $credit_string;
	}
	
?>