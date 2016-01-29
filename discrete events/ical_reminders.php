<?php
include_once "include_db.php";
include_once "include_functions.php";
$uekey = @$_GET["key"] ;
$USERNAME =  executesql_returnArray("select username from users where remindersicalkey='$uekey' ;");
if(!$USERNAME){ exit();}
$tmp_manageUsers = new manageUsers() ;
$actual_key = $tmp_manageUsers->get_userSingleDetail( $USERNAME, 'remindersicalkey' );

if(!$uekey || $uekey <> $actual_key ){exit();}

$result = mysql_query("select * from scheduledmails where emailby_user='$USERNAME' order by email_scheduledon");
$scE_Count = @mysql_num_rows($result);
?>
BEGIN:VCALENDAR
PRODID:-//CenterLimit LLC//DiscreteEvents Reminders Calendar 0.01//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:DiscreteEvents Reminders
X-WR-TIMEZONE:Asia/Calcutta
X-WR-CALDESC:DiscreteEvents Reminders for <?php echo $USERNAME; ?>
<?php
if($scE_Count == 0){ 
	echo "\nEND:VCALENDAR"; exit();
}

while ($row = mysql_fetch_assoc($result)) { 
	echo "\nBEGIN:VEVENT";
	echo "\nDTSTART:".caldateTS_to_icalDate($row['email_scheduledon']);
	echo "\nDTEND:".caldateTS_to_icalDate($row['email_scheduledon'] , true);
	echo "\nCREATED:20091028T110705Z";
//	echo "\nDESCRIPTION:". "Reminder to {$row['email_to']} about {$row['email_subject']}";
	echo "\nDESCRIPTION:".$row['email_subject'];
	echo "\nLAST-MODIFIED:20091028T110743Z";
	echo "\nLOCATION:Home";
	echo "\nSEQUENCE:0";
	echo "\nSTATUS:CONFIRMED";
	echo "\nSUMMARY:".$row['email_subject'];
	echo "\nTRANSP:OPAQUE";
	echo "\nEND:VEVENT";
}
echo "\nEND:VCALENDAR";
?>