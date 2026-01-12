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
                    <label class="form-label">Nhà cung cấp</label>
                    <select name="maNCC" class="form-control" required>
                        <option value="">-- Chọn NCC --</option>
                        <?php foreach ($data['suppliers'] as $s): ?>
                            <option value="<?php echo $s['maNCC']; ?>"><?php echo $s['tenNCC']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h5>Sản phẩm</h5>
                <table class="table" id="lines">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="product[]" class="form-control order-product-select" required>
                                    <option value="">-- Chọn sản phẩm --</option>
                                    <?php foreach ($data['products'] as $p): ?>
                                        <option value="<?php echo $p['maHH']; ?>"><?php echo $p['tenHH']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="qty[]" class="form-control" min="1" value="1" required></td>
                            <td><input type="number" step="0.01" name="price[]" class="form-control" value="0.00"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Xóa</button></td>
                        </tr>
                    </tbody>
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
function refreshOrderProductOptions() {
    var tbody = document.getElementById('lines').getElementsByTagName('tbody')[0];
    var selectedValues = [];
    var selects = tbody.querySelectorAll('select.order-product-select');
    selects.forEach(function(sel) {
        var val = sel.value;
        if (val) selectedValues.push(val);
    });

    selects.forEach(function(sel) {
        var current = sel.value;
        var options = sel.querySelectorAll('option');
        options.forEach(function(opt) {
            if (!opt.value) return; // skip placeholder
            // Cho phép hiển thị option đang chọn ở chính select đó
            if (opt.value !== current && selectedValues.indexOf(opt.value) !== -1) {
                opt.disabled = true;
            } else {
                opt.disabled = false;
            }
        });
    });
}

function addRow() {
    var table = document.getElementById('lines').getElementsByTagName('tbody')[0];
    var row = table.rows[0].cloneNode(true);
    var selects = row.getElementsByTagName('select');
    for (var i=0;i<selects.length;i++) selects[i].selectedIndex = 0;
    var inputs = row.getElementsByTagName('input');
    for (var i=0;i<inputs.length;i++) {
        if (inputs[i].type === 'number') {
            inputs[i].value = (inputs[i].name === 'qty[]') ? 1 : '0.00';
        } else {
            inputs[i].value = '';
        }
    }
    table.appendChild(row);
    refreshOrderProductOptions();
}

function removeRow(btn) {
    var tbody = document.getElementById('lines').getElementsByTagName('tbody')[0];
    if (tbody.rows.length <= 1) return;
    var row = btn.parentNode.parentNode;
    tbody.removeChild(row);
    refreshOrderProductOptions();
}

document.addEventListener('DOMContentLoaded', function() {
    // gắn sự kiện change cho tất cả select sản phẩm trong đơn đặt hàng
    var tbody = document.getElementById('lines').getElementsByTagName('tbody')[0];
    tbody.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('order-product-select')) {
            refreshOrderProductOptions();
        }
    });
    refreshOrderProductOptions();
});
</script>
