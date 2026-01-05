<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Quản lý Vị trí</h1>
        <a href="<?php echo BASE_URL; ?>/vitri/create" class="btn btn-primary">+ Thêm vị trí</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light"><tr><th>Code</th><th>Dãy</th><th>Kệ</th><th>Ô</th><th>Hành động</th></tr></thead>
                    <tbody>
                        <?php if (!empty($data['rows'])): ?>
                            <?php foreach ($data['rows'] as $r): ?>
                                <tr>
                                    <td><?php echo $r['maViTri']; ?></td>
                                    <td><?php echo htmlspecialchars($r['day']); ?></td>
                                    <td><?php echo htmlspecialchars($r['ke']); ?></td>
                                    <td><?php echo htmlspecialchars($r['o']); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/vitri/edit/<?php echo $r['maViTri']; ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                                        <a href="<?php echo BASE_URL; ?>/vitri/delete/<?php echo $r['maViTri']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa vị trí?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted">Chưa có vị trí nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
