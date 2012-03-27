<?php
/*


CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  pagado BOOL NOT NULL,
  habilitado BOOL NOT NULL,
  referido_id INT,
  nombre TEXT,
  nick TEXT,
  email TEXT,
  pass TEXT,
  dinero INT NOT NULL,
  domicilio TEXT,
  cp TEXT,
  poblacion TEXT,
  pais TEXT,
  metodo TEXT,
  trans_cuenta TEXT,
  paypal_cuenta TEXT,
  fecha DATETIME NOT NULL
);

CREATE TABLE eventos (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  releated_fk INT,
  data TEXT,
  tipo INT NOT NULL,
  fecha DATETIME NOT NULL 
);

INSERT INTO users(nombre,referido_id,nick,email,pass,dinero) VALUES ('David',NULL,'david','david@bengoa','453oi345',0);
INSERT INTO eventos(id_usuario,releated_fk,data,tipo,fecha) VALUES (1,1,"sdasd",1,NOW()); 
 
 */

include_once("config.php");

$link = null;

function connect(){
  global $link,$mysql_host,$mysql_user,$mysql_pas,$mysql_db;

  $link = mysql_pconnect($mysql_host,$mysql_user,$mysql_pas);
  if(!$link){
    die("Error conectando al servidor MySQL");
  }
  $rv = mysql_select_db($mysql_db,$link);
  if(!$rv){
    die("Error seleccionando base de datos");
  }
}

function get_link(){
  global $link;
  if(!$link){
    connect();
  }
  return $link;
}

function query($query,$remp = array()){
  $lnk = get_link();
  $remp = array_map('mysql_real_escape_string', $remp);
  //error_log(vsprintf($query,$remp));
  $q = mysql_query(vsprintf($query,$remp),$lnk);
  if(!$q){
    if(_c("debug")){
      echo vsprintf($query,$remp)."<br/>";
      echo mysql_error();
    }
    die("Error en la consulta a la base de datos");

  }
  return $q;
}

function get_first($q){
  $r = mysql_fetch_assoc($q);
  if($r)
    return $r;

  throw new Exception("El elemento no existe");

}

function get_insert_id(){
  global $link;
  return mysql_insert_id($link);
}
function table_result($q){
  $init = false;
  echo "<table border='1'>";
  while($r = mysql_fetch_assoc($q)){
    if(!$init){
      $init = true;
      echo "<tr>";
      foreach($r as $k => $v){
        echo "<th>$k</th>";
      }
      echo "</tr>";
    }
    echo "<tr>";
    foreach($r as $k => $v){
      echo "<td>$v</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
}


?>
