<?php

	include_once "include_db.php";
	include_once "include_functions.php";
	checkUserSessionandCookie();
	include_once "include_header.php";
	$username = $_SESSION["uname"];
?>
<SCRIPT>

var localajaxinit = function(){

}; // End of localajaxinit

</SCRIPT>


<?php

	$searchString = get_POST_var('search_term');

	$sreport = new searchReport();
	$sreport->search_ftext($searchString);
	// $sreport->search_fromdate( $fromdate );
	// $sreport->search_todate( $todate );
	// $sreport->search_username( $username );
	// $sreport->search_taskid( $taskid );
	$sreport->search_results();



include "include_footer.php";
?>