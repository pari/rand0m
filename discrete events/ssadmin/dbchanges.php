<?php

include ("../include_variables.php");
include ("include_header.php");
include ("../include_functions.php");

?>
<script>

<?php

	$dbnames = executesql_returnStrictArray( "select dbname from " . MASTERDB . ".subdomains ORDER BY pid" );
	echo "var dbnames = ['" . implode( "','", $dbnames) . "'];" ;
?>

var localajaxinit = function(){
	My_JsLibrary.selectMainTab('dbchanges.php');

};


var generatesql = function(){

	var t = _$('sqlToRunOnEach').value;
	var finalsql = "";

	for(var i=0; i < dbnames.length; i++ ){
		finalsql += "\n use " + dbnames[i] + "; \n" ;
		finalsql += t;
	}

	_$('finalSQL').value = finalsql ;
};


</script>

<center>
	Enter Sql to run on each:<BR>
	<textarea id="sqlToRunOnEach" cols=60 rows=10></textarea>
	<BR><BR>
	Final SQL is:<BR>
	<textarea id="finalSQL" cols=60 rows=10></textarea>
	<BR>
<span class="bluebutton" onclick="generatesql()">Generate</span>
</center>

<?php
	include ("include_footer.php");
?>