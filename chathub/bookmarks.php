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

	td.std {
	    max-width: 20px;
	    width: 20px;
	}
";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";
?>
<script>
	// $(document).ready(function() {});
	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
		
		$("IMG.bmarkstar").live("click", function() {
			var clickedImg = this;
			var msgid = $(this).attr('msgid');
			var rid = $(this).attr('rid');
			CJS_AJAX( 'updateChatMessageBookMark' , {
				msgid: msgid,
				roomId: rid,
				callback:function(a){
					if(a){
						var response = My_JsLibrary.responsemsg;
						if(response.contains('deleted bookmark')){
							$(clickedImg).fadeOut('slow');
							var sid = "#"+msgid;
							$(sid).remove();
							//window.location = "bookmarks.php";
							return;	
						}
						
						if(response.contains('user does not have access to room')){
							My_JsLibrary.showErrMsg();
						}
						
					}else{
						My_JsLibrary.showErrMsg();
					}
				}
			});
			return false; 
		});
		
	};
</script>

	<center>
		<div style='margin-left: auto; margin-right: auto; margin:2px; font-size: 110%; font-weight: bold; padding: 10px; width: 82%;'>List of Recent BookMarks</div>
		<div id='BOOKMARKSLIST'>
			<?php
				$MB = new ManageBookMarks();
				$LASTX_BOOKMARKS_SQL = $MB->get_LastX_BookMarks_query( $CURRENT_USERID , 200 );
				$result = mysql_query($LASTX_BOOKMARKS_SQL) ;

				$MCR = new ManageChatRooms();

				while ($row = mysql_fetch_array($result)) {
					
					$tmp_preview_str = "<div style='margin-top: 10px; padding: 10px; background-color: #F1F4E3; border-bottom: 2px solid #E7E7E7; text-align:left; display: table; width: 96%;' id='{$row['bkm_msgId']}'>";
					
					if($row['msgType'] == 'F'){
						$TMP_MF = new ManageFiles();
						$d_fileId = ($row['fileId']);
						$fileInfo = $TMP_MF->get_file_Info($d_fileId , $CURRENT_USERID );
						
						$tmp_preview_str .= "<div class='umsg' style='float:left; line-height:150%;'>".USERID_TO_USERNAME($row['saidBy_empl_id'])." has uploaded a file <a href='chatfiledownload.php?fc={$fileInfo['fileId']}'>{$fileInfo['fileName']}</a>  <span style='color: #A9A9A9;'>".formatBytesToHumanReadable($fileInfo['fileSize'])."</span>&nbsp;&nbsp;&nbsp;<a rel='prettyPhoto[iframes]' href='filemail.php?fid={$fileInfo['fileId']}&iframe=true&width=800&height=400'>Email</a>";
						
						if( in_array($fileInfo['fileExt'] , array('jpg', 'jpeg', 'gif', 'png', 'bmp')) ){
							$tmp_preview_str .= "<br/><img src=files/chat_files/thumbs/{$fileInfo['fileRandomName']}>";
						}
						
						$tmp_preview_str .= "</div>";
					}else{
						// bkm_id, bkms.bkm_msgId, bkms.bkm_dmsgid, bkms.bkm_roomId, cRoom.message_base64 
						$tmp_preview_str .= "<div style='float:left; line-height:150%;'>".USERID_TO_USERNAME($row['saidBy_empl_id']).": ".base64_decode($row['message_base64'])."</div>";
					}
					
					$tmp_preview_str .= "<div style='float:right;'><img src='images/bookmark.png' class='bmarkstar' msgid='{$row['bkm_msgId']}'  rid='{$row['bkm_roomId']}' ></div>";
					
					$tmp_preview_str .= "<div style='float:right; color: #C7AD8B; margin-right: 10px;'> ".$MCR->get_roomTitle($row['bkm_roomId'])."</div>";
					
					$tmp_preview_str .= "</div>";
 					
					echo $tmp_preview_str ;
					
					
				}
			?>
		</div>
	</center>
<?php
some_prettyPicture_JsCrap();
?>

<?php
include_once "include_footer.php";
?>
