<?php

if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ) {
	ob_start('ob_gzhandler');
	//header("Content-Encoding: gzip");
}

$offset = 24 * 60 * 60; // cache for 24 hours
$expire = "Expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
header ($expire);
header("Content-Type: application/javascript");

include "jquery.js";
include "custom.js";

?>