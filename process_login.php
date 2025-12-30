<?php
require_once 'config/database.php';

header('Content-Type: application/json');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener datos del formulario
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
    exit;
}

$conn = conectarDB();

// Buscar usuario por nusuario o teléfono
$sql = "SELECT * FROM usuarios 
        WHERE (nusuario = ? OR telefono = ?) 
        AND activo = 1 
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    
    // Verificar contraseña
    if (password_verify($password, $usuario['password_hash'])) {
        // Buscar información adicional según el tipo de usuario
        $usuario_completo = $usuario;
        
        if ($usuario['tipo'] === 'conductor') {
            $sql_conductor = "SELECT c.*, v.* 
                            FROM conductores c 
                            LEFT JOIN vehiculos v ON c.vehiculo_actual_id = v.id 
                            WHERE c.usuario_id = ?";
            $stmt_conductor = $conn->prepare($sql_conductor);
            $stmt_conductor->bind_param("i", $usuario['id']);
            $stmt_conductor->execute();
            $conductor_info = $stmt_conductor->get_result()->fetch_assoc();
            
            if ($conductor_info) {
                $usuario_completo = array_merge($usuario_completo, $conductor_info);
            }
        }
        
        // Iniciar sesión
        $_SESSION['usuario'] = $usuario_completo;
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_type'] = $usuario['tipo'];
        $_SESSION['nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'user_type' => $usuario['tipo']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}

$stmt->close();
$conn->close();
?>