<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tra cứu Tồn kho</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary">Kiểm kê kho</button>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Tìm tên hàng, mã lô...">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option>Tất cả kho</option>
                <option>Kho A</option>
                <option>Kho B</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Tìm kiếm</button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Mã Lô</th>
                        <th>Vị trí (Dãy-Kệ-Ô)</th>
                        <th>Hạn sử dụng</th>
                        <th class="text-center">Số lượng</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
               <tbody>
                    <?php if (!empty($data['stocks'])): ?>
                        <?php foreach ($data['stocks'] as $item): ?>
                        <tr>
                            <td>
                                <span class="fw-bold"><?php echo $item['tenHH']; ?></span><br>
                                <small class="text-muted"><?php echo $item['maHH']; ?></small>
                            </td>
                            <td><span class="badge bg-secondary"><?php echo $item['maLo']; ?></span></td>
                            <td>
                                <?php 
                                    // Nếu chưa gán vị trí thì báo chưa có
                                    echo !empty($item['viTriCuThe']) ? $item['viTriCuThe'] : '<span class="text-muted fst-italic">Chưa xếp kệ</span>'; 
                                ?>
                            </td>
                            <td>
                                <?php 
                                    // Format ngày tháng cho đẹp
                                    echo $item['hanBaoHanh'] ? date('d/m/Y', strtotime($item['hanBaoHanh'])) : 'Không BH'; 
                                ?>
                            </td>
                            <td class="text-center fs-5">
                                <strong><?php echo number_format($item['soLuongTon']); ?></strong>
                            </td>
                            <td>
                                <?php if($item['soLuongTon'] <= 10): ?>
                                    <span class="badge bg-warning text-dark">Sắp hết</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Sẵn sàng</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Kho đang trống!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>