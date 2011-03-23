<?php
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