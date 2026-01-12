<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <?php $exp = $data['export']; $lines = $data['lines']; ?>
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h4 mb-0"><?php echo htmlspecialchars($data['title'] ?? 'Chi tiết Phiếu Xuất'); ?></h1>
            <small class="text-muted">Mã: <strong><?php echo htmlspecialchars($exp['maPX']); ?></strong></small>
        </div>
        <div class="btn-group">
            <a href="<?php echo BASE_URL; ?>/export" class="btn btn-sm btn-outline-secondary">&larr; Quay lại</a>
            <button class="btn btn-sm btn-outline-primary" onclick="window.print();">In</button>
        </div>
    </div>

    <!-- Summary cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Khách hàng</div>
                    <div class="fw-bold"><?php echo htmlspecialchars($exp['tenKH']); ?></div>
                    <?php if (!empty($exp['sdt'])): ?>
                        <div class="small text-muted">SĐT: <?php echo htmlspecialchars($exp['sdt']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Ngày xuất</div>
                    <div class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($exp['ngayXuat'])); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Người xuất</div>
                    <div class="fw-bold"><?php echo htmlspecialchars($exp['tenND']); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <?php
                        $totalItems = 0; $totalAmount = 0;
                        foreach ($lines as $ln) {
                            $totalItems += intval($ln['soLuong']);
                            $totalAmount += intval($ln['soLuong']) * floatval($ln['donGia']);
                        }
                    ?>
                    <div class="small text-muted">Tổng giá trị xuất</div>
                        <div class="fw-bold"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($exp['diaChi'])): ?>
        <div class="mb-3">
             <div class="card">
                <div class="card-body py-2">
                    <span class="text-muted me-2">Địa chỉ:</span> 
                    <strong><?php echo htmlspecialchars($exp['diaChi']); ?></strong>
                </div>
             </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($exp['ghiChu'])): ?>
        <div class="mb-3"><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($exp['ghiChu'])); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Sản phẩm</th>
                            <th style="width:80px">SL</th>
                            <th style="width:120px">Đơn giá xuất</th>
                            <th style="width:120px">Thành tiền</th>
                            <th style="width:100px">Lô</th>
                            <th style="width:100px">Vị trí</th>
                            <th>Serial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lines)): ?>
                            <?php $i = 1; foreach ($lines as $ln): ?>
                                <?php $lineTotal = intval($ln['soLuong']) * floatval($ln['donGia']); ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($ln['tenHH'] ?? $ln['maHH']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($ln['maHH']); ?></div>
                                    </td>
                                    <td><?php echo intval($ln['soLuong']); ?></td>
                                    <td><?php echo number_format($ln['donGia'], 0, ',', '.'); ?> đ</td>
                                    <td><?php echo number_format($lineTotal, 0, ',', '.'); ?> đ</td>
                                    <td>
                                        <?php if (!empty($ln['lots'])): ?>
                                            <?php foreach ($ln['lots'] as $lot): ?>
                                                <div><?php echo htmlspecialchars($lot['maLo']); ?></div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($ln['locations'])): ?>
                                            <div class="small">
                                                <?php foreach ($ln['locations'] as $loc): ?>
                                                    <div><?php echo htmlspecialchars($loc['maViTri']); ?></div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($ln['serials'])): ?>
                                            <div class="small text-monospace">
                                                <?php foreach ($ln['serials'] as $s): ?>
                                                    <div><?php echo htmlspecialchars($s); ?></div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">Không có chi tiết.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($lines)): ?>
                        <tfoot>
                            <tr class="table-light">
                                <td></td>
                                <td class="text-end"><strong>Tổng</strong></td>
                                <td><strong><?php echo $totalItems; ?></strong></td>
                                <td></td>
                                <td><strong><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</strong></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
