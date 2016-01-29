<?php

include_once "include_db.php";
include_once "include_functions.php";
checkUserSessionandCookie();
include_once "include_header.php";
$username = $_SESSION["uname"];

?>
<SCRIPT>
var localajaxinit = function(){
	My_JsLibrary.selectMainTab('scheduled.php');
	$($.date_input.initialize);

	$("img.toCurrent").click(function(){
		var workid = $(this).attr('workid');
		DE_USER_action( 'task_UnSchedule' , {
			workid : workid,
			callback:function(a){
				if(a){
					$("TR.STT_TR_" + workid).hide();
					My_JsLibrary.showfbmsg( "Task moved back to 'New Tasks' Queue !", 'green');
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	});
	
}; // End of localajaxinit

</SCRIPT>

<div style='display:block !important; height: 20px !important; margin: -18px 0 0 !important;padding: 0 !important; position: fixed !important; top: 45% !important; width: 17px; z-index: 100000 ; right:0; background-color: #ECECEC ;' onclick="My_JsLibrary.windowReload();" TITLE="Reload Page">
	<img src='images/icon_refresh.gif' border=0>
	<span id='span_refreshcountdown'></span>
</div>

<?php

// List Scheduled Tasks
	$ureport = new taskReports();
	$ureport->doNotIncludePersonalCondition = true;
	$ureport->showOnlyMyTasks = false;
	$sortBy = get_GET_var('sortby');	
	if( $sortBy ){
		$ureport->orderbyfield = $sortBy ;
	}
	$ureport->listWorksScheduled();
?>
<?php
	include "include_footer.php";
?>