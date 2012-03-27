<?php
/*CONEXION MYSQL*/


function inserta($dni, $nombre, $ap1, $ap2, $titulacion){
 //echo "intruduce(" . $dni . " " . $nombre . " ". $ap1 . " ". $ap2 . " ". $titulacion . ")\n\n";

    $query="INSERT INTO users (id, name, dni, titulacion, conf1, conf2, conf3, conf4, conf5, conf6) VALUES (null, '$nombre', '$dni', '$titulacion', TRUE, null, null, null, null, null)";
    echo "$query\n";
//mysql_query($query, $conexion) or die (mysql_error());


}

$tex = file_get_contents("bd.txt");
$texto=str_replace("%&%", " ", "$tex");
$tok = strtok($texto, "\n");
while ($tok) {
	$datos=explode("|",$tok);

	list($dni, $nam, $ap1, $ap2, $tit) = split('[|]', $tok);
	$nam=ereg_replace ("&", " ", $nam);
	$ap1=ereg_replace ("&", " ", $ap1);
	$ap2=ereg_replace ("&", " ", $ap2);
	$dni=strtoupper($dni);
	inserta($dni,$nam,$ap1,$ap2,$tit);

   $tok = strtok(" \n\t");
}

?>
