<?php 
require_once '../config/database.php'; 
$conn=conectarDB();


$op = '4';
if(isset($_POST['op'])) 
    if($_POST['op']!='')
        $op = $_POST['op'];
$sql_op="WHERE tipo='$op'";
if($op<3)
    $sql_op="WHERE tipo<3";
?>
<!-- main title -->
<div class="col-12">
    <div class="main__title">
        <h2 style="margin-right:10px">Usuarios /</h2>
        <select class="filter__select" name="sort" id="filter__sort_type_user_list">
			<option value="4"    <?php if($op=='4'){echo 'selected';}?>>Clientes</option>
			<option value="3"   <?php if($op=='3'){echo 'selected';}?>>Condutores</option>
                 
			<option value="2" <?php if(($op<3)){echo 'selected';}?>>Administradores</option>
		</select>
        <!--<span class="main__title-stat">14,452 Total</span>-->
        <div class="main__title-wrap">
            <a href="javascript:cargarPaginaFullCache('paginas/new-user.php','adm')"
                class="main__title-link main__title-link--wrap">Nuevo Usuario</a>
            <!-- search -->
            <form action="#" class="main__title-form">
                <input type="text" placeholder="Buscar" id="buscar">
                <button type="button">
                    <i class="ti ti-search"></i>
                </button>
            </form>
            <!-- end search -->
        </div>
    </div>
</div>
<!-- end main title -->

<!-- items -->
<div class="col-12">
    <div class="catalog catalog--1">
        <table class="catalog__table">
            <thead>
                <tr>
                    <th>FOTO</th>
                    <th>INFORMACION DEL USUARIO</th>
                    <th>TELEFONO / EMAIL</th>
                    <th>DIRECCIÓN</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>

            <tbody>
                <?php
                try {
                    // Consulta limpia sin caracteres invisibles
                   $sql = "SELECT  id,nusuario, nombre, apellido, email, telefono, foto_url, verificado, activo,direccion,ubicacion,fecha_registro,tipo
                            FROM usuarios
                            $sql_op 
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
                            $estado_class = ($usuario['activo'] == 1) ? 'green' : 'red';
                            
                            // Foto
                            if (!empty($usuario['foto_url'])) {
                                $foto = htmlspecialchars($usuario['foto_url']);
                            } else {
                                $foto = 'assets/img/user.svg';
                            }
							if (date('Y-m-d', strtotime($usuario['fecha_registro'])) == date('Y-m-d'))
								$style_NU="background-color:#f9ab002b";
							else
								$style_NU="";
                            ?>
                <tr style="<?php echo $style_NU ?>">
                    <td>
						
                        <div class="catalog__text">
                            <div class="sidebar__user-img" style="<?php if($usuario['tipo']=='admin'){echo 'border:2px solid #f9ab00';}?>;width:80px;height:80px">
                                <a href="#" tittle="Mi Perfil"><img src="<?php echo $foto; ?>" alt=""></a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="catalog__text">
							<a href="#" class="catalog__btn catalog__btn--view" style="margin-right:5px;<?php if($usuario['tipo']!='admin'){echo 'display:none';}?>" title="ADMINISTRADOR" >
								<i class="ti ti-star-filled"></i>
							</a>
							<?php echo $usuario['nusuario']; ?></br>
                            <?php echo $fullName; ?></a></div>
                    </td>
                    <td>
                        <div class="catalog__text"><a
                                href="#"><?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?></br><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></a>
                        </div>
                    </td>

                    <td>
                        <div class="catalog__text">
                            <?php 
											if($usuario['ubicacion']!='' ){
												echo '<a href="javascript:ver_ubicacion(\''.$usuario["ubicacion"].'\')" class="catalog__btn catalog__btn--view" style="margin-right:5px">
														<i class="ti ti-location-pin"></i>
      													</a>';	
											}
											echo $usuario['direccion']; ?>

                        </div>
                    </td>
                    <td>
                        <div class="catalog__text catalog__text--<?php echo $estado_class?>"><?php echo $estado; ?>
                        </div>
                    </td>

                    <td>
                        <div class="catalog__btns">
                            
                            <a href="javascript:cargarPaginaFullCache('paginas/edit-user.php','<?php echo $usuario['id']; ?>,RO')" class="catalog__btn catalog__btn--view">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="javascript:cargarPaginaFullCache('paginas/edit-user.php',<?php echo $usuario['id']; ?>)" class="catalog__btn catalog__btn--edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <?php if(($op<3)){?>
                                <a href="#" class="catalog__btn catalog__btn--view">
                                    <i class="ti ti-car"></i>
                                </a>
                            <?php }?>
                            <button type="button" data-bs-toggle="modal" class="catalog__btn catalog__btn--banned"
                                data-bs-target="#modal-status">
                                <i class="ti ti-lock"></i>
                            </button>
							<button type="button" 
									class="catalog__btn catalog__btn--delete" 
									onclick="prepararEliminar(<?php echo $usuario['id']; ?>)">
								<i class="ti ti-trash"></i>
							</button>
							
                        </div>
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
    </div>
