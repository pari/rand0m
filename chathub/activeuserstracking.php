<script>
	$(document).ready(function() {
	
		KeyBoard_Mouse_TRACKER = setTimeout(Logout_Action,30000*60);
		<?php
		if( !in_array($CURRENT_SCRIPTNAME , array('chatroom.php') ) ){ ?>
		Last_ping_Tracker = setTimeout(Register_Last_Ping,5000*60);
		<?php } ?>
		$(document).keyup(function (event) {
			delayLogout();
		});
		
		$(document).mousemove(function (event) {
			delayLogout();
		});

	});


	var delayLogout = function(){
		clearTimeout(KeyBoard_Mouse_TRACKER);
		KeyBoard_Mouse_TRACKER = setTimeout(Logout_Action,30000*60);
	}

	
	var Logout_Action = function(){
		CJS_AJAX( 'Logout' , {
			callback:function(a){
				if(a){
					window.location = "index.php"; return;
				}else{
					My_JsLibrary.showErrMsg();
				}
			}
		});
	}
	
	var Register_Last_Ping = function(){
	
		CJS_AJAX( 'Register_Last_Ping' , {
			callback:function(a){
				if(a){
					Last_ping_Tracker = setTimeout(Register_Last_Ping,5000*60);
					return;
				}else{
					My_JsLibrary.showErrMsg();
				}
			}
		});
	}
</script>
