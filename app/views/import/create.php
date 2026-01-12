<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    .select2-results__option[aria-disabled="true"] {
        display: none !important;
    }
</style>

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
                            <select name="maNCC" id="importSupplier" class="form-select" required>
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
                                <th style="width: 12%">Hạn Bảo Hành (Lô)</th>
                                <th style="width: 14%">Số lượng</th>
                                <th style="width: 15%">Đơn giá nhập</th>
                                <th style="width: 12%">Thành tiền</th>
                                <th style="width: 7%">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <select name="product_id[]" class="form-select form-select-sm import-product-select" required style="min-width:220px;">
                                            <option value="">-- Chọn hàng --</option>
                                            <?php foreach ($data['products'] as $p): ?>
                                                <option value="<?php echo $p['maHH']; ?>" data-loai="<?php echo $p['loaiHang']; ?>">
                                                    <?php echo $p['tenHH']; ?> (<?php echo $p['maHH']; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="badge bg-secondary type-badge">LO</span>
                                    </div>
                                </td>
                               <td>
                                    <input type="date" name="expiry[]" class="form-control form-control-sm" 
                                        min="<?php echo date('Y-m-d'); ?>">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" name="quantity[]" class="form-control form-control-sm qty-input" min="1" value="1" required>
                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2 open-serial-modal" title="Nhập serial" style="white-space:nowrap;">Nhập serial</button>
                                    </div>
                                    <input type="hidden" name="serials[]" class="serials-hidden">
                                </td>
                                <td>
                                    <input type="number" name="price[]" class="form-control form-control-sm price-input" min="0" value="0" required>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    function initImportSelect2(context) {
        var $ctx = context ? $(context) : $(document);

        $('#importSupplier').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Chọn NCC --',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0,
            dropdownParent: $('body')
        });

        $ctx.find('.import-product-select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Chọn hàng --',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0,
            dropdownParent: $('body')
        });
    }

    // [LOGIC QUAN TRỌNG] Ẩn sản phẩm đã chọn
    function refreshImportProductOptions() {
        var $rows = $('#productTable tbody tr');
        
        // 1. Lấy danh sách mã hàng đã được chọn (trừ dòng trống)
        var selectedValues = [];
        $rows.each(function() {
            var val = $(this).find('.import-product-select').val();
            if (val) selectedValues.push(val);
        });

        // 2. Duyệt qua từng dòng và từng option
        $rows.each(function() {
            var $select = $(this).find('.import-product-select');
            var currentValue = $select.val();

            $select.find('option').each(function() {
                var optionValue = $(this).val();
                if (!optionValue) return; // Bỏ qua option placeholder

                // Nếu giá trị này đã được chọn ở dòng khác -> Disable nó
                // (CSS ở trên sẽ lo việc ẩn những cái bị disabled đi)
                if (selectedValues.indexOf(optionValue) !== -1 && optionValue !== currentValue) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
            
            // Cần thiết để Select2 cập nhật lại trạng thái ngay lập tức nếu đang mở
            // Tuy nhiên với disabled + CSS, Select2 thường tự render lại khi mở dropdown
        });
    }

    $(document).ready(function() {
        initImportSelect2();
        refreshImportProductOptions();

        // Khi thay đổi sản phẩm -> Cập nhật lại toàn bộ danh sách
        $(document).on('change', '.import-product-select', function() {
            refreshImportProductOptions();
            
            // Cập nhật giao diện LO/SERIAL (như cũ)
            var loai = this.options[this.selectedIndex] ? this.options[this.selectedIndex].getAttribute('data-loai') : null;
            var tr = this.closest('tr');
            var qty = tr.querySelector('input[name="quantity[]"]');
            var serialBtn = tr.querySelector('.open-serial-modal');
            var badge = tr.querySelector('.type-badge');
            
            if (loai === 'SERIAL') {
                if (serialBtn) serialBtn.style.display = '';
                if (badge) { badge.innerText = 'SERIAL'; badge.className = 'badge bg-success type-badge'; }
            } else {
                if (serialBtn) serialBtn.style.display = 'none';
                if (badge) { badge.innerText = 'LO'; badge.className = 'badge bg-secondary type-badge'; }
            }
            
            // Tính lại tiền
            var ev = new Event('input', { bubbles: true });
            if (qty) qty.dispatchEvent(ev);
        });

        // Thêm dòng mới
        document.getElementById('addRow').addEventListener('click', function() {
            var tableBody = document.querySelector('#productTable tbody');
            var firstRow = tableBody.rows[0];
            
            // Hủy Select2 ở dòng mẫu trước khi clone để tránh lỗi sự kiện
            var $firstSelect = $(firstRow).find('.import-product-select');
            if ($firstSelect.data('select2')) {
                $firstSelect.select2('destroy');
            }

            var newRow = firstRow.cloneNode(true);

            // Tái khởi tạo lại Select2 cho dòng đầu (vì vừa bị destroy)
            initImportSelect2(firstRow); 
            
            // Reset dữ liệu dòng mới
            $(newRow).find('input').val('');
            $(newRow).find('input[type="number"]').val(0);
            $(newRow).find('input[name="quantity[]"]').val(1);
            $(newRow).find('select').val(''); 
            $(newRow).find('.type-badge').text('LO').attr('class', 'badge bg-secondary type-badge');
            $(newRow).find('.open-serial-modal').hide();

            tableBody.appendChild(newRow);

            // Khởi tạo Select2 cho dòng mới
            initImportSelect2(newRow);
            updateEvents(); 

            // QUAN TRỌNG: Gọi hàm này để ẩn các sản phẩm đã chọn ở trên khỏi dòng mới
            refreshImportProductOptions();
        });

        // Xóa dòng
        document.getElementById('productTable').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeRow')) {
                var rowCount = document.getElementById('productTable').tBodies[0].rows.length;
                if (rowCount > 1) {
                    e.target.closest('tr').remove();
                    calculateTotal();
                    refreshImportProductOptions(); // Gọi lại để nhả (hiện lại) sản phẩm của dòng vừa xóa
                } else {
                    alert('Phải có ít nhất 1 dòng sản phẩm!');
                }
            }
        });
        
        // --- CÁC HÀM TÍNH TOÁN & MODAL (GIỮ NGUYÊN) ---
        function updateEvents() {
            var qtyInputs = document.querySelectorAll('.qty-input');
            var priceInputs = document.querySelectorAll('.price-input');
            var dateInputs = document.querySelectorAll('input[type="date"]');
            var serialHidden = document.querySelectorAll('.serials-hidden');

            qtyInputs.forEach(input => { input.oninput = calculateRow; });
            priceInputs.forEach(input => { input.oninput = calculateRow; });
            serialHidden.forEach(sh => { sh.onchange = calculateRow; });
            
            dateInputs.forEach(input => {
                input.onchange = function() {
                    var today = new Date().toISOString().split('T')[0];
                    if(this.value && this.value < today) {
                        alert('Hạn bảo hành không được nhỏ hơn ngày hiện tại!');
                        this.value = ''; 
                    }
                };
            });
        }

        function calculateRow(e) {
            var row = e.target.closest('tr');
            var price = parseFloat(row.querySelector('.price-input').value) || 0;
            var qty = parseInt(row.querySelector('.qty-input').value) || 0;
            var subtotal = qty * price;
            row.querySelector('.subtotal').value = new Intl.NumberFormat('vi-VN').format(subtotal);
            calculateTotal();
        }

        function calculateTotal() {
            var total = 0;
            var rows = document.querySelectorAll('#productTable tbody tr');
            rows.forEach(row => {
                var price = parseFloat(row.querySelector('.price-input').value) || 0;
                var qty = parseInt(row.querySelector('.qty-input').value) || 0;
                total += (qty * price);
            });
            document.getElementById('grandTotal').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
        }

        var currentRowIndex = null;
        document.addEventListener('click', function(e){
            if(e.target && e.target.classList.contains('open-serial-modal')){
                openSerialModal(e);
            }
        });

        function openSerialModal(evt) {
            var btn = (evt.currentTarget) ? evt.currentTarget : evt.target;
            var tr = btn.closest('tr');
            var table = document.querySelector('#productTable tbody');
            var rows = Array.prototype.slice.call(table.querySelectorAll('tr'));
            currentRowIndex = rows.indexOf(tr);

            var qty = parseInt(tr.querySelector('input[name="quantity[]"]').value) || 0;
            if (qty <= 0) qty = 1;

            var hidden = tr.querySelector('.serials-hidden');
            var existing = [];
            if (hidden && hidden.value.trim() !== '') {
                existing = hidden.value.split(/\r\n|\r|\n/).map(s => s.trim()).filter(s => s !== '');
            }

            renderSerialModalRows(qty, existing);
            var modal = document.getElementById('serialModal');
            var bs = bootstrap.Modal.getOrCreateInstance(modal);
            bs.show();
        }

        function renderSerialModalRows(count, existing) {
            var body = document.querySelector('#serialModal tbody');
            body.innerHTML = '';
            for (var i = 0; i < count; i++) {
                var val = existing[i] || '';
                var tr = document.createElement('tr');
                tr.innerHTML = '<td style="width:50px">' + (i+1) + '</td>' +
                    '<td><div class="input-group"><input type="text" class="form-control serial-input" value="' + escapeHtml(val) + '" placeholder="Nhập serial"></div></td>' +
                    '<td style="width:120px"><button type="button" class="btn btn-sm btn-outline-primary scan-serial">Quét</button></td>';
                body.appendChild(tr);
            }
            
            body.querySelectorAll('.scan-serial').forEach(btn => {
                btn.onclick = function(e){
                    var row = e.currentTarget.closest('tr');
                    var input = row.querySelector('.serial-input');
                    var code = 'SCAN-' + Date.now().toString().slice(-6) + '-' + Math.floor(Math.random()*100);
                    input.value = code;
                };
            });
        }

        document.getElementById('serialSaveBtn').addEventListener('click', function(){
            var modal = document.getElementById('serialModal');
            var rows = modal.querySelectorAll('tbody tr');
            var vals = [];
            rows.forEach(r => {
                var v = r.querySelector('.serial-input').value.trim();
                if (v !== '') vals.push(v);
            });

            var table = document.querySelector('#productTable tbody');
            var tr = table.querySelectorAll('tr')[currentRowIndex];
            if (tr) {
                var hidden = tr.querySelector('.serials-hidden');
                if (hidden) hidden.value = vals.join('\n');
                if (vals.length > 0) {
                    var qtyInput = tr.querySelector('input[name="quantity[]"]');
                    if (qtyInput) {
                        qtyInput.value = vals.length;
                        qtyInput.dispatchEvent(new Event('input', { bubbles: true })); 
                    }
                }
            }
            var bs = bootstrap.Modal.getInstance(modal);
            bs.hide();
        });

        function escapeHtml(s) { return (s+'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
        updateEvents();
    });
</script>

<div class="modal fade" id="serialModal" tabindex="-1" aria-labelledby="serialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serialModalLabel">Nhập Serials</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light"><tr><th>#</th><th>Serial</th><th></th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="serialSaveBtn">Lưu Serials</button>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>