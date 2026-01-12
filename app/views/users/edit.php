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
                <form action="<?php echo BASE_URL; ?>/user/update" method="POST">
                    <!-- ID Hidden/Readonly -->
                    <input type="hidden" name="maND" value="<?php echo $user['maND']; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã Người dùng</label>
                            <input type="text" class="form-control" value="<?php echo $user['maND']; ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" name="tenND" class="form-control" value="<?php echo $user['tenND']; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="sdt" class="form-control" value="<?php echo $user['sdt']; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" value="<?php echo $user['taiKhoan']; ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="matKhau" class="form-control" placeholder="Để trống nếu không đổi">
                            <small class="text-muted">Chỉ nhập nếu muốn đổi mật khẩu</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="maVaiTro" class="form-select" required>
                            <?php foreach($roles as $role): ?>
                                <option value="<?php echo $role['maVaiTro']; ?>" 
                                    <?php echo ($user['maVaiTro'] == $role['maVaiTro']) ? 'selected' : ''; ?>>
                                    <?php echo $role['tenVaiTro']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-save me-2"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
