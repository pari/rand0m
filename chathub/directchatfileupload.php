<?php
set_time_limit(0);
include_once "include_db.php";
include_once "include_functions.php";

if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CURRENT_USER = $_SESSION["uname"] ;


$result = 0;

if(get_POST_var('to_user'))
{
	$to_user = get_POST_var('to_user');
	if($to_user == '' || $to_user == '0')
	{
		echo "please select a user to send file or message";
		exit();
	}
}

$new_message = get_POST_var('new_message');
if(isset($_FILES['myfile']['name']) && $_FILES['myfile']['name']!='')
{
	$file_type = $_FILES['myfile']['type'];
	$errors = $_FILES['myfile']['error'];
	$file_name = $_FILES['myfile']['name'];
	$size = $_FILES['myfile']['size'];

	if ($errors == UPLOAD_ERR_OK)
	{
		$tmp_name = $tmp_names;
		
		//$allowedExtensions = array("txt","csv","htm","html","xml","css","doc","xls","rtf","ppt","pdf","swf","flv","avi","wmv","mov","jpg","jpeg","gif","png"); 

		$type = end(explode(".",strtolower($file_name)));
		
		$MF = new ManageFiles();
		$UFID = $MF->createUnique_FID(16);

		$name = $UFID.".".$type;
		
		$file_name = str_replace(' ','_',$file_name);
		
		
		
		// Edit upload location here
		$destination_path = 'files/chat_files/';
		
		$target_path = UPLOAD_PATH . $name;
		$Thumb_Path = UPLOAD_PATH. 'thumbs/' .$name;
		
		if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path))
		{
			$result = 1;
		
			$MF->updateDiskSpace($size);
			
			if(in_array($file_type, array("image/gif", "image/jpeg", "image/png", "image/pjpeg", "image/bmp")))
			{
				try {
				
				copy($target_path, $Thumb_Path);
				// initialize object 
				$image = new Gmagick();
				
				$image->readImage($target_path);
				
				$old_width = $image->getImageWidth();
				$old_height = $image->getImageHeight();
				
				$new_width = 200;
				
				$new_height = ($old_height/$old_width)*$new_width;
				
				// exec("gm mogrify -resize {$new_width}x{$new_height} -quality 90 /websites/chat_cigniti/files/chat_files/thumbs/{$name}");
				exec("gm mogrify -resize {$new_width}x{$new_height} -quality 90 ".APP_INSTALLPATH.UPLOAD_PATH."thumbs/{$name}");
				
				//$image->thumbnailImage($new_width, $new_height);
				//$image->thumbnailImage(200, 150);
				//$thumbFile = dirname($file) . '/' . basename($target_path, '.JPG') . '.thumb.jpg';
				//$image->writeImage($Thumb_Path);
				
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
		}	
	}
}

if($fileId)
$fileType = 'F';
else
$fileType = 'M';

$success = execute_sqlInsert( 'tbl_DirectMessages', 
    array( 
	    'to_uid'=>$to_user ,
	    'from_uid'=>$_SESSION["empl_id"] ,
	    'msg_base64' => base64_encode( htmlentities( $new_message, ENT_QUOTES ) ),
	    'msg_plain' => $new_message,
	    'msgType' => $fileType,
	    'fileId' => $fileId
					    
    )
);


register_LastPingAt();
$result = 1;



//send_Action_Response('Success' , $result );
//$redirect= "Location:dmsg.php?id=".base64_encode($to_user)."&re=".$result."";
//header($redirect);
?>
<?php /*?><script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?>);</script>   <?php */?>



