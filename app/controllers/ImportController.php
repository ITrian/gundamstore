<?php
class ImportController extends Controller {

    public function __construct() {
        $this->requireLogin();
    }

    public function index() {
        // Gọi View ra để test xem chạy chưa
        $data = []; 
        $this->view('import/index', $data);
    }
    
    // Hàm create để vào trang tạo phiếu
    public function create() {
        // Tạm thời để trống data để test view trước
        $data = [
            'suppliers' => [], 
            'products' => []
        ];
        $this->view('import/create', $data);
    }
}
?>