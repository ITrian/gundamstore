<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <?php $imp = $data['import']; $lines = $data['lines']; ?>
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h4 mb-0"><?php echo htmlspecialchars($data['title'] ?? 'Chi tiết Phiếu Nhập'); ?></h1>
            <small class="text-muted">Mã: <strong><?php echo htmlspecialchars($imp['maPN']); ?></strong></small>
        </div>
        <div class="btn-group">
            <a href="<?php echo BASE_URL; ?>/import" class="btn btn-sm btn-outline-secondary">&larr; Quay lại</a>
            <button class="btn btn-sm btn-outline-primary" onclick="window.print();">In</button>
        </div>
    </div>

    <!-- Summary cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Nhà cung cấp</div>
                    <div class="fw-bold"><?php echo htmlspecialchars($imp['tenNCC']); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Ngày nhập</div>
                    <div class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($imp['ngayNhap'])); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Người nhập</div>
                    <div class="fw-bold"><?php echo htmlspecialchars($imp['tenND']); ?></div>
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
                    <div class="small text-muted">Tổng</div>
                    <div class="fw-bold"><?php echo $totalItems; ?> cái — <?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($imp['ghiChu'])): ?>
        <div class="mb-3"><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($imp['ghiChu'])); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Sản phẩm</th>
                            <th style="width:100px">SL</th>
                            <th style="width:140px">Đơn giá</th>
                            <th style="width:140px">Thành tiền</th>
                            <th>LOT / Vị trí</th>
                            <th>Serials</th>
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
                                                <div class="mb-2">
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($lot['maLo']); ?> <small class="text-muted">(Nhập: <?php echo intval($lot['soLuongNhap']); ?>)</small></div>
                                                    <?php if (!empty($lot['locations'])): ?>
                                                        <div class="small">
                                                            <?php foreach ($lot['locations'] as $loc): ?>
                                                                <div><?php echo htmlspecialchars($loc['maViTri']); ?> — <?php echo htmlspecialchars($loc['day'] . '-' . $loc['ke'] . '-' . $loc['o']); ?> <span class="badge bg-secondary ms-1"><?php echo intval($loc['soLuong']); ?></span></div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($ln['lots'])): ?>
                                            <?php foreach ($ln['lots'] as $lot): ?>
                                                <?php if (!empty($lot['serials'])): ?>
                                                    <div class="mb-2 small text-monospace">
                                                        <?php foreach ($lot['serials'] as $s): ?>
                                                            <div><?php echo htmlspecialchars($s['serial']); ?><?php if (!empty($s['maViTri'])) echo ' <span class="text-muted">@' . htmlspecialchars($s['maViTri']) . '</span>'; ?></div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Không có chi tiết.</td></tr>
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
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
