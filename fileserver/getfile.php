<?php

include_once "get_common.php";

$fetched_row['file_b64_content'] = base64_encode(file_get_contents($file_total_path));

$PMWRAP->update_table( 'tbl_files' , array('download_count' => $fetched_row['download_count'] + 1 ) , array('fid' => $fetched_row['fid'] ) );

echo json_encode(
	array(
		'result' => 'success',
		'msg' => 'success' ,
		'filedata' => $fetched_row
	)
);


?>