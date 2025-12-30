<style>
#sidebar-menu>ul>li>a .nav-icon {
    font-size: 1.625rem !important;
}
#sidebar-menu>ul>li>a {
    padding: 11px 11px !important;

}
.sidebar-text{ font-size:1.05rem}
</style>
<!-- Left Sidebar Start -->
            <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <div class="logo-box">
                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="assets/images/logo-sm.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo-light.png" alt="" height="26">
                                </span>
                            </a>
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="assets/images/logo-sm.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo-dark.png" alt="" height="26">
                                </span>
                            </a>
                        </div>

                        <ul id="side-navbar">

							<li>
                                <a href="apps-calendar.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:ranking-line-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text">Resumen</span>
                                </a>
                            </li>
							
                            <li>
                                <a href="#sidebarContacts" data-bs-toggle="collapse">
                                    <span class="nav-icon">
                                        <iconify-icon icon="uil:user"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text">Usuarios</span>
                                    <span class="menu-arrow"></span>
                                </a>

                                <div class="collapse" id="sidebarContacts">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="#" onclick="cargarPaginaFullCache('paginas/user-adm.php')" class="tp-link"><i class="ti ti-point"></i>Administradores</a>
                                        </li>
                                        <li>
                                            <a href="app-contacts-list.html" class="tp-link"><i class="ti ti-point"></i>Clientes</a>
                                        </li>
										<li>
                                            <a href="app-contacts-list.html" class="tp-link"><i class="ti ti-point"></i>Conductores</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
							<li>
                                <a href="apps-todolist.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="dashicons:car"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text">Vehiculos</span>
                                </a>
                            </li>
							<li>
                                <a href="apps-todolist.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:point-on-map-line-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text">Viajes</span>
                                </a>
                            </li>
							<li>
                                <a href="apps-todolist.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:chat-round-money-bold"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text">Tasa de Cambio</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>
        
                </div>
            </div>
            <!-- Left Sidebar End -->