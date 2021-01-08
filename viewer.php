<?php 
  
// Store the file name into variable 
$file = $_GET['file'];
$file = base64_decode($file);
  
// Header content type 
header("Content-type: application/pdf"); 
  
header("Content-Length: " . filesize($file)); 
  
// Send the file to the browser. 
readfile($file); 
  
?>