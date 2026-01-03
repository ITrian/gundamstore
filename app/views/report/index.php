<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Báo cáo Thống kê</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="fw-bold">Từ ngày:</label>
                    <input type="date" name="from_date" id="startDate" class="form-control" 
                        value="<?php echo $data['from_date']; ?>">
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Đến ngày:</label>
                    <input type="date" name="to_date" id="endDate" class="form-control" 
                        value="<?php echo $data['to_date']; ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Xem báo cáo
                    </button>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-filter"></i> Xem
                    </button>
                    
                    <a href="<?php echo BASE_URL; ?>/report/exportExcel?from_date=<?php echo $data['from_date']; ?>&to_date=<?php echo $data['to_date']; ?>" 
                    class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>

                    <a href="<?php echo BASE_URL; ?>/report/printReport?from_date=<?php echo $data['from_date']; ?>&to_date=<?php echo $data['to_date']; ?>" 
                    target="_blank" class="btn btn-danger">
                    <i class="bi bi-printer"></i> PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-danger border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                Tổng Chi Phí Nhập (<?php echo $data['stats']['so_phieu_nhap']; ?> phiếu)
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800">
                                <?php echo number_format($data['stats']['chi_phi_nhap']); ?> ₫
                            </div>
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
                                Tổng Doanh Thu (<?php echo $data['stats']['so_phieu_xuat']; ?> đơn)
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800">
                                <?php echo number_format($data['stats']['doanh_thu']); ?> ₫
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Chênh lệch (Thu - Chi)
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800">
                                <?php echo number_format($data['stats']['loi_nhuan']); ?> ₫
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Biểu đồ Thu / Chi</h6>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Top sản phẩm bán chạy</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['top_products'])): ?>
                                    <?php foreach ($data['top_products'] as $p): ?>
                                    <tr>
                                        <td><?php echo $p['tenHH']; ?></td>
                                        <td class="fw-bold text-center"><?php echo $p['soLuongBan']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center">Chưa có dữ liệu</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Lấy dữ liệu từ PHP sang JS
    const totalImport = <?php echo $data['stats']['chi_phi_nhap']; ?>;
    const totalExport = <?php echo $data['stats']['doanh_thu']; ?>;

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar', // Loại biểu đồ cột
        data: {
            labels: ['Tổng Chi Phí Nhập', 'Tổng Doanh Thu'],
            datasets: [{
                label: 'Số tiền (VNĐ)',
                data: [totalImport, totalExport],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)', // Màu đỏ cho chi phí
                    'rgba(75, 192, 192, 0.6)'  // Màu xanh cho doanh thu
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: false // Ẩn chú thích vì đã có label trục
                }
            }
        }
    });
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    // 1. Khi thay đổi "Từ ngày"
    startDateInput.addEventListener('change', function() {
        // Cập nhật thuộc tính min của "Đến ngày"
        endDateInput.min = this.value;
        
        // Nếu ngày kết thúc hiện tại lại nhỏ hơn ngày bắt đầu mới chọn -> Reset ngày kết thúc bằng ngày bắt đầu
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
    });

    // 2. Khi thay đổi "Đến ngày"
    endDateInput.addEventListener('change', function() {
        // Cập nhật thuộc tính max của "Từ ngày"
        startDateInput.max = this.value;
        
        // Nếu ngày bắt đầu hiện tại lại lớn hơn ngày kết thúc mới chọn -> Reset ngày bắt đầu bằng ngày kết thúc
        if (startDateInput.value && startDateInput.value > this.value) {
            startDateInput.value = this.value;
        }
    });

    // 3. Chạy logic này ngay khi tải trang (để áp dụng cho dữ liệu mặc định)
    window.addEventListener('DOMContentLoaded', function() {
        if(startDateInput.value) endDateInput.min = startDateInput.value;
        if(endDateInput.value) startDateInput.max = endDateInput.value;
    });
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>