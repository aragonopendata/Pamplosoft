<?php

$dir="";
if(isset($_GET["accion"])){

	if($_GET["accion"] =='nacional'){
			$dir = 'http://opendata.aragon.es/socialdata/trendings?type=spain';
		}
		else {
			if($_GET["accion"] =='diferencial'){
				$dir = 'http://opendata.aragon.es/socialdata/trendings?type=diff';
			}
		}


}else{
	$dir = 'http://opendata.aragon.es/socialdata/trendings';
}



renderweb($dir);

function renderweb($url){
	$json = file_get_contents($url);

	$obj = json_decode($json,true);

	$names = array();
	$urls = array();
	$i = 0;

	foreach ($obj as $key => $value) {

		if($key == 'results'){
		 
	    		foreach ($value as $k) {
				$names[] = $k["name"]; 
				$urls[] = $k ["url"];

	    		}

		}
	}

	$file = file_get_contents('./project/index.html');

	$trozos = explode('##repetir##',$file);
	$cuerpo ="";
	$aux = "";
	$trending = "";
	for($i=0;$i<=9;$i++){
		$aux= $trozos[1];
		$aux = str_replace('##posicion##',$i,$aux);
		$aux = str_replace('##name##',$names[$i],$aux);
		$cuerpo .= $aux;	
		if ($i == 9){
			$trending .=  "'".$names[$i]."'";
		}else{
			$trending .=  "'".$names[$i]."'".",";
		}
	}
	$trozos[0] = str_replace('##trendings##',$trending,$trozos[0]);
	echo $trozos[0].$cuerpo.$trozos[2];
}


?>
