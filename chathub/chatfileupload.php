<?php
set_time_limit(0);
include_once "include_db.php";
include_once "include_functions.php";

if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CURRENT_USER = $_SESSION["uname"] ;

// check whether this user has access to this room
$GMU = new ManageUsers();
$GMU->userId=$_SESSION['empl_id'];
$rid = get_POST_var('rid');
if(!$GMU->has_AccessToRoom($rid))
{
	echo "You are not authorised to access this page.";
	exit();
}

// get file size and generate a random code

$MF = new ManageFiles();
$UFID = $MF->createUnique_FID(16);

$size = $_FILES['myfile']['size'];
$file_name = str_replace(' ','_',$_FILES['myfile']['name']);
$file_type = $_FILES['myfile']['type'];
$errors = $_FILES['myfile']['error'];

//move the uploaded file to UPLOAD_PATH and insert into database

$result = 0;


	if ($errors == UPLOAD_ERR_OK) {
		$tmp_name = $tmp_names;
		
	//$allowedExtensions = array("txt","csv","htm","html","xml","css","doc","xls","rtf","ppt","pdf","swf","flv","avi","wmv","mov","jpg","jpeg","gif","png"); 

		$type = end(explode(".",strtolower($file_name)));
		$name = $UFID.".".$type;
		
		$file_name = str_replace(' ','_',$file_name);
		
		//if (in_array($type,$allowedExtensions)) { 
		
		// Edit upload location here
		$destination_path = 'files/chat_files/';
		
		$target_path = UPLOAD_PATH . $name;
		$Thumb_Path = UPLOAD_PATH . 'thumbs/' .$name;
		
		if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
		$result = 1;
		//$sql = execute_sqlQuery("UPDATE tbl_CompInfo SET comp_Logo='$name' WHERE comp_Id='$comp_Id'");
		$MF->updateDiskSpace($size);
		
		if(in_array($file_type, array("image/gif", "image/jpeg", "image/png", "image/pjpeg", "image/bmp"))){
			try {
			copy($target_path, $Thumb_Path);
			
			// initialize object 
			$image = new Gmagick();
			
			$image->readImage($target_path);
		
			$old_width = $image->getImageWidth();
			$old_height = $image->getImageHeight();
			
			$new_width = 200;
			
			$new_height = ($old_height/$old_width)*$new_width;
			
			exec("gm mogrify -resize {$new_width}x{$new_height} -quality 90 ".APP_INSTALLPATH.UPLOAD_PATH."thumbs/{$name}");
			
			//$image->thumbnailImage($new_width, $new_height);
			//$thumbFile = dirname($file) . '/' . basename($target_path, '.JPG') . '.thumb.jpg';
			//$image->writeImage($Thumb_Path);
			
			/*$thumb_size = $image->getsize();
			$MF->updateDiskSpace($thumb_size);*/
			
			// free resource handle
			$image->destroy();

			} catch (Exception $e) {
			die ($e->getMessage());
			}
		}
		
		
		$file_success = execute_sqlInsert( 'tbl_chatFiles', 
				array( 
					'fileName'=>$file_name ,
					'fileRandomName'=>$name ,
					'fileExt' => $type,
					'fileSize' => $size,
					'fileCode' => $UFID,
					'fileType' => $file_type
				)
			);
			
		$fileId = mysql_insert_id();
		
		if($fileId)
		{
			$newmsg = 'has uploaded a file <a href="chatfiledownload.php?fc='.$fileId.'">'.$file_name.'</a>';
			$success = execute_sqlInsert( 'tbl_ChatRooms', 
					array( 
						'saidBy_username'=>$CURRENT_USER ,
						'saidBy_empl_id'=>$_SESSION["empl_id"] ,
						'message_base64' => '',
						'message_plain_mysqlescaped' => $newmsg,
						'chatRoom' => $rid,
						'msgType' => 'F',
						'fileId' => $fileId
					)
				);
				
				register_LastPingAt();
		}
		$result = 1;
	}

//}
}
?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?>);</script>   



