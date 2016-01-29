<?php
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
	align:center;
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
	}";


include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";
?>
<script>
	var localajaxinit = function(){
		
		
	};	
</script>
	

<?php
$TMP_MU = new ManageUsers();
$ALLUSERS = $TMP_MU->getAllUserIdsInDomain();

$TMP_MCR = new ManageChatRooms();
$ACTIVE_USERS = $TMP_MCR->get_ListOfActiveUsersIn_Application();

?>
<table align=center class="manageLRS" cellpadding=5 cellspacing=5 width="500" style='margin-top:30px;'>
<tbody>

	<table class="members">
	<tbody>
	<?php
	foreach( $ALLUSERS as $this_uId ){
	
	$TMP_MU->userId = $this_uId;
	
	if($this_uId == $CURRENT_USERID)
	continue;
	
	
	$THIS_USER_PROFILE = $TMP_MU->getUserProfile($this_uId);
	//print_r($THIS_USER_PROFILE);
	$input_statusmsg = html_entity_decode(stripslashes(base64_decode($THIS_USER_PROFILE['statusMsg'])));
	$statusmsg = format_makeLinks($input_statusmsg);	
	
	?>
		<tr <?php if(in_array($this_uId,$ACTIVE_USERS)){?>bgcolor="#B9E1F4"<?php } ?>>
			<td class="avatar">
			<?php
			$userpic = 'files/users/thumbs/'.$THIS_USER_PROFILE['userImage'];
			if($THIS_USER_PROFILE['userImage']!='' && file_exists($userpic))
			{
			?>
			<img src="<?=$userpic?>" alt="Avatar">
			<?php } else { ?>
			<img src="images/avatar.png" alt="Avatar">
			<?php } ?>
			</td>
			<td class="name">
			<?php echo $THIS_USER_PROFILE['emplFullName'];?><br>
			<a href="mailto:<?php echo $THIS_USER_PROFILE['emplEmail_id'];?>"><?php echo $THIS_USER_PROFILE['emplEmail_id'];?></a>
			</td>
			<td class="status" width="400">
			<?php echo $statusmsg;?>
			</td>
			
			<td class="umsg">
			<a rel='prettyPhoto[iframes]' href="dmsg.php?id=<?php echo base64_encode($THIS_USER_PROFILE['empl_id']);?>&iframe=true&width=536&height=245">Send Direct message</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
	</table>

</tbody>
</table>

<?php
some_prettyPicture_JsCrap();
include_once "include_footer.php";
?>

