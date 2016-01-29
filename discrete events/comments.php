<?php

	include_once "include_db.php";
	include_once "include_functions.php";
	checkUserSessionandCookie();
	include_once "include_header.php";

	$username = $_SESSION["uname"];
?>
<SCRIPT>

var localajaxinit = function(){
	My_JsLibrary.selectMainTab('comments.php');

	var hm = My_JsLibrary.parseGETparam( 'howmany') || '25' ;
	My_JsLibrary.selectbox.selectOption(_$('select_comments_howmany'), hm);
}; // End of localajaxinit

</SCRIPT>


<?php

	function showHowManyCommentsSelect(){	?>
		<center>
			<div style='margin:10px;'>
				Show 
				<select id="select_comments_howmany" onchange="var hm = this.value; My_JsLibrary.updatePageWithGetVar('howmany', hm);">
					<option value='25'>25 Comments</option>
					<option value='50'>50 Comments</option>
					<option value='100'>100 Comments</option>
					<option value='250'>250 Comments</option>
				</select>
			</div>
		</center>
	<?
	}

	showHowManyCommentsSelect();

	$howmany = get_GET_var('howmany');
	if(!$howmany){$howmany = 25;} 

	$manageUsers = new manageUsers();
	$manageUsers->getUnreadComments($username, $howmany);

	showHowManyCommentsSelect();


include "include_footer.php";
?>