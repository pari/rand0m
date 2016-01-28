<?php

exit();


// Improvements could be made such that instead of base 64 encoding file content as a regular post string
// You can use Snoopy's file upload (MIME encoding as a regular browser)
// Needs some testing and analysis ... sample code is give below

$url = "http://www.domain.tld/pfad/zum/formular.php";
include "Snoopy.class.php";
$snoopy = new Snoopy();
// Multi-Part aktivieren, sonst können keine Dateien übertragen werden
$snoopy->set_submit_multipart();
$postVars = array();
$postVars['name'] = 'Snoopy';
$postFiles = array();
$postFiles['userfile'] = dirname(__FILE__) . '/bla.pdf';
$snoopy->submit($url, $postVars, $postFiles);
$body = $snoopy->results;

// Further all the file serving is actually done by PHP and not apache
// this can be fixed by using Apache module ‘mod_xsendfile’. ( http://www.jasny.net/articles/how-i-php-x-sendfile/ ) or https://tn123.org/mod_xsendfile/
// When serving files like this - if you want to send meta data along you can use custom http header strings

header("X-Sendfile: $somefile");
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' . $filename . '"');



?>