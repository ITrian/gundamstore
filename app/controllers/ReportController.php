<?php
class ReportController extends Controller {
    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        // Giả lập số liệu báo cáo
        $stats = [
            'doanh_thu' => 150000000,
            'don_hang' => 45,
            'hang_ton' => 1200,
            'sap_het' => 5
        ];

        $data = ['title' => 'Báo cáo thống kê', 'stats' => $stats];
        $this->view('report/index', $data);
    }
}