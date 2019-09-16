<?php
//vector de paises
$pais=[
  "Argentina",
  "Bolivia",
  "Brasil",
  "Colombia",
  "Chile",
  "Ecuador",
  "Paraguay",
  "Peru",
  "Uruguay",
  "Venezuela",
  "España",
  "Inglaterra",
  "Francia",
  "Estados Unidos"
];

//Cargamos Archivo json categorias y convertimos en vector
$categoriajson=file_get_contents("categorias.json");
$categorias=json_decode($categoriajson, true);

//Inicializar variables que reciben datos del formulario
$name="";
$correo="";
$user="";
$intereses=[];
$edad="";
$paisf="";
$genero="";
$passw="";

//incializo variables de control de llenado de formulario

$vname=false;
$vcorreo=false;
$vuser=false;
$vintereses=false;
$vedad=false;
$vpaisf=false;
$vgenero=false;
$vpassw=false;
$vcpassw=false;
$ruser=false;

//valiamos los datos recibidos del formulario

if(isset($_POST["submit"])){
    if($_POST["nombre"]==""){
        $vname=true;
    }else{
        $name=$_POST["nombre"];
    }
    if($_POST["mail"]=="" ||
    !filter_var($_POST["mail"],FILTER_VALIDATE_EMAIL)){
        $vcorreo=true;
    }else{
        $correo=$_POST["mail"];
    }
    if($_POST["login"]=="" || strlen($_POST["login"]) >= 15){
        $vuser=true;
    }else {
        $user=$_POST["login"];
    }
    if(!isset($_POST["hobbie"])){
        $vintereses=true;
    }else {
        $intereses=$_POST["hobbie"];
    }
    if($_POST["edad"]=="" || !is_numeric($_POST["edad"])){
        $vedad=true;
    }else{
      $edad=$_POST["edad"];
    }
    if($_POST["pais"]==""){
      $vpaisf=true;
    }else{
      $paisf=($_POST["pais"]);
    }
    if(!isset($_POST["gen"])){
      $vgenero=true;
    }else {
      $genero=$_POST["gen"];
    }
    if($_POST["clave"]==""){
        $vpassw=true;
    }else if($_POST["cclave"]=="" ||
    $_POST["clave"]!=$_POST["cclave"] ){
        $vcpassw=true;
    }else {
      $passw=$_POST["clave"];
    }

//validamos que el archivo de usuario exista y validamos
//que el nombre de usuario (login) no este en uso.
    if(file_exists("usuario.json")){
      $usuariosjson=file_get_contents("usuario.json");
      $usuarios=json_decode($usuariosjson, true);
      if($usuarios){
      foreach ($usuarios as $us) {
          if($us["login"]==$_POST["login"]){
                $ruser=true;
                break;
          }
        }
      }
    }
//envio de datos
//verificamos que no hayan errores
echo "nombre: ".$vname."correo:".$vcorreo."usuario: ".$vuser."interes: ".$vintereses.
    "edad: ".$vedad ."pais: ".$vpaisf ."genero:".$vgenero."pass: ".$vpassw.
    " c clave: ". $vcpassw . "Existe ".$ruser;
if(!$vname && !$vcorreo && !$vuser && !$vintereses &&
    !$vedad && !$vpaisf && !$vgenero && !$vpassw &&
    !$vcpassw && !$ruser){
      //Encriptamos la password
      $passwmd = md5($passw);
      $usuario=[
      "nombre"=> $name,
      "mail" => $correo,
      "login" =>$user,
      "hobbie" =>$intereses,
      "edad" =>$edad,
      "pais" => $paisf,
      "gen" => $genero,
      "clave" => $passwmd
    ];
    $usuarios[]=$usuario;
    $usuariosjson=json_encode($usuarios,JSON_PRETTY_PRINT);
    file_put_contents("usuario.json",$usuariosjson);
    header("Location: felicitaciones.php");
    exit;
    }
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Registro Usuario</title>
  </head>
  <body>
      <form action="registro.php" method="post">
        <fieldset>
              <legend>Registro</legend>
              <label for="nombre">Nombre Completo</label><br>
              <?php if($vname){ ?>
                <span>Debe ingresar el nombre</span><?php } ?>
              <input type="text" name="nombre"><br>

              <label for="mail">Correo-E</label><br>
              <?php if($vcorreo){ ?>
                <span>Debe ingresar un correo valido</span><?php } ?>
              <input type="text" name="mail"><br>

              <label for="login">Usuario</label><br>
              <?php if($vuser){ ?>
                <span>Debe ingresar Usuario</span><?php }else if($ruser){ ?>
                  <span>Este nombre de Usuario esta en uso</span><?php } ?>
              <input type="text" name="login"><br>

              <label for="hobbie[]">Intereses</label><br>
              <?php foreach ($categorias['categorias'] as
              $categoria): ?>
                <input type="checkbox" name="hobbie[]"
                value="<?= $categoria['id']?>"
                <?= in_array($categoria['id'],$intereses)
                ? "checked": "" ?>>
                <?= $categoria['nombre'] ?><br>
              <?php endforeach; ?><br>

              <label for="edad">Edad:</label><br>
              <?php if($vedad){ ?>
                <span>Debe Ingresar su edad</span><?php } ?>
              <input type="text" name="edad"><br>

              <label for="pais">Nacionalidad</label><br>
              <?php if($vpaisf){ ?>
                <span>Seleccione su pais de nacimiento</span><?php } ?>
              <select name="pais">
                <option value="">Seleccione pais...</option>
                    <?php
                      for ($i=0; $i < 14 ; $i++) { ?>
                          <option value="<?php echo $i; ?>">
                                <?php echo $pais[$i]; ?>
                          </option>
                    <?php  }   ?>
              </select><br>

              <label for="gen">Genero</label><br>
              <?php if($vgenero){ ?>
                <span>Seleccione Genero</span><?php } ?>
              <input type="radio" name="gen" value="1">
              <label for="gen">Varon</label>
              <input type="radio" name="gen" value="2">
              <label for="gen">Mujer</label>
              <input type="radio" name="gen" value="3">
              <label for="gen">Otre</label><br>

              <label for="clave">Contraseña</label><br>
              <?php if($vpassw){ ?>
                <span>Debe ingresar una clave</span><?php } ?>
              <input type="password" name="clave"><br>

              <label for="cclave">Confirmar Contraseña</label><br>
              <?php if($vcpassw){ ?>
                <span>Las contraseñas no coinciden</span><?php } ?>
              <input type="password" name="cclave">

              <input type="submit" name="submit" value="Enviar">
        </fieldset>
      </form>
      <a href="index.php">Regresar</a>
  </body>
</html>
