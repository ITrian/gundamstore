<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo $data['title']; ?></h1>
        <a href="<?php echo BASE_URL; ?>/phieudathang/create" class="btn btn-primary">Thêm đơn đặt hàng</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã DH</th>
                            <th>Nhà cung cấp</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['orders'])): ?>
                            <?php foreach ($data['orders'] as $o): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?php echo $o['maDH']; ?></td>
                                    <td><?php echo $o['tenNCC'] ?? ''; ?></td>
                                    <td><?php echo $o['ngayDatHang']; ?></td>
                                    <td><?php echo $o['trangThai']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/phieudathang/show/<?php echo $o['maDH']; ?>" class="btn btn-sm btn-outline-info">Xem</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted">Chưa có đơn đặt hàng.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
