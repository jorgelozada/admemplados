<?php
//INICIALIZACION DE VARIABLES
  $array_paises=[
    "Argentina",
    "Brasil",
    "Bolivia",
    "Chile",
    "Paraguay",
    "Perú",
    "Venezuela",
    "Colombia",
    "Uruguay",
    "Ecuador"
  ];

  /*
  Leemos el archivo categorias.json, donde tenemos guardadas las categorias
  de intereses que queremos mostrar en el formulario. Una vez que leemos el contenido del archivo,
   decodificamos su contenido para transformarlo de json a un array de php.
   */
  $categoriasJson = file_get_contents('categorias.json');
  $categorias = json_decode($categoriasJson, true);

  /*
  Inicializamos las variables donde guardaremos los datos que enviamos del formulario.
  En caso de que algun dato no pase las validaciones, y haya que volver a completar el formulario,
  estas variables nos serviran para que los campos que si pasaron las validaciones conserven los datos
  y no haya que volver a completarlos
  */
  $name = "";
  $username ="";
  $email = "";
  $edad ="";
  $pais_enviado="";
  $intereses = [];
  $genre="";
  $password="";
  //ARRAY DE USUARIOS GUARDADOS
  $usuarios = [];

  /*
  Inicializamos las variables donde registraremos si sucedio un error de validación
  en algún dato. Cada variable se corresponde con un error de validación en un campo
  del formulario.
  */
  $emptyNameError = false;
  $emptyUsernameError = false;
  $invalidEmailError = false;
  $notNumericAgeError = false;
  $emptyInterestError = false;
  $emptyGenreError = false;
  $emptyCountryError = false;
  $notSetPasswordError = false;
  $confirmPasswordError = false;
  $registeredUsernameError = false;

