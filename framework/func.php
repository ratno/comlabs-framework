<?php
/**
 * @author Ratno Putro Sulistiyono, ratno@comlabs.itb.ac.id
 * Fungsi-fungsi untuk  mendukung framework sederhana yang digunakan untuk pelatihan di Comlabs USDI ITB
 */
function url($controller,$method=null,$var=null) {
    $out = BASEURL.SUBDIR."/".INDEX."$controller";
    if($method) {
        $out .= "/$method";
    }
    if(is_array($var)) {
		foreach($var as $k=>$c){
			if($k && $c){
				$out .= "/$k:$c";
			}
		}
	} else {
        $out .= "/$var";
    }
    return $out;
}

function d($data,$die=false){ //debug
	echo "<pre>";
	echo "\n--debug start at ".date("d-m-Y h:i:s")."--\n";
	print_r($data);
	echo "\n--end debug--\n";
	echo "</pre>";
	if($die) die("-die-");
}

function selected($data,$pilihan){
	if($data==$pilihan) return "selected=selected";
	else return "";
}

function set_keamanan($data_user){
	$_SESSION['identitas_login'] = '--B0lehMasukG4n--';
	$_SESSION['data_user'] = $data_user;
}

function cek_keamanan($roles){
	if(in_array("public",$roles)){ // klo di roles dikasi public, ya langsung aja akses
		return true; 
	} else { // klo ndak cek identitasnya
		// jika identitas_login sesuai dengan yang di set di set_keamanan berarti ok
		if($_SESSION['identitas_login'] == '--B0lehMasukG4n--'){
			$user = $_SESSION['data_user'];
			if(in_array($user['role'],$roles)){
				return $user; // user boleh akses, maka berikan info user
			} else {
				header("Location: ".url("index","control_panel")); // ga boleh akses, lemparkan ke halaman control panel 
			}
		} else {
			header("Location: ".url("index","login")); // belum punya login, lemparkan ke halaman login 
		}
	}
}