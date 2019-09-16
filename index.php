<?php
if(isset($_GET["msj"])){
      echo "<h2>".$_GET["msj"]."</h2>";
}

 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Mi Primer Sistema</title>
  </head>
  <body>
      <form action="funciones/logear.php" method="post">
          <fieldset>
            <legend>Acceder</legend>
                  <label for="login">Usuario</label>
                  <input type="text" name="login">
                  <label for="pass">Clave</label>
                  <input type="password" name="pass">
                  <button type="submit">Ingresar</button>
                  <span><a href="registro.php">Registrarse</a></span>
          </fieldset>
      </form>
  </body>
</html>
