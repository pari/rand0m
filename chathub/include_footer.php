<?php
	
	if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }
	
?>
	<DIV class='userfooter' style='padding: 5px;'>
		<?php
		
		if( $AM_I_ADMIN ){
			echo "<A href='company_info.php'>Edit Company Info</A> | " ;
			echo "<A href='create_user.php'>Create User</A> | " ;
		}
		
		if( $AM_I_ADMIN || $GMU->has_Privilege('Can Invite Other Users')){ 
			echo "<A href='invite_users.php'>Invite New Users</A> | " ;
		}
		
		if( $AM_I_ADMIN ){
			echo "<A href='users.php'>Manage Users</A> | " ;
		}
		
		?>
		<A href='edituser.php'>Edit Profile</A>  | <A href='options.php'>Change Password</A>
		<DIV style='margin-top: 10px;'>
		<?php echo APPNAME.", ver ".APPVERSION;?> &copy; 2010-2014. <A href='http://centerlimit.com/' target='_blank'>CenterLimit</A>
		</DIV>
	</DIV>

</BODY>
</HTML>
