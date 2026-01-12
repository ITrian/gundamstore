<?php
class InventoryController extends Controller {
    private $inventoryModel;

    public function __construct() {
        $this->requireLogin();
        // Gọi Model vừa tạo
        $this->inventoryModel = $this->model('InventoryModel');
    }

    public function index() {
        if (!checkPermission('Q_XEM_HANG') && !checkPermission('Q_QL_HANG')) {
            $this->requirePermission('Q_XEM_HANG');
        }
        // Lấy dữ liệu thật từ DB
        $stocks = $this->inventoryModel->getAllStock();

        $data = [
            'title' => 'Tồn kho thực tế', 
            'stocks' => $stocks
        ];
        
        $this->view('inventory/index', $data);
    }
}
?>