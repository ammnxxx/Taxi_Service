<?php
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

// Iniciar sesión
session_start();
?>