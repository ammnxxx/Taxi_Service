<?php
require_once 'config/database.php';

header('Content-Type: application/json');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$action = $_POST['action'] ?? '';
$conn = conectarDB();

switch ($action) {
    case 'send_code':
        handleSendCode($conn);
        break;
        
    case 'verify_code':
        handleVerifyCode($conn);
        break;
        
    case 'resend_code':
        handleResendCode($conn);
        break;
        
    case 'reset_password':
        handleResetPassword($conn);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

$conn->close();

function handleSendCode($conn) {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email requerido']);
        return;
    }
    
    // Verificar si el email existe
    $sql = "SELECT id, nombre FROM usuarios WHERE email = ? AND activo = 1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'No existe una cuenta con este email']);
        return;
    }
    
    $user = $result->fetch_assoc();
    
    // Generar código de verificación (6 dígitos)
    $code = sprintf('%06d', mt_rand(0, 999999));
    $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    $token = bin2hex(random_bytes(32));
    
    // Guardar en sesión (en producción usarías una tabla temporal en la BD)
    $_SESSION['recovery_code'] = [
        'email' => $email,
        'code' => $code,
        'token' => $token,
        'expires' => $expires,
        'attempts' => 0
    ];
    
    // En un entorno real, aquí enviarías el código por email
    // mail($email, "Código de recuperación", "Tu código es: $code");
    
    // Para pruebas, mostrar el código en la respuesta
    echo json_encode([
        'success' => true,
        'message' => "Código enviado a $email. Código de prueba: $code",
        'debug_code' => $code // Solo para pruebas, eliminar en producción
    ]);
}

function handleVerifyCode($conn) {
    $email = trim($_POST['email'] ?? '');
    $code = trim($_POST['code'] ?? '');
    
    if (empty($email) || empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        return;
    }
    
    // Verificar el código en sesión
    if (!isset($_SESSION['recovery_code']) || 
        $_SESSION['recovery_code']['email'] !== $email) {
        echo json_encode(['success' => false, 'message' => 'Solicitud no válida']);
        return;
    }
    
    $recoveryData = $_SESSION['recovery_code'];
    
    // Verificar intentos
    if ($recoveryData['attempts'] >= 3) {
        echo json_encode(['success' => false, 'message' => 'Demasiados intentos fallidos']);
        return;
    }
    
    // Verificar expiración
    if (strtotime($recoveryData['expires']) < time()) {
        echo json_encode(['success' => false, 'message' => 'El código ha expirado']);
        return;
    }
    
    // Verificar código
    if ($recoveryData['code'] !== $code) {
        $_SESSION['recovery_code']['attempts']++;
        echo json_encode(['success' => false, 'message' => 'Código incorrecto']);
        return;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Código verificado correctamente',
        'token' => $recoveryData['token']
    ]);
}

function handleResendCode($conn) {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email requerido']);
        return;
    }
    
    // Verificar si el email existe
    $sql = "SELECT id FROM usuarios WHERE email = ? AND activo = 1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'No existe una cuenta con este email']);
        return;
    }
    
    // Generar nuevo código
    $code = sprintf('%06d', mt_rand(0, 999999));
    $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    $token = bin2hex(random_bytes(32));
    
    $_SESSION['recovery_code'] = [
        'email' => $email,
        'code' => $code,
        'token' => $token,
        'expires' => $expires,
        'attempts' => 0
    ];
    
    // En un entorno real, aquí enviarías el código por email
    // mail($email, "Nuevo código de recuperación", "Tu nuevo código es: $code");
    
    echo json_encode([
        'success' => true,
        'message' => 'Nuevo código enviado',
        'debug_code' => $code // Solo para pruebas, eliminar en producción
    ]);
}

function handleResetPassword($conn) {
    $email = trim($_POST['email'] ?? '');
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    
    if (empty($email) || empty($token) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        return;
    }
    
    // Verificar token
    if (!isset($_SESSION['recovery_code']) || 
        $_SESSION['recovery_code']['email'] !== $email ||
        $_SESSION['recovery_code']['token'] !== $token) {
        echo json_encode(['success' => false, 'message' => 'Token no válido']);
        return;
    }
    
    // Verificar expiración
    if (strtotime($_SESSION['recovery_code']['expires']) < time()) {
        echo json_encode(['success' => false, 'message' => 'La solicitud ha expirado']);
        return;
    }
    
    // Actualizar contraseña
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $sql = "UPDATE usuarios SET password_hash = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $passwordHash, $email);
    
    if ($stmt->execute()) {
        // Limpiar datos de recuperación
        unset($_SESSION['recovery_code']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente. Redirigiendo al login...'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña']);
    }
}
?>