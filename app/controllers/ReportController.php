<?php
class ReportController extends Controller {
    private $reportModel;

    public function __construct() {
        $this->requireLogin();
        // Cần quyền báo cáo
        $this->requirePermission('Q_BAOCAO');
        $this->reportModel = $this->model('ReportModel');
    }

    public function index() {
        // 1. Xử lý bộ lọc ngày tháng
        // Nếu người dùng chọn ngày thì lấy, nếu không thì mặc định là ngày đầu tháng -> hôm nay
        $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
        $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

        // 2. Gọi Model lấy số liệu
        $totalImport = $this->reportModel->getImportCost($fromDate, $toDate);
        $totalExport = $this->reportModel->getExportRevenue($fromDate, $toDate);
        $countImport = $this->reportModel->countTransactions('PHIEUNHAP', 'ngayNhap', $fromDate, $toDate);
        $countExport = $this->reportModel->countTransactions('PHIEUXUAT', 'ngayXuat', $fromDate, $toDate);
        $topProducts = $this->reportModel->getTopSelling($fromDate, $toDate);

        // Tính lợi nhuận giả định (Doanh thu - Chi phí nhập)
        // Lưu ý: Đây là cách tính đơn giản (Cash flow), chưa phải lãi gộp chuẩn kế toán
        $profit = $totalExport - $totalImport;

        $data = [
            'title' => 'Báo cáo Thống kê',
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'stats' => [
                'chi_phi_nhap' => $totalImport,
                'doanh_thu' => $totalExport,
                'so_phieu_nhap' => $countImport,
                'so_phieu_xuat' => $countExport,
                'loi_nhuan' => $profit
            ],
            'top_products' => $topProducts
        ];

        $this->view('report/index', $data);
    }
    public function exportExcel() {
        // 1. Lấy dữ liệu giống như hàm index
        $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
        $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

        $stats = [
            'chi_phi_nhap' => $this->reportModel->getImportCost($fromDate, $toDate),
            'doanh_thu' => $this->reportModel->getExportRevenue($fromDate, $toDate),
            'loi_nhuan' => $this->reportModel->getExportRevenue($fromDate, $toDate) - $this->reportModel->getImportCost($fromDate, $toDate)
        ];
        $topProducts = $this->reportModel->getTopSelling($fromDate, $toDate);

        // 2. Thiết lập Header để trình duyệt hiểu đây là file Excel
        $fileName = "Bao_cao_tu_{$fromDate}_den_{$toDate}.xls";
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // 3. Quan trọng: Thêm BOM để Excel đọc đúng tiếng Việt
        echo "\xEF\xBB\xBF"; 

        // 4. Xuất nội dung HTML (Excel sẽ tự convert HTML Table thành các ô)
        ?>
        <style>
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #000; padding: 5px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
            .number { text-align: right; }
            .header-title { font-size: 18px; font-weight: bold; text-align: center; color: #2c3e50; }
            .sub-title { text-align: center; font-style: italic; }
        </style>

        <table>
            <tr>
                <td colspan="3" class="header-title">BÁO CÁO HOẠT ĐỘNG KINH DOANH</td>
            </tr>
            <tr>
                <td colspan="3" class="sub-title">Từ ngày <?php echo date('d/m/Y', strtotime($fromDate)); ?> đến ngày <?php echo date('d/m/Y', strtotime($toDate)); ?></td>
            </tr>
            <tr><td colspan="3"></td></tr> <tr style="background-color: #dff0d8;"><th colspan="3">I. TỔNG QUAN TÀI CHÍNH</th></tr>
            <tr>
                <td>1</td>
                <td>Tổng Doanh Thu (Tiền bán hàng)</td>
                <td class="number"><?php echo number_format($stats['doanh_thu']); ?> VNĐ</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Tổng Chi Phí (Tiền nhập hàng)</td>
                <td class="number"><?php echo number_format($stats['chi_phi_nhap']); ?> VNĐ</td>
            </tr>
            <tr>
                <td>3</td>
                <td><strong>Lợi Nhuận Tạm Tính</strong></td>
                <td class="number" style="color: red; font-weight: bold;"><?php echo number_format($stats['loi_nhuan']); ?> VNĐ</td>
            </tr>

            <tr><td colspan="3"></td></tr>

            <tr style="background-color: #d9edf7;"><th colspan="3">II. TOP SẢN PHẨM BÁN CHẠY</th></tr>
            <tr>
                <th>STT</th>
                <th>Tên Sản Phẩm</th>
                <th>Số Lượng Đã Bán</th>
            </tr>
            <?php 
            $i = 1;
            foreach ($topProducts as $p): ?>
            <tr>
                <td style="text-align: center;"><?php echo $i++; ?></td>
                <td><?php echo $p['tenHH']; ?></td>
                <td class="number"><?php echo $p['soLuongBan']; ?></td>
            </tr>
            <?php endforeach; ?>
            
            <tr><td colspan="3"></td></tr>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: center;">
                    <em>Ngày ... tháng ... năm ...</em><br>
                    <strong>Người lập báo cáo</strong><br>
                    <br><br><br>
                    <?php echo $_SESSION['user_name'] ?? 'Admin'; ?>
                </td>
            </tr>
        </table>
        <?php
        exit(); // Dừng code để không bị lẫn HTML khác
    }
    public function printReport() {
        // Lấy dữ liệu y hệt như trên
        $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
        $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

        $data = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'stats' => [
                'chi_phi_nhap' => $this->reportModel->getImportCost($fromDate, $toDate),
                'doanh_thu' => $this->reportModel->getExportRevenue($fromDate, $toDate),
                'loi_nhuan' => $this->reportModel->getExportRevenue($fromDate, $toDate) - $this->reportModel->getImportCost($fromDate, $toDate)
            ],
            'top_products' => $this->reportModel->getTopSelling($fromDate, $toDate)
        ];

        // Gọi View in ấn (lưu ý: view này không load header/sidebar/footer chung)
        $this->view('report/print', $data);
    }
}
?>