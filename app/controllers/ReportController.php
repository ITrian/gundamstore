<?php
class ReportController extends Controller {
    private $reportModel;

    public function __construct() {
        $this->requireLogin();
        // Load Model vừa tạo
        $this->reportModel = $this->model('ReportModel');
    }

    public function index() {
        // Lấy dữ liệu thật từ Database
        $revenue = $this->reportModel->getTotalRevenue();
        $ordersCount = $this->reportModel->countExportOrders();
        $lowStockCount = $this->reportModel->countLowStock();
        $topProducts = $this->reportModel->getTopSellingProducts();

        // Đóng gói dữ liệu gửi sang View
        $data = [
            'title' => 'Báo cáo thống kê',
            'stats' => [
                'doanh_thu' => $revenue,
                'don_hang' => $ordersCount,
                'sap_het' => $lowStockCount
            ],
            'top_products' => $topProducts
        ];

        $this->view('report/index', $data);
    }
}
?>