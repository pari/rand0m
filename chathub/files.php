<?php

include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="
	#BOOKMARKSLIST{
		margin-left: auto;
		margin-right: auto;
		margin:2px;
		overflow: auto;
		width: 82%;
		height: 73%;
		background-color: #FFFFFF;
		border: 1px dotted #999;
	}
";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";


?>
<script>
	// $(document).ready(function() {});
	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
		$($.date_input.initialize);
	};

	var FILES = [];

	var searchFiles = function(){
		// Clear existing files
		// append new files
		var date = $('#files_date').val();
		
		$('#title').hide();
		$('#files_result').text('Files uploaded on '+date);
		$('#files_result').show();
		CJS_AJAX( 'searchFiles' , {
			DATE : date ,
			callback:function(a){
				if(a){
					eval(My_JsLibrary.responsemsg);
					append_NEW_FILES();
				}
			}
		});
	};
  
	var append_NEW_FILES = function()
	{
		
		var jq_div_ml = $("#BOOKMARKSLIST") ;
		jq_div_ml.empty();
  
		var append_file = function ( file )
		{
			// file = { fileId : '', msgBy : '' , msgTime : '' , fileName : '' , fileSize : '' , fileRandomName : '' }
			var fileName = file.filename;
			var msgBy = file.msgBy.capitalizeFirstChar();
			var msgTime = file.msgTime.timestamp_to_Date();
			var fileRandomName = file.fileRandomName;
			var filesize = file.filesize.formatBytesToHumanReadable(1);
			var imgTypes = ["jpeg","jpg","gif","png","bmp","pjpeg"];

			var display = '<div style="margin-top: 10px; padding: 10px; background-color: rgb(241, 244, 227); border-bottom: 2px solid rgb(231, 231, 231); text-align: left; display: table; width: 96%;">';
			display += '<div style="float: left; line-height: 150%;"><a href="chatfiledownload.php?fc=' +file.fileid+ '">'+ fileName +'</a><br>';
			if(imgTypes.contains(file.fileExt))
			display += '<img src="files/chat_files/thumbs/'+fileRandomName+'">';
			display += '</div><div style="float: right; color: rgb(199, 173, 139); margin-right: 10px;">'+msgTime+'</div>';
			display +=  '<div style="float: right; color: rgb(199, 173, 139); margin-right: 10px;">- '+msgBy+'</div>';
			display += '<div style="float: right; color: rgb(199, 173, 139); margin-right: 10px;">'+filesize+'</div>';
			display += '</div> ';
			
		      jq_div_ml.append(display);

		};

		for(var t = 0 ; t < FILES.length ; t++){
			append_file(FILES[t]);
		}
	};

</script>

	<center>
		<div id="title" style='margin-left: auto; margin-right: auto; margin:2px; font-size: 110%; font-weight: bold; padding: 10px; width: 82%;'>List of Recent Files</div>
		<p id="files_result" style="display:none; color:red; font-size:14px;"></p>
		 <div id="search">
			<input type='text' class='date_input' size=12 id='files_date'><input type="button" id="Go" name="Go" value="Go" onclick="searchFiles()" />
		 </div>   

		<div id='BOOKMARKSLIST'>
			<?php
				$MF = new ManageFiles();
				$LASTX_FILES_SQL = $MF->get_Last_XFiles_RelatedToUser_fullDetails_sql ( $CURRENT_USERID , 200 );
				$result = mysql_query( $LASTX_FILES_SQL ) ;
				
				while ($row = mysql_fetch_array($result)) {
				$FUPLOAD_INFO = $MF->getChatFileUploadInfo($row['fileId']);
					// fileId, fileName, fileRandomName, fileExt, fileSize, fileCode, fileType
					$tmp_preview_str = "<div style='margin-top: 10px; padding: 10px; background-color: #F1F4E3; border-bottom: 2px solid #E7E7E7; text-align:left; display: table; width: 96%;'>";
					
					$tmp_preview_str .= "<div style='float:left; line-height:150%;'><a href=\"chatfiledownload.php?fc={$row['fileId']}\">{$row['fileName']}</a>";
					if(in_array($row['fileExt'],array('jpg','png','gif','bmp','jpeg','pjpeg'))){
						$tmp_preview_str .= "<br/><a href=\"chatfiledownload.php?fc={$row['fileId']}\"><img src=files/chat_files/thumbs/{$row['fileRandomName']}></a>";
					}
					
					$tmp_preview_str .= "</div>";
					$tmp_preview_str .= "<div style='float:right;color: #C7AD8B; margin-right: 10px;'>".caldateTS_to_humanWithTS($GMU->convert_to_UsersTimeZone($FUPLOAD_INFO['uploadedDate']))."</div>";
					
					$tmp_preview_str .= "<div style='float:right; color: #C7AD8B; margin-right: 10px;'>- {$FUPLOAD_INFO['uploadedBy']}</div>";
					$tmp_preview_str .= "<div style='float:right; color: #C7AD8B; margin-right: 10px;'>".formatBytesToHumanReadable($row['fileSize'])."</div>";
					$tmp_preview_str .= "</div>";
					echo $tmp_preview_str;
				}
			?>
		</div>
	</center>

<?php
include_once "include_footer.php";
?>
