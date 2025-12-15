<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo Phiếu Nhập Kho Mới</h1>

    <form action="<?php echo BASE_URL; ?>/import/store" method="POST" id="importForm">
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin Nhà Cung Cấp</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="font-weight-bold">Nhà cung cấp (*)</label>
                            <select name="maNCC" class="form-select" required>
                                <option value="">-- Chọn NCC --</option>
                                <?php foreach ($data['suppliers'] as $ncc): ?>
                                    <option value="<?php echo $ncc['maNCC']; ?>">
                                        <?php echo $ncc['tenNCC']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Ghi chú nhập hàng</label>
                            <input type="text" name="ghiChu" class="form-control" placeholder="Ví dụ: Nhập hàng đợt 2...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Chi tiết hàng nhập</h6>
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    <i class="bi bi-plus-circle"></i> Thêm dòng
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%">Sản phẩm</th>
                                <th style="width: 15%">Hạn Bảo Hành (Lô)</th>
                                <th style="width: 15%">Số lượng</th>
                                <th style="width: 20%">Đơn giá nhập</th>
                                <th style="width: 10%">Thành tiền</th>
                                <th style="width: 5%">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-select" required>
                                        <option value="">-- Chọn hàng --</option>
                                        <?php foreach ($data['products'] as $p): ?>
                                            <option value="<?php echo $p['maHH']; ?>">
                                                <?php echo $p['tenHH']; ?> (<?php echo $p['maHH']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                               <td>
                                    <input type="date" name="expiry[]" class="form-control" 
                                        min="<?php echo date('Y-m-d'); ?>">
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" class="form-control qty-input" min="1" value="1" required>
                                </td>
                                <td>
                                    <input type="number" name="price[]" class="form-control price-input" min="0" value="0" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control subtotal" value="0" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end font-weight-bold">TỔNG CỘNG:</td>
                                <td colspan="2" class="font-weight-bold text-primary" id="grandTotal">0 đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <a href="<?php echo BASE_URL; ?>/home" class="btn btn-secondary me-2">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary btn-lg">Lưu & Nhập Kho</button>
        </div>
    </form>
</div>

<script>
    // 1. Thêm dòng mới
    document.getElementById('addRow').addEventListener('click', function() {
        var table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
        var newRow = table.rows[0].cloneNode(true);
        
        // Reset giá trị các input trong dòng mới
        var inputs = newRow.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].value = (inputs[i].type === 'number') ? 0 : '';
            if(inputs[i].name == 'quantity[]') inputs[i].value = 1;
        }
        
        // Reset Select box
        newRow.getElementsByTagName('select')[0].value = '';
        
        table.appendChild(newRow);
        updateEvents(); // Gán lại sự kiện tính tiền cho dòng mới
    });

    // 2. Xóa dòng
    document.getElementById('productTable').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('removeRow')) {
            var rowCount = document.getElementById('productTable').tBodies[0].rows.length;
            if (rowCount > 1) {
                e.target.closest('tr').remove();
                calculateTotal(); // Tính lại tổng tiền
            } else {
                alert('Phải có ít nhất 1 dòng sản phẩm!');
            }
        }
    });

    // 3. Tính thành tiền tự động
    function updateEvents() {
        var qtyInputs = document.querySelectorAll('.qty-input');
        var priceInputs = document.querySelectorAll('.price-input');
        // Lấy thêm các ô ngày hết hạn
        var dateInputs = document.querySelectorAll('input[type="date"]');

        qtyInputs.forEach(input => {
            input.addEventListener('input', calculateRow);
        });
        priceInputs.forEach(input => {
            input.addEventListener('input', calculateRow);
        });
        
        // --- THÊM ĐOẠN NÀY ---
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                var today = new Date().toISOString().split('T')[0];
                if(this.value && this.value < today) {
                    alert('Hạn bảo hành không được nhỏ hơn ngày hiện tại!');
                    this.value = ''; // Xóa trắng nếu chọn sai
                }
            });
        });
        // ---------------------
    }

    function calculateRow(e) {
        var row = e.target.closest('tr');
        var qty = row.querySelector('.qty-input').value;
        var price = row.querySelector('.price-input').value;
        var subtotal = qty * price;
        
        // Format tiền tệ
        row.querySelector('.subtotal').value = new Intl.NumberFormat('vi-VN').format(subtotal);
        calculateTotal();
    }

    function calculateTotal() {
        var total = 0;
        var rows = document.querySelectorAll('#productTable tbody tr');
        rows.forEach(row => {
            var qty = row.querySelector('.qty-input').value || 0;
            var price = row.querySelector('.price-input').value || 0;
            total += (qty * price);
        });
        document.getElementById('grandTotal').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
    }

    // Khởi chạy lần đầu
    updateEvents();
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>