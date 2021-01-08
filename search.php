<?php
include "config.php";

// Search result

    $searchText = $_POST['nik'];
    //$searchText = '113105';
    $ch = curl_init('http://sid.polibatam.ac.id/apilogin/web/api/auth/cek-id?id='.$searchText.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($ch));


    if($result->status == "success") {
    	$name= $result->data->name;
    	$email=$result->data->email;
    	$jabatan=$result->data->jabatan;
        $search_arr[] = array("name" => $name, "email" => $email, "jabatan" => $jabatan);
     }
     else{
     	$search_arr[] = array("name" => "-", "email" => "-", "jabatan" => "-");
     }

    
    echo json_encode($search_arr);


?>