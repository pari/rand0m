<?php
include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CURRENT_USER = $_SESSION["uname"] ;

if(!isset($_POST['submit']))
{
echo "You are not authorised to access this page";
exit();
}

$name = get_POST_var("name");
$email = get_POST_var("email");
$mobile = get_POST_var("mobile");
$designation = get_POST_var("designation");
$Timezone = get_POST_var("Timezone");

$uid = $_SESSION["empl_id"];

$existingEmail = executesql_returnArray("select emplEmail_id from tblAppUsers where empl_id!='$uid' AND emplEmail_id='{$email}'");;
$er='';
if( $existingEmail ){
	//send_Action_Response('Fail' , 'Email Already Exists !');
	$result = 0;
	$er=f;
}else{
	$result = execute_sqlUpdate('tblAppUsers', array('emplEmail_id'=>$email, 'emplFullName'=>$name, 'emplMobileNo'=>$mobile, 'emplDesignation'=>$designation, 'TimeZone'=>$Timezone), array('empl_id'=>$uid) );
	//send_Action_Response( 'Success' , 'Updated!' );
	$result = 1;
}


if(isset($_FILES['userimg']['name']))
{
	$type = $_FILES['userimg']['type'];
	$errors = $_FILES['userimg']['error'];
	$file_name = $_FILES['userimg']['name'];


	if (in_array($type, array("image/gif","image/jpeg","image/png","image/pjpeg")))
	{
		if ($errors == UPLOAD_ERR_OK)
		{
			$tmp_name = $tmp_names;
			
			if($type == "image/gif")
			$name = $uid.".gif";
			else if($type == "image/jpeg")
			$name = $uid.".jpg";
			else if($type == "image/png")
			$name = $uid.".png";
			else if($type == "image/pjpeg")
			$name = $uid.".jpg";
			else if($type == "image/bmp")
			$name = $uid.".bmp";
			
			
			// Edit upload location here
			$destination_path = 'files/users/';
			
			$target_path = $destination_path . $name;
			$Thumb_Path = $destination_path. 'thumbs/' .$name;
			
			if(@move_uploaded_file($_FILES['userimg']['tmp_name'], $target_path))
			{
				$result = 1;
				$sql = execute_sqlQuery("UPDATE tblAppUsers SET userImage='$name' WHERE empl_id='$uid'");
				
				try {
				// initialize object 
				$image = new Gmagick();
				
				$image->readImage($target_path);
				$image->thumbnailImage(55, 55);
				$image->writeImage($Thumb_Path);
				
				// free resource handle
				$image->destroy();

				} catch (Exception $e) {
				die ($e->getMessage());
				}
			}
		}
	}
}
header("Location:edituser.php?res=$result&er=$er");
?>


