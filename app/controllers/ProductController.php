<?php
class ProductController extends Controller {

    public function __construct() {
        $this->requireLogin(); // Bắt buộc đăng nhập mới xem được
    }

    public function index() {
        // 1. Gọi Model để lấy danh sách sản phẩm
        $productModel = $this->model('ProductModel');
        $products = $productModel->getAllProducts();

        // 2. Đẩy dữ liệu ra View
        $data = [
            'products' => $products
        ];
        $this->view('products/index', $data);
    }
}
?>