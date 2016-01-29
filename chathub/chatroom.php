<?php
include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }
$CUSTOM_STYLES = "
	.pp_nav{ display:none; }
	#div_activeUsers{ width: 15%; }
	div.usr_actv {
		margin-top: 3px;
		background-image: url('images/uac.png');
		background-repeat: no-repeat;
		background-position: 0px 3px;
		padding-left : 14px;
		color : #A1AD86 ;
	}
	div.usr_inactv {
		margin-top: 3px;
		background-image: url('images/uinac.png');
		background-repeat: no-repeat;
		background-position: 0px 3px;
		padding-left : 14px;
		color : #A1AD86 ;
	}
	#div_messageLog{
		margin-left: auto;
		margin-right: auto;
		margin:2px;
		overflow: auto;
		width: 82%;
		height: 65%;
		background-color: #FFFFFF;
		border: 1px dotted #999;
	}
	#tbl_messageLog{
		font-size: 90%;
		width: 98%;
	}
	#tbl_messageLog TR TD.msgtime{
		padding : 2px;
		margin: 2px;
		font-weight: bold;
		color: #B7B7B7;
	}
	#tbl_messageLog TR TD.un{
		font-weight: bold;
		font-size: 110%;
	}
	#tbl_messageLog TR TD.umsg{
		padding: 5px;
		margin: 3px;
		/*background-color: #FFF6A9;*/
		/*border: 1px solid #D6D6D6;*/
	}
	#f1_upload_process{
		z-index:100;
		visibility:hidden;
		position:absolute;
		text-align:center;
		width:400px;
	}
	.bmarkstar{
		cursor:pointer;
	}
	TR.cbs td.std{ width: 20px; max-width: 20px;}
	TR.cbs td.std img.bmarkstar{ visibility: hidden; }
	TR.cbs:hover td.std img.bmarkstar{ visibility: visible; }
	TR.cbs td.std img.albk{ visibility: visible; }
	TR.cbs td.std img.nalbk{ visibility: hidden; }
";

include_once "include_functions.php";
include_once "include_header.php";

$rid = get_GET_var('rid');
if(!$rid || ! $GMU->has_AccessToRoom($rid)){
	echo "You do not have access to this chatroom.";
	include_once "include_footer.php";
	exit();
}
$GMU->LogIn_ToRoom($rid);

include_once "include_header_links.php";

