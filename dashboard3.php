<?php
session_start();
require_once 'config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$userType = $_SESSION['user_type'] ?? 'pasajero';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Taxi Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --dark-color: #2d3748;
            --light-color: #f7fafc;
            --success-color: #48bb78;
            --warning-color: #ed8936;
            --danger-color: #f56565;
            --sidebar-width: 250px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .sidebar-header h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .user-profile {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin-bottom: 15px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: var(--primary-color);
        }
        
        .user-profile h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .user-profile p {
            font-size: 12px;
            opacity: 0.8;
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-block;
            text-transform: capitalize;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }
        
        .menu-item i {
            width: 20px;
            margin-right: 15px;
            font-size: 18px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }
        
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: var(--dark-color);
            font-size: 24px;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--dark-color);
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 50%;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        /* Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 24px;
            color: white;
        }
        
        .icon-rides {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        .icon-earnings {
            background: linear-gradient(135deg, var(--success-color) 0%, #2f855a 100%);
        }
        
        .icon-rating {
            background: linear-gradient(135deg, var(--warning-color) 0%, #c05621 100%);
        }
        
        .icon-vehicle {
            background: linear-gradient(135deg, #4299e1 0%, #2b6cb0 100%);
        }
        
        .stat-info h3 {
            font-size: 28px;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: #718096;
            font-size: 14px;
        }
        
        /* Sections */
        .dashboard-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-header h2 {
            color: var(--dark-color);
            font-size: 20px;
        }
        
        .section-header a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .section-header a:hover {
            text-decoration: underline;
        }
        
        /* Recent Activity */
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }
        
        .activity-content h4 {
            font-size: 16px;
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .activity-content p {
            font-size: 13px;
            color: #718096;
        }
        
        .activity-time {
            margin-left: auto;
            font-size: 12px;
            color: #a0aec0;
        }
        
        /* User Info */
        .user-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
        }
        
        .info-item label {
            display: block;
            font-size: 12px;
            color: #718096;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-item span {
            font-size: 16px;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--dark-color);
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .user-info-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
        
        /* Animations */
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        /* Driver Specific Styles */
        .driver-status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .status-active { background: var(--success-color); }
        .status-inactive { background: var(--danger-color); }
        .status-busy { background: var(--warning-color); }
        
        .toggle-status-btn {
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .toggle-status-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .vehicle-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .vehicle-info p {
            font-size: 12px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Taxi Services</h2>
                <p>Sistema de gestión</p>
            </div>
            
            <div class="user-profile">
                <div class="profile-img">
                    <?php if(isset($usuario['foto_url']) && !empty($usuario['foto_url'])): ?>
                        <img src="<?php echo htmlspecialchars($usuario['foto_url']); ?>" alt="Foto perfil">
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </div>
                <h3><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></h3>
                <p><?php echo htmlspecialchars($usuario['tipo']); ?></p>
                
                <?php if($userType === 'conductor'): ?>
                    <div class="driver-status">
                        <span class="status-indicator status-<?php echo $usuario['modo_trabajo'] ?? 'inactivo'; ?>"></span>
                        <span><?php echo ucfirst($usuario['modo_trabajo'] ?? 'inactivo'); ?></span>
                        <button class="toggle-status-btn" id="toggleStatusBtn">
                            <?php echo ($usuario['modo_trabajo'] ?? 'inactivo') === 'activo' ? 'Desactivar' : 'Activar'; ?>
                        </button>
                    </div>
                    
                    <?php if(isset($usuario['marca']) && isset($usuario['modelo'])): ?>
                        <div class="vehicle-info">
                            <p><i class="fas fa-car"></i> <?php echo htmlspecialchars($usuario['marca'] . ' ' . $usuario['modelo']); ?></p>
                            <p><i class="fas fa-tag"></i> <?php echo htmlspecialchars($usuario['placa'] ?? 'Sin placa'); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="sidebar-menu">
                <a href="#" class="menu-item active" data-section="overview">
                    <i class="fas fa-tachometer-alt"></i> Resumen
                </a>
                
                <?php if($userType === 'pasajero'): ?>
                    <a href="#" class="menu-item" data-section="request-ride">
                        <i class="fas fa-taxi"></i> Solicitar Viaje
                    </a>
                    <a href="#" class="menu-item" data-section="my-rides">
                        <i class="fas fa-history"></i> Mis Viajes
                    </a>
                    <a href="#" class="menu-item" data-section="payment-methods">
                        <i class="fas fa-credit-card"></i> Métodos de Pago
                    </a>
                <?php endif; ?>
                
                <?php if($userType === 'conductor'): ?>
                    <a href="#" class="menu-item" data-section="available-rides">
                        <i class="fas fa-road"></i> Viajes Disponibles
                    </a>
                    <a href="#" class="menu-item" data-section="active-rides">
                        <i class="fas fa-map-marker-alt"></i> Viaje Activo
                    </a>
                    <a href="#" class="menu-item" data-section="earnings">
                        <i class="fas fa-money-bill-wave"></i> Ganancias
                    </a>
                    <a href="#" class="menu-item" data-section="vehicle">
                        <i class="fas fa-car"></i> Mi Vehículo
                    </a>
                <?php endif; ?>
                
                <?php if($userType === 'admin' || $userType === 'operador'): ?>
                    <a href="#" class="menu-item" data-section="manage-users">
                        <i class="fas fa-users"></i> Gestionar Usuarios
                    </a>
                    <a href="#" class="menu-item" data-section="all-rides">
                        <i class="fas fa-list"></i> Todos los Viajes
                    </a>
                    <a href="#" class="menu-item" data-section="rates">
                        <i class="fas fa-chart-line"></i> Tasas y Tarifas
                    </a>
                    <a href="#" class="menu-item" data-section="reports">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </a>
                <?php endif; ?>
                
                <a href="#" class="menu-item" data-section="profile">
                    <i class="fas fa-user-cog"></i> Mi Perfil
                </a>
                <a href="#" class="menu-item" data-section="settings">
                    <i class="fas fa-cog"></i> Configuración
                </a>
                <a href="#" class="menu-item" data-section="help">
                    <i class="fas fa-question-circle"></i> Ayuda
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div>
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Dashboard</h1>
                    <p id="welcomeMessage">Bienvenido al sistema de Taxi Services</p>
                </div>
                
                <div class="header-actions">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationCount">3</span>
                    </button>
                    <button class="logout-btn" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-cards" id="statsCards">
                <!-- Las tarjetas se cargarán dinámicamente según el tipo de usuario -->
            </div>
            
            <!-- Dashboard Content -->
            <div id="dashboardContent">
                <!-- El contenido se cargará dinámicamente según la sección seleccionada -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></h2>
                    </div>
                    <p>Selecciona una opción del menú para comenzar.</p>
                </div>
            </div>
            
            <!-- User Information Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Información de la Cuenta</h2>
                </div>
                <div class="user-info-grid">
                    <div class="info-item">
                        <label>Nombre Completo</label>
                        <span><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <span><?php echo htmlspecialchars($usuario['email'] ?? 'No especificado'); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Teléfono</label>
                        <span><?php echo htmlspecialchars($usuario['telefono']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Tipo de Usuario</label>
                        <span><?php echo htmlspecialchars(ucfirst($usuario['tipo'])); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Fecha de Registro</label>
                        <span><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Estado</label>
                        <span class="<?php echo $usuario['activo'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo $usuario['activo'] == 1 ? 'Activo' : 'Inactivo'; ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Actividad Reciente</h2>
                    <a href="#" id="viewAllActivity">Ver todo</a>
                </div>
                <ul class="activity-list" id="recentActivity">
                    <!-- La actividad reciente se cargará dinámicamente -->
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function() {
        // Variables globales
        const userType = '<?php echo $userType; ?>';
        const userId = '<?php echo $_SESSION['user_id']; ?>';
        
        // Toggle menu en móviles
        $('#menuToggle').click(function() {
            $('#sidebar').toggleClass('active');
        });
        
        // Cerrar menú al hacer clic fuera en móviles
        $(document).click(function(e) {
            if ($(window).width() <= 768) {
                if (!$(e.target).closest('#sidebar').length && 
                    !$(e.target).closest('#menuToggle').length && 
                    $('#sidebar').hasClass('active')) {
                    $('#sidebar').removeClass('active');
                }
            }
        });
        
        // Cambiar sección del menú
        $('.menu-item').click(function(e) {
            e.preventDefault();
            
            // Actualizar clase activa
            $('.menu-item').removeClass('active');
            $(this).addClass('active');
            
            const section = $(this).data('section');
            loadSection(section);
        });
        
        // Cerrar sesión
        $('#logoutBtn').click(function() {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                $.ajax({
                    url: 'logout.php',
                    type: 'GET',
                    success: function() {
                        window.location.href = 'index.php';
                    }
                });
            }
        });
        
        // Toggle estado del conductor
        if (userType === 'conductor') {
            $('#toggleStatusBtn').click(function() {
                const currentStatus = '<?php echo $usuario["modo_trabajo"] ?? "inactivo"; ?>';
                const newStatus = currentStatus === 'activo' ? 'inactivo' : 'activo';
                
                $.ajax({
                    url: 'update_driver_status.php',
                    type: 'POST',
                    data: { status: newStatus },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Actualizar UI
                            $('.status-indicator').removeClass().addClass('status-indicator status-' + newStatus);
                            $('.driver-status span').eq(1).text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                            $('#toggleStatusBtn').text(newStatus === 'activo' ? 'Desactivar' : 'Activar');
                            showNotification('Estado actualizado correctamente', 'success');
                        } else {
                            showNotification(response.message, 'error');
                        }
                    },
                    error: function() {
                        showNotification('Error de conexión', 'error');
                    }
                });
            });
        }
        
        // Cargar estadísticas iniciales
        loadStats();
        loadRecentActivity();
        
        // Notificaciones
        loadNotifications();
        
        $('#notificationBtn').click(function() {
            showNotificationsModal();
        });
        
        // Funciones
        function loadSection(section) {
            $.ajax({
                url: 'load_section.php',
                type: 'POST',
                data: { section: section, user_type: userType },
                dataType: 'html',
                beforeSend: function() {
                    $('#dashboardContent').html('<div class="loading">Cargando...</div>');
                },
                success: function(response) {
                    $('#dashboardContent').html(response);
                    updateWelcomeMessage(section);
                },
                error: function() {
                    $('#dashboardContent').html('<div class="error">Error al cargar la sección</div>');
                }
            });
        }
        
        function loadStats() {
            $.ajax({
                url: 'load_stats.php',
                type: 'POST',
                data: { user_type: userType, user_id: userId },
                dataType: 'json',
                success: function(stats) {
                    renderStatsCards(stats);
                },
                error: function() {
                    $('#statsCards').html('<div class="error">Error al cargar estadísticas</div>');
                }
            });
        }
        
        function renderStatsCards(stats) {
            let html = '';
            
            if (userType === 'pasajero') {
                html = `
                    <div class="stat-card">
                        <div class="stat-icon icon-rides">
                            <i class="fas fa-taxi"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.total_rides || 0}</h3>
                            <p>Viajes Realizados</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-earnings">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-info">
                            <h3>$${stats.total_spent || '0.00'}</h3>
                            <p>Total Gastado</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-rating">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.avg_rating || '0.0'}</h3>
                            <p>Calificación Promedio</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-vehicle">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.active_rides || 0}</h3>
                            <p>Viajes Activos</p>
                        </div>
                    </div>
                `;
            } else if (userType === 'conductor') {
                html = `
                    <div class="stat-card">
                        <div class="stat-icon icon-rides">
                            <i class="fas fa-road"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.completed_rides || 0}</h3>
                            <p>Viajes Completados</p>
                        </div>
                    </div>
                    <div class="stat-card pulse">
                        <div class="stat-icon icon-earnings">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-info">
                            <h3>$${stats.total_earnings || '0.00'}</h3>
                            <p>Ganancias Totales</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-rating">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.avg_rating || '0.0'}/5</h3>
                            <p>Calificación</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-vehicle">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.available_rides || 0}</h3>
                            <p>Viajes Disponibles</p>
                        </div>
                    </div>
                `;
            } else if (userType === 'admin' || userType === 'operador') {
                html = `
                    <div class="stat-card">
                        <div class="stat-icon icon-rides">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.total_users || 0}</h3>
                            <p>Usuarios Totales</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-earnings">
                            <i class="fas fa-taxi"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.active_rides || 0}</h3>
                            <p>Viajes Activos</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-rating">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3>$${stats.daily_revenue || '0.00'}</h3>
                            <p>Ingresos Hoy</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-vehicle">
                            <i class="fas fa-car-side"></i>
                        </div>
                        <div class="stat-info">
                            <h3>${stats.active_drivers || 0}</h3>
                            <p>Conductores Activos</p>
                        </div>
                    </div>
                `;
            }
            
            $('#statsCards').html(html);
        }
        
        function loadRecentActivity() {
            $.ajax({
                url: 'load_activity.php',
                type: 'POST',
                data: { user_type: userType, user_id: userId },
                dataType: 'json',
                success: function(activities) {
                    renderRecentActivity(activities);
                }
            });
        }
        
        function renderRecentActivity(activities) {
            let html = '';
            
            if (activities.length === 0) {
                html = '<li class="activity-item"><p>No hay actividad reciente</p></li>';
            } else {
                activities.forEach(function(activity) {
                    let icon = '';
                    let iconClass = '';
                    
                    switch(activity.type) {
                        case 'ride':
                            icon = 'fa-taxi';
                            iconClass = 'bg-primary';
                            break;
                        case 'payment':
                            icon = 'fa-credit-card';
                            iconClass = 'bg-success';
                            break;
                        case 'rating':
                            icon = 'fa-star';
                            iconClass = 'bg-warning';
                            break;
                        case 'update':
                            icon = 'fa-sync-alt';
                            iconClass = 'bg-info';
                            break;
                        default:
                            icon = 'fa-bell';
                            iconClass = 'bg-secondary';
                    }
                    
                    html += `
                        <li class="activity-item">
                            <div class="activity-icon ${iconClass}">
                                <i class="fas ${icon}"></i>
                            </div>
                            <div class="activity-content">
                                <h4>${activity.title}</h4>
                                <p>${activity.description}</p>
                            </div>
                            <div class="activity-time">${activity.time}</div>
                        </li>
                    `;
                });
            }
            
            $('#recentActivity').html(html);
        }
        
        function loadNotifications() {
            // Simular carga de notificaciones
            const notifications = [
                { id: 1, title: 'Nuevo viaje disponible', message: 'Tienes un nuevo viaje cercano', read: false },
                { id: 2, title: 'Pago recibido', message: 'Se ha procesado tu pago de $15.50', read: false },
                { id: 3, title: 'Calificación recibida', message: 'Un pasajero te calificó con 5 estrellas', read: false }
            ];
            
            $('#notificationCount').text(notifications.filter(n => !n.read).length);
        }
        
        function showNotificationsModal() {
            // Implementar modal de notificaciones
            alert('Funcionalidad de notificaciones - En desarrollo');
        }
        
        function updateWelcomeMessage(section) {
            const messages = {
                'overview': 'Bienvenido al panel de control',
                'request-ride': 'Solicita un viaje ahora',
                'my-rides': 'Historial de tus viajes',
                'available-rides': 'Viajes disponibles cerca de ti',
                'active-rides': 'Sigue tu viaje en tiempo real',
                'earnings': 'Consulta tus ganancias',
                'profile': 'Administra tu perfil',
                'settings': 'Configura tus preferencias'
            };
            
            $('#welcomeMessage').text(messages[section] || 'Bienvenido al sistema de Taxi Services');
        }
        
        function showNotification(message, type) {
            // Crear notificación temporal
            const notification = $(`
                <div class="notification ${type}">
                    ${message}
                </div>
            `);
            
            $('body').append(notification);
            
            notification.css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                padding: '15px 20px',
                background: type === 'success' ? '#48bb78' : '#f56565',
                color: 'white',
                borderRadius: '5px',
                zIndex: '9999',
                boxShadow: '0 2px 10px rgba(0,0,0,0.2)'
            });
            
            setTimeout(() => {
                notification.fadeOut(() => notification.remove());
            }, 3000);
        }
        
        // Auto-refresh para conductores
        if (userType === 'conductor') {
            setInterval(function() {
                loadStats();
                loadRecentActivity();
            }, 30000); // Cada 30 segundos
        }
        
        // Ver toda la actividad
        $('#viewAllActivity').click(function(e) {
            e.preventDefault();
            loadSection('my-rides');
        });
    });
    </script>
</body>
</html>