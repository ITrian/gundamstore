<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <h3 class="mb-4 text-gray-800">Trang chủ - Quản lý kho hàng gia dụng</h3>
    
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card text-bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Tổng đầu sản phẩm</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-1 fw-bold">
                            <?php echo number_format($data['total_products']); ?>
                        </span>
                        <i class="bi bi-box-seam fs-1"></i>
                    </div>
                    <small>Số lượng mã hàng đang quản lý</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card text-bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Tổng số lượng tồn kho</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-1 fw-bold">
                            <?php echo number_format($data['total_inventory']); ?>
                        </span>
                        <i class="bi bi-archive fs-1"></i>
                    </div>
                    <small>Tổng tất cả sản phẩm trong các lô</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-triangle-fill"></i> Danh sách mặt hàng sắp hết (<= 10)</span>
                    <a href="<?php echo BASE_URL; ?>/inventory" class="btn btn-sm btn-light">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã HH</th>
                                    <th>Tên sản phẩm</th>
                                    <th class="text-center" style="width: 150px;">Tồn kho</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['low_stock_products'])): ?>
                                    <?php foreach ($data['low_stock_products'] as $item): ?>
                                        <tr>
                                            <td class="fw-bold text-primary"><?php echo $item['maHH']; ?></td>
                                            
                                            <td><?php echo $item['tenHH']; ?></td>
                                            
                                            <td class="text-center">
                                                <?php if($item['tongTon'] == 0): ?>
                                                    <span class="badge bg-danger rounded-pill px-3">0 (Hết hàng)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark rounded-pill px-3">
                                                        <?php echo $item['tongTon']; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-success py-4">
                                            <i class="bi bi-check-circle-fill fs-3"></i><br>
                                            Tuyệt vời! Không có hàng hóa nào sắp hết.
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
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>