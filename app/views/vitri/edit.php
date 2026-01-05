<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4">Sửa Vị trí</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/vitri/update" method="POST">
                <input type="hidden" name="maViTri" value="<?php echo htmlspecialchars($data['row']['maViTri']); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Dãy (day)</label>
                            <input type="text" name="day" class="form-control" required value="<?php echo htmlspecialchars($data['row']['day']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kệ (ke)</label>
                            <input type="text" name="ke" class="form-control" required value="<?php echo htmlspecialchars($data['row']['ke']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Ô (o)</label>
                            <input type="text" name="o" class="form-control" required value="<?php echo htmlspecialchars($data['row']['o']); ?>">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?php echo BASE_URL; ?>/vitri" class="btn btn-secondary me-2">Hủy</a>
                    <button class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
