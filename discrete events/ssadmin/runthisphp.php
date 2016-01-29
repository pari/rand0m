<?php

	include ("../include_variables.php");
	include ("include_header.php");
	include ("../include_functions.php");

?>

<script>
	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('index.php');
	};
</script>
<?php




	$query = mysql_query("select commentID, comment from devents_navrosh.COMMENTS"); 
	WHILE ($row = @mysql_fetch_array($query)){
		extract($row); // $commentID, $comment
		$newcomment = mynl2br($comment);
		$result = mysql_query( "update devents_navrosh.COMMENTS set comment='".$newcomment."' where commentID='$commentID' ");
	}




include ("include_footer.php");

?>