<?php
/*
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name TEXT,
  dni TEXT,
  titulacion INT,
  conf1 BOOL NOT NULL DEFAULT FALSE,
  conf2 BOOL NOT NULL DEFAULT FALSE,
  conf3 BOOL NOT NULL DEFAULT FALSE,
  conf4 BOOL NOT NULL DEFAULT FALSE,
  conf5 BOOL NOT NULL DEFAULT FALSE,
  conf6 BOOL NOT NULL DEFAULT FALSE
);
*/

include_once("db.php");

$confs = array(
    "Charla coloquio",
    "Turing",
    "Mierda de windows",
    "Bluebrain",
    "GPU",
    "Tuenti"
);
$tits = array(
    "Otros",
    "Grado en informatica",
    "Grado en software",
    "Grado en computadores",
    "Grado en ing. salud",
    "Superior",
    "Sistemas",
    "Gestion",
    "Otros"
);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    switch($_GET["action"]){
        case "update":
            query("UPDATE users SET conf1=%d, conf2=%d, conf3=%d, conf4=%d, conf5=%d, conf6=%d WHERE id=%d", array(
                isset($_POST['conf1'])?1:0,
                isset($_POST['conf2'])?1:0,
                isset($_POST['conf3'])?1:0,
                isset($_POST['conf4'])?1:0,
                isset($_POST['conf5'])?1:0,
                isset($_POST['conf6'])?1:0,
                $_GET["id"]
            ));
            header('Location: ?action=search&res=ModificadoCorrectamente') ;
            die();
        case "new":
            query("INSERT INTO users(name,dni,titulacion) VALUES ('%s','%s',%d)", array($_POST['name'], strtoupper($_POST['dni']), $_POST['titulacion']));
            header('Location: ?action=update&id=' . get_insert_id());
            die();
        case "search":
            try{
                $tbl = get_first(query("SELECT * FROM users WHERE dni='%s';", array(strtoupper($_POST['dni']))));
                header('Location: ?action=update&id=' . $tbl["id"]);
            }catch(Exception $e){
                header('Location: ?action=new&dni=' . strtoupper($_POST["dni"]));
            }
            die();
    }
}

$action = isset($_GET["action"])?$_GET["action"]:"search";
if($action=="update"){
    $alu = get_first(query("SELECT * FROM users WHERE id=%d", array($_GET["id"])));
}

?>
<html>
    <body>
        <?php
            switch($action){
                case "search": ?>
                    <?php
                        if(isset($_GET["res"])){
                            echo "<div style='background-color: #68eb9a;'>{$_GET['res']}</div>";
                        }
                    ?>
                    <form action="?action=search" method="post" accept-charset="utf-8">
                        Buscar DNI: <input type="text" name="dni" value="" /><input type="submit" value="Buscar">
                   </form><?php
                break; case "update":
                    echo "<div>Editando a {$alu['name']} DNI {$alu['dni']}</div>";
                    echo '<form action="?action=update&id=' . $_GET['id'] . '" method="post" accept-charset="utf-8">';
                    for ($i = 1; $i < 7; $i++) {
                        $val = $alu["conf$i"]=="1"?"checked='1'":"";
                        echo "<p><input type='checkbox' name='conf$i' $val id='conf$i'/><label for='conf$i'>{$confs[$i-1]}</label></p>";
                    }
                    echo "<input type='submit' value='Cambiar'></form>";
                break; case "new": ?>
                    <div>Añadir usuario</div>
                    <form action="?action=new" method="post" accept-charset="utf-8">
                        <p>Nombre <input type="text" name="name" value="" /></p>
                        <p>DNI <input type="text" name="dni" value="<?php echo $_GET['dni']; ?>" /></p>
                        <p>Titulacion
                        <select name="titulacion">
                            <?php foreach($tits as $k => $v) echo "<option name='$k'>$v</option>"; ?>
                        </select>
                        <input type='submit' value='Cambiar'></form>
                    </form><?php
                break; default:
                    die("");
            }?>
    </body>
</html>
