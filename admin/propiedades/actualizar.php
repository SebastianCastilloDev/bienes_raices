<?php 

require '../../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header('Location/admin');
    }

    //Base de datos
    require '../../includes/config/database.php';
    $db = conectarDB();

    //Obtener los datos de la propiedad
    $consulta = "SELECT * FROM propiedades WHERE id = ${id}";
    $resultado = mysqli_query($db,$consulta);
    $propiedad = mysqli_fetch_assoc($resultado);

    //consula para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);


    //Arreglo con mensajes de errores

    $errores = [];

    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones = $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamientos = $propiedad['estacionamientos'];
    $vendedorId = $propiedad['vendedores_id'];
    $imagenPropiedad = $propiedad['imagen'];

    //Ejecutar el codigo despues de enviar el formulario
    if($_SERVER['REQUEST_METHOD']==='POST'){


        // echo "<pre>";
        // var_dump($_POST);
        // echo "/<pre>";

        // echo "<pre>";
        // var_dump($_FILES);
        // echo "/<pre>";

        // exit;

        $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
        $precio = mysqli_real_escape_string($db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
        $wc = mysqli_real_escape_string($db, $_POST['wc']);
        $estacionamientos = mysqli_real_escape_string($db, $_POST['estacionamientos']);
        $vendedorId = mysqli_real_escape_string($db, $_POST['vendedor']);
        $creado = date('Y/m/d');

        //asignar files a una variable
        $imagen = $_FILES['imagen'];
        
        

        if(!$titulo){
            $errores[] = "Debes añadir un titulo";
        }
        if(!$precio){
            $errores[] = "Debes añadir un precio";
        }
        if(strlen($descripcion)<50){
            $errores[] = "La descripcion es obligatoria y debe tener al menos 50 caracteres";
        }
        if(!$habitaciones){
            $errores[] = "Debes añadir un habitaciones";
        }
        if(!$wc){
            $errores[] = "Debes añadir un wc";
        }
        if(!$estacionamientos){
            $errores[] = "Debes añadir un estacionamientos";
        }
        if(!$vendedorId){
            $errores[] = "Debes añadir un vendedorId";
        }


        //validar por tamaño (100kb maximo)
        $medida = 1000*1000;
        if($imagen['size']>$medida){
            $errores[] = "la imagen es muy pesada";
        }


        // echo "<pre>";
        // var_dump($errores);
        // echo "/<pre>";

        // exit;

        //Revisar que el arreglo de erroes este vacio
        if(empty($errores)){
            
            // Subida de archivos
            
            
            //crear una carpeta
            $carpetaImagenes = '../../imagenes/';
            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            $nombreImagen = '';
            
            if($imagen['name']){
                unlink($carpetaImagenes.$propiedad['imagen']);
                //generar nombre unico
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
    
                //subir la imagen
                move_uploaded_file($imagen['tmp_name'],$carpetaImagenes . $nombreImagen);
            } else {
                $nombreImagen = $propiedad['imagen'];
            }


            //Insertar en la bd
            $query = "UPDATE propiedades SET titulo = '${titulo}', precio = ${precio}, imagen = '${nombreImagen}', descripcion = '${descripcion}', habitaciones = ${habitaciones}, wc = ${wc}, estacionamientos = ${estacionamientos}, vendedores_id = ${vendedorId} WHERE id = ${id}";
            
            // echo $query;

            $resultado = mysqli_query($db, $query);
    
            if($resultado){

                //redirecionar al usuario
                header('Location: /admin?resultado=2');
            }
        }
    }

    incluirTemplate('header')
?>

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error):  ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        
        <?php endforeach;  ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo  de la propiedad" value="<?php echo $titulo; ?>">
                <label for="precio">precio:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio  de la propiedad" value="<?php echo $precio; ?>">
                <label for="imagen">imagen:</label>
                <img src="/imagenes/<?php echo $imagenPropiedad; ?>" alt="" class="imagen-small">
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png"  value="<?php echo $imagen; ?>">
                <label for="descripcion">descripcion:</label>
                <textarea id="descripcion" name="descripcion" placeholder="descripcion  de la propiedad"><?php echo $descripcion; ?></textarea>
            </fieldset>
            <fieldset>
                <legend>Informacion de la propiedad</legend>
                <label for="habitaciones">habitaciones:</label>
                <input type="number" min="1" max="9" id="habitaciones" name="habitaciones" placeholder="habitaciones  de la propiedad" value="<?php echo $habitaciones; ?>">
                <label for="wc">wc:</label>
                <input type="number" min="1" max="9" id="wc" name="wc" placeholder="wc  de la propiedad" value="<?php echo $wc; ?>">
                <label for="estacionamientos">estacionamientos:</label>
                <input type="number" min="1" max="9" id="estacionamientos" name="estacionamientos" placeholder="estacionamientos  de la propiedad" value="<?php echo $estacionamientos; ?>">
            </fieldset>
            <fieldset>
                <legend>Vendedor</legend>
                <select name="vendedor" id="vendedor">
                    <option value="">Seleccione</option>

                    <?php while($vendedor = mysqli_fetch_assoc($resultado)): ?>
                        <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre']." ".$vendedor['apellido']; ?></option>
                    
                    <?php endwhile; ?>
                </select>
            </fieldset>
            <input type="submit" value="Actualizar propiedad" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer')
?>