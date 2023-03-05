<?php 
    require '../../includes/app.php';

    use App\Vendedor;

    estaAutenticado();

    //validar que se aun id valido
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id){
        header('Location: /admin');
    }

    //obtener el arreglo del vendedor de la bd
    $vendedor = Vendedor::find($id);

    //Arreglo con mensakjes de errores
    $errores = Vendedor::getErrores();

    if($_SERVER['REQUEST_METHOD']==='POST'){
        //asignnar los valores

        $args = $_POST['vendedor'];
        $vendedor->sincronizar($args);

        //7validacion
        $errores = $vendedor->validar();

        if(empty($errores)){
            $vendedor->guardar();
        }
    }
    incluirTemplate('header')
?>

    <main class="contenedor seccion">
        <h1>Actualizar Vendedor</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error):  ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
        
        <?php endforeach;  ?>

        <form class="formulario" method="POST">
            
            <?php include '../../includes/templates/formulario_vendedores.php' ?>

            <input type="submit" value="Guardar Vendedor" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer')
?>