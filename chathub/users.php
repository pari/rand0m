<?php
// Manage Users and their privileges and access to various rooms

include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="
	#pwdTitleRow{
		font-size: 15px;
		font-weight : bold ;
		color : #718DA1;
	}
	span.bluebuttonSmall , div.bluebuttonSmall {
		background-color: #EBE9E9;
		border: 1px outset #B6C7E5;
		color: #445A80;
		line-height: 1.4em;
		padding: 2px 4px;
		cursor: pointer;
		font-size: 85%;
	}
	table.members {
		border-color:#EEEEEE -moz-use-text-color -moz-use-text-color;
		border-right:medium none;
		border-style:solid none none;
		border-width:1px medium medium;
		font-size:11px;
		margin:0;
	}
	table {
		border-collapse:collapse;
	}
	table.members td.avatar {
		width:55px;
	}
	table.members td {
		border-bottom:1px solid #EFEFEF;
		color:#666666;
		font-size:12px;
		padding:8px;
		vertical-align:top;
	}
	td {
		font-family:'Lucida Grande',verdana,arial,helvetica,sans-serif;
	}
	table.members td.name {
		color:#222222;
		font-size:12px;
		font-weight:bold;
		width:100px;
	}
	table.members td.perms {
		font-size:11px;
		padding-left:10px;
		vertical-align:middle;
		white-space:normal;
		width:50%;
	}
	table.members td.name a {
		color:#666666;
		font-size:11px;
		font-weight:normal;
		text-decoration:none;
	}
	table.members td.name a:hover {
		background-color:#0066CC;
		color:#FFFFFF;
	}
	table.members td.access a {
		color:#666666;
		font-size:11px;
		font-weight:normal;
		text-decoration:underline;
	}
	table.members td.access a:hover {
		background-color:#0066CC;
		color:#FFFFFF;
	}
	table.members td.perms div {
	display:inline;
	line-height:21px;
}
";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";

if( !$AM_I_ADMIN ){
	include_once "include_footer.php";
	exit();
}

?>
<script>
	// $(document).ready(function() {});
	CURRENT_EDITUSER = 0;
	
	var localajaxinit = function(){
		$("A.a_user_cra").live('click', function(){
			CURRENT_EDITUSER = $(this).attr('uid');
			var curuname = Base64.decode($(this).attr('uname'));
			$('#div_manageUser_Rooms_title').html("Edit Rooms for User : " + curuname );
			CJS_AJAX( 'getRoomIdsForUser' , {
				uid: CURRENT_EDITUSER,
				callback:function(a){
					if(a){
						eval(My_JsLibrary.responsemsg); // USER_ROOMS = [] ;
						var ALL_ROOMS_CHK = $("input.room_prev") ;
						
						for(var i=0; i< ALL_ROOMS_CHK.length ; i++){
							var thisroom_value = $(ALL_ROOMS_CHK[i]).val();
							if( USER_ROOMS.contains(thisroom_value) ){
								$(ALL_ROOMS_CHK[i]).attr ('checked', 'checked');
							}else{
								$(ALL_ROOMS_CHK[i]).removeAttr ('checked');
							}
						}
						$("#div_manageUser_Rooms_container").showWithBg();
					}else{
						My_JsLibrary.showErrMsg();
					}
				}
			});
		});
		
		
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
		$(".chk_user_priv").live("change", function() {
			var prv = $(this).val();
			var user = $(this).attr('userid');
			var chk = ($(this).is(':checked')) ? 1 : 0;
			CJS_AJAX( 'updateUserPriv' , {
				prv: prv,
				user: user,
				chk: chk,
				callback:function(a){
					if(a){
						//alert("Privilages Updated.");
						return;
					}else{
						My_JsLibrary.showErrMsg();
					}
				}
			});
		});

		$(".room_prev").live("change", function() {
			var roomid = $(this).val();
			var chk = ($(this).is(':checked')) ? 1 : 0;
			CJS_AJAX( 'updateRoomPriv' , {
				roomid: roomid,
				user: CURRENT_EDITUSER,
				chk: chk,
				callback:function(a){
					if(a){
						 My_JsLibrary.showfbmsg("Room Access Updated !", 'green', 4);
					}else{
						My_JsLibrary.showErrMsg();
					}
				}
			});
		});
	};

</script>


<?php
$MU =  new ManageUsers();
$users = $MU->getAllUserIdsInDomain();
$privilages = $MU->get_All_Privileges_in_App();

?>
<table align=center class="manageLRS" cellpadding=5 cellspacing=5 width="500" style='margin-top:30px;'>
<tbody>
<div id="member_list">
	<table class="members">
	<tbody>
	<?php
	foreach($users as $this_userId ){
		$MU->userId = $this_userId;
		$THIS_USER_PROFILE = $MU->getUserProfile();
		$THIS_USER_PRIVILEGES = $MU->get_Privileges(); ?>
		<tr>
			<td class="avatar">
				<img src='files/users/thumbs/<?php echo USERID_TO_USERPIC($this_userId); ?>'>
			</td>
			<td class="name">
				<?php echo $THIS_USER_PROFILE['emplFullName'];?><br>
				<a href="mailto:<?php echo $THIS_USER_PROFILE['emplEmail_id'];?>"><?php echo $THIS_USER_PROFILE['emplEmail_id'];?></a>
			</td>
			<td class="perms">
				<?php
				foreach($privilages as $this_privilege){
					$checked_str = (in_array($this_privilege,$THIS_USER_PRIVILEGES))? " checked" : '';
					echo "
						<div class='remote_checkbox'>
							<input type='checkbox' class='chk_user_priv' value='{$this_privilege}' userid='{$this_userId}'  name='priv_{$this_userId}' id='priv_{$this_userId}' {$checked_str}>
						</div>
						<strong>".$MU->get_Privilege_Name_By_Id($this_privilege)."</strong><br/>
					";
				}
				?>
			</td>
			<td class="access">
				<A href='#' class='a_user_cra' uid='<?php echo $this_userId; ?>' uname='<?php echo base64_encode($THIS_USER_PROFILE['emplFullName']);?>'>Change rooms access</A>
			</td>
		</tr>
		<?php 
	}
	?>
	</tbody>
	</table>
</div>
</tbody>
</table>





<div id="div_manageUser_Rooms_container" style=" display:none;width: 440" class="divAbovedivAboveBg">
	<TABLE width="440" cellpadding=0 cellspacing=0 border=0  class="divHeadingTable">
	<TR>
		<TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title2" id='div_manageUser_Rooms_title'></TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19" bgcolor="#C49762"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="438" cellpadding="4" cellspacing=4 border=0>
		<?php
		$MCR = new ManageChatRooms();
		$rooms = $MCR->get_ListOfAllRooms();
		foreach($rooms as $this_room){
			echo "<TR>
					<TD align='right' width='50'>
						<input type='checkbox' class='room_prev' value='{$this_room['roomId']}' id='room_{$this_room['roomId']}'>
					</TD>
					<TD>
						<label for='room_{$this_room['roomId']}'>{$this_room['roomName']}</label>
					</TD>
				</TR>
			";
		}
		
		?>
		<TR>
			<TD align=center colspan=2 >
				<input type='button' value="Close" onclick="$('#div_manageUser_Rooms_container').hideWithBg();">
			</TD>
		</TR>
	</TABLE>
</div>




<?php
include_once "include_footer.php";
?>
