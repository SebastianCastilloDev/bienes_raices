<?php 

    require '../../includes/app.php';

    use App\Propiedad;
    use App\Vendedor;
    use Intervention\Image\ImageManagerStatic as Image;
    // $propiedad = new Propiedad;

    estaAutenticado();
    $propiedad = new Propiedad();

    //consulta para obtener todos los vendedores
    $vendedores = Vendedor::all();
    // debuguear($vendedores);
    // debuguear($vendedores);

    //Arreglo con mensakjes de errores
    $errores = Propiedad::getErrores();

    // $creado = date('Y/m/d');

    //Ejecutar el codigo despues de enviar el formulario
    if($_SERVER['REQUEST_METHOD']==='POST'){


        //crea una nueva isntancia
        $propiedad = new Propiedad($_POST['propiedad']);


        //generar nombre unico
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        
        //setear la imagen

        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        
        //validar
        $errores = $propiedad->validar();


        
        //Revisar que el arreglo de erroes este vacio
        if(empty($errores)){
            
            if(!is_dir(CARPETA_IMAGENES)){
                mkdir(CARPETA_IMAGENES);
            }

            //Guarda la imagen en el servidor
            $image->save(CARPETA_IMAGENES.$nombreImagen);
            
            //Guarda en la base de datos
            $propiedad->guardar();
            

        }
    }

    incluirTemplate('header')
?>

    <main class="contenedor seccion">
        <h1>Crear Propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error):  ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        
        <?php endforeach;  ?>

        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            
            <?php include '../../includes/templates/formulario_propiedades.php' ?>

            <input type="submit" value="Crear propiedad" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer')
?>