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

        <meta charset="utf-8" />
        <title>Starter | Venix - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

        <!-- Head CSS -->
        <script src="assets/js/head.js"></script>

    </head>

    <!-- body start -->
    <body data-menu-color="dark" data-sidebar="hidden">

        <!-- Begin page -->
        <div id="app-layout">


            <!-- Topbar Start -->
            <div class="topbar-custom">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            <li>
                                <button type="button" class="button-toggle-menu nav-link">
                                    <iconify-icon icon="solar:hamburger-menu-linear" class="fs-22 align-middle text-dark"></iconify-icon>
                                </button>
                            </li>
                            <li class="d-none d-lg-block">
                                <form class="app-search d-none d-md-block me-auto">
                                    <div class="position-relative topbar-search">
                                        <iconify-icon icon="solar:minimalistic-magnifer-line-duotone"
                                            class="fs-18 align-middle text-dark position-absolute text-dark top-50 translate-middle-y ms-2">
                                        </iconify-icon>
                                        <input type="text" class="form-control shadow-none" placeholder="Search for somethings" />
                                    </div>
                                </form>
                            </li>
                        </ul>

                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center gap-2">
                            

                            <!-- Button Trigger Customizer Offcanvas -->
                            <li class="d-none d-sm-flex">
                                <button type="button" class="btn nav-link" data-toggle="fullscreen">
                                    <iconify-icon icon="solar:full-screen-bold-duotone" class="fs-22 align-middle text-dark fullscreen noti-icon"></iconify-icon>
                                </button>
                            </li>

                            <!-- Light/Dark Mode Button Themes -->
                            <li class="d-none d-sm-flex">
                                <button type="button" class="btn nav-link d-flex align-items-center justify-content-center"
                                    id="light-dark-mode">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <iconify-icon icon="solar:sun-2-bold-duotone" class="fs-22 text-dark align-middle dark-mode"></iconify-icon>
                                        <iconify-icon icon="solar:moon-bold-duotone" class="fs-22 text-dark align-middle light-mode"></iconify-icon>
                                    </div>
                                </button>
                            </li>

                            <!-- Notifications -->
                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-bs-auto-close="outside">
                                    <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-22 text-dark align-middle"></iconify-icon>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end dropdown-lg dropdown-notifications">
                        
                                    <div class="dropdown-item noti-title">
                                        <p class="m-0 fs-14 mb-0 fw-medium text-dark">
                                            <span class="float-end">
                                                <a href="" class="text-dark">
                                                    <small>
                                                        <iconify-icon icon="solar:close-circle-broken" class="fs-20 text-dark align-middle light-mode"></iconify-icon>
                                                    </small>
                                                </a>
                                            </span>
                                            Your Notifications
                                        </p>
                                    </div>

                                    <!-- Navbar -->
                                    <div class="notification-tabs">
                                        <div class="border-bottom border-dashed">
                                            <ul class="nav nav-tabs nav-tabs-custom border-bottom-0 mb-2" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link-tab mx-0 active fw-medium" data-bs-toggle="tab" href="#viewAll" role="tab" aria-selected="true">
                                                        View All
                                                        <span class="badge bg-light ms-1 text-dark rounded-2 fs-12">24</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item text-muted" role="presentation">
                                                    <a class="nav-link-tab mx-0 fw-medium" data-bs-toggle="tab" href="#files" role="tab" aria-selected="false" tabindex="-1">Projects</a>
                                                </li>
                                                <li class="nav-item text-muted" role="presentation">
                                                    <a class="nav-link-tab mx-0 fw-medium" data-bs-toggle="tab" href="#jobs" role="tab" aria-selected="false" tabindex="-1">Team</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="ms-0 noti-scroll" data-simplebar>
                                        <div class="tab-content" id="myTabContent">

                                            <div class="tab-pane fade show active" id="viewAll" role="tabpanel" aria-labelledby="all-tab" tabindex="0">
                                                <div class="tab-pane-notification">

                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed pt-0">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-03.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Caitlyn</span> commented in </p>
                                                                <span class="badge badge-custom d-flex align-items-center gap-1 text-dark fs-12">
                                                                    <iconify-icon icon="solar:chart-square-broken" class="fs-14 text-dark align-middle"></iconify-icon>
                                                                    <span>File</span>
                                                                </span>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <small class="text-muted">Friday 3:12 PM</small>
                                                                <small class="text-muted">2 hours ago</small>
                                                            </div>
                                                            <div class="p-2 bg-light rounded-3">
                                                                <p class="mb-0 user-msg fs-13">How long will it take to finish this task? The client is waiting on this feature.</p>
                                                            </div>
                                                        </div>
                                                    </a>
    
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-04.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Mathide</span> followed you </p>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                                <small class="text-muted">Friday 3:12 PM</small>
                                                                <small class="text-muted">2 hours ago</small>
                                                            </div>
                                                        </div>
                                                    </a>
    
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-06.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Zaid</span> invited you to </p>
                                                                <span class="badge badge-custom d-flex align-items-center gap-1 text-dark fs-12">
                                                                    <iconify-icon icon="solar:chart-square-broken" class="fs-14 text-dark align-middle"></iconify-icon>
                                                                    <span>Blog design</span>
                                                                </span>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <small class="text-muted">Friday 3:12 PM</small>
                                                                <small class="text-muted">3 hours ago</small>
                                                            </div>
                                                            <p class="d-inline-flex gap-2 mb-0">
                                                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="button">Decline</button>
                                                                <button type="button" aria-label="Accept Invitation" class="btn btn-sm btn-dark" data-bs-toggle="button" aria-pressed="true">Accept</button>
                                                            </p>
                                                        </div>
                                                    </a>

                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted pb-0">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-10.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Lily-Rose</span> shared a file in</p>
                                                                <span class="badge badge-custom d-flex align-items-center gap-1 text-dark fs-12">
                                                                    <iconify-icon icon="solar:chart-square-broken" class="fs-14 text-dark align-middle"></iconify-icon>
                                                                    <span>Marketing site</span>
                                                                </span>
                                                                <span class="badge-dot bg-danger ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <small class="text-muted">Friday 1:40 PM</small>
                                                                <small class="text-muted">4 hours ago</small>
                                                            </div>
                                                            <div class="p-2 bg-light rounded-3">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="d-flex flex-column">
                                                                        <p class="mb-1 fs-13 text-dark">Marketing site v4.0.fig</p>
                                                                        <small class="text-muted">14 MB</small>
                                                                    </div>
                                                                    <div class="ms-auto">
                                                                        <iconify-icon icon="solar:download-minimalistic-bold-duotone" class="fs-22 text-dark align-middle light-mode"></iconify-icon>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- projects -->
                                            <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="projects-tab" tabindex="0">
                                                <div class="tab-pane-notification">

                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed pt-0">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-05.svg" class="rounded-circle avatar avatar-sm" alt="user image" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Eleanor</span> commented in </p>
                                                                <span class="badge badge-custom d-flex align-items-center gap-1 text-dark fs-12">
                                                                    <iconify-icon icon="solar:chart-square-broken" class="fs-14 text-dark align-middle"></iconify-icon>
                                                                    <span>Project Alpha</span>
                                                                </span>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <small class="text-muted">Monday 10:30 AM</small>
                                                                <small class="text-muted">1 hour ago</small>
                                                            </div>
                                                            <div class="p-2 bg-light rounded-3">
                                                                <p class="mb-0 user-msg fs-13">Please update the UI components before tomorrow's meeting.</p>
                                                            </div>
                                                        </div>
                                                    </a>
                                        
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-07.svg" class="rounded-circle avatar avatar-sm" alt="user image" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Leonard</span> followed you </p>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                                <small class="text-muted">Monday 10:12 AM</small>
                                                                <small class="text-muted">1 hour ago</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                        
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-08.svg" class="rounded-circle avatar avatar-sm" alt="user image" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Sophia</span> invited you to </p>
                                                                <span class="badge badge-custom d-flex align-items-center gap-1 text-dark fs-12">
                                                                    <iconify-icon icon="solar:chart-square-broken" class="fs-14 text-dark align-middle"></iconify-icon>
                                                                    <span>Landing Page Review</span>
                                                                </span>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <small class="text-muted">Monday 9:50 AM</small>
                                                                <small class="text-muted">2 hours ago</small>
                                                            </div>
                                                            <p class="d-inline-flex gap-2 mb-0">
                                                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="button">Decline</button>
                                                                <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="button" aria-pressed="true">Accept</button>
                                                            </p>
                                                        </div>
                                                    </a>
                                        
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted pb-0">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-09.svg" class="rounded-circle avatar avatar-sm" alt="user image" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Marcus</span> shared a file in</p>
                                                                <span class="badge badge-custom d-flex align-items-center gap-1 text-dark fs-12">
                                                                    <iconify-icon icon="solar:chart-square-broken" class="fs-14 text-dark align-middle"></iconify-icon>
                                                                    <span>Client Assets</span>
                                                                </span>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <small class="text-muted">Monday 9:00 AM</small>
                                                                <small class="text-muted">3 hours ago</small>
                                                            </div>
                                                            <div class="p-2 bg-light rounded-3">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="d-flex flex-column">
                                                                        <p class="mb-1 fs-13 text-dark">Client-Brief-v2.pdf</p>
                                                                        <small class="text-muted">2 MB</small>
                                                                    </div>
                                                                    <div class="ms-auto">
                                                                        <iconify-icon icon="solar:download-minimalistic-bold-duotone" class="fs-22 text-dark align-middle light-mode"></iconify-icon>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="jobs" role="tabpanel" aria-labelledby="teams-tab" tabindex="0">
                                                <div class="tab-pane-notification">

                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed pt-0">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-05.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Andrew</span> assigned you a task</p>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                                <small class="text-muted">Friday 2:30 PM</small>
                                                                <small class="text-muted">3 hours ago</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                        
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-07.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Olivia</span> mentioned you in a project comment</p>
                                                                <span class="badge-dot bg-danger ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                                <small class="text-muted">Friday 1:15 PM</small>
                                                                <small class="text-muted">4 hours ago</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                        
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-08.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Sophia</span> uploaded new project files</p>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                                <small class="text-muted">Friday 12:00 PM</small>
                                                                <small class="text-muted">5 hours ago</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                        
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted border-bottom border-dashed">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-09.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Daniel</span> shared project access with you</p>
                                                                <span class="badge-dot bg-primary ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                                <small class="text-muted">Friday 11:30 AM</small>
                                                                <small class="text-muted">6 hours ago</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                        
                                                    <a href="javascript:void(0);" class="dropdown-item notify-item-data text-muted pb-0">
                                                        <div class="notify-icon">
                                                            <img src="assets/images/users/avatar/avatar-11.svg" class="rounded-circle avatar avatar-sm" alt="user images" />
                                                        </div>
                                                        <div class="notify-content">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <p class="mb-0 me-1"><span class="text-dark fw-medium">Isabella</span> completed the design review</p>
                                                                <span class="badge-dot bg-danger ms-auto"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between mb-0">
                                                                <small class="text-muted">Friday 10:45 AM</small>
                                                                <small class="text-muted">7 hours ago</small>
                                                            </div>
                                                        </div>
                                                    </a>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- User Dropdown -->
                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                   <?php if(isset($usuario['foto_url']) && !empty($usuario['foto_url'])): ?>
										<img src="<?php echo htmlspecialchars($usuario['foto_url']); ?>" alt="user-image" class="img-fluid " />
									<?php else: ?>
										<img src="assets/images/users/no-image-black.jpg" alt="user-image" class="img-fluid " />
									<?php endif; ?>
									
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                    <!-- item-->
                                    <div class="dropdown-header noti-title border-bottom border-dashed d-flex align-items-center">
									<?php if(isset($usuario['foto_url']) && !empty($usuario['foto_url'])): ?>
										<img src="<?php echo htmlspecialchars($usuario['foto_url']); ?>" alt="user-image" class="avatar avatar-xs rounded-circle me-2" />
									<?php else: ?>
										<img src="assets/images/users/no-image-black.jpg" alt="user-image" class="avatar avatar-xs rounded-circle me-2" />
									<?php endif; ?>
                                       
                                        <h6 class="text-overflow m-0">Bienvenido !</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="pages-profile.html" class="dropdown-item notify-item border-bottom border-dashed">
                                        <iconify-icon icon="solar:user-bold-duotone" class="fs-18 align-middle" id="selected-language-image"></iconify-icon>
                                        <span>Mi Perfil</span>
                                    </a>

                                    <!-- item-->
                                    <a href="page-profile.html" class="dropdown-item notify-item border-bottom border-dashed">
                                        <iconify-icon icon="solar:settings-bold-duotone" class="fs-18 align-middle" id="selected-language-image"></iconify-icon>
                                        <span>Configuración</span>
                                    </a>

                                    <!-- item-->
                                    <a href="auth-lock-screen.html" class="dropdown-item notify-item border-bottom border-dashed">
                                        <iconify-icon icon="solar:shield-keyhole-bold-duotone" class="fs-18 align-middle" id="selected-language-image"></iconify-icon>
                                        <span>Pantalla de Bloqueo</span>
                                    </a>

                                    <!-- item-->
                                    <a href="auth-logout.html" class="dropdown-item notify-item">
                                        <iconify-icon icon="solar:logout-2-bold-duotone" class="fs-18 align-middle" id="selected-language-image"></iconify-icon>
                                        <span>Cerrar Sessión</span>
                                    </a>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <!-- end Topbar -->

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

                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="#sidebarDashboards" data-bs-toggle="collapse">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:widget-6-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Dashboards </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarDashboards">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="index.html" class="tp-link"><i class="ti ti-point"></i>CRM</a>
                                        </li>
                                        <li>
                                            <a href="ecommerce.html" class="tp-link"><i class="ti ti-point"></i>E Commerce</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="menu-title mt-2">Apps</li>

                            <li>
                                <a href="apps-todolist.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:server-minimalistic-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Todo List </span>
                                </a>
                            </li>

                            <li>
                                <a href="#sidebarContacts" data-bs-toggle="collapse">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:smartphone-2-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Contacts </span>
                                    <span class="menu-arrow"></span>
                                </a>

                                <div class="collapse" id="sidebarContacts">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="apps-contacts.html" class="tp-link"><i class="ti ti-point"></i>Contacts Card</a>
                                        </li>
                                        <li>
                                            <a href="app-contacts-list.html" class="tp-link"><i class="ti ti-point"></i>Contacts List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="apps-calendar.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Calendar </span>
                                </a>
                            </li>

                            <li>
                                <a href="apps-chat.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:chat-dots-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Chat </span>
                                </a>
                            </li>

                            <li>
                                <a href="mail.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:mailbox-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Email </span>
                                </a>
                            </li>

                            <li>
                                <a href="app-integrations.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:mirror-left-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Integrations </span>
                                </a>
                            </li>

                            <li>
                                <a href="app-notes.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:notes-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> Notes </span>
                                </a>
                            </li>

                            <li>
                                <a href="app-file-manager.html" class="tp-link">
                                    <span class="nav-icon">
                                        <iconify-icon icon="solar:file-text-bold-duotone"></iconify-icon>
                                    </span>
                                    <span class="sidebar-text"> File Manager </span>
                                </a>
                            </li>

                            
                        </ul>
                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>
        
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
         
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-18 fw-medium m-0">Starter</h4>
                            </div>
            
                            <div class="text-end">
                                <ol class="breadcrumb m-0 py-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                                    <li class="breadcrumb-item active">Starter</li>
                                </ol>
                            </div>
                        </div>

                    </div> <!-- container-fluid -->
                </div> <!-- content -->
                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col fs-13 text-muted text-center">
                                &copy; <script>document.write(new Date().getFullYear())</script> - Made by <a href="#!" class="text-reset fw-semibold">Zoyothemes</a> 
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Vendor -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/iconify-icon/iconify-icon.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- App js-->
        <script src="assets/js/app.js"></script>
        
    </body>
</html>