<?php
class ExportController extends Controller {
    private $exportModel;

    public function __construct() {
        $this->requireLogin();
        $this->exportModel = $this->model('ExportModel');
    }

    public function index() {
        // Lấy dữ liệu thật
        $orders = $this->exportModel->getAllOrders();

        $data = [
            'title' => 'Quản lý Xuất kho', 
            'orders' => $orders
        ];
        
        $this->view('export/index', $data);
    }
    
    // Giữ nguyên hàm create của bạn
    public function create() {
        $data = ['title' => 'Tạo phiếu xuất kho'];
        $this->view('export/create', $data);
    }
}
?>