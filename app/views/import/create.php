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
                                <th style="width: 10%">Số lượng</th>
                                <th style="width: 18%">Serials (nếu có)</th>
                                <th style="width: 15%">Đơn giá nhập</th>
                                <th style="width: 10%">Thành tiền</th>
                                <th style="width: 5%">Xóa</th>
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
                                    <input type="number" name="quantity[]" class="form-control form-control-sm qty-input" min="1" value="1" required>
                                </td>
                                    <td style="vertical-align:top;">
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-outline-primary btn-sm me-2 simulate-scan" title="Giả lập quét">Quét</button>
                                            <textarea name="serials[]" class="form-control serials-input" placeholder="Nhập từng serial trên 1 dòng" style="min-height:80px; width:100%;"></textarea>
                                        </div>
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
        // Reset textarea for serials
        var ta = newRow.querySelector('.serials-input');
        if (ta) ta.value = '';
        
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
        // listen to serials changes to recalc
        var serialInputs = document.querySelectorAll('.serials-input');
        serialInputs.forEach(function(si){ si.oninput = calculateRow; });
        
    // product change: toggle serials vs quantity
        var selects = document.querySelectorAll('select[name="product_id[]"]');
        selects.forEach(sel => {
            sel.onchange = function() {
                var loai = this.options[this.selectedIndex] ? this.options[this.selectedIndex].getAttribute('data-loai') : null;
                var tr = this.closest('tr');
                var qty = tr.querySelector('input[name="quantity[]"]');
                var serials = tr.querySelector('textarea[name="serials[]"]');
                var badge = tr.querySelector('.type-badge');
                if (loai === 'SERIAL') {
                    if (qty) qty.style.display = 'none';
                    if (serials) serials.parentElement.style.display = '';
                    if (badge) { badge.innerText = 'SERIAL'; badge.className = 'badge bg-success type-badge'; }
                } else {
                    if (qty) qty.style.display = '';
                    if (serials) serials.parentElement.style.display = 'none';
                    if (badge) { badge.innerText = 'LO'; badge.className = 'badge bg-secondary type-badge'; }
                }
                // recalc row after changing type
                var ev = new Event('input', { bubbles: true });
                if (serials) serials.dispatchEvent(ev);
                if (qty) qty.dispatchEvent(ev);
            };
        });

        // simulate scan buttons
        var simButtons = document.querySelectorAll('.simulate-scan');
        simButtons.forEach(function(btn){
            btn.onclick = function() {
                var tr = btn.closest('tr');
                var ta = tr.querySelector('.serials-input');
                if (!ta) return;
                // generate a fake scanned code (you may replace with prompt or external input)
                var code = 'SCAN-' + Date.now() + '-' + Math.floor(Math.random()*1000);
                // append to textarea (simulate scanner entering then ENTER)
                if (ta.value && ta.value.trim() !== '') {
                    ta.value = ta.value.trim() + '\n' + code + '\n';
                } else {
                    ta.value = code + '\n';
                }
                // focus and dispatch input/change events so app recalculates
                ta.focus();
                ta.dispatchEvent(new Event('input', { bubbles: true }));
            };
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
        var price = parseFloat(row.querySelector('.price-input').value) || 0;

        // If serials textarea is visible and has values, compute qty from serial lines
        var serialsTa = row.querySelector('.serials-input');
        var qty = 0;
        if (serialsTa && serialsTa.parentElement.style.display !== 'none') {
            var lines = serialsTa.value.split(/\r\n|\r|\n/).map(function(s){ return s.trim(); }).filter(function(s){ return s !== ''; });
            qty = lines.length;
            // reflect qty in hidden qty input for backend
            var qtyInput = row.querySelector('.qty-input');
            if (qtyInput) qtyInput.value = qty;
        } else {
            qty = parseInt(row.querySelector('.qty-input').value) || 0;
        }

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
            var serialsTa = row.querySelector('.serials-input');
            var qty = 0;
            if (serialsTa && serialsTa.parentElement.style.display !== 'none') {
                var lines = serialsTa.value.split(/\r\n|\r|\n/).map(function(s){ return s.trim(); }).filter(function(s){ return s !== ''; });
                qty = lines.length;
            } else {
                qty = parseInt(row.querySelector('.qty-input').value) || 0;
            }
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
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>