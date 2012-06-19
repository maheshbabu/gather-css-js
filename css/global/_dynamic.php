<?php

/*
 * List of files to include
 * If any of these files change - the script will force the browser to download new package
 */

$file_array = array(
    'global/global1.css',
    'global/global2.css',
);



/*
 * Settings
 */

$folder = '/css/';
$mime_type = 'text/css';
$compress = true;



/*
 * That's where the magic happens!
 */

require_once($_SERVER['DOCUMENT_ROOT'].'/AutoVersion.php');
$av = new AutoVersion();

if (!defined('GATHERCONTENT')) {
    $av->fly($folder, $file_array, $mime_type, $compress);
} else {
	return $av->last_modified($folder, $file_array);
}
