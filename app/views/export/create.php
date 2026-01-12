<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo Phiếu Xuất Kho Mới</h1>
    <form action="<?php echo BASE_URL; ?>/export/store" method="POST" id="exportForm">

        <div class="alert alert-danger d-none" id="exportFormError"></div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin Khách Hàng</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="font-weight-bold">Khách hàng (*)</label>
                            <div class="d-flex gap-2">
                                <div style="flex-grow: 1;">
                                    <select id="customer" name="maKH" class="form-select" required>
                                        <option value="">-- Tìm SĐT hoặc tên khách --</option>
                                        <?php if (!empty($data['customers'])): ?>
                                            <?php foreach ($data['customers'] as $c): ?>
                                                <option value="<?php echo $c['maKH']; ?>"
                                                    data-address="<?php echo htmlspecialchars($c['diaChi'] ?? ''); ?>"
                                                    data-sdt="<?php echo htmlspecialchars($c['sdt'] ?? ''); ?>">
                                                    <?php echo htmlspecialchars($c['tenKH']); ?> (<?php echo htmlspecialchars($c['sdt'] ?? 'Chưa có SĐT'); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-outline-primary" id="addCustomerBtn" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Thêm khách mới" style="width: 50px;">
                                    <i class="bi bi-plus-lg">+</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Địa chỉ giao hàng</label>
                            <input type="text" id="customerAddress" name="customerAddress" class="form-control" readonly placeholder="Tự động hiện khi chọn khách...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Chi tiết hàng xuất</h6>
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    <i class="bi bi-plus-circle"></i> Thêm dòng
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="productTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 28%">Sản phẩm</th>
                                <th style="width: 10%">Số lượng</th>
                                <th style="width: 15%">Đơn giá xuất</th>
                                <th style="width: 15%">Serial</th>
                                <th style="width: 10%">Thành tiền</th>
                                <th style="width: 7%">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-select form-select-sm product-select" required style="width: 100%;">
                                        <option value="">-- Chọn hàng --</option>
                                        <?php foreach ($data['products'] as $p): ?>
                                            <option value="<?php echo $p['maHH']; ?>"
                                                    data-price="<?php echo $p['donGiaBan'] ?? 0; ?>"
                                                    data-cost="<?php echo $p['donGiaNhap'] ?? ($p['donGiaBan'] ?? 0); ?>"
                                                    data-loai="<?php echo $p['loaiHang'] ?? 'LO'; ?>"
                                                    data-ton="<?php echo (int)($p['tongTon'] ?? 0); ?>">
                                                <?php echo htmlspecialchars($p['tenHH']); ?> (<?php echo (int)($p['tongTon'] ?? 0); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="quantity[]" class="form-control form-control-sm qty-input text-center" min="1" value="1" required></td>
                                <td><input type="number" name="price[]" class="form-control form-control-sm price-input text-end" min="0" value="" required></td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary open-serial-modal" title="Chọn serial" style="display:none;">Serial</button>
                                    </div>
                                    <input type="hidden" name="serials[]" class="serials-hidden" value="">
                                    <div class="small text-muted serial-summary mt-1"></div>
                                </td>
                                <td><input type="text" class="form-control form-control-sm subtotal text-end fw-bold" value="0" readonly></td>
                                <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-trash"></i>X</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end font-weight-bold">TỔNG CỘNG:</td>
                                <td colspan="2" class="font-weight-bold text-primary h5" id="grandTotal">0 đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <a href="<?php echo BASE_URL; ?>/home" class="btn btn-secondary me-2">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary btn-lg">Lưu & Xuất Kho</button>
        </div>
    </form>
</div>

<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="quickAddCustomerForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm khách mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên khách (*)</label>
                    <input type="text" name="tenKH" class="form-control" required placeholder="Nhập tên khách hàng">
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại (*)</label>
                    <input type="text" name="sdt" class="form-control" required placeholder="Nhập số điện thoại">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Nhập email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="diaChi" class="form-control" placeholder="Nhập địa chỉ">
                </div>

                <div class="alert alert-danger d-none" id="modalErrorMsg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Serial selection -->
<div class="modal fade" id="serialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn Serial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="serialModalBody">Đang tải serial...</div>
                <div class="alert alert-danger d-none" id="serialModalError"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="serialModalSave">Lưu serial</button>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Init Select2 form Customer
        $('#customer').select2({
             theme: 'bootstrap-5',
             placeholder: '-- Tìm SĐT hoặc tên khách --',
             allowClear: true,
             width: '100%',
             dropdownParent: $('body')
        });

        // Init Select2 for Products
        $('.product-select').each(function() {
            initProductSelect2($(this));
        });

        // Tự động điền địa chỉ khi chọn

        $('#customer').on('change', function() {
            var selectedData = $(this).find(':selected');
            var address = selectedData.data('address') || '';
            $('#customerAddress').val(address);
        });

        // Khi chọn sản phẩm: nếu là SERIAL thì chỉ hiện nút Serial, KHÔNG mở modal ngay
        $(document).on('change', '.product-select', function() {
            var row = $(this).closest('tr');
            var loai = $(this).find('option:selected').data('loai');
            if (loai && loai.toString().toUpperCase() === 'SERIAL') {
                row.find('.open-serial-modal').show();
            } else {
                row.find('.open-serial-modal').hide();
                row.find('.serials-hidden').val('');
                row.find('.serial-summary').text('');
            }
            refreshExportProductOptions();
        });

        // Khi người dùng nhập xong số lượng và nhấn Enter, nếu là hàng SERIAL thì tự mở modal serial
        $(document).on('keydown', '.qty-input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var row = $(this).closest('tr');
                var loai = row.find('.product-select option:selected').data('loai');
                if (loai && loai.toString().toUpperCase() === 'SERIAL') {
                    row.find('.open-serial-modal').trigger('click');
                }
            }
        });

        // Serial modal open when clicking a button
        $(document).on('click', '.open-serial-modal', function() {
            var row = $(this).closest('tr');
            var maHH = row.find('.product-select').val();
            if (!maHH) { alert('Chọn sản phẩm trước'); return; }
            $('#serialModalBody').html('Đang tải serial...');
            $('#serialModalError').addClass('d-none');
            var modal = new bootstrap.Modal(document.getElementById('serialModal'));
            modal.show();
            $('#serialModal').data('row', row).data('maHH', maHH);
            // Lấy danh sách serial đã chọn trước đó từ hidden để pre-check
            var previouslySelected = [];
            var hiddenVal = row.find('.serials-hidden').val();
            if (hiddenVal) {
                try {
                    previouslySelected = JSON.parse(hiddenVal);
                } catch (e) {
                    previouslySelected = [];
                }
            }

            // Lấy số lượng mong muốn từ ô quantity để auto-chọn theo FIFO
            var desiredQty = parseInt(row.find('.qty-input').val(), 10) || 0;
            $.ajax({
                url: '<?php echo BASE_URL; ?>/product/serials',
                data: { maHH: maHH },
                dataType: 'json',
                success: function(res) {
                    if (!res.success) { $('#serialModalBody').html('<div class="text-danger">Lỗi tải serial</div>'); return; }
                    if (!res.serials || !res.serials.length) { $('#serialModalBody').html('<div>Không có serial khả dụng</div>'); return; }
                    var html = '<div class="row">';
                    res.serials.forEach(function(s, idx) {
                        // Nếu đã có lựa chọn trước đó thì ưu tiên giữ nguyên
                        var isPreviouslySelected = previouslySelected.indexOf(s.serial) !== -1;
                        var checked = '';
                        if (isPreviouslySelected) {
                            checked = ' checked';
                        } else if (!previouslySelected.length && desiredQty > 0 && idx < desiredQty) {
                            // Nếu chưa có lựa chọn cũ, tự động chọn theo FIFO đúng bằng số lượng mong muốn
                            checked = ' checked';
                        }

                        html += '<div class="col-md-4"><label class="form-check">'
                             + '<input class="form-check-input serial-check" type="checkbox" value="' + s.serial + '" data-malo="' + (s.maLo||'') + '"' + checked + '>'
                             + ' <span class="form-check-label">' + s.serial + (s.maLo?(' ('+s.maLo+')'):'') + '</span></label></div>';
                    });
                    html += '</div>';
                    $('#serialModalBody').html(html);
                }, error: function(){ $('#serialModalBody').html('<div class="text-danger">Lỗi</div>'); }
            });
        });

        // Save selected serials
        $(document).on('click', '#serialModalSave', function() {
            var modal = $('#serialModal');
            var row = modal.data('row');
            var selected = [];
            $('#serialModalBody').find('.serial-check:checked').each(function(){ selected.push($(this).val()); });
            if (!selected.length) { alert('Chọn ít nhất 1 serial'); return; }
            row.find('.serials-hidden').val(JSON.stringify(selected));
            row.find('.serial-summary').text('Đã chọn ' + selected.length + ' serial');
            row.find('.qty-input').val(selected.length);
            var modalInstance = bootstrap.Modal.getInstance(document.getElementById('serialModal'));
            modalInstance.hide();
        });



        // --- PHẦN 2: XỬ LÝ THÊM KHÁCH MỚI (AJAX) ---
        $('#quickAddCustomerForm').on('submit', function(e) {
            e.preventDefault();

            var btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Đang lưu...');
            $('#modalErrorMsg').addClass('d-none');

            $.ajax({
                url: '<?php echo BASE_URL; ?>/partner/quickAdd',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        // Tạo Option mới cho Select2
                        var displayText = res.data.tenKH + ' (' + res.data.sdt + ')';
                        var newOption = new Option(displayText, res.data.maKH, true, true);
                        // Gán dữ liệu ẩn
                        $(newOption).attr('data-address', res.data.diaChi);
                        $(newOption).attr('data-sdt', res.data.sdt);
                        if (res.data.email) {
                            $(newOption).attr('data-email', res.data.email);
                        }
                        // Thêm vào Select và chọn luôn
                        $('#customer').append(newOption).trigger('change');
                        // Đóng modal đúng chuẩn Bootstrap 5 (sử dụng getOrCreateInstance để an toàn)
                        var modalEl = document.getElementById('addCustomerModal');
                        var modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modalInstance.hide();
                        // Thêm bước dọn dẹp phòng hờ backdrop bị kẹt
                        setTimeout(function() {
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                        }, 200);
                        $('#quickAddCustomerForm')[0].reset();
                    } else {
                        $('#modalErrorMsg').text('Lỗi: ' + res.message).removeClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi:", xhr.responseText);
                    $('#modalErrorMsg').text('Lỗi hệ thống (Xem Console)').removeClass('d-none');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Lưu');
                }
            });
        });

        // --- PHẦN 3: CÁC LOGIC KHÁC ---
        function showExportError(msg) {
            $('#exportFormError').text(msg).removeClass('d-none');
            // scroll to top of form so user sees it
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function clearExportError() {
            $('#exportFormError').addClass('d-none').text('');
        }

        function validateSerialBeforeSubmit() {
            clearExportError();
            var ok = true;
            var firstMessage = '';

            $('#productTable tbody tr').each(function() {
                var row = $(this);

                var selectedOpt = row.find('.product-select option:selected');
                var loai = (selectedOpt.data('loai') || '').toString().toUpperCase();
                var ton = parseInt(selectedOpt.data('ton')) || 0;
                var qty = parseInt(row.find('.qty-input').val(), 10) || 0;

                // Kiểm tra số lượng tồn >= số lượng xuất
                if (qty > 0 && qty > ton) {
                    ok = false;
                    firstMessage = 'Số lượng xuất cho sản phẩm ' + (selectedOpt.text() || '') + ' vượt quá số lượng tồn kho .';
                    row.find('.qty-input').focus();
                    return false; // break each
                }

                // Chỉ xử lý logic serial cho hàng SERIAL
                if (loai !== 'SERIAL') return;

                var serialJson = row.find('.serials-hidden').val();
                var serials = [];
                try { serials = serialJson ? JSON.parse(serialJson) : []; } catch(e) { serials = []; }

                if (qty <= 0) {
                    ok = false;
                    firstMessage = 'Sản phẩm SERIAL phải có số lượng > 0.';
                    row.find('.qty-input').focus();
                    return false;
                }

                if (!serials.length) {
                    ok = false;
                    firstMessage = 'Bạn chưa chọn serial cho sản phẩm SERIAL.';
                    row.find('.open-serial-modal').focus();
                    return false;
                }

                if (serials.length !== qty) {
                    ok = false;
                    firstMessage = 'Số lượng SERIAL đã chọn (' + serials.length + ') phải bằng số lượng (' + qty + '). Vui lòng chọn lại serial.';
                    row.find('.open-serial-modal').focus();
                    return false;
                }
            });

            if (!ok) {
                showExportError(firstMessage);
            }
            return ok;
        }

        // Block submit if serial selection doesn't match quantity
        $('#exportForm').on('submit', function(e) {
            if (!validateSerialBeforeSubmit()) {
                e.preventDefault();
            }
        });

        function updateGrandTotal() {
            var total = 0;
            $('#productTable tbody tr').each(function() {
                var qty = parseFloat($(this).find('.qty-input').val()) || 0;
                var price = parseFloat($(this).find('.price-input').val()) || 0;
                total += qty * price;
            });
            $('#grandTotal').text(new Intl.NumberFormat('vi-VN').format(total) + ' đ');
        }

        $(document).on('input', '.qty-input, .price-input', function() {
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('.qty-input').val()) || 0;
            var price = parseFloat(row.find('.price-input').val()) || 0;
            row.find('.subtotal').val(new Intl.NumberFormat('vi-VN').format(qty * price));
            updateGrandTotal();
        });

        // If user changes qty on SERIAL row, force them to reselect serials
        $(document).on('input', '.qty-input', function() {
            var row = $(this).closest('tr');
            var loai = (row.find('.product-select option:selected').data('loai') || '').toString().toUpperCase();
            if (loai !== 'SERIAL') return;
            row.find('.serials-hidden').val('');
            row.find('.serial-summary').text('');
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
            updateGrandTotal();
        });

        // Nút thêm dòng
        $('#addRow').click(function() {
            var tableBody = $('#productTable tbody');
            var firstRow = tableBody.find('tr:first');
            var newRow = firstRow.clone(true); // Clone with events/data? No, clean it.
            
            // Clean Select2 artifacts
            newRow.find('.select2-container').remove();
            var sel = newRow.find('select.product-select');
            sel.removeClass('select2-hidden-accessible');
            sel.removeAttr('data-select2-id');
            sel.find('option').removeAttr('data-select2-id');
            sel.val('');


            // reset inputs
            newRow.find('.qty-input').val(1);
            newRow.find('.price-input').val('');
            newRow.find('.subtotal').val(0);
            newRow.find('.serials-hidden').val('');
            newRow.find('.serial-summary').text('');
            newRow.find('.open-serial-modal').hide();
            
            tableBody.append(newRow);

            // Re-init Select2
            initProductSelect2(sel);

            updateGrandTotal();
            refreshExportProductOptions();
        });

        function initProductSelect2(element) {
            element.select2({
                theme: 'bootstrap-5',
                placeholder: '-- Chọn hàng --',
                allowClear: true,
                dropdownParent: element.parent()
            }).on('select2:select', function (e) {
                 $(this).trigger('change');
            }).on('select2:unselect', function (e) {
                 $(this).trigger('change');
            });
        }


        // initial total
        updateGrandTotal();

        // Không cho chọn trùng sản phẩm trong cùng 1 phiếu xuất
        function refreshExportProductOptions() {
            var tbody = $('#productTable tbody')[0];
            if (!tbody) return;
            var selectedValues = [];
            $(tbody).find('select.product-select').each(function() {
                var val = $(this).val();
                if (val) selectedValues.push(val);
            });

            $(tbody).find('select.product-select').each(function() {
                var currentSelect = $(this);
                var current = currentSelect.val();
                currentSelect.find('option').each(function() {
                    var v = $(this).attr('value');
                    if (!v) return; // placeholder
                    if (v !== current && selectedValues.indexOf(v) !== -1) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
                
                // Select2 does not automatically refresh disabled options visualization unless triggered or re-inited
                // Use a trick or just let the validation catch it? 
                // Select2 respects 'disabled' property if re-rendered? 
                // We can trigger 'change.select2' 
            });
        }

        // chạy lần đầu
        refreshExportProductOptions();
    });
</script>