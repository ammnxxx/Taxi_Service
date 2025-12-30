 
<?php 
require_once '../config/database.php'; 
$conn=conectarDB();
?>

<link href="assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<div class="card">
    <div class="card-body">
        <table id="fixed-header-datatable" class="table table-striped dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombres y Apellidos</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Estado</th>
                    <th>Operacion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Consulta limpia sin caracteres invisibles
                    $sql = "SELECT id, nombre, apellido, email, telefono, foto_url, verificado, activo 
                            FROM usuarios 
                            WHERE tipo = 'admin' 
                            ORDER BY id DESC";
                    
                    if (!isset($conn)) {
                        throw new Exception("La variable de conexión \$conn no está definida.");
                    }

                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while ($usuario = $result->fetch_assoc()) {
                            $nombre = htmlspecialchars($usuario['nombre'] ?? '');
                            $apellido = htmlspecialchars($usuario['apellido'] ?? '');
                            $fullName = trim($nombre . ' ' . $apellido);
                            
                            // Iniciales
                            $user_iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellido, 0, 1));
                            
                            // Estado
                            $estado = ($usuario['activo'] == 1) ? 'Activo' : 'Inactivo';
                            $estado_class = ($usuario['activo'] == 1) ? 'success' : 'danger';
                            
                            // Foto
                            if (!empty($usuario['foto_url'])) {
                                $foto = '<img src="' . htmlspecialchars($usuario['foto_url']) . '" class="img-fluid rounded-circle thumb-xl" style="width:40px; height:40px; object-fit:cover;">';
                            } else {
                                $foto = '<div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:40px; height:40px;">' . $user_iniciales . '</div>';
                            }
                            ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td><?php echo $foto; ?></td>
                                <td><?php echo $fullName; ?></td>
                                <td><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?></td>
                                <td><span class="badge bg-<?php echo $estado_class; ?>"><?php echo $estado; ?></span></td>
                                <td>
                                    
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">No se encontraron administradores.</td></tr>';
                    }
                } catch (Exception $e) {
                    echo '<tr><td colspan="7" class="text-center text-danger">Error: ' . $e->getMessage() . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div> </div>

		<!-- Datatables js -->
        <script src="assets/libs/datatables.net/js/dataTables.min.js"></script>

        <!-- dataTables.bootstrap5 -->
        <script src="assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>

        <!-- buttons.colVis -->
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>

        <!-- buttons.bootstrap5 -->
        <script src="assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>

        <!-- dataTables.keyTable -->
        <script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-keytable-bs5/js/keyTable.bootstrap5.min.js"></script>

        <!-- dataTable.responsive -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>

        <!-- dataTables.select -->
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="assets/libs/datatables.net-select-bs5/js/select.bootstrap5.min.js"></script>

        <!-- Datatable Demo App Js -->
        <script src="assets/js/pages/datatable.init.js"></script>