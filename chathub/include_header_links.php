<?php
include_once "activeuserstracking.php";
$TMCR = new ManageChatRooms();
$MU = new ManageUsers();
$MU->userId = $CURRENT_USERID ;
$DM_COUNT = $MU->get_newDirectMessageCount();
$DM_COUNT_STR = ($DM_COUNT) ? " ({$DM_COUNT})" : "";
$LOGGEDIN_ROOMS = $MU->get_LoggedInRooms();
?>
<div class="mainMenu" style='margin-top: 5px;'>
	<span style='font-size:110%;'><I><?php echo $CURRENT_USER; ?></I></span>
	<SPAN class='i' selclass='ic' goto="lobby.php" TITLE="Get into the ChatRooms from the Lobby">Lobby</SPAN>
	
	<?php
	foreach($LOGGEDIN_ROOMS as $THIS_ROOM){
		echo "<SPAN class='l' selclass='lc' goto='chatroom.php?rid={$THIS_ROOM}' TITLE='Goto ChatRoom'>".$TMCR->get_roomTitle($THIS_ROOM)."</SPAN>";
	}
	?>
	
	<SPAN class='j' selclass='jc' goto="bookmarks.php" TITLE="list of bookmarked messages">BookMarks</SPAN>
	<SPAN class='g' selclass='gc' goto="files.php" TITLE="Files from various Chat Rooms">Files</SPAN>
	<SPAN class='h' selclass='hc' goto="dchat.php" TITLE="Send Direct Messages to other users">Messages<?php echo $DM_COUNT_STR; ?></SPAN>
</div>