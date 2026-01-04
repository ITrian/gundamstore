<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo $data['title']; ?></h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Thông tin đơn</h5>
            <p><strong>Mã DH:</strong> <?php echo $data['order']['header']['maDH']; ?></p>
            <p><strong>Nhà cung cấp:</strong> <?php echo $data['order']['header']['tenNCC'] ?? $data['order']['header']['maNCC']; ?></p>
            <p><strong>Ngày đặt:</strong> <?php echo $data['order']['header']['ngayDatHang']; ?></p>

            <h5 class="mt-4">Chi tiết</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã HH</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['order']['lines'] as $ln): ?>
                            <tr>
                                <td><?php echo $ln['maHH']; ?></td>
                                <td><?php echo $ln['soLuong']; ?></td>
                                <td><?php echo $ln['donGia']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <a href="<?php echo BASE_URL; ?>/phieudathang" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
