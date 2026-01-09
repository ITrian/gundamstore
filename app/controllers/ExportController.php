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
        $partnerModel = $this->model('PartnerModel');
        $productModel = $this->model('ProductModel');
        $vitriModel = $this->model('VitriModel');

        $data = [
            'title' => 'Tạo phiếu xuất kho',
            'customers' => $partnerModel->getCustomers(),
            'products' => $productModel->getAll(),
            'vitri' => $vitriModel->getAll()
        ];
        $this->view('export/create', $data);
    }
}
?>