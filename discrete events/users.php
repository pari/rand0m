<?php

	include_once "include_db.php";
	include_once "include_functions.php";
	checkUserSessionandCookie();
	include_once "include_header.php";

	$username = $_SESSION["uname"];

	if(!IsSadmin()){
		header("Location: welcome.php");
		exit();
	}

?>
<SCRIPT>
var SELECTEDUSERNAME = '';
var MYUSERS = 
	<?php
	$result = mysql_query("select username, password, user_reportsTo, user_primaryEmail, user_alertEmail, user_phNo, user_mobileNo, user_type, user_status from users ORDER BY username ");
	IF (@mysql_num_rows($result)==0){
		echo "{}";
	}else{
		$MYUSERS = array();
		while ( $row = mysql_fetch_assoc($result) ) { // $row['username'] , $row['password']
			$tmp_arr = array();
			foreach ( $row as $key => $value) {
				$tmp_arr[$key] = base64_encode($value);
			}
			$MYUSERS[$row['username']] = $tmp_arr ;
		}
		echo json_encode( $MYUSERS );
	}
	?>
;


var localajaxinit = function(){
	My_JsLibrary.selectMainTab('users.php');

	ManageUsersJsFunctions.loadUsersTable(); //show users in table

	$('#Table_usersList').click(function (e) {
		if( $(e.target).is('td') ){}else{ return false; }
		var thistd = e.target ;
		if( thistd.className != 'username' ){ return false; }
		SELECTEDUSERNAME = $(thistd).html() ;
			$("#CreateUser_Form_dialogtitle").html( "edit user '" + SELECTEDUSERNAME + "'") ;
			_$('cu_uname').value = SELECTEDUSERNAME ;
			_$('cu_uname').disabled = true;
			_$('cu_passwd').value = Base64.decode( MYUSERS[SELECTEDUSERNAME]['password'] );
			_$('cu_emaild').value = Base64.decode( MYUSERS[SELECTEDUSERNAME]['user_primaryEmail'] );
			_$('cu_alertEmail').value = Base64.decode( MYUSERS[SELECTEDUSERNAME]['user_alertEmail'] );
			_$('cu_phNo').value = Base64.decode( MYUSERS[SELECTEDUSERNAME]['user_phNo'] );
			_$('cu_mobileNo').value = Base64.decode( MYUSERS[SELECTEDUSERNAME]['user_mobileNo']) ;
			My_JsLibrary.selectbox.selectOption(_$('cu_status'), Base64.decode( MYUSERS[SELECTEDUSERNAME]['user_status']) );
			$('#CreateUser_Form').showWithBg() ;
		return false;
	});


}; // End of localajaxinit



