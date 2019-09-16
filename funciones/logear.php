<?php

$login=$_POST["login"];
$pass=$_POST["pass"];
$clave= md5($pass);
$usuariosjson=file_get_contents("../usuario.json");
$usuarios=json_decode($usuariosjson, true);

if($usuarios){
foreach ($usuarios as $us) {
    if($us["login"]==$login){
          if($clave==$us["clave"]){
                session_start();
                $_SESSION["inicio"]="SI";
                $_SESSION["login"]=$login;
                $_SESSION["nombre"]=$us["nombre"];
                header("Location: ../principal.php");
                exit;
          }else{
              header("Location: ../index.php?msj=Usuario o Clave Invalido");
             }
    }else{
            header("Location: ../index.php?msj=Usuario o Clave Invalido");
  }
}
}

 ?>
