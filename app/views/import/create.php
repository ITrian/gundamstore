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
                                            <select name="product_id[]" class="form-select form-select-sm" required style="min-width:220px;">
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
                                        <!-- hidden container to store serials for this row -->
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
    // Reset hidden serials value
    var sh = newRow.querySelector('.serials-hidden');
    if (sh) sh.value = '';
        
        // Reset Select box
        newRow.getElementsByTagName('select')[0].value = '';
        
        table.appendChild(newRow);
        updateEvents(); // Gán lại sự kiện tính tiền cho dòng mới
        // Trigger change on the new select so visibility/badge updates
        var newSelect = newRow.querySelector('select[name="product_id[]"]');
        if (newSelect) newSelect.dispatchEvent(new Event('change'));
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

    // Use assignment to avoid duplicate handlers when re-initializing
    qtyInputs.forEach(input => { input.oninput = calculateRow; });
    priceInputs.forEach(input => { input.oninput = calculateRow; });
    // hidden serials do not drive UI directly; modal will update them
    var serialHidden = document.querySelectorAll('.serials-hidden');
    serialHidden.forEach(function(sh){ sh.onchange = calculateRow; });
        
    // product change: toggle serials vs quantity
        var selects = document.querySelectorAll('select[name="product_id[]"]');
        selects.forEach(sel => {
            sel.onchange = function() {
                var loai = this.options[this.selectedIndex] ? this.options[this.selectedIndex].getAttribute('data-loai') : null;
                var tr = this.closest('tr');
                var qty = tr.querySelector('input[name="quantity[]"]');
                var serialBtn = tr.querySelector('.open-serial-modal');
                var badge = tr.querySelector('.type-badge');
                if (loai === 'SERIAL') {
                    // for serial-managed products we still show qty and allow serial input via modal
                    if (serialBtn) serialBtn.style.display = '';
                    if (badge) { badge.innerText = 'SERIAL'; badge.className = 'badge bg-success type-badge'; }
                } else {
                    if (serialBtn) serialBtn.style.display = 'none';
                    if (badge) { badge.innerText = 'LO'; badge.className = 'badge bg-secondary type-badge'; }
                }
                // recalc row after changing type
                var ev = new Event('input', { bubbles: true });
                if (qty) qty.dispatchEvent(ev);
            };
        });

    // serial modal open buttons
        var serialOpenBtns = document.querySelectorAll('.open-serial-modal');
        serialOpenBtns.forEach(function(btn){ btn.onclick = openSerialModal; });
        
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
        var price = parseFloat(row.querySelector('.price-input').value) || 0;
        // Qty always from qty input; serials are stored in hidden input populated from modal
        var qty = parseInt(row.querySelector('.qty-input').value) || 0;

        var subtotal = qty * price;
        
        // Format tiền tệ
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

    // Khởi chạy lần đầu
    updateEvents();
    // Trigger change on existing selects to set correct visibility
    document.querySelectorAll('select[name="product_id[]"]').forEach(function(s){
        var ev = new Event('change'); s.dispatchEvent(ev);
    });

    // --- Serial modal logic ---
    // Modal elements (we'll create modal HTML below)
    var currentRowIndex = null;

    function openSerialModal(evt) {
        var btn = (evt.currentTarget) ? evt.currentTarget : evt;
        var tr = btn.closest('tr');
        var table = document.querySelector('#productTable tbody');
        // compute index of the row within tbody
        var rows = Array.prototype.slice.call(table.querySelectorAll('tr'));
        var idx = rows.indexOf(tr);
        currentRowIndex = idx;

        // get qty
        var qty = parseInt(tr.querySelector('input[name="quantity[]"]').value) || 0;
        if (qty <= 0) qty = 1;

        // get existing serials from hidden input
        var hidden = tr.querySelector('.serials-hidden');
        var existing = [];
        if (hidden && hidden.value.trim() !== '') {
            existing = hidden.value.split(/\r\n|\r|\n/).map(function(s){ return s.trim(); }).filter(function(s){ return s !== ''; });
        }

        renderSerialModalRows(qty, existing);
        var modal = document.getElementById('serialModal');
        if (modal) {
            var bs = new bootstrap.Modal(modal);
            bs.show();
            modal._bs = bs;
        }
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

        // wire scan buttons
        document.querySelectorAll('#serialModal .scan-serial').forEach(function(b){
            b.onclick = function(e){
                var row = e.currentTarget.closest('tr');
                var input = row.querySelector('.serial-input');
                if (!input) return;
                var code = 'SCAN-' + Date.now() + '-' + Math.floor(Math.random()*1000);
                input.value = code;
            };
        });
    }

    // Save serials from modal into hidden input of the row
    (function(){
        function saveHandler(){
            var modal = document.getElementById('serialModal');
            var rows = modal.querySelectorAll('tbody tr');
            var vals = [];
            rows.forEach(function(r){
                var v = r.querySelector('.serial-input').value.trim();
                if (v !== '') vals.push(v);
            });

            // find the row
            var table = document.querySelector('#productTable tbody');
            var tr = table.querySelectorAll('tr')[currentRowIndex];
            if (tr) {
                var hidden = tr.querySelector('.serials-hidden');
                if (hidden) hidden.value = vals.join('\n');
                // also update qty to match number of serials if desired
                if (vals.length > 0) {
                    var qtyInput = tr.querySelector('input[name="quantity[]"]');
                    if (qtyInput) qtyInput.value = vals.length;
                    qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }

            // hide modal
            if (modal && modal._bs) modal._bs.hide();
        }

        var btn = document.getElementById('serialSaveBtn');
        if (btn) {
            btn.addEventListener('click', saveHandler);
        } else {
            // if not yet present, attach after DOM ready
            document.addEventListener('DOMContentLoaded', function(){
                var b2 = document.getElementById('serialSaveBtn');
                if (b2) b2.addEventListener('click', saveHandler);
            });
        }
    })();

    // Utility: escape HTML for insertion into value
    function escapeHtml(s) { return (s+'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
</script>

<!-- Serial input modal -->
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
                        <tbody>
                            <!-- rows injected here -->
                        </tbody>
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