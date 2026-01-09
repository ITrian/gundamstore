<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo Phiếu Xuất Kho Mới</h1>
    <form action="<?php echo BASE_URL; ?>/export/store" method="POST" id="exportForm">

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
                                <th style="width: 30%">Sản phẩm</th>
                                <th style="width: 14%">Số lượng</th>
                                <th style="width: 15%">Đơn giá xuất</th>
                                <th style="width: 14%">Vị trí xuất</th>
                                <th style="width: 12%">Thành tiền</th>
                                <th style="width: 7%">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-select form-select-sm product-select" required style="width: 100%;">
                                        <option value="">-- Chọn hàng --</option>
                                        <?php foreach ($data['products'] as $p): ?>
                                            <option value="<?php echo $p['maHH']; ?>" data-price="<?php echo $p['donGiaBan'] ?? 0; ?>">
                                                <?php echo htmlspecialchars($p['tenHH']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="quantity[]" class="form-control form-control-sm qty-input text-center" min="1" value="1" required></td>
                                <td><input type="number" name="price[]" class="form-control form-control-sm price-input text-end" min="0" value="0" required></td>
                                <td>
                                    <select name="vitri[]" class="form-select form-select-sm" required>
                                        <option value="">-- Chọn --</option>
                                        <?php if (!empty($data['vitri'])): ?>
                                            <?php foreach ($data['vitri'] as $vt): ?>
                                                <option value="<?php echo $vt['maViTri']; ?>">
                                                    <?php echo $vt['maViTri']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // --- PHẦN 1: KÍCH HOẠT Ô TÌM KIẾM KHÁCH HÀNG ---
        $('#customer').select2({
            theme: 'bootstrap-5', // Dùng giao diện Bootstrap 5 cho đẹp
            placeholder: '-- Tìm SĐT hoặc tên khách --',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0, // BẮT BUỘC hiện ô tìm kiếm dù ít dữ liệu
            dropdownParent: $('body')
        });

        // Tự động điền địa chỉ khi chọn
        $('#customer').on('change', function() {
            var selectedData = $(this).find(':selected');
            var address = selectedData.data('address') || '';
            $('#customerAddress').val(address);
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
                        // Đóng modal đúng chuẩn Bootstrap 5
                        var modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                        if (modal) modal.hide();
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
        $(document).on('input', '.qty-input, .price-input', function() {
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('.qty-input').val()) || 0;
            var price = parseFloat(row.find('.price-input').val()) || 0;
            row.find('.subtotal').val(new Intl.NumberFormat('vi-VN').format(qty * price));
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });

        // Nút thêm dòng
        $('#addRow').click(function() {
            var newRow = $('#productTable tbody tr:first').clone();
            newRow.find('input').val('');
            newRow.find('.qty-input').val(1);
            newRow.find('.price-input').val(0);
            newRow.find('.subtotal').val(0);
            $('#productTable tbody').append(newRow);
        });
    });
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>