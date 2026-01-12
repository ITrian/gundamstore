<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    .select2-results__option[aria-disabled="true"] {
        display: none !important;
    }
</style>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo $data['title']; ?></h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/phieudathang/store" method="post" id="orderForm">
                <div class="mb-3">
                    <label class="form-label">Nhà cung cấp</label>
                    <select name="maNCC" id="orderSupplier" class="form-select" required>
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
                            <th style="width: 40%">Sản phẩm</th>
                            <th style="width: 20%">Số lượng</th>
                            <th style="width: 30%">Đơn giá</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="product[]" class="form-select order-product-select" required style="width: 100%;">
                                    <option value="">-- Chọn sản phẩm --</option>
                                    <?php foreach ($data['products'] as $p): ?>
                                        <option value="<?php echo $p['maHH']; ?>"><?php echo $p['tenHH']; ?> (<?php echo $p['maHH']; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="qty[]" class="form-control" min="1" value="1" required></td>
                            <td><input type="number" step="0.01" name="price[]" class="form-control" value="0.00"></td>
                            <td><button type="button" class="btn btn-danger btn-sm removeRow">Xóa</button></td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-secondary" id="btnAddRow">Thêm dòng</button>
                <button type="submit" class="btn btn-primary">Lưu đơn</button>
                <a href="<?php echo BASE_URL; ?>/phieudathang" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    function initOrderSelect2(context) {
        var $ctx = context ? $(context) : $(document);

        $('#orderSupplier').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Chọn NCC --',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0,
            dropdownParent: $('body')
        });

        $ctx.find('.order-product-select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Chọn sản phẩm --',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0,
            dropdownParent: $('body')
        });
    }

    // [LOGIC QUAN TRỌNG] Ẩn các sản phẩm đã được chọn ở dòng khác
    function refreshOrderProductOptions() {
        var $rows = $('#lines tbody tr');

        // 1. Thu thập các mã hàng đã chọn
        var selected = [];
        $rows.each(function() {
            var val = $(this).find('.order-product-select').val();
            if (val) selected.push(val);
        });

        // 2. Với mỗi select, Disable các option đã được chọn ở dòng khác
        // CSS ở đầu file sẽ tự động ẩn (display: none) các option bị disabled
        $rows.each(function() {
            var $row = $(this);
            var $sel = $row.find('.order-product-select');
            
            var currentVal = $sel.val();

            $sel.find('option').each(function() {
                var optVal = $(this).attr('value') || '';
                if (!optVal) return; // Bỏ qua option placeholder

                if (selected.indexOf(optVal) !== -1 && optVal !== currentVal) {
                    $(this).prop('disabled', true); // Disable -> CSS sẽ ẩn đi
                } else {
                    $(this).prop('disabled', false);
                }
            });
        });
    }

    $(document).ready(function() {
        initOrderSelect2();
        refreshOrderProductOptions();

        // Khi thay đổi sản phẩm -> Cập nhật lại danh sách cho các dòng khác
        $(document).on('change', '.order-product-select', function() {
            refreshOrderProductOptions();
        });

        // Thêm dòng mới
        $('#btnAddRow').click(function() {
            var tableBody = document.querySelector('#lines tbody');
            var firstRow = tableBody.rows[0];

            // 1. Hủy Select2 ở dòng mẫu trước khi clone
            var $firstSelect = $(firstRow).find('.order-product-select');
            if ($firstSelect.data('select2')) {
                $firstSelect.select2('destroy');
            }

            // 2. Clone dòng
            var newRow = firstRow.cloneNode(true);

            // 3. Khôi phục Select2 cho dòng mẫu ngay lập tức
            initOrderSelect2(firstRow);

            // 4. Reset giá trị trong dòng mới
            $(newRow).find('input').val('');
            $(newRow).find('input[type="number"][name="qty[]"]').val(1);
            $(newRow).find('input[type="number"][name="price[]"]').val('0.00');
            $(newRow).find('select').val(''); // Reset select

            // 5. Thêm dòng mới vào bảng
            tableBody.appendChild(newRow);

            // 6. Khởi tạo Select2 cho dòng mới
            initOrderSelect2(newRow);

            // 7. Cập nhật lại danh sách option (ẩn những món đã chọn ở trên)
            refreshOrderProductOptions();
        });

        // Xóa dòng
        $(document).on('click', '.removeRow', function() {
            var tbody = document.querySelector('#lines tbody');
            if (tbody.rows.length > 1) {
                $(this).closest('tr').remove();
                refreshOrderProductOptions(); // Gọi lại để hiện lại sản phẩm vừa xóa
            } else {
                alert('Phải có ít nhất 1 dòng sản phẩm!');
            }
        });
    });
</script>