</div>
<!-- end items -->

<!-- paginator 
				<div class="col-12">
					<div class="main__paginator">
						<span class="main__paginator-pages">10 of 169</span>

						<ul class="main__paginator-list">
							<li>
								<a href="#">
									<i class="ti ti-chevron-left"></i>
									<span>Prev</span>
								</a>
							</li>
							<li>
								<a href="#">
									<span>Next</span>
									<i class="ti ti-chevron-right"></i>
								</a>
							</li>
						</ul>

						<ul class="paginator">
							<li class="paginator__item paginator__item--prev">
								<a href="#"><i class="ti ti-chevron-left"></i></a>
							</li>
							<li class="paginator__item"><a href="#">1</a></li>
							<li class="paginator__item paginator__item--active"><a href="#">2</a></li>
							<li class="paginator__item"><a href="#">3</a></li>
							<li class="paginator__item"><a href="#">4</a></li>
							<li class="paginator__item"><span>...</span></li>
							<li class="paginator__item"><a href="#">29</a></li>
							<li class="paginator__item"><a href="#">30</a></li>
							<li class="paginator__item paginator__item--next">
								<a href="#"><i class="ti ti-chevron-right"></i></a>
							</li>
						</ul>
					</div>
				</div> -->
<!-- end paginator -->
<!-- status modal -->
<div class="modal fade" id="modal-status" tabindex="-1" aria-labelledby="modal-status" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal__content">
                <form action="#" class="modal__form">
                    <h4 class="modal__title">Status change</h4>

                    <p class="modal__text">Are you sure about immediately change status?</p>

                    <div class="modal__btns">
                        <button class="modal__btn modal__btn--apply" type="button"><span>Apply</span></button>
                        <button class="modal__btn modal__btn--dismiss" type="button" data-bs-dismiss="modal"
                            aria-label="Close"><span>Dismiss</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end status modal -->

<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modal-delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal__content">
                <form action="#" class="modal__form">
                    <h4 class="modal__title">Eliminar Usuario</h4>

                    <p class="modal__text">¿Estás seguro de que deseas eliminar este Usuario permanentemente?</p>
                    
                    <input type="hidden" id="id-registro-eliminar" value="">

                    <div class="modal__btns">
                        <button id="btn-confirmar-eliminacion" class="modal__btn modal__btn--apply" type="button">
                            <span>Eliminar</span>
                        </button>
                        
                        <button class="modal__btn modal__btn--dismiss" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span>Cancelar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function inicializar_ajax() {}
function prepararEliminar(id) {
    console.log("Función prepararEliminar activada para ID:", id);

    // 1. Asignar el ID al input de prueba
    var inputPrueba = document.getElementById('id-eliminar');
    if (inputPrueba) {
        inputPrueba.value = id;
        console.log("ID puesto en input #buscar");
    }

    // 2. Asignar el ID al campo oculto del modal (el que usamos para el AJAX)
    var inputOculto = document.getElementById('id-registro-eliminar');
    if (inputOculto) {
        inputOculto.value = id;
    }

    // 3. Abrir el modal usando la API nativa de Bootstrap
    var modalElement = document.getElementById('modal-delete');
    if (modalElement) {
        var myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
        myModal.show();
    } else {
        console.error("No se encontró el modal-delete en el DOM");
    }
}
$(document).ready(function() {
// 2. Ejecutar la eliminación al confirmar
$(document).off('click', '#btn-confirmar-eliminacion').on('click', '#btn-confirmar-eliminacion', function() {
	var idParaEliminar = $('#id-registro-eliminar').val();
	var $btn = $(this);

	// Bloquear botón para evitar doble clic
	$btn.prop('disabled', true).text('Eliminando...');

	// Llamada a tu rutina de eliminación
	$.ajax({
		url: 'paginas/ajax_eliminar_usuario.php?op=' + idParaEliminar,
		method: 'GET',
		dataType: 'json',
		success: function(response) {
			if (response.success) {
				// Cerrar modal
				bootstrap.Modal.getInstance(document.getElementById('modal-delete')).hide();
				
				// Recargar el listado usando tu función original
				cargarPaginaFullCache('paginas/user-adm.php');
			} else {
				alert("Error: " + response.message);
			}
		},
		error: function() {
			alert("Error de conexión");
		},
		complete: function() {
			$btn.prop('disabled', false).text('Eliminar');
		}
	});
});
});
</script>