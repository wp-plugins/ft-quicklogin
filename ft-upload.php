<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('/', '', $_POST['folder']) . '/wp-content/uploads/';
	$targetFile =  str_replace('//', '/', $targetPath) . $_POST['timestamp'] . '-' . $_FILES['Filedata']['name'];
	
	$fileTypes = array('jpg','jpeg','gif','png');
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if( in_array($fileParts['extension'], $fileTypes) ) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
	 	echo '0';
	}
}
?>