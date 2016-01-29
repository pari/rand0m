<?php
include_once "include_db.php";
include_once "include_functions.php";
require_once "phpmailer/class.phpmailer.php";

if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CURRENT_USER = $_SESSION["uname"] ;

if(isset($_POST['submit']))
{
	$errMsg = '';
	$fid = get_POST_var('fid');
	$your_comment = get_POST_var('your_comment');
	$emails = get_POST_var('their_email');
	
	if($fid!='' && $fid!='0')
	{
		$send_MF = new ManageFiles();
		$send_FILEINFO = $send_MF->get_file_Info( $fid , $_SESSION["empl_id"] );
		if(!count($send_FILEINFO)){
		echo "Invalid file or privilege";
		exit();
		}
	
		//$fileInfo = getChatFileInfoById($fid);
	}
	else
	{
		$errMsg .= 'These seems to be some problem with this file.<br />';
	}
	
	if(trim($emails) == '')
	$errMsg .= 'Please enter valid email address(s)<br />';
	
	if($errMsg == '')
	{
		
		
		$valid_email_cnt = 0;
		$invalid_emails = '';
		
		$emails = str_replace(chr(13), "", $emails); //remove carriage returns 
		$emails = str_replace(chr(10), "", $emails); //remove line feeds 
		
		$file_name = UPLOAD_PATH.$send_FILEINFO['fileRandomName'];
		$file_act_name = $send_FILEINFO['fileName'];
			
		$subject = 'Simple Chat File';
		$body = nl2br($your_comment)."<br/><br/><br/>Please find the file attached.";
		
		$send_FileUploadInfo = $send_MF->getChatFileUploadInfo($send_FILEINFO['fileId']);
			
		$msg = sendMailUsingMailer($emails, $subject, $body, $file_name, $file_act_name);
		
		if($msg == 'Letter is sent')
		{
			$newmsg = "has emailed ".$file_act_name."</a> to ".$emails ." [ <a href=\"chatfiledownload.php?fc={$send_FILEINFO['fileId']}\">View File</a> ]";
	  		$success = execute_sqlInsert( 'tbl_ChatRooms', 
						array( 
						'saidBy_username'=>$_SESSION["uname"] ,
						'saidBy_empl_id'=>$_SESSION["empl_id"] ,
						'message_base64' => '',
						'message_plain_mysqlescaped' => $newmsg,
						'chatRoom' => $send_FileUploadInfo['roomId'],
						'msgType' => 'E'
					)
				);
			
		}
		else
		{
		}	
		
		register_LastPingAt();
		
	}	
}


if(get_GET_var('fid'))
{
	$fid = get_GET_var('fid');
	
	$MF = new ManageFiles();
	$FILEINFO = $MF->get_file_Info( $fid , $_SESSION["empl_id"] );
	if(!count($FILEINFO)){
	echo "Invalid file or privilege";
	exit();
	}

	$FileUploadInfo = $MF->getChatFileUploadInfo($FILEINFO['fileId']);
	
}
?>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Send this page to a friend</title>
<link rel="stylesheet" href="send.css" type="text/css">
<!--[if IE]>
<style type="text/css"> 
h1 {height:50px; width:745px;}
ul#images{ position:absolute; margin-left:-34px; }
ul#images li{margin-left:-55px;}
</style>
<![endif]-->
</head>
<body>
<div id="wrapper">
<div id="content">
	<form method="post" name="send">
		<input type="hidden" name="fid" value="<?=$FILEINFO['fileId']?>" />
		<!-- start the main banner --> 
		<h1><img src="images/title_send_to_friend.gif" alt="Send this page to a friend"></h1>
		<!-- end the main banner -->
		<center><?php if($errMsg1=''){ echo $errMsg."<br/>";} if($msg){ echo $msg."<br/>"; } if($invalid_emails!=''){ echo "Invalid Emails: ".$invalid_emails;} ?></center>
		<div id="inner">
			<table id="image_description" align="center" cellpadding="3" width="70%">
				<tbody>
					<tr>
					<td align="center" valign="top" width="230">
					      <div id="image_box">
						      <?php
						      if(in_array($FILEINFO['fileType'],array("image/gif", "image/jpeg", "image/png", "image/pjpeg", "image/bmp", "image/jpg")))
						      { ?>
						      <img src="<?=UPLOAD_PATH?>thumbs/<?=$FILEINFO['fileRandomName']?>" width="150" height="120" >
						      <?php } else { ?>
						      <?php } ?>
					      </div>
					</td>
					<td align="left" valign="top">
						<div id="edit_url">
						<span>File Name: </span><?=$FILEINFO['fileName']?>
						</div>
						
						<div id="edit_url">
						<span>Size: </span><?=formatBytesToHumanReadable($FILEINFO['fileSize'])?>
						</div>
						
						<div id="edit_url">
						<span>Uploaded by: </span><?=$FileUploadInfo['uploadedBy']?>
						</div>
						
						<div id="edit_url">
						<span>Uploaded Date: </span><?php echo caldateTS_to_humanWithTS($FileUploadInfo['uploadedDate']);?>
						</div>
					</td>
					</tr>
				</tbody>
			</table>
			
			
			<div class="sep"></div>
			
			<table align="center" cellpadding="4" width="95%">
				<tbody>
					<tr>
						<td rowspan="2" valign="top" width="50%">
						
							<img src="images/title_message.gif" alt="Your Message">
							<br><br>
							<table cellpadding="3" width="100%">
								<tbody>
								<!--<tr>
									<th width="100">Your Name:</th>
									<td><input name="your_name" class="input_box" tabindex="3" type="text"></td>
								</tr>
								<tr>
									<th>Your Email: <span class="required">*</span></th>
									<td><input name="your_email" id="your_email" class="input_box" tabindex="4" type="text"></td>
								</tr>-->
								<tr>
									<th style="padding-top: 11px;" valign="top">Your MesSAGE:</th>
									<td><textarea name="your_comment" class="input_box" id="your_comment" tabindex="5"></textarea>
									Type a message to your friend above.</td>
								</tr>
								</tbody></table>
							
						</td>
						<td valign="top" width="50%">
						
							<img src="images/title_friend.gif" alt="Your Friends">
							<br><br>
							<table id="friends_link" cellpadding="3" width="100%">
								<tbody class="dynamic_block">
									<tr>
										<th width="100">Email Address(s): <span class="required">*</span></th>
										<td><textarea name="their_email" class="input_box" id="their_email" tabindex="5"></textarea>Add multiple email addresses separated by commas(,)
										<!--<input name="their_email[]" class="friend_email input_box" tabindex="6" type="text">--></td>
										<!--<td width="53">
											<a href="#" onClick="return selrem(this,'friends_link');" class="remove_addit"><img src="button_remove.gif" alt="Remove" border="0"></a>
											<a style="display: inline;" href="#" onClick="return seladd(this,'friends_link');" class="add_addit"><img src="button_add.gif" alt="Add" border="0"></a>
										</td>-->
									</tr>
								</tbody>
							</table>
							
						</td>
					</tr>
					<tr>
						<td align="right" valign="bottom">
							<!--<input name="send_page" value="Send Page!" src="button_send_page.jpg" tabindex="7" type="image">-->
							<input type="submit" name="submit" value="" style=" width:140px; height:26px; border:none;  background-image:url(images/button_send_page.png)" /> 
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="footer"></div>
	</form>
</div>
</div>
</body>
</html>