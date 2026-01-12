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
                                <!-- 1. Mã PX -->
                                <td><strong><?php echo htmlspecialchars($item['maPX']); ?></strong></td>

                                <!-- 2. Ngày Xuất -->
                                <td><?php echo date('d/m/Y H:i', strtotime($item['ngayXuat'])); ?></td>

                                <!-- 3. Khách Hàng -->
                                <td><?php echo htmlspecialchars($item['tenKH']); ?></td>

                                <!-- 4. Nhân viên thực hiện -->
                                <td><?php echo htmlspecialchars($item['nguoiTao']); ?></td>

                                <!-- 5. Tổng Giá Trị Xuất -->
                                <td class="text-end fw-bold text-primary">
                                    <?php echo number_format($item['tongTien']); ?> đ
                                </td>

                                <!-- 6. Thao tác -->
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/export/show/<?php echo urlencode($item['maPX']); ?>" class="btn btn-sm btn-info text-white mt-1">Chi tiết</a>
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