<?php
include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }
$CUSTOM_STYLES ="
	div.room{
		width:45%;
		padding:0px;
		margin:10px;
		float:left;
		border:0px solid;
	}
	
	div.room h2 {
		font-size:22px;
		letter-spacing:-1px;
		margin:0;
		padding:0;		
	}
	div.room h2 a{
		color:#483D8B;
	}
	div.room div.membercount {
		color: #666666;
		font-family: georgia;
		font-size: 13px;
		font-style: italic;
		font-weight: normal;
		margin:0px 0px 4px 0px;
	}
	
	div.room div.mein{
		color: #008000;
	}
	
	div.room p {
		font-size:14px;
		margin:0 0 5px;
	}

";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";


	// $input_statusmsg = html_entity_decode(stripslashes(base64_decode(getStatusMsg())));
	// $statusmsg = format_makeLinks($input_statusmsg);
	// if($statusmsg == ''){}
	// $statusmsg = 'Add status message';
	// <div id="statusBox">
	// 	<div id="msg">
	// 		<span id="textmsg">
			//$statusmsg
	// 		</span>
	// 		<span><button name="edit" value="edit" id="edit" >edit</button></span>
	// 	</div>
	// 	<div id="inputmsg">
	// 		<input type="text" name="status" id="status" value='
	// 		//$input_statusmsg
	// 		' />
	// 		<div id="buttons"><button name="update" id="update" onClick="updateStatusMsg()">Update</button>&nbsp;&nbsp;<button name="cancel" id="cancel">Cancel</button></div>
	// 	</div>
	// </div>
	?>
	<script>
			$(document).ready(function() {
				// $('#edit').click(function() {
				// 	$('#msg').hide();
				//  	$('#inputmsg').show();
				// });
				// 		
				// $('#cancel').click(function() {
				// 	$('#inputmsg').hide();
				//  	$('#msg').show();
				// });
			});
	
	
			var localajaxinit = function(){
				My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
			};
			
			// var updateStatusMsg = function(){
			// 	var msg = _$('status').value;
			// 	CJS_AJAX( 'updateStatusMsg' , {
			// 		msg: msg,
			// 		callback:function(a){
			// 			if(a){
			// 				window.location = 'lobby.php';					
			// 			}else{
			// 				My_JsLibrary.showErrMsg();
			// 			}
			// 		}
			// 	});
			// };
			
			var show_newRoom_form = function(){
				$('#div_createNewRoom_container').showWithBg();
				_$('newRoom_title').focus();
			};
			
			var create_newRoom = function(){
				var nr_title = _$('newRoom_title').value ;
				var nr_description = _$('newRoom_Description').value ;
				var selectedUsers = My_JsLibrary.getValuesOfSelectedCheckboxes_ofClass( 'newRoom_users' );
				
				CJS_AJAX( 'createRoom' , {
					room_name: nr_title,
					room_desc: nr_description,
					room_users: selectedUsers.join('||'),
					callback:function(a){
						if(a){
							My_JsLibrary.windowReload();
						}else{
							My_JsLibrary.showErrMsg();
						}
					}
				});
			};
			
			
			
			
			
	</script>

	<div style='float:left;'>
		
		
		<table cellpadding=5 cellspacing=5 width="600" style='margin-top:30px;' align='center' border=0>
			<tbody>
			<TR><TD>
			<?php

			$MU = new ManageUsers();
			$MCR = new ManageChatRooms();
			$MU->userId = $CURRENT_USERID ;
			$MY_ROOMS = $MU->getUser_AllowedChatRooms();
			
			if(!count($MY_ROOMS)){
				echo "You dont have access to any rooms.";
			}else{
				foreach($MY_ROOMS as $THIS_ROOM){

					$ACTIVE_MEMBER_COUNT = count( $MCR->get_ListOfActiveUsersIn_ChatRoom($THIS_ROOM) );

					if($MU->isUserCurrently_InRoom($THIS_ROOM)){
						$OTHERS = $ACTIVE_MEMBER_COUNT - 1 ;
						$me_in_class = "mein";
						if($OTHERS > 0){
							$members_string = "You and {$OTHERS} others";
						}else{
							$members_string = "Only You";
						}
					}else{
						$OTHERS = $ACTIVE_MEMBER_COUNT ;
						$me_in_class = "menotin";
						if($OTHERS > 0){
							$members_string = "{$OTHERS} users in chat room";
						}else{
							$members_string = "empty";
						}
					}
					echo "
						<div class='room'>
							<h2><a href='chatroom.php?rid={$THIS_ROOM}'>".$MCR->get_roomTitle($THIS_ROOM)."</a></h2>
							<div class='membercount {$me_in_class}'>{$members_string}</div>
							<p>".$MCR->get_roomDescription($THIS_ROOM)."</p>
						</div>
					";
				}

			}

			?>
			</TD></TR>
			<TR><TD align='center'>
				<?php
				// links to create a room
				// Admin or the users with privilege to create a room
				if( $AM_I_ADMIN || $GMU->has_Privilege('Can Create New Rooms')){ 
					echo "<A href='#' onclick='show_newRoom_form()'>Create New Room</A>" ;
				}
				?>
				</TD>
			</TR>
			</tbody>
		</TABLE>

	</div>
	<div style='float : right; margin-right: 30px;'>
		<div style='margin-top: 20px;'><A href='directory.php'>User Directory</A></div>
		<div style='margin-top: 20px; border-bottom: 1px solid #999;'> &nbsp;List of Recent Files&nbsp;</div>
		
		<?php
		$MF = new ManageFiles();
		$LIST_OF_RECENT_FILES = $MF->get_Last_XFiles_RelatedToUser($CURRENT_USERID,5);
		
		foreach($LIST_OF_RECENT_FILES as $this_fileId){
			$THIS_FILE_INFO = $MF->get_file_Info($this_fileId, $CURRENT_USERID);
			echo "<div style='padding: 4px;'><a href=\"chatfiledownload.php?fc={$this_fileId}\">{$THIS_FILE_INFO['fileName']}</a></div>";
		}
		?>
		
	</div>
	
	<div style='clear:both;'></div>


	<div id="div_createNewRoom_container" style="display:none; width: 740" class="divAbovedivAboveBg">
		<TABLE width="740" cellpadding=0 cellspacing=0 border=0  class="divHeadingTable">
		<TR>
			<TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title2">Create New Room</TD>
			<TD onclick="My_JsLibrary.hideDrag(event);" width="19" bgcolor="#C49762"><img src="images/close.gif" border=0></TD>
		</TR>
		</TABLE>
		<TABLE width="738" cellpadding="4" cellspacing=4 border=0>
			<TR><TD align='right'>Room Title : </TD>
				<TD><input type='text' size='57' id='newRoom_title'></TD>
			</TR>
			<TR><TD align='right' valign='top' align='right'><NOBR>Room Description : </NOBR></TD>
				<TD><textarea cols=50 rows=3 id='newRoom_Description'></textarea></TD>
			</TR>
			<TR><TD align='right' valign='top' align='right'>Users : </TD>
				<TD>
					<div>
						<?php
						$ALLUSERS = $GMU->getAllUserIdsInDomain();
						foreach( $ALLUSERS as $this_uId ){
							echo "<span ><label><NOBR><input type='checkbox' class='newRoom_users' value='{$this_uId}'>&nbsp;".ucfirst(USERID_TO_USERNAME($this_uId))."</NOBR></label></span>&nbsp;<WBR>";
						}
						?>
					</div>
				</TD>
			</TR>
			<TR>
				<TD align=center valign='middle' colspan=2>
					<input type='button' value="Cancel" onclick="$('#div_createNewRoom_container').hideWithBg();">
					<input type='button' value="Create" onclick="create_newRoom();">
				</TD>
			</TR>
		</TABLE>
	</div>

<?php

include_once "include_footer.php";

?>
