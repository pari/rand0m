<?php
include_once "include_db.php";
include_once "include_functions.php";

if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

?>
<script>
	var localajaxinit = function(){
		
		
	};	
</script>
	

<?php
$TMP_MU = new ManageUsers();
$ALLUSERS = $TMP_MU->getAllUserIdsInDomain();

if(get_GET_var('id'))
{
	$id = get_GET_var('id');
	$to_user_Id = base64_decode($id);	
}
else
{
	echo "Invalid User.";
	exit();
}

$res = get_GET_var('re');
?>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Direct Message</title>
<meta charset="UTF-8">
<script type="text/javascript" src="js/alljs.php?t=1"></script>
<link rel="stylesheet" href="send.css" type="text/css">
<style>
#content {
margin:28px auto 0;
text-align:center;
width:500px;
}

#f1_upload_process{
z-index:100;
visibility:hidden;
position:absolute;
text-align:center;
width:400px;
}
</style>

<!--[if IE]>
<style type="text/css"> 
h1 {height:50px; width:745px;}
ul#images{ position:absolute; margin-left:-34px; }
ul#images li{margin-left:-55px;}
</style>
<![endif]-->


<script language="javascript" type="text/javascript">
<!--
function startUpload(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function stopUpload(success){
      var result = '';
	  if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
      }
      else {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
      document.getElementById('f1_upload_form').style.visibility = 'visible';      
      return true;   
}
//-->
</script> 
</head>
<body>
<script>
	var send_onEnter = function(e){
		if(e.keyCode != 13){ return true; }
		newmsg = _$('new_message').value;
		newmsg = newmsg.trim();
		to_user = _$('to_user').value;
		if(newmsg){
			post_new_direct_message(newmsg,to_user);
		}
		_$('new_message').value = '';
		_$('new_message').focus();
		return false;
	};

	var post_direct_message = function(){	
		newmsg = _$('new_message').value;
		newmsg = newmsg.trim();
		to_user = _$('to_user').value;
		if(newmsg){
			post_new_direct_message(newmsg,to_user);
		}
		_$('new_message').value = '';
		_$('new_message').focus();
		return false;
	};

	var post_new_direct_message = function(newmsg,to_user){
		// POST new message into database
		// append formatted message to div_messageLog
		CJS_AJAX( 'post_direct_message' , {
			newmsg : Base64.encode(newmsg),
			to_user : to_user,
			callback:function(a){
				if(a){
					//eval(My_JsLibrary.responsemsg);
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};

</script>
<div id="wrapper">
<div id="content">
	<form action="directchatfileupload.php" method="post" enctype="multipart/form-data" target="upload_target" >
		<p id="f1_upload_process">Loading...<br/><img src="loader.gif" /><br/></p>
		<center><?php if($errMsg!=''){ echo $errMsg."<br/>";} if($msg){ echo $msg."<br/>"; } if($invalid_emails!=''){ echo "Invalid Emails: ".$invalid_emails;} ?></center>
		<div id="inner">
			<!-- start errors -->
			<div class="sep"></div>
			<!-- start the your message box -->
			
				<table align="center" cellpadding="4" width="95%">
					<tbody>
					<tr>
						<td valign="top" width="50%">
							<table cellpadding="3" width="100%">
								<tbody>
								<tr>
									<th width="100">To:</th>
									<td>
										<select name="to_user" id="to_user" class="input_box" tabindex="3">
										<option value="">Choose an user</option>
										<?php
										
										foreach( $ALLUSERS as $this_uId ){
										$TMP_MU->userId = $this_uId;
										if($this_uId == $CURRENT_USERID)
										continue;
										?>
										<option value="<?=$this_uId?>" <?php if($this_uId == $to_user_Id){?> selected="selected"<?php } ?>><?php echo $TMP_MU->getUserAttribute('emplFullName');?></option>
										<?php } ?>										
										</select>
									</td>
								</tr>
								<tr>
									<th style="padding-top: 11px;" valign="top">MESSAGE:</th>
								  <td><textarea name="new_message" class="input_box" id="new_message" tabindex="5" onKeyUp="send_onEnter(event)"></textarea>
									Type a message to your friend.</td>
								</tr>
								<p id="f1_upload_form" align="center">
								<tr>
									<th style="padding-top: 11px;" valign="top">UPload:</th>
								 	<td>
									<input type="file" name="myfile" id="myfile"  class="input_box"  /> <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
									</td>
									
								</tr>
								</p>
								 <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
								</tbody>
							</table>
							
						</td>
						
					</tr>
					<!--<tr>
						<td align="right" valign="bottom">
							<input type="button" name="submit" value="" style=" width:140px; height:26px; border:none; background-image:url(button_send_page.jpg)" onClick="post_direct_message()" /> 
						</td>
					</tr>-->
				      </tbody>
				</table>
				
			<!-- end the your message box -->
		</div>
	</form>
</div>
</div>
</body></html>