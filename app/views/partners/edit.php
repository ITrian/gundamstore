<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h3 class="mb-4">Cập nhật thông tin</h3>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/partner/update/<?php echo $data['type']; ?>" method="POST">
                
                <?php 
                    $p = $data['partner']; 
                    $code = ($data['type'] == 'supplier') ? $p['maNCC'] : $p['maKH'];
                    $name = ($data['type'] == 'supplier') ? $p['tenNCC'] : $p['tenKH'];
                ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Mã Đối Tác</label>
                        <input type="text" name="code" class="form-control bg-light" value="<?php echo $code; ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Tên Đối Tác</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $p['sdt']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $p['email']; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="3"><?php echo $p['diaChi']; ?></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?php echo BASE_URL; ?>/partner/<?php echo $data['type']; ?>" class="btn btn-secondary me-2">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>