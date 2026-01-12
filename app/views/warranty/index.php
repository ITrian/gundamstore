<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tra cứu & Bảo hành</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="" method="GET" class="d-flex gap-2">
                <input type="text" name="keyword" class="form-control form-control-lg" 
                       placeholder="Nhập Serial hoặc Mã hàng" 
                       value="<?php echo htmlspecialchars($data['keyword']); ?>" required>
                <button type="submit" class="btn btn-primary btn-lg">Tra cứu</button>
            </form>
            <?php if ($data['message']) echo "<div class='alert alert-danger mt-3'>{$data['message']}</div>"; ?>
        </div>
    </div>

    <?php if ($data['type'] == 'SINGLE'): $info = $data['result']; ?>
        <div class="card shadow border-left-success">
            <div class="card-header"><h6 class="font-weight-bold text-success">Thông tin bảo hành (Theo Serial)</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Sản phẩm:</strong> <?php echo $info['tenHH']; ?> (<?php echo $info['maHH']; ?>)</p>
                        <p><strong>Serial:</strong> <span class="text-danger fw-bold"><?php echo $info['serial']; ?></span></p>
                        <p><strong>NCC:</strong> <?php echo $info['tenNCC']; ?></p>
                        <p><strong>Hạn BH:</strong> <?php echo date('d/m/Y', strtotime($info['hanBaoHanh'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <form action="<?php echo BASE_URL; ?>/warranty/create" method="POST">
                            <input type="hidden" name="maHH" value="<?php echo $info['maHH']; ?>">
                            <input type="hidden" name="serial" value="<?php echo $info['serial']; ?>">
                            
                            <label>Mô tả lỗi:</label>
                            <textarea name="moTaLoi" class="form-control mb-2" rows="3" required></textarea>
                            <button class="btn btn-warning w-100 fw-bold">Tạo phiếu bảo hành</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($data['type'] == 'LIST'): ?>
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0">Chọn Lô hàng để bảo hành:</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã Lô</th>
                            <th>Tên Hàng</th>
                            <th>Ngày Nhập</th>
                            <th>Hạn Bảo Hành</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['result'] as $row): ?>
                        <tr>
                            <td><?php echo $row['maLo']; ?></td>
                            <td><?php echo $row['tenHH']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['ngayNhap'])); ?></td>
                            <td>
                                <?php 
                                    $date = date('d/m/Y', strtotime($row['hanBaoHanh']));
                                    echo (strtotime($row['hanBaoHanh']) < time()) ? "<span class='text-danger'>$date (Hết hạn)</span>" : "<span class='text-success'>$date</span>";
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" 
                                        onclick="openWarrantyModal('<?php echo $row['maLo']; ?>', '<?php echo $row['tenHH']; ?>', '<?php echo $row['maHH']; ?>')">
                                    Bảo hành
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="warrantyModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo BASE_URL; ?>/warranty/create" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Bảo hành cho Lô hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Sản phẩm: <b id="modalProductName"></b></p>
                            
                            <input type="hidden" name="serial" id="modalSerialInput"> <input type="hidden" name="maHH" id="modalMaHHInput">     <div class="mb-3">
                                <label>Mô tả lỗi:</label>
                                <textarea name="moTaLoi" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Lưu phiếu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openWarrantyModal(maLo, tenHH, maHH) {
                document.getElementById('modalProductName').innerText = tenHH + ' (' + maLo + ')';
                document.getElementById('modalSerialInput').value = maLo; // Gán mã lô vào serial
                document.getElementById('modalMaHHInput').value = maHH;   // Gán mã hàng
                new bootstrap.Modal(document.getElementById('warrantyModal')).show();
            }
        </script>
    <?php endif; ?>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>