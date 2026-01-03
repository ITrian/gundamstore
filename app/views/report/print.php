<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>In Báo Cáo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14pt; }
        .company-name { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .report-title { font-size: 20pt; font-weight: bold; text-align: center; margin-top: 30px; }
        .report-date { text-align: center; font-style: italic; margin-bottom: 30px; }
        .table th { background-color: #f8f9fa !important; text-align: center; }
        .signature-section { margin-top: 50px; }
        
        /* Chỉ thị cho máy in */
        @media print {
            @page { margin: 2cm; }
            .no-print { display: none; } /* Ẩn nút in khi in thật */
        }
    </style>
</head>
<body class="bg-white">

    <div class="container py-4">
        <div class="no-print mb-4 text-center">
            <button onclick="window.print()" class="btn btn-primary btn-lg">
                <i class="bi bi-printer-fill"></i> Bấm vào đây để In / Lưu PDF
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg">Đóng</button>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <div class="company-name">CÔNG TY KHO GIA DỤNG ABC</div>
                <div>Địa chỉ: 123 Đường Láng, Hà Nội</div>
                <div>Hotline: 0912.345.678</div>
            </div>
            <div class="col-6 text-end">
                <div>Mẫu số: BC-01/KHO</div>
                <div><i>(Ban hành theo QĐ số 123/QĐ-GD)</i></div>
            </div>
        </div>

        <hr class="border border-dark border-2 opacity-100">

        <div class="report-title">BÁO CÁO KẾT QUẢ KINH DOANH</div>
        <div class="report-date">
            (Từ ngày <?php echo date('d/m/Y', strtotime($data['from_date'])); ?> 
            đến ngày <?php echo date('d/m/Y', strtotime($data['to_date'])); ?>)
        </div>

        <h5 class="fw-bold mt-4">I. TỔNG HỢP TÀI CHÍNH</h5>
        <table class="table table-bordered border-dark">
            <tbody>
                <tr>
                    <td width="50%"><strong>1. Tổng doanh thu bán hàng</strong></td>
                    <td class="text-end"><?php echo number_format($data['stats']['doanh_thu']); ?> VNĐ</td>
                </tr>
                <tr>
                    <td><strong>2. Tổng chi phí nhập hàng</strong></td>
                    <td class="text-end"><?php echo number_format($data['stats']['chi_phi_nhap']); ?> VNĐ</td>
                </tr>
                <tr class="table-light">
                    <td><strong class="text-uppercase">3. Lợi nhuận tạm tính (1 - 2)</strong></td>
                    <td class="text-end fw-bold fs-5"><?php echo number_format($data['stats']['loi_nhuan']); ?> VNĐ</td>
                </tr>
            </tbody>
        </table>

        <h5 class="fw-bold mt-4">II. TOP SẢN PHẨM BÁN CHẠY</h5>
        <table class="table table-bordered border-dark">
            <thead>
                <tr>
                    <th width="10%">STT</th>
                    <th>Tên Sản Phẩm</th>
                    <th width="20%">Số lượng bán</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach ($data['top_products'] as $p): ?>
                <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td><?php echo $p['tenHH']; ?></td>
                    <td class="text-center fw-bold"><?php echo $p['soLuongBan']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row signature-section text-center">
            <div class="col-6">
                <strong>Người lập biểu</strong><br>
                <i>(Ký, họ tên)</i>
                <br><br><br><br>
                <?php echo $_SESSION['user_name'] ?? 'Nhân viên kho'; ?>
            </div>
            <div class="col-6">
                <strong>Ngày ... tháng ... năm 20...</strong><br>
                <strong>Giám đốc</strong><br>
                <i>(Ký, đóng dấu)</i>
            </div>
        </div>
    </div>

    <script>
        // Tự động mở hộp thoại in khi vào trang
        window.onload = function() {
            // window.print(); // Bỏ comment dòng này nếu muốn tự in luôn
        }
    </script>
</body>
</html>