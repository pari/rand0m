<?php
include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="

	#BOOKMARKSLIST{
		margin-left: auto;
		margin-right: auto;
		margin:2px;
		overflow: auto;
		width: 82%;
		height: 73%;
		background-color: #FFFFFF;
		border: 1px dotted #999;
	}

	.leftbox_msg
	{
		padding: 0px 20px 10px 0px;
		text-align: justify;
		width: 60%;
	}

	.rightbox_msg
	{
		float: right;
		padding: 0px 20px 10px 0px;
		text-align: justify;
		width: 40%;
	}

";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";
?>
<script>
	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
	};
	
	
	$(document).ready(function() {
		$('.markasread').click(function() { 
			var UnMarkedMsgIds = [];
			var dmsgid =  $(this).attr('dmsgid');
			UnMarkedMsgIds.push(dmsgid);
			Mark_As_Read(UnMarkedMsgIds);
		});
		
		$('.markallasread').click(function() {
			var UnMarkedMsgIds = [];
			UnMarkedMsgIds = My_JsLibrary.getAttributeValues_of_ElementsOfClass_WithAttributeValue('markasread', 'msgstatus', 'N' , 'dmsgid' ,'div');
			Mark_As_Read(UnMarkedMsgIds);
		});
	});
		
	var Mark_As_Read = function(UnMarkedMsgIds){
		CJS_AJAX( 'Message_Mark_As_Read' , {
			UMDMSGIDS : UnMarkedMsgIds.join('||') ,
			callback:function(a){
				if(a){
					window.location = 'dchat.php';
					return ;
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};
</script>

	<center>
		<div style='margin-left: auto; margin-right: auto; margin:2px; font-size: 110%; font-weight: bold; padding: 10px; width: 82%;'>List of Recent Direct Messages</div>
		<div id='BOOKMARKSLIST'>
		<div id='markall' class="markallasread" style='cursor: pointer; float:right; padding:5px 10px 0px 0px;' >Mark all as read</div>
			<?php
				$DM = new DirectMessages();
				$LASTX_DirectMessages_SQL = $DM->get_AllUnread_Plus_Xread_DirectMessages ( $CURRENT_USERID , 20 );
				$result = mysql_query( $LASTX_DirectMessages_SQL ) ;
				
				while ($row = mysql_fetch_array($result)) {
					// dmsgid, from_uid, to_uid, msg_base64, msgtime, msgType, fileId, msgStatus (N/R)
					if($row['msgStatus'] == 'N'  && $row['to_uid'] == $_SESSION['empl_id']) {
						$bgcolor = '#73C171';
						$textcolor = '#FFFFFF';
					}else{
						$bgcolor = '#F1F4E3';
						$textcolor = '#000000';
					}
					$tmp_preview_str =  "<div style='margin-top: 10px; padding: 10px; color: {$textcolor}; background-color: {$bgcolor}; border-bottom: 2px solid #E7E7E7; text-align:left; display:table; width:96%;'>";
					if($row['to_uid'] == $_SESSION['empl_id']){
						$msg_class = "leftbox_msg";
						$user_float = "float:left;";
						$mark_float = "float:right;";
						$from_to_name = USERID_TO_USERNAME($row['from_uid']);
						$user_image = USERID_TO_USERPIC($row['from_uid']);
					}else{
						$msg_class = "rightbox_msg";
						$user_float = "float:right;";
						$mark_float = "float:left;";
						$from_to_name = "To ".USERID_TO_USERNAME($row['to_uid']);
						$user_image = USERID_TO_USERPIC($row['to_uid']);
					}
					
					$tmp_preview_str .=  "<div style='{$user_float}'>{$from_to_name}:<div><img width='60' height='60' src='files/users/thumbs/{$user_image}'><div>".$row['msgtime']."</div></div></div>";
					
					$file_str = '';
					if($row['msgType'] == 'F'){
						$MF = new ManageFiles();
						$THIS_FILEINFO = $MF->get_file_Info($row['fileId'],$CURRENT_USERID);
						$file_str .= "<div>File Name: <a href='chatfiledownload.php?fc={$THIS_FILEINFO['fileId']}'>{$THIS_FILEINFO['fileName']}</a>";
						if( in_array($THIS_FILEINFO['fileExt'] , array('jpg', 'jpeg', 'gif', 'png', 'bmp')) ){
							$file_str .= "<br/><a href='chatfiledownload.php?fc={$THIS_FILEINFO['fileId']}'><img src=files/chat_files/thumbs/{$THIS_FILEINFO['fileRandomName']}></a>";
						}
						$file_str .= "</div>";
					}
					
					$tmp_preview_str .=  "<div class='{$msg_class}'>&nbsp;".stripslashes(base64_decode($row['msg_base64']))."{$file_str}</div>";
					
					if($row['msgStatus'] == 'N' && $row['to_uid'] == $_SESSION['empl_id']){
						$tmp_preview_str .= "<div id='mark' dmsgid='{$row['dmsgid']}' class='markasread' msgstatus='{$row['msgStatus']}' style='cursor: pointer; {$mark_float}' >Mark as read</div>" ;
					}
					
					$tmp_preview_str .= "</div>" ;
					echo $tmp_preview_str;
				}
				
				
			?>
		</div>
	</center>

<?php
include_once "include_footer.php";
?>
