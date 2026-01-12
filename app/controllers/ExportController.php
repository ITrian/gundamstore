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
        // Lấy danh sách sản phẩm kèm tồn kho để hiển thị trong form xuất
        $productModel = $this->model('ProductModel');
        $products = $productModel->getAll();

        // Lấy danh sách khách hàng đang hoạt động
        $partnerModel = $this->model('PartnerModel');
        $customers = $partnerModel->getCustomers();

        $data = [
            'title' => 'Tạo phiếu xuất kho',
            'products' => $products,
            'customers' => $customers
        ];
        $this->view('export/create', $data);
    }
}
?>