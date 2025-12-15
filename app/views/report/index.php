<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Báo cáo hoạt động</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                Tháng này
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 border-primary border-3 border-top-0 border-end-0 border-bottom-0">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Doanh thu (Tháng)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($data['stats']['doanh_thu']); ?> đ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 border-success border-3 border-top-0 border-end-0 border-bottom-0">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đơn hàng đã xuất</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['stats']['don_hang']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 border-warning border-3 border-top-0 border-end-0 border-bottom-0">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cảnh báo tồn kho</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['stats']['sap_het']; ?> sản phẩm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top 5 sản phẩm bán chạy nhất</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng bán</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nồi cơm điện Cuckoo</td>
                            <td>120</td>
                            <td>120,000,000 đ</td>
                        </tr>
                        <tr>
                            <td>Chảo Sunhouse</td>
                            <td>85</td>
                            <td>25,000,000 đ</td>
                        </tr>
                        <tr>
                            <td>Máy xay sinh tố</td>
                            <td>60</td>
                            <td>18,000,000 đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>