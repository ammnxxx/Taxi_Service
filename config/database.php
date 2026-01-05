<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Configuración de conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Cambiar según tu configuración
define('DB_PASS', 'root'); // Cambiar según tu configuración
define('DB_NAME', 'taxi_services');
define('DB_CHARSET', 'utf8mb4');

// Función para conectar a la base de datos
function conectarDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset(DB_CHARSET);
    return $conn;
}
function UserType($tipo){
    $text_usuarios[0]="Desarrollador";
    $text_usuarios[1]="Administrador";
    $text_usuarios[2]="Operador";
    $text_usuarios[3]="Conductor";
    $text_usuarios[4]="Cliente";
    return  $text_usuarios[$tipo];
}
    


if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>