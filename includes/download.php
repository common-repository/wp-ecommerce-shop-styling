<?php
ob_start();
require_once(__DIR__."/../../../../wp-admin/admin.php");
ob_end_clean ();
if( strpos($_GET['filename'], '/') !== FALSE )
    die();
if( strrpos( strtolower($_GET['filename']), '.pdf') !== strlen($_GET['filename'])-4 )
    die();
header('Content-disposition: attachment; filename='.$_GET['filename']);
header('Content-type: application/pdf');
readfile(HAET_INVOICE_PATH.$_GET['filename']);
?> 