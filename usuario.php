<?php 

//importar la base de datos
require 'includes/config/database.php';
$db = conectarDB();


//Crear un email y password
$email = "correo@correo.com";
$password = "123456";

$passwordHash = password_hash($password,PASSWORD_BCRYPT);
 

//Qeury para usuario
$query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}');";

//Agregar a la bd
mysqli_query($db, $query);