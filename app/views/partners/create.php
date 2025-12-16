<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <?php echo ($data['type'] == 'supplier') ? 'Thêm Nhà Cung Cấp Mới' : 'Thêm Khách Hàng Mới'; ?>
    </h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin đối tác</h6>
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/partner/<?php echo ($data['type'] == 'supplier') ? 'store_supplier' : 'store_customer'; ?>" method="POST">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">
                            Mã <?php echo ($data['type'] == 'supplier') ? 'NCC' : 'Khách hàng'; ?> (*)
                        </label>
                        <input type="text" name="code" class="form-control" required 
                               placeholder="<?php echo ($data['type'] == 'supplier') ? 'Ví dụ: NCC01' : 'Ví dụ: KH01'; ?>">
                        <small class="text-muted">Mã này là duy nhất, không được trùng.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">
                            Tên <?php echo ($data['type'] == 'supplier') ? 'Nhà cung cấp' : 'Khách hàng'; ?> (*)
                        </label>
                        <input type="text" name="name" class="form-control" required placeholder="Nhập tên đầy đủ...">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email liên hệ</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="3"></textarea>
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <a href="<?php echo BASE_URL; ?>/partner/<?php echo $data['type']; ?>" class="btn btn-secondary me-2">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Lưu thông tin
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>