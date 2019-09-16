<?php
session_start();
if(isset($_GET["msj"])){
      echo "<h2>".$_GET["msj"]."</h2>";
}
echo "<h2>".$_SESSION["nombre"]."</h2>";
$perfil=$_SESSION["login"].".jpg";
 ?>
 <!DOCTYPE html>
 <html>
  <head>
     <meta charset="utf-8">
     <title>Datos de Perfil </title>
   </head>
   <body>
     <header>
        <?php
            if(file_exists("funciones/fotos/$perfil")){ ?>
            <img src="funciones/fotos/<?= $perfil; ?>"
              alt="foto_perfil" width="100px">
              <?php }else{ ?>
              <form action="funciones/subir.php" method="post" enctype="multipart/form-data">
                  <fieldset>
                    <input type="file" name="archivo">
                    <input type="submit" value="Enviar">
                  </fieldset>
              </form>
            <?php } ?>
     </header>
   </body>
 </html>
