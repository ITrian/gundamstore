<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo $data['title']; ?></h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/phieudathang/store" method="post" id="orderForm">
                <div class="mb-3">
                    <label class="form-label">Nhà cung cấp (*)</label>
                    <select name="maNCC" id="select-ncc" class="form-control" required style="width: 100%;">
                        <option value="">-- Chọn NCC --</option>
                        <?php foreach ($data['suppliers'] as $s): ?>
                            <option value="<?php echo $s['maNCC']; ?>"><?php echo $s['tenNCC']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h5>Chi tiết sản phẩm</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="lines">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="min-width: 300px;">
                                <select name="product[]" class="form-control order-product-select" required style="width: 100%;">
                                    <option value="">-- Tìm và chọn sản phẩm --</option>
                                    <?php foreach ($data['products'] as $p): ?>
                                        <option value="<?php echo $p['maHH']; ?>"><?php echo $p['tenHH']; ?> (<?php echo $p['maHH']; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="qty[]" class="form-control qty-input" min="1" value="1" required oninput="calculateTotal()"></td>
                            <td><input type="number" step="0.01" name="price[]" class="form-control price-input" value="0.00" oninput="calculateTotal()"></td>
                            <td><input type="text" class="form-control line-total" value="0" readonly></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Xóa</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-5">Tổng giá trị đơn hàng:</td>
                            <td colspan="2" class="fw-bold fs-5 text-danger" id="grand-total">0 ₫</td>
                        </tr>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-secondary" onclick="addRow()">Thêm dòng</button>
                <button type="submit" class="btn btn-primary">Lưu đơn</button>
                <a href="<?php echo BASE_URL; ?>/phieudathang" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // 1. Áp dụng Select2 cho ô chọn Nhà cung cấp
    $('#select-ncc').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Chọn nhà cung cấp --',
        allowClear: true
    });

    // 2. Khởi tạo Select2 cho dòng sản phẩm đầu tiên
    initSelect2ForProduct($('.order-product-select'));
});

// Hàm khởi tạo Select2 cho các ô chọn sản phẩm
function initSelect2ForProduct(selector) {
    selector.select2({
        theme: 'bootstrap-5',
        placeholder: '-- Tìm kiếm sản phẩm --',
        allowClear: true
    }).on('select2:select', function (e) {
        // Khi chọn xong thì disable option đó ở các dòng khác
        refreshOrderProductOptions();
    }).on('select2:unselect', function (e) {
        refreshOrderProductOptions();
    });
}

function refreshOrderProductOptions() {
    // Lấy danh sách các giá trị đã được chọn
    var selectedValues = [];
    $('.order-product-select').each(function() {
        var val = $(this).val();
        if (val) selectedValues.push(val);
    });

    // Duyệt qua từng ô select để disable các option đã được chọn ở ô khác
    $('.order-product-select').each(function() {
        var currentSelect = $(this);
        var currentValue = currentSelect.val();
        
        currentSelect.find('option').each(function() {
            var optionValue = $(this).val();
            if (!optionValue) return;
            
            // Nếu option này nằm trong danh sách đã chọn, VÀ không phải là giá trị của chính ô này
            if (selectedValues.includes(optionValue) && optionValue !== currentValue) {
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false);
            }
        });
        
        // Cần trigger change để Select2 cập nhật lại giao diện (nhưng hạn chế loop)
        // Lưu ý: Select2 không tự động ẩn option disabled trong dropdown visual, nhưng sẽ không cho chọn
    });
}

function addRow() {
    var tableBody = $('#lines tbody');
    // Clone dòng đầu tiên (lưu ý: clone sẽ copy cả các class select2 đã init, cần destroy sau đó)
    var newRow = tableBody.find('tr:first').clone();
    
    // Reset inputs
    newRow.find('input[type="number"]').val(1);
    newRow.find('.price-input').val('0.00');
    newRow.find('.line-total').val('0');

    // Xử lý Select2 cho dòng mới
    // 1. Xóa container cũ của select2 (do clone copy html đã render)
    newRow.find('.select2-container').remove();
    // 2. Reset select về trạng thái thuần và bỏ chọn
    var newSelect = newRow.find('select');
    newSelect.removeClass('select2-hidden-accessible');
    newSelect.removeAttr('data-select2-id');
    newSelect.find('option').removeAttr('data-select2-id');
    newSelect.val('');
    
    // Thêm dòng vào bảng
    tableBody.append(newRow);

    // Init lại Select2 cho ô select mới
    initSelect2ForProduct(newSelect);
    
    refreshOrderProductOptions();
    calculateTotal();
}

function removeRow(btn) {
    var tbody = $('#lines tbody');
    if (tbody.find('tr').length <= 1) {
        alert('Phải có ít nhất một dòng sản phẩm.');
        return;
    }
    
    // Xóa dòng chứa nút bấm
    $(btn).closest('tr').remove();
    
    refreshOrderProductOptions();
    calculateTotal();
}

function calculateTotal() {
    var totalOrder = 0;
    
    $('#lines tbody tr').each(function() {
        var row = $(this);
        var qty = parseFloat(row.find('.qty-input').val()) || 0;
        var price = parseFloat(row.find('.price-input').val()) || 0;
        var lineTotal = qty * price;
        
        row.find('.line-total').val(lineTotal.toLocaleString('en-US'));
        totalOrder += lineTotal;
    });
    
    $('#grand-total').text(totalOrder.toLocaleString('vi-VN', {style : 'currency', currency : 'VND'}));
}
</script>
