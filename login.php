<?php   

//incluye el header
require 'includes/app.php';
$db = conectarDB();

//Autenticar el usuario

$errores = [];
if($_SERVER['REQUEST_METHOD']==='POST'){

    $email = mysqli_real_escape_string($db,filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db,$_POST['password']);

    if(!$email){
        $errores[] = "El email es obligatorio o no es valido";
    }
    if(!$password){
        $errores[] = "El password es obligatorio";
    }

    if(empty($errores)){
        //revisar si el usuario existe

        $query = "SELECT * FROM usuarios WHERE email = '${email}'";
        $resultado = mysqli_query($db, $query);

        if($resultado->num_rows){
            // Revisar si el pass es correcto
            $usuario = mysqli_fetch_assoc($resultado);
            //verificar si el password es corercot o n
            
            $auth = password_verify($password,$usuario['password']);

            if($auth){  
                session_start();

                //Llenar el arreglo de la sesion
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;

                header('Location: /admin');
            } else {
                $errores[]="El password es incorrecto";
            }
        }else{
            $errores[]="El usuario no existe";
        }
    }
}



    incluirTemplate('header')
?>


    <main class="contenedor seccion contenido-centrado">

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <h1>Iniciar Sesion</h1>
        <form class="formulario" method="POST">
        <fieldset>
                <legend>Email y Password</legend>


                <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Tu Email" id="email" >

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu password" id="password" >

                
            </fieldset>

            <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
        </form>
    </main>
<?php 
    incluirTemplate('footer')
?>