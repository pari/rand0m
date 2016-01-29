<?php
include_once "include_db.php";
include_once "include_functions.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CURRENT_USER = $_SESSION["uname"] ;
$TMP_MU = new ManageUsers();
$TMP_MU->userId = $_SESSION['empl_id'];
if(!$TMP_MU->isAdminUser())
{
echo 'You are not authorised to access this page.';
exit();
}
$cname = $_POST['c_name'];

$result = 1;

setAppVariable('comp_Name',$cname);

$size = $_FILES['c_logo']['size'];
$type = $_FILES['c_logo']['type'];
$errors = $_FILES['c_logo']['error'];
$file_name = $_FILES['c_logo']['name'];
$comp_Id=1;

  if (in_array($type, array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg', 'image/bmp'))){
	if ($errors == UPLOAD_ERR_OK) {
		$tmp_name = $tmp_names;
		
		if($type == "image/gif")
		$name = $comp_Id.".gif";
		else if($type == "image/jpeg")
		$name = $comp_Id.".jpg";
		else if($type == "image/png")
		$name = $comp_Id.".png";
		else if($type == "image/pjpeg")
		$name = $comp_Id.".jpg";
		else if($type == "image/bmp")
		$name = $comp_Id.".bmp";
		
		// Edit upload location here
		$destination_path = 'files/c_logos/';
		
		$target_path = $destination_path . $name;
		$Thumb_Path = $destination_path. 'thumbs/' .$name;
				
		if(@move_uploaded_file($_FILES['c_logo']['tmp_name'], $target_path)) {
			$result = 1;
			setAppVariable('comp_Logo',$name);
		}
}
}
header("Location:company_info.php?res=$result");
?>


