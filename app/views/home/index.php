<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <h3>Trang chủ - Quản lý kho hàng gia dụng</h3>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card text-bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Tổng sản phẩm</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-1 fw-bold">
                            <?= $data['total_products'] ?? 0 ?>
                        </span>
                        <i class="bi bi-box-seam fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Tổng tồn kho</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-1 fw-bold">
                            <?= $data['total_inventory'] ?? 0 ?>
                        </span>
                        <i class="bi bi-archive fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning fw-bold">
                    Danh sách mặt hàng sắp hết
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th class="text-center">Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['low_stock_products'])): ?>
                                <?php foreach ($data['low_stock_products'] as $item): ?>
                                    <tr>
                                        <td><?= $item['maSP'] ?></td>
                                        <td><?= $item['tenSP'] ?></td>
                                        <td class="text-center text-danger fw-bold">
                                            <?= $item['soLuong'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Không có mặt hàng nào sắp hết
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