?>
<script>
	
	var startUpload = function(){
		document.getElementById('f1_upload_process').style.visibility = 'visible';
		document.getElementById('f1_upload_form').style.visibility = 'hidden';
		return true;
	};

	var stopUpload = function(success){
		var result = '';
		if (success == 1){
		  result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
		}
		else {
		  result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
		}
	      
		document.getElementById('f1_upload_process').style.visibility = 'hidden';
		document.getElementById('f1_upload_form').innerHTML = '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
		document.getElementById('f1_upload_form').style.visibility = 'visible';
		  
		$("#file_upload_result").html(result);
		$("#file_upload_result").show();
		$("#file_uploads").hide();
		setTimeout("$('#file_upload_result').fadeOut('slow')",5000);
		return true;   
	};

	var isWindowActive = true;
	var RANDOM_COLORS = [ '#E2F6FC' , '#FFFADB' ]; //'#E2F6FC' , '#FFFADB' , '#F7ECFC' , '#F4FFAD' , 'EFEFEF' , '#F6E4DC'
	var document_title = "Cigniti Chat :: <?php echo ucwords(strtolower($CURRENT_USER)); ?>" ;
	var CHAT_USERS = {};
	var LASTMSG_10_MIN_INTERVAL = '0000-00-00 00:0' ;
	var MY_NAME = '<?php echo $CURRENT_USER ;?>';

	var lastmsg_user = '';
	var umsg_bgclr = '';
	<?php
	// Load last 100 messages into an array NEW_MESSAGES
	// and get the LASTFETCHEDMSGID
	$TMC = new ManageChatRooms();
	$NMFR = $TMC->get_NewMessages_fromRoom( array( 'condition'=>'lastXMessages', 'value'=>1500) , $rid , $CURRENT_USERID );
	
	echo " NEW_MESSAGES = ".json_encode( $NMFR['NEW_MESSAGES'] ). " ; " ;
	echo " LASTFETCHEDMSGID = {$NMFR['LASTFETCHEDMSGID']} ;"  ;
	?>

	var append_NEW_MESSAGES = function(){
		var jq_div_ml = $("#tbl_messageLog") ;
		$("#tbl_messageLog TBODY TR.tmpmsg").remove();

		var append_message = function( nmsg ){
		
			// nmsg = { msgBy : '' , msgTime : '' , msg_base64 : '' }
			// show time if first 15 characters (same 10 minute interval) of '2010-10-09 04:31:42' is different than LASTMSG_TIME 
			var thisMsg_10minuteInterval = nmsg.msgTime.substring(0, 15) ;
			if( thisMsg_10minuteInterval != LASTMSG_10_MIN_INTERVAL ){
				var timeStampDiv = "<TR><TD></TD><TD class='msgtime' colspan=2>" + nmsg.msgTime.timestamp_to_Date() + "</TD></TR>";
				jq_div_ml.append( timeStampDiv );
				LASTMSG_10_MIN_INTERVAL = thisMsg_10minuteInterval ;
			}
			
			if(!lastmsg_user){
				lastmsg_user = nmsg.msgBy ;
				umsg_bgclr = RANDOM_COLORS[1];
				var uname_str =  nmsg.msgBy.capitalizeFirstChar() ;
			}else{
				if( nmsg.msgBy == lastmsg_user){
					var uname_str =  '' ;
				}else{
					umsg_bgclr = (umsg_bgclr == RANDOM_COLORS[1]) ? RANDOM_COLORS[0] : RANDOM_COLORS[1] ;
					var uname_str =  nmsg.msgBy.capitalizeFirstChar() ;
				}
			}
			
			if(nmsg.msgid == '0'){
				if(nmsg.msgType == 'L' || nmsg.msgType == 'E'){
					lastmsg_user = '';
					newMessageDiv = "<TR style='background-color: #F2F3E7;'><TD class='msgtime' colspan=3 style='color: #999;font-weight:normal; padding: 4px; border-bottom: 1px solid #C9C89E; '>&nbsp;&nbsp;" + uname_str + ' ' + Base64.decode(nmsg.msg_base64).replaceURLWithHTMLLinks() + "</TD></TR>";
				}else{
					newMessageDiv ="<tr style='background-color:"+ umsg_bgclr +";'><td class='un' align='right' valign='top'>"+ uname_str +" </td> <td class='umsg' colspan=2>"+ Base64.decode(nmsg.msg_base64).replaceURLWithHTMLLinks() +"</td></tr>" ;
					lastmsg_user = nmsg.msgBy ;
				}
			}else{
				// general chat messages, can be bookmarked or undo bookmarked
				if( Number(nmsg.bookmark)){
					//already bookmarked
					var td_star = "<img src='images/bookmark.png' class='bmarkstar albk' msgid='"+nmsg.msgid+"' id='"+nmsg.msgid+"'>";
				}else{
					// not already bookmarked
					var td_star = "<img src='images/nobookmark.png' class='bmarkstar nalbk' msgid='"+nmsg.msgid+"' id='"+nmsg.msgid+"'>";
				}
				newMessageDiv ="<tr style='background-color:"+ umsg_bgclr +";' class='cbs'><td class='un' align='right' valign='top'>"+ uname_str +" </td><td class='umsg'>"+ Base64.decode(nmsg.msg_base64).replaceURLWithHTMLLinks() + "</td><TD valign='top' class='std'>" + td_star + "</TD></tr>";
				lastmsg_user = nmsg.msgBy ;
			}
			
			jq_div_ml.append(newMessageDiv);
		};
		
		for(var t = 0 ; t < NEW_MESSAGES.length ; t++){
			append_message(NEW_MESSAGES[t]);
		}
		if(t){
			scroll_chat_bottom();
			// if window is not active .. start blinking
			if(!isWindowActive){ alert_About_newMessages(); }
		}
		
		intialize_prettyPhoto_for_latest_links();

	};
	
	
	var fetch_messages_NewerThan = function(){
		CJS_AJAX( 'postmessage' , {
			newmsg : '' ,
			LMSGID : LASTFETCHEDMSGID ,
			ROOMID : '<?php echo $rid?>',
			callback:function(a){
				if(a){
					eval(My_JsLibrary.responsemsg);
					append_NEW_MESSAGES();
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};
	
	/*var refresh_activeUsers = function(){
		var auldiv = _$('div_activeUsers') ;
		var aul = ACTIVE_USERS.length ;
		$(auldiv).empty();
		for(var i=0; i < aul ; i++){
			$(auldiv).append("<div class='usr_actv'>" + ACTIVE_USERS[i].capitalizeFirstChar() + "</div>" );
		}
	};
	
	var get_activeUsers = function(){
		CJS_AJAX( 'fetch_activeUsers' , {
			activefor : '0',
			ROOMID : '<?php //echo $rid?>',
			callback:function(a){
				if(a){
					eval(My_JsLibrary.responsemsg);
					refresh_activeUsers();
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};*/


	var refresh_allUsers = function(){
		$('#div_activeUsers').empty();
		$('#div_inactiveUsers').empty();
		var append_user = function ( user )
		{	
			// user = { userid : '', username : '', username : '', status : '' }
			var userName = user.username.capitalizeFirstChar();
			var userStatus = user.status;
			
			if(userStatus == 'active'){
				var status_class = 'usr_actv';
			}
			else {
				var status_class = 'usr_inactv';
			}
			
			var display = "<div class='"+status_class+" umsg'><a rel='prettyPhoto[iframes]' href='dmsg.php?id="+ Base64.encode(user.userid) +"&iframe=true&width=536&height=245'>"+ userName +"</a></div>";
			
			if(userStatus == 'active'){
				$('#div_activeUsers').append(display);
			}
			else
			{
				$('#div_inactiveUsers').append(display);
			}

		};

		for(var t = 0 ; t < ALL_USERS.length ; t++){
			append_user(ALL_USERS[t]);
		}
		
		intialize_prettyPhoto_for_latest_links();
	};
	

	var get_Users_InRoom = function(){
		CJS_AJAX( 'fetch_Users_InRoom' , {
			activefor : '0',
			ROOMID : '<?php echo $rid?>',
			callback:function(a){
				if(a){
					eval(My_JsLibrary.responsemsg);
					refresh_allUsers();
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};
	
	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName()."?rid={$rid}"; ?>', true);
		
		$($.date_input.initialize);
		
		// for each NEW_MESSAGES -> append_message( this_msg ) ;
		append_NEW_MESSAGES();
		// setInterval that fetches new messages 
		get_Users_InRoom(<?php echo $rid;?>);
		POLLNMSGS_ID = setInterval(fetch_messages_NewerThan , <?php echo CHAT_REFRESH_INTERVAL ;?> );
		ACTIVEUSERS_SIID = setInterval(get_Users_InRoom , <?php echo CHAT_REFRESH_INTERVAL ;?> * 3 );
		_$('text_new_message').focus();
		$(window).focus(function(){ 
			isWindowActive = true; 
			clear_NewMessages_alert();
		});
		$(window).blur(function(){ isWindowActive = false; }); 
		//$(document).blur/focus also works
		$(".bmarkstar").live("click", function() {
			var clickedImg = this;
			var msgid = $(this).attr('msgid');
			CJS_AJAX( 'updateChatMessageBookMark' , {
				msgid: msgid,
				roomId: '<?php echo $rid; ?>',
				callback:function(a){
					if(a){
						var response = My_JsLibrary.responsemsg;
						if(response.contains('added bookmark')){
							$(clickedImg).attr('src', 'images/bookmark.png');
							$(clickedImg).removeClass();
							$(clickedImg).addClass('bmarkstar albk');
							return;
						}
						
						
						if(response.contains('deleted bookmark')){
							$(clickedImg).attr('src', 'images/nobookmark.png');
							$(clickedImg).removeClass();
							$(clickedImg).addClass('bmarkstar nalbk');
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
	}; // end of localajaxinit()

	var alert_About_newMessages = function(){
		var flag_alert = true;
		var flip_title = function(){
			document.title = (flag_alert)? document_title : "New Messages ...";
			flag_alert = !flag_alert ;
		};
		FLAG_TITLEID = setInterval( flip_title , 500 );
	};
	
	var scroll_chat_bottom = function(){
		var jq_div_ml = $("#div_messageLog") ;
		jq_div_ml.attr({ scrollTop: jq_div_ml.attr("scrollHeight") });
	};
	
	var clear_NewMessages_alert = function(){
		if(FLAG_TITLEID){
			clearInterval( FLAG_TITLEID );
		}
		document.title = document_title ;
		scroll_chat_bottom();
	};

	var send_onEnter = function(e){
		if(e.keyCode != 13){ return true; }
		newmsg = _$('text_new_message').value;
		newmsg = newmsg.trim();
		if(newmsg){
			append_temporary_message(newmsg);
			post_new_message(newmsg);
		}
		_$('text_new_message').value = '';
		_$('text_new_message').focus();
		return false;
	};

	var append_temporary_message = function(msg){
		var jq_div_ml = $("#tbl_messageLog") ;
		var td_star = "";
		newMessageDiv ="<tr style='background-color:"+ umsg_bgclr +";' class='tmpmsg'><td class='un' align='right'>"+ MY_NAME +" </td><td class='umsg'>"+ msg.replaceURLWithHTMLLinks() + "</td><TD valign='top' class='std'>" + td_star + "</TD></tr>";
		jq_div_ml.append(newMessageDiv);
	};

	var post_new_message = function(newmsg){
		// POST new message into database
		// append formatted message to div_messageLog
		clearInterval(POLLNMSGS_ID);
		CJS_AJAX( 'postmessage' , {
			newmsg : Base64.encode(newmsg),
			LMSGID : LASTFETCHEDMSGID ,
			ROOMID : '<?php echo $rid?>',
			callback:function(a){
				if(a){
					eval(My_JsLibrary.responsemsg);
					append_NEW_MESSAGES();
					//clearInterval(POLLNMSGS_ID);
					POLLNMSGS_ID = setInterval( fetch_messages_NewerThan , <?php echo CHAT_REFRESH_INTERVAL ;?> );
				}else{
					//clearInterval(POLLNMSGS_ID);
					POLLNMSGS_ID = setInterval( fetch_messages_NewerThan , <?php echo CHAT_REFRESH_INTERVAL ;?> );
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};

	var LeaveRoom = function(){
		var rid = '<?php echo $rid; ?>';
		var uid = '<?php echo $CURRENT_USERID ;?>';
		CJS_AJAX( 'LeaveRoom' , {
			rid: rid ,
			uid: uid ,
			callback:function(a){
				if(a){
					window.location = "lobby.php"; return;
				}else{
					My_JsLibrary.showErrMsg();
				}
			}
		});
	};
	
	var fetchArchives = function(){
		// Stop POSTing new message into database
		// Clear existing messages
		// append formatted archive messages to div_messageLog
		clearInterval(POLLNMSGS_ID);
		var date = $('#archives_date').val();
		$('#tbl_messageLog').empty();
		$('#archive_result').text('Archives for '+date);
		$('#archive_result').show();
		CJS_AJAX( 'fetchArchives' , {
			DATE : date ,
			ROOMID : '<?php echo $rid?>',
			callback:function(a){
				if(a){
					eval(My_JsLibrary.responsemsg);
					append_NEW_MESSAGES();
				}
			}
		});
	};

	var intialize_prettyPhoto_for_latest_links =  function(){
		$(".umsg:first a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'slow',slideshow:2000, autoplay_slideshow: false});
		$(".umsg:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'fast',slideshow:10000});

		$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
			custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
			changepicturecallback: function(){ initialize(); }
		});

		$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
			custom_markup: '<div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
			changepicturecallback: function(){ _bsap.exec(); }
		});
	};

</script>
	<p id="file_upload_result" style="display:none; padding:10px 0px 0px 230px; color:red; font-size:14px;"></p>
	<p id="archive_result" style="display:none; padding:10px 0px 0px 230px; color:red; font-size:14px;"></p>
	<div id="file_uploads" style='display:none;'>
		 <form action="chatfileupload.php" method="post" enctype="multipart/form-data" target="upload_target" onSubmit="startUpload();" >
		 <input type="hidden" name="rid" value="<?php echo $rid; ?>" id="rid" />
		 <p id="f1_upload_process">Loading...<br/><img src="images/loader.gif" /><br/></p>
                     <p id="f1_upload_form" align="center"><br/>
                         <label>File:  
                              <input name="myfile" type="file" size="30" />
                         </label>
                         <label>
                             <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
                         </label>
                     </p>
         <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
		 </form>
	</div>
	
	<div style='clear:both; float:left;' id='div_messageLog'>
		<table border="0" cellspacing="0" cellpadding="0" id='tbl_messageLog'>
			<colgroup>
				<col width='100' style='max-width:100px;' />
				<col />
			</colgroup>
		</table>
	</div>
	<div style='float:right; margin-left: 10px;'>
		<div style='padding: 5px;'><a href="#" onClick="LeaveRoom()">leave this Room</a></div>
		<div style='padding: 5px;'><a href="#" onClick=" $('#file_upload_result').hide(); $('#file_uploads').toggle();">Upload a File</a></div>
		<div style='padding: 5px;'><a href="#" onClick="$('#archives').toggle();">Archives</a></div>
		<div id="archives" style='display:none;'>
		<input type='text' class='date_input' size=12 id='archives_date'><input type="button" id="Go" name="Go" value="Go" onclick="fetchArchives()" />
		</div>
		
		<div style='margin-top: 20px; margin-left:10px; border-top: 2px dotted #999; padding-top: 5px;  border-bottom: 2px dotted #999; padding-bottom: 3px;'><I>&nbsp;&nbsp;Active Users</I></div>
		<div id='div_activeUsers'></div>
		<div id='div_inactiveUsers'></div>
	</div>

<table border="0" cellspacing="5" cellpadding="5">
	<tr>
		<td valign='top'>You :</td>
		<td>
			<textarea id="text_new_message" onKeyUp="send_onEnter(event)" rows="3" cols="60"></textarea>
		</td>
	</tr>
</table>

<?php
some_prettyPicture_JsCrap();
?>
<?php
include_once "include_footer.php";
?>
