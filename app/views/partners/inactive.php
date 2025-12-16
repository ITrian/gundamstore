<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 text-danger">Đã ngưng giao dịch</h1>
        
        <a href="<?php echo BASE_URL; ?>/partner/supplier" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <ul class="nav nav-tabs mb-4" id="trashTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-danger" id="supplier-tab" data-bs-toggle="tab" data-bs-target="#supplier-pane" type="button" role="tab">
                <i class="bi bi-shop"></i> Nhà Cung Cấp 
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-danger" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer-pane" type="button" role="tab">
                <i class="bi bi-people"></i> Khách Hàng
            </button>
        </li>
    </ul>

    <div class="tab-content" id="trashTabContent">
        
        <div class="tab-pane fade show active" id="supplier-pane" role="tabpanel">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã NCC</th>
                                    <th>Tên Nhà Cung Cấp</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Email</th>
                                    <th>Địa Chỉ</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['inactive_suppliers'])): ?>
                                    <?php foreach ($data['inactive_suppliers'] as $item): ?>
                                    <tr>
                                        <td class="fw-bold text-secondary"><?php echo $item['maNCC']; ?></td>
                                        <td class="fw-bold"><?php echo $item['tenNCC']; ?></td>
                                        <td><?php echo $item['sdt']; ?></td>
                                        <td><?php echo $item['email']; ?></td>
                                        <td><?php echo $item['diaChi']; ?></td>
                                        <td>
                                            <span class="badge bg-secondary">Ngưng giao dịch</span>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/partner/restore/supplier/<?php echo $item['maNCC']; ?>" 
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Bạn muốn kích hoạt lại NCC này để tiếp tục giao dịch?');">
                                                <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center text-muted py-4">Danh sách trống.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="customer-pane" role="tabpanel">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã KH</th>
                                    <th>Tên Khách Hàng</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Email</th>
                                    <th>Địa Chỉ</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['inactive_customers'])): ?>
                                    <?php foreach ($data['inactive_customers'] as $item): ?>
                                    <tr>
                                        <td class="fw-bold text-secondary"><?php echo $item['maKH']; ?></td>
                                        <td class="fw-bold"><?php echo $item['tenKH']; ?></td>
                                        <td><?php echo $item['sdt']; ?></td>
                                        <td><?php echo $item['email']; ?></td>
                                        <td><?php echo $item['diaChi']; ?></td>
                                        <td>
                                            <span class="badge bg-secondary">Ngưng hoạt động</span>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/partner/restore/customer/<?php echo $item['maKH']; ?>" 
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Bạn muốn kích hoạt lại Khách hàng này?');">
                                                <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center text-muted py-4">Danh sách trống.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>