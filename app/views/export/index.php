<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Danh sách Phiếu Xuất</h1>
        <a href="<?php echo BASE_URL; ?>/export/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tạo phiếu xuất mới
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Mã PX</th>
                            <th>Ngày Xuất</th>
                            <th>Khách Hàng</th>
                            <th>Nhân viên thực hiện</th>
                            <th>Tổng Giá Trị Xuất</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['orders'])): ?>
                            <?php foreach ($data['orders'] as $item): ?>
                            <tr>
                                <td><strong><?php echo $item['maPX']; ?></strong></td>
                                <td>
                                    <?php echo $item['tenKH']; ?><br>
                                    <small class="text-muted">Người tạo: <?php echo $item['nguoiTao']; ?></small>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($item['ngayXuat'])); ?></td>
                                <td class="text-end fw-bold text-primary">
                                    <?php echo number_format($item['tongTien']); ?> đ
                                </td>
                                <td>
                                    <span class="badge bg-success">Đã xuất</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info text-white">Chi tiết</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">Chưa có phiếu xuất nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>