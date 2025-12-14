<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo Phiếu Nhập Kho</h1>

    <form action="<?php echo BASE_URL; ?>/import/store" method="POST">
        
        <div class="card mb-4">
            <div class="card-header">Thông tin phiếu nhập</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Nhà cung cấp:</label>
                        <select name="maNCC" class="form-control" required>
                            <option value="">-- Chọn NCC --</option>
                            <?php foreach ($data['suppliers'] as $ncc): ?>
                                <option value="<?php echo $ncc['maNCC']; ?>">
                                    <?php echo $ncc['tenNCC']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Ghi chú:</label>
                        <input type="text" name="ghiChu" class="form-control" placeholder="Nhập ghi chú...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Chi tiết hàng nhập</span>
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    + Thêm dòng
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="productTable">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th width="150">Số lượng</th>
                            <th width="200">Đơn giá nhập</th>
                            <th width="100">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="product_id[]" class="form-control" required>
                                    <option value="">-- Chọn hàng --</option>
                                    <?php foreach ($data['products'] as $p): ?>
                                        <option value="<?php echo $p['maHH']; ?>">
                                            <?php echo $p['tenHH']; ?> (<?php echo $p['maHH']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
                            </td>
                            <td>
                                <input type="number" name="price[]" class="form-control" min="0" value="0" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm removeRow">Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Lưu Phiếu Nhập</button>
    </form>
</div>

<script>
document.getElementById('addRow').addEventListener('click', function() {
    var table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
    var newRow = table.rows[0].cloneNode(true);
    
    // Reset giá trị input trong dòng mới
    var inputs = newRow.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].value = '';
    }
    
    table.appendChild(newRow);
});

// Sự kiện xóa dòng (Dùng Event Delegation)
document.getElementById('productTable').addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('removeRow')) {
        var rowCount = document.getElementById('productTable').rows.length;
        if (rowCount > 2) { // Giữ lại ít nhất 1 dòng (1 thead + 1 tr)
            e.target.closest('tr').remove();
        } else {
            alert('Phải có ít nhất một sản phẩm!');
        }
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>