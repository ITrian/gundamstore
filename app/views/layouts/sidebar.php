<div class="d-flex">
    <div class="bg-primary text-white p-3" style="width:250px; min-height:100vh;">

        <h5 class="text-center mb-4">
            <a href="<?php echo BASE_URL; ?>/home" class="text-white text-decoration-none">
                KHO GIA DỤNG
            </a>
        </h5>

        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/home">
                    <i class="fas fa-home me-2"></i> Trang chủ
                </a>
            </li>

            <!-- Đối tác: chỉ ai có quyền hệ thống hoặc nhập xuất -->
            <?php if (checkPermission('Q_HETHONG') || checkPermission('Q_NHAP_KHO') || checkPermission('Q_XUAT_KHO')): ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/partner/supplier">
                    <i class="fas fa-users me-2"></i> Đối tác
                </a>
            </li>
            <?php endif; ?>

            <!-- Sản phẩm & Danh mục: check quyền Q_XEM_HANG hoặc Q_QL_HANG -->
            <?php if (checkPermission('Q_XEM_HANG') || checkPermission('Q_QL_HANG')): ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/product">
                    <i class="fas fa-box me-2"></i> Sản phẩm
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/category">
                    <i class="fas fa-tags me-2"></i> Danh mục
                </a>
            </li>
            <?php endif; ?>

            <!-- Đặt hàng & Nhập kho: check quyền Nhập kho -->
            <?php if (checkPermission('Q_NHAP_KHO')): ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/phieudathang">
                    <i class="fas fa-shopping-cart me-2"></i> Đặt hàng
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/import">
                    <i class="fas fa-download me-2"></i> Nhập kho
                </a>
            </li>
            <?php endif; ?>

            <!-- Xuất kho: check quyền Xuất kho -->
            <?php if (checkPermission('Q_XUAT_KHO')): ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/export">
                    <i class="fas fa-upload me-2"></i> Xuất kho
                </a>
            </li>
            <?php endif; ?>

            <!-- Tồn kho & Vị trí: check quyền Xem hàng hoặc Quản lý hàng -->
            <?php if (checkPermission('Q_XEM_HANG') || checkPermission('Q_QL_HANG')): ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/inventory">
                    <i class="fas fa-warehouse me-2"></i> Tồn kho
                </a>
            </li>

            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/vitri">
                    <i class="fas fa-map-marker-alt me-2"></i> Vị trí
                </a>
            </li>
            <?php endif; ?>

            <!-- Bảo hành: check quyền Xuất hoặc Nhập (thường liên quan hậu mãi) hoặc Q_XEM_HANG -->
            <?php if (checkPermission('Q_XEM_HANG') || checkPermission('Q_NHAP_KHO') || checkPermission('Q_XUAT_KHO')): ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/warranty">
                    <i class="fas fa-shield-alt me-2"></i> Bảo hành
                </a>
            </li>
            <?php endif; ?>

            <!-- Báo cáo: check quyền Q_BAOCAO -->
            <?php if (checkPermission('Q_BAOCAO')): ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/report">
                    <i class="fas fa-chart-bar me-2"></i> Báo cáo
                </a>
            </li>
            <?php endif; ?>

            <hr class="text-white">

            <li class="nav-item mt-2">
                <a class="nav-link text-warning fw-bold" href="<?php echo BASE_URL; ?>/auth/logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>

    <div class="flex-grow-1 p-4">