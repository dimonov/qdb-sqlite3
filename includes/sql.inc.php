<?php

function do_hash($hsh){
	return hash("sha1", $hsh);
}

function escape_str($unescaped){
global $dbc;
	return (isset($unescaped) && !($unescaped == ""))?
	htmlspecialchars($dbc->escapeString($unescaped)) :
	NULL;
}

function disconnect(){
global $dbc;
$dbc->close();
}

?>