var ManageUsersJsFunctions = {
	loadUsersTable : function(){ // ManageUsersJsFunctions.loadUsersTable();
		var tbl = _$('Table_usersList') ;
		while( tbl.rows.length > 1){
			var lastRow = tbl.rows.length -1;
			tbl.deleteRow(lastRow);
		}

		for( var i in MYUSERS ){
			if( !MYUSERS.hasOwnProperty(i) ){ continue; }
			var tr = tbl.insertRow(tbl.rows.length);
			var base64decodeorspace = function(i){ if(!i){return '&nbsp;';} return Base64.decode(i) || '&nbsp;' ; }
			My_JsLibrary.tr_addCell( tr, {html: i , className : 'username', title:'Click to Edit this user' });
			My_JsLibrary.tr_addCell( tr, {html: base64decodeorspace(MYUSERS[i]['password']) });
			My_JsLibrary.tr_addCell( tr, {html: base64decodeorspace(MYUSERS[i]['user_primaryEmail']) });
			My_JsLibrary.tr_addCell( tr, {html: base64decodeorspace(MYUSERS[i]['user_alertEmail']) });
			My_JsLibrary.tr_addCell( tr, {html: base64decodeorspace(MYUSERS[i]['user_phNo']) });
			My_JsLibrary.tr_addCell( tr, {html: base64decodeorspace(MYUSERS[i]['user_mobileNo']) });
		}
	},

	showCreateUserForm : function(){ // ManageUsersJsFunctions.showCreateUserForm()
		$("#CreateUser_Form_dialogtitle").html("Create New User");
		SELECTEDUSERNAME = '' ;
		My_JsLibrary.resetTheseFields(['cu_uname' , 'cu_passwd', 'cu_emaild', 'cu_alertEmail' , 'cu_phNo' , 'cu_mobileNo']); _$('cu_status').selectedIndex = 0 ;
		$('#CreateUser_Form').showWithBg();
	},


	createUser_submit : function(){ // ManageUsersJsFunctions.createUser_submit()
		var username = My_JsLibrary.getFieldValue('cu_uname');
		var password = My_JsLibrary.getFieldValue('cu_passwd');
		var emailid = My_JsLibrary.getFieldValue('cu_emaild');
		var alertEmail = My_JsLibrary.getFieldValue('cu_alertEmail');
		var phoneNo = My_JsLibrary.getFieldValue('cu_phNo');
		var mobileNo = My_JsLibrary.getFieldValue('cu_mobileNo');
		var userstatus = My_JsLibrary.getFieldValue('cu_status');

		var thisformaction = (SELECTEDUSERNAME) ? 'updateUser' : 'createUser' ;
		var successResponsemsg = (SELECTEDUSERNAME) ? 'updated user' : 'New user ' + username + 'created' ;

		DE_USER_action( thisformaction ,
		{
			username: username,
			password: password,
			emailid: emailid,
			alertEmail : alertEmail,
			phoneNo: phoneNo,
			mobileNo: mobileNo,
			userstatus: userstatus,

			callback:function(a){
				if(a){
					$('#CreateUser_Form').hideWithBg();
					MYUSERS[username] = {};
					MYUSERS[username]['username'] = Base64.encode(username);
					MYUSERS[username]['password'] = Base64.encode(password);
					MYUSERS[username]['user_primaryEmail'] = Base64.encode(emailid);
					MYUSERS[username]['user_alertEmail'] = Base64.encode(alertEmail);
					MYUSERS[username]['user_phNo'] = Base64.encode(phoneNo);
					MYUSERS[username]['user_mobileNo'] = Base64.encode(mobileNo);
					MYUSERS[username]['user_status'] = Base64.encode(userstatus);
					My_JsLibrary.showfbmsg( successResponsemsg , 'blue' );
					ManageUsersJsFunctions.loadUsersTable();
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	}
};

</SCRIPT>

<div style='clear:both;'></div>

<table id='Table_usersList' align=center class="manageUsers" cellpadding=0 cellspacing=0 width='90%'>
	<tr>
		<td class="firstRow" width='160'>
				<span>Username</span>
				<span class="bluebuttonSmall" onclick="ManageUsersJsFunctions.showCreateUserForm()" style='margin-left:5px;'>New</span>
		</td>
		<td class="firstRow passwordcol" width='110'>Password</td>
		<td class="firstRow">Email</td>
		<td class="firstRow">AlertEmail</td>
		<td class="firstRow">Phone</td>
		<td class="firstRow">Mobile</td>
	</tr>

</table>


<div id="CreateUser_Form" style="display:none; width: 550" class="divAboveBg">
	<TABLE width="100%" cellpadding=0 cellspacing=2 border=0 class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title" id='CreateUser_Form_dialogtitle'>Create New User</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="545" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="right">UserName :</TD>
			<TD><input type="text" size=16 id="cu_uname" class="hilight"></TD>
		</TR>
		<TR><TD align="right">Password :</TD>
			<TD><input type="text" size=16 id="cu_passwd" class="hilight"></TD>
		</TR>
		<TR><TD align="right">Email Id :</TD>
			<TD><input type="text" size=26 id="cu_emaild" class="hilight"></TD>
		</TR>
		<TR><TD align="right">Alert Email :</TD>
			<TD><input type="text" size=26 id="cu_alertEmail" class="hilight"></TD>
		</TR>
		<TR><TD align="right">Phone Number :</TD>
			<TD><input type="text" size=12 id="cu_phNo" class="hilight"></TD>
		</TR>
		<TR><TD align="right">Mobile Number :</TD>
			<TD><input type="text" size=12 id="cu_mobileNo" class="hilight"></TD>
		</TR>
		<TR><TD align="right">Status :</TD>
			<TD><select id='cu_status'>	<option value='A'>Active</option> <option value='S'>Suspended</option> </select></TD>
		</TR>
		<TR>
			<TD></TD>
			<TD style="padding:10px;"><span class="bluebuttonSmall" onclick="ManageUsersJsFunctions.createUser_submit()">Submit</span></TD>
		</TR>
	</TABLE>
</div>

<?php
	include "include_footer.php";
?>