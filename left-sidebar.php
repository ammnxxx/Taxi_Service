<!-- sidebar -->
<div class="sidebar">
    <!-- sidebar logo -->
    <a href="index.php" class="sidebar__logo">
        <img src="assets/img/logo.png" alt="" style="height:60px">
    </a>
    <!-- end sidebar logo -->

    <!-- sidebar user -->
    <div class="sidebar__user">
        <div class="sidebar__user-img">
            <a href="#" tittle="Mi Perfil"><img src="<?php echo $foto_usuario; ?>" alt=""></a>
        </div>

        <div class="sidebar__user-title">
            <span style="text-transform: uppercase;color:#f9ab00;"><?php echo UserType($usuario['tipo'])?></span>
            <p><?php echo $usuario['nombre'].' '.$usuario['apellido']?></p>
        </div>

        <button class="sidebar__user-btn" type="button" title="Cerrar Sesión"
            onclick="window.location.href='logout.php'">
            <i class="ti ti-logout"></i>
        </button>
    </div>
    <!-- end sidebar user -->

    <!-- sidebar nav -->
    <div class="sidebar__nav-wrap">
        <ul class="sidebar__nav">
            <li class="sidebar__nav-item">
                <a href="assets/dashboard.php" class="sidebar__nav-link sidebar__nav-link--active"><i
                        class="ti ti-layout-grid"></i> <span>Resumen</span></a>
            </li>
            <li class="sidebar__nav-item">
                <a href="assets/catalog.html" class="sidebar__nav-link"><i class="ti ti-movie"></i>
                    <span>Viajes</span></a>
            </li>
            <li class="sidebar__nav-item">
                <a href="assets/catalog.html" class="sidebar__nav-link"><i class="ti ti-movie"></i>
                    <span>Choferes</span></a>
            </li>
            <li class="sidebar__nav-item">
                <a href="assets/catalog.html" class="sidebar__nav-link"><i class="ti ti-movie"></i>
                    <span>Vehiculos</span></a>
            </li>
            <li class="sidebar__nav-item">
                <a href="javascript:cargarPaginaFullCache('paginas/user-list.php')" class="sidebar__nav-link"><i
                        class="ti ti-users"></i> <span>Usuarios</span></a>
            </li>
            <li class="sidebar__nav-item">
                <a href="assets/settings.html" class="sidebar__nav-link"><i class="ti ti-settings"></i>
                    <span>Configuración</span></a>
            </li>
        </ul>
    </div>
    <!-- end sidebar nav -->

    <!-- sidebar copyright -->
    <div class="sidebar__copyright">© TAXIS LAS VEGAS, <?php echo date('Y');?>. <br>Desarrollado por <a
            href="www.ofitecsistemas.com.ve" target="_blank">OfitecSistemas</a></div>
    <!-- end sidebar copyright -->
</div>
<!-- end sidebar -->