//VALIDACION

  /*
  Esta porción del codigo realiza las validaciones. Primero, evalua si el formulario fue enviado.
  Luego, efectua validaciones en cada campo, para asegurar que los datos cumplen con los criterios que definimos.
  Si el campo no las cumple, registramos el error cambiando el valor de la variable correspondiente a ese error a "true".
  Si el campo las cumple, guarda el valor que se ingreso en las variables definidas anteriormente, de manera que si algún otro
  campo no pasa la validación, los valores que si pasaron queden guardados en estas variables, para poder autocompletar el
  formulario.
  */
  if (isset($_POST["submit"])) {
    if (!isset($_POST["name"])){
      $emptyNameError = true;
    } else {
      $name=$_POST["name"];
    }
    if (!isset($_POST["username"]) || strlen($_POST["username"]) >= 15){
      $emptyUsernameError = true;
    } else {
      $username=$_POST["username"];
    }
    if (!isset($_POST["email"]) || !filter_var($_POST["email"],FILTER_VALIDATE_EMAIL)){
      $invalidEmailError = true;
    }else {
      $email = $_POST["email"];
    }
    if (!isset($_POST["edad"]) || !is_numeric($_POST["edad"])){
      $notNumericAgeError = true;
    } else {
      $edad=$_POST["edad"];
    }
    if (!isset($_POST["intereses"])){
      $emptyInterestError = true;
    }else{
      $intereses=$_POST["intereses"];
    }
    if (!isset($_POST["gen"])){
      $emptyGenreError = true;
    }else{
      $genre=$_POST["gen"];
    }
    if (!isset($_POST["country"])){
      $emptyCountryError = true;
    }else{
      $pais_enviado=$_POST["country"];
    }
    if (!isset($_POST["password"])) {
      $notSetPasswordError = true;
    } elseif (!isset($_POST["confirm_password"]) || $_POST["password"]!= $_POST["confirm_password"] ) {
      $confirmPasswordError = true;
    } else {
      $password = $_POST["password"];
    }
    /*
    Guardaremos los usuarios en el archivo usuarios.json. Si el archivo existe,
    leemos el contenido del archivo, lo decodificamos y lo cargamos en el array usuarios.
    Si el archivo no existe, creamos un array vacio con el mismo nombre.
    */
    if (file_exists("usuarios.json")) {
      $usuariosJson=file_get_contents('usuarios.json');
      $usuarios=json_decode($usuariosJson,true);
      foreach ($usuarios as $us) {
        if ($us["username"] == $username) {
          $registeredUsernameError = true;
          echo "El nombre de usuario ya existe";
          break;
        }
      }
    }
    //ENVIO DE DATOS
    // Evaluamos si no se produjo ningún error. Si esto es así, procedemos a procesar la información del formulario
    if (!$emptyNameError && !$invalidEmailError && !$notNumericAgeError && !$emptyInterestError && !$emptyGenreError && !$emptyCountryError && !$notSetPasswordError && !$confirmPasswordError && !$registeredUsernameError) {
      // Encriptamos la contraseña
      $md5Pass = md5($password);
      $usuario = [
        "name"=>$name,
        "email"=>$email,
        "username" => $username,
        "intereses"=>$intereses,
        "edad"=>$edad,
        "pais"=>$pais_enviado,
        "gen"=>$genre,
        "password"=>$md5Pass
      ];
      $usuarios[]=$usuario;
      /*
      Si se quisiese que el JSON tuviese un formato similar al que hicimos en el ejercicio de las categorias,
      debemos cambiar la linea anterior por:
      $usuarios["usuarios"][]=$usuario;
      Se debe cambiar esto tambien en el foreach que recorre el array de usuarios para confirmar que el
      nombre de usuario no esta en uso.
      */
      $usuariosJson = json_encode($usuarios,JSON_PRETTY_PRINT);
      file_put_contents('usuarios.json',$usuariosJson);
      header("Location:felicitaciones.php");
      exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Contact us</title>
</head>
<body>

    <div id='fg_membersite'>
        <form id='register' action='' method='post'>
            <fieldset >
                <legend>Registrate</legend>

                <input type='hidden' name='submitted' id='submitted' value='1'/>

                <div class='short_explanation'>* campos requeridos</div>


                <div><span class='error'></span></div>
                <div class='container'>
                    <label for='name' >Nombre completo: </label>
                    <!--
                    Colocamos sentencias if, evaluando si ocurrio un error de validacion en cada campo del formulario.
                    Si el dato enviado no pasa la validación, se muestra un error al lado del campo.
                  -->
                    <?php if ($emptyNameError): ?>
                      <span>El campo de nombre debe tener mas de 0 y menos de 15 caracteres</span>
                    <?php endif; ?>
                    <br/>
                    <input type='text' name='name' id='name' value='<?= $name ?>' maxlength="50" /><br/>
                    <span id='register_name_errorloc' class='error'></span>
                </div>
                <div class='container'>
                    <label for='email' >Email:</label>
                    <?php if ($invalidEmailError): ?>
                      <span>El email no cumple el formato;</span>
                    <?php endif; ?>
                    <br/>
                    <input type='text' name='email' id='email' value='<?= $email?>' maxlength="50" /><br/>
                    <span id='register_email_errorloc' class='error'></span>
                </div>
                <div class='container'>
                    <label for='username' >Nombre de usuario*:</label>
                    <?php if ($emptyUsernameError): ?>
                      <span>El campo de usuario debe tener mas de 0 y menos de 15 caracteres</span>
                    <?php endif; ?>
                    <br/>
                    <input type='text' name='username' id='username' value='<?= $username?>' maxlength="50" /><br/>
                    <span id='register_username_errorloc' class='error'></span>
                </div>
                <div class="container">
                  <label for="intereses[]">Intereses:</label><br>
                  <!--
                  Este checkbox se genera a partir de la informacion en el archivo categorias.json.
                  Si el formulario se envia con errores de validacion, la estrategia para recuperar los
                  campos de un checkbox es la siguiente:
                  1 - Se almacena en un array cuales fueron los checkbox seleccionados.
                  2 - Al momento de volver a generar cada checkbox, observamos si el valor (value) de ese checkbox se encuentra en el array de datos enviados previamente.
                      Para eso utilizamos un if y la funcion in_array.
                  3 - Si ese dato se envio anteriormente, agregamos el atributo "checked" a ese checkbox.
                      Este atributo le indica al navegador que esa checkbox se inicializa tildada
                -->
                  <?php foreach ($categorias['categorias'] as  $categoria): ?>
                    <input type="checkbox" name="intereses[]" value="<?= $categoria['id']?>" <?= in_array($categoria['id'],$intereses) ? "checked": "" ?>><?= $categoria['nombre'] ?><br>
                  <?php endforeach; ?>
                </div>
                <div class="container">
                  <label for="edad">Edad:</label>
                  <input type="number" name="edad" value="<?= $edad?>">
                  <?php if ($notNumericAgeError): ?>
                    <span>El campo de edad debe ser un numero no vacio</span>
                  <?php endif; ?>
                </div>
                <div class="container">
                  <label for="country">Pais</label>
                  <select class="" name="country">
                    <!--
                    Para los select, la estrategia es similar a la utilizada con los checkbox.
                    A diferencia del checkbox, el select envia un solo dato. Por esto, en lugar de usar la funcion in_array,
                    guardamos el dato enviado en la variable paises enviados. Si el valor almacenado en esta variable es igual al valor
                    que envia el option, le agregamos el atributo "selected".
                  -->
                  <?php foreach ($array_paises as $llave => $pais):?>
                    <option value="<?=$llave;?>" <?= ($pais_enviado == $llave) ? "selected" : "" ; ?>><?=$pais;?></option>
                  <?php endforeach; ?>
                  </select>
                </div>
                <div class="container">
                  <label for="gen">Genero : </label>
                  <?php if ($emptyGenreError): ?>
                    <span>Debe indicar un genero;</span>
                  <?php endif; ?>
                  <br>
                    <input type="radio" name="gen" value="v"<?= ($genre == "v")?"checked":"" ?>>varon<br>
                    <input type="radio" name="gen" value="m"<?= ($genre == "m")?"checked":"" ?>>mujer<br>
                    <input type="radio" name="gen" value="o"<?= ($genre == "o")?"checked":"" ?>>otre<br>
                </div>
                <div class='container' style='height:80px;'>
                    <label for='password' >Contraseña*:</label>
                    <?php if ($notSetPasswordError): ?>
                      <span>Debe indicar una contraseña;</span>
                    <?php endif; ?>
                    <br/>
                    <div class='pwdwidgetdiv' id='thepwddiv' ></div>
                    <input type='password' name='password' id='password' maxlength="50" />
                    <div id='register_password_errorloc' class='error' style='clear:both'></div>
                    <?php if (!isset($_GET['version_corta'])): ?>
                        <label for='password' >Confirmar contraseña*:</label>
                        <?php if ($confirmPasswordError): ?>
                          <span>La contraseña debe coincidir con la elegida en el campo de arriba;</span>
                        <?php endif; ?><br/>
                        <div class='pwdwidgetdiv' id='theconfirmpwddiv' ></div>
                        <input type='password' name='confirm_password' id='confirm_password' maxlength="50" />
                        <div id='confirm_password_errorloc' class='error' style='clear:both'></div>
                    <?php endif; ?>
                </div>
                <div class='container'>
                    <input type='submit' name='submit' value='Enviar' />
                </div>

            </fieldset>
        </form>

    </body>
</html>
