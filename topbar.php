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
                                       
                                        <h6 class="text-overflow m-0">Bienvenid@ !</h6>
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
                                    <a href="logout.php" class="dropdown-item notify-item">
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