<?php

	include_once "include_db.php";
	include_once "include_functions.php";
	checkUserSessionandCookie();
	include_once "include_header.php";

	$username = $_SESSION["uname"];
	if( !IsSadmin() ) {
		header("Location: welcome.php");
		exit();
	}
?>
<SCRIPT>

	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('projects.php');
		$("div.projectDesc").click(function(){
			this.contentEditable = true;
			this.className = 'projectDescContentEditable';
			this.ORIGINALCONTENT = $(this).html();
			this.focus();
			var thisprj = $(this).attr('projectName');

			var pjo = $('div.projectDescOptions') ;
			for(var i=0; i < pjo.length ; i++){
				if( $(pjo[i]).attr('projectName') == thisprj ){ $(pjo[i]).show(); continue; }
			}
		});

		$("div.projectDescOptions span.cancelDescEdit").click(function(){
			var thisprj = $(this).attr('projectName');

			 $(this).parent().hide();
			var pjd = $('div.projectDescContentEditable') ;

			for(var i=0; i < pjd.length ; i++){
				var this_pjd = pjd[i];
				if( $(this_pjd).attr('projectName') == thisprj ){ 
						this_pjd.className="projectDesc";
						$(this_pjd).html(this_pjd.ORIGINALCONTENT);
						this_pjd.contentEditable = false;
					continue;
				}
			}
		});

		$("div.projectDescOptions span.updateDescEdit").click(function(){
			var thisprj = $(this).attr('projectName');
			var NEWCONTENT = '';

			var pjd = $('div.projectDescContentEditable') ;
			for(var i=0; i < pjd.length ; i++){
				var this_pjd = pjd[i];
				if( $(this_pjd).attr('projectName') == thisprj ){ 
						NEWCONTENT = $(this_pjd).html();
					continue;
				}
			}

			DE_USER_action( 'updateProjectDesc',
			{
				projectName: thisprj,
				projectDesc: NEWCONTENT,
				callback:function(a){
					if(a){
						window.location.href='projects.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		});


	}; // End of localajaxinit


	var manageProjectsJsfunctions = {

		createNewProject_form: function(){ // manageProjectsJsfunctions.createNewProject_form()
			//My_JsLibrary.showdeadcenterdiv( 'CreateProject_Form' );
			$('#CreateProject_Form').showWithBg();
		},

		createProject: function(){ // manageProjectsJsfunctions.createProject()
			var projectname = My_JsLibrary.getFieldValue('cp_prj_name');
			var projectDesc = My_JsLibrary.getFieldValue('cp_prj_desc');
			DE_USER_action( 'createNewProject',
			{
				projectName: projectname,
				projectDesc: projectDesc,
				callback:function(a){
					if(a){
						window.location.href='projects.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		},


		closeProject : function( prj ){ // manageProjectsJsfunctions.closeProject( prj );
			if(!confirm("Are you sure you want to close this project? \n New tasks can not be added to this project once closed")){return;}
			DE_USER_action( 'closeProject',
			{
				projectName: prj,
				callback:function(a){
					if(a){
						window.location.href='projects.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		},

		deleteProject : function( prj ){ // manageProjectsJsfunctions.deleteProject( prj );
			DE_USER_action( 'deleteProject',
			{
				projectName: prj,
				callback:function(a){
					if(a){
						window.location.href='projects.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		},

		addUserToProject : function(user,project){ // manageProjectsJsfunctions.addUserToProject(user,project)
			DE_USER_action( 'addUserToProject',
			{
				user: user,
				project: project,
				callback:function(a){
					if(a){
						window.location.href='projects.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		},

		removeUserFromProject : function(user,project){ // manageProjectsJsfunctions.removeUserFromProject(user,project)
			DE_USER_action( 'removeUserFromProject',
			{
				user: user,
				project: project,
				callback:function(a){
					if(a){
						window.location.href='projects.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		}
	};

</SCRIPT>



<?php
	$manageUsers = new manageUsers();
	$allusers = $manageUsers->listOfAllUsers();
	$manageProjects = new manageProjects();
	$allProjects = $manageProjects->listOfAllProjectsIncludeClosed();
?>
<div class="ProjectsListing">
	<div style="margin-bottom: 10px;">
		<span onclick="manageProjectsJsfunctions.createNewProject_form();" class='bluebutton'>Add Project</span>
	</div>
	<?php
	foreach ($allProjects as $project ) {
		if( $project == DEFAULTPERSONALPROJECT ){ continue; }
		$usersInThisProject = $manageProjects->getUsersListInProject($project) ;
		$isProjectActive = $manageProjects->isProjectActive( $project );

		echo "\n<div class='project'>" ;
			if( $isProjectActive ){
				echo "<div class='projectname'>$project</div>";
			}else{
				echo "<div class='projectname' style='color:#D78F7B'>$project [CLOSED]</div>";
			}

			echo "	<div class='projectDesc' projectName='$project'>". $manageProjects->getProjectDescription($project) ."</div>";
			echo "	<div class='projectDescOptions' projectName='$project'>
						<span class='bluebuttonSmall updateDescEdit' projectName='$project'>Update</span>
						<span class='bluebuttonSmall cancelDescEdit' projectName='$project'>Cancel</span>
					</div>";

			echo "<div class='projectUsers'> Users: ";
				foreach ($allusers as $thisuser){
					if( in_array($thisuser, $usersInThisProject) ){
						echo "<span class='userInProject' title='click to remove this user from project' onclick=\"manageProjectsJsfunctions.removeUserFromProject('".$thisuser."' , '".$project."')\"><B>$thisuser</B></span>";
					}else{
						echo "<span class='userNotInProject' title='click to include this user in project' onclick=\"manageProjectsJsfunctions.addUserToProject('".$thisuser."' , '".$project."')\">$thisuser</span>";
					}
				}
			echo "</div>";

			$projectOptions = array();
			if( $project <> DEFAULTPROJECT && $isProjectActive ){
				$projectOptions[] = "<span class='blueTextButton' onclick=\"manageProjectsJsfunctions.closeProject('".$project."')\">Close Project</span>";
			}else{
				// reactivate project
			}
			if( $project <> DEFAULTPROJECT &&  $manageProjects->canProjectBeDeleted($project) ){
				$projectOptions[] = "<span class='blueTextButton' onclick=\"manageProjectsJsfunctions.deleteProject('".$project."')\">Delete Project</span>";
			}

			if( count($projectOptions) ){ echo "<div class='projectOptions' project='$project'>".implode(" | ", $projectOptions)."</div>"; }
		echo "</div> ";
	}
	?>
</div>




<div id="CreateProject_Form" style="display:none; width: 500" class="divAboveBg">
	<TABLE width="100%" cellpadding=0 cellspacing=2 border=0 class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title">Create New Project</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="445" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="right">Project Name :</TD>
			<TD><input type="text" size=26 id="cp_prj_name" class="hilight"></TD>
		</TR>
		<TR><TD align="right" valign="top">Description :</TD>
			<TD><textarea id="cp_prj_desc" cols=35 rows=2></textarea></TD>
		</TR>
		<TR>
			<TD></TD>
			<TD style="padding:10px;"><span class="bluebuttonSmall" onclick="manageProjectsJsfunctions.createProject()">Submit</span></TD>
		</TR>
	</TABLE>		
</div>


<?php

	include "include_footer.php";

?>