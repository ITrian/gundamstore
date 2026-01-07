<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4">Thêm Vị trí mới</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/vitri/store" method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Dãy (day)</label>
                            <input type="text" name="day" class="form-control" required placeholder="Ví dụ: A">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kệ (ke)</label>
                            <input type="text" name="ke" class="form-control" required placeholder="Ví dụ: 1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Ô (o)</label>
                            <input type="text" name="o" class="form-control" required placeholder="Ví dụ: 01">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Sức chứa tối đa</label>
                            <input type="number" name="sucChuaToiDa" class="form-control" required value="100" min="1" step="1">
                            <small class="text-muted">Ví dụ: 100 (điểm/đơn vị chứa)</small>
                        </div>
                    </div>
                    <!-- trangThai input removed: trạng thái giờ là động (Trống/Đầy) tính từ mức chiếm -->
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?php echo BASE_URL; ?>/vitri" class="btn btn-secondary me-2">Hủy</a>
                    <button class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
