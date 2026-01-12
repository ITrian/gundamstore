<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<div class="d-flex">
    <?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><?php echo $title; ?></h3>
            <a href="<?php echo BASE_URL; ?>/user" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
        </div>

        <div class="card shadow" style="max-width: 800px; margin: 0 auto;">
            <div class="card-body p-4">
                <form action="<?php echo BASE_URL; ?>/user/store" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã Người dùng <span class="text-danger">*</span></label>
                            <input type="text" name="maND" class="form-control" required placeholder="VD: ND001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" name="tenND" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="sdt" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" name="taiKhoan" class="form-control" required autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="matKhau" class="form-control" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="maVaiTro" class="form-select" required>
                            <option value="">-- Chọn vai trò --</option>
                            <?php foreach($roles as $role): ?>
                                <option value="<?php echo $role['maVaiTro']; ?>">
                                    <?php echo $role['tenVaiTro']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Lưu người dùng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
