<?php
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode([]);
    exit;
}

$user_type = $_POST['user_type'] ?? 'pasajero';
$user_id = $_POST['user_id'] ?? 0;
$conn = conectarDB();

$activities = [];

switch ($user_type) {
    case 'pasajero':
        // Últimos viajes
        $sql = "SELECT 'ride' as type, 
                       'Viaje ' || estado as title,
                       CONCAT('De ', origen_direccion, ' a ', destino_direccion) as description,
                       DATE_FORMAT(fecha_solicitud, '%H:%i') as time
                FROM viajes 
                WHERE pasajero_id = ? 
                ORDER BY fecha_solicitud DESC 
                LIMIT 5";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }
        $stmt->close();
        break;
        
    case 'conductor':
        // Obtener ID del conductor
        $sql = "SELECT id FROM conductores WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $conductor_id = $stmt->get_result()->fetch_assoc()['id'] ?? 0;
        $stmt->close();
        
        if ($conductor_id) {
            // Últimos viajes como conductor
            $sql = "SELECT 'ride' as type, 
                           'Viaje completado' as title,
                           CONCAT('Ganaste $', ROUND(tarifa_usd * 0.8, 2)) as description,
                           DATE_FORMAT(fecha_finalizacion, '%H:%i') as time
                    FROM viajes 
                    WHERE conductor_id = ? AND estado = 'completado'
                    ORDER BY fecha_finalizacion DESC 
                    LIMIT 5";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $conductor_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
            $stmt->close();
        }
        break;
        
    case 'admin':
    case 'operador':
        // Actividad reciente del sistema
        $activities = [
            ['type' => 'update', 'title' => 'Sistema actualizado', 'description' => 'Nueva versión implementada', 'time' => '10:30'],
            ['type' => 'ride', 'title' => 'Viaje sospechoso', 'description' => 'Viaje #456 marcado para revisión', 'time' => '09:45'],
            ['type' => 'payment', 'title' => 'Pago procesado', 'description' => 'Pago de $120.50 procesado', 'time' => '09:15'],
            ['type' => 'rating', 'title' => 'Nueva calificación', 'description' => 'Usuario #789 calificado con 5 estrellas', 'time' => '08:30']
        ];
        break;
}

// Si no hay actividades, agregar una por defecto
if (empty($activities)) {
    $activities[] = [
        'type' => 'update',
        'title' => 'Bienvenido',
        'description' => 'Comienza a usar el sistema',
        'time' => date('H:i')
    ];
}

$conn->close();
echo json_encode($activities);
?>