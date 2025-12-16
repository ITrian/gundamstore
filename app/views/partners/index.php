<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Đối tác kinh doanh</h1>
        
        <a href="<?php echo BASE_URL; ?>/partner/inactive" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-trash"></i> Đã Ngưng Giao Dịch
        </a>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?php echo ($data['type'] == 'supplier') ? 'active fw-bold' : ''; ?>" 
               href="<?php echo BASE_URL; ?>/partner/supplier">
               <i class="bi bi-shop"></i> Nhà Cung Cấp
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($data['type'] == 'customer') ? 'active fw-bold' : ''; ?>" 
               href="<?php echo BASE_URL; ?>/partner/customer">
               <i class="bi bi-people"></i> Khách Hàng
            </a>
        </li>
    </ul>

    <?php 
        $createLink = ($data['type'] == 'supplier') ? 'create_supplier' : 'create_customer';
        $btnLabel = ($data['type'] == 'supplier') ? 'Nhà cung cấp' : 'Khách hàng';
    ?>
    <a href="<?php echo BASE_URL; ?>/partner/<?php echo $createLink; ?>" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Thêm <?php echo $btnLabel; ?>
    </a>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã Đối Tác</th>
                            <th>Tên Đơn Vị / Khách Hàng</th>
                            <th>Số Điện Thoại</th>
                            <th>Email</th>
                            <th>Địa Chỉ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['list'])): ?>
                            <?php foreach ($data['list'] as $item): ?>
                                <?php 
                                    $code = ($data['type'] == 'supplier') ? $item['maNCC'] : $item['maKH'];
                                    $name = ($data['type'] == 'supplier') ? $item['tenNCC'] : $item['tenKH'];
                                ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?php echo $code; ?></td>
                                    <td class="fw-bold"><?php echo $name; ?></td>
                                    <td><?php echo $item['sdt']; ?></td>
                                    <td><?php echo $item['email']; ?></td>
                                    <td><?php echo $item['diaChi']; ?></td>
                                    <td>
                                        <span class="badge bg-success">Hoạt động</span>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/partner/edit/<?php echo $data['type']; ?>/<?php echo $code; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                           <i class="bi bi-pencil"></i> Sửa
                                        </a>

                                        <a href="<?php echo BASE_URL; ?>/partner/delete/<?php echo $data['type']; ?>/<?php echo $code; ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa đối tác này? Nếu đã có giao dịch, hệ thống sẽ chuyển sang trạng thái ngưng hoạt động.');">
                                           <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>