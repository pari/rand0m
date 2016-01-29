<?php

if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ) {
	ob_start('ob_gzhandler');
	//header("Content-Encoding: gzip");
}

$offset = 24 * 60 * 60; // cache for 24 hours
$expire = "Expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
header ($expire);
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");

include "global.css";

?>