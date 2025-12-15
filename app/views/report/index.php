<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Báo cáo hiệu quả kinh doanh</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-calendar"></i> Toàn thời gian
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Tổng Doanh Thu (Xuất kho)
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800">
                                <?php echo number_format($data['stats']['doanh_thu']); ?> ₫
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Đơn hàng đã xuất
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800">
                                <?php echo number_format($data['stats']['don_hang']); ?> đơn
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Cảnh báo tồn kho (<= 10)
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800">
                                <?php echo number_format($data['stats']['sap_het']); ?> mã hàng
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 fw-bold"><i class="bi bi-graph-up"></i> Top 5 Sản phẩm bán chạy nhất</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Số lượng bán</th>
                            <th class="text-end">Doanh thu mang lại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['top_products'])): ?>
                            <?php foreach ($data['top_products'] as $prod): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo $prod['tenHH']; ?></td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark fs-6">
                                        <?php echo number_format($prod['totalSold']); ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    <?php echo number_format($prod['revenue']); ?> ₫
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Chưa có dữ liệu bán hàng. Hãy tạo phiếu xuất kho để xem báo cáo!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>