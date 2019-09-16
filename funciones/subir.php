<?php
session_start();
      if($_FILES["archivo"]["error"] == UPLOAD_ERR_OK){
          $nombre = $_FILES["archivo"]["name"];
          $archivo = $_FILES["archivo"]["tmp_name"];
          $ext = pathinfo($nombre, PATHINFO_EXTENSION);
          if($ext=="jpg"){
                $nomc= "../fotos/".$_SESSION["login"].".".$ext;
                if(file_exists($nomc)){
                  header("location: ../principal.php?msj=Archivo Existe");
                } else {
                          $sarchivo = dirname(__FILE__);
                          $sarchivo = $sarchivo . "/fotos/";
                          $sarchivo = $sarchivo . $_SESSION["login"] .".". $ext;

                          move_uploaded_file($archivo,$sarchivo);
                          header("location: ../principal.php?msj=Carga Exitosa");
                          exit;
              }
      }else{
        header("location: ../principal.php?msj=La foto debe ser JPG");
        exit;
      }
    }
 ?>
