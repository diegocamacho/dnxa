<?
	
	$data="130	02/01/2017	16:10:21	1";
	
	echo "<h1>Prueba</h1>";
	
	echo dameID($data);
	echo "<br>";
	echo dameFecha($data);
	echo "<br>";
	echo dameHora($data);
	echo "<br>";
	echo dameTipo($data);
	//Saco el datos
	function dameID($data){ 
		list($id)=explode("	",$data); 

		return $id;
	}
	
	function dameFecha($data){ 
		list($id,$fecha)=explode("	",$data); 

		return $fecha;
	}
	
	function dameHora($data){ 
		list($id,$fecha,$hora)=explode("	",$data); 

		return $hora;
	}
	
	function dameTipo($data){ 
		list($id,$fecha,$hora,$tipo)=explode("	",$data); 

		return $tipo;
	}
	
	
	
	
	
	
	