<?php

include_once "get_common.php";

echo json_encode(
	array(
		'result' => 'success',
		'msg' => 'success' ,
		'filedata' => $fetched_row
	)
);


?>