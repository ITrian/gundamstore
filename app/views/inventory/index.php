<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tra cứu Tồn kho</h1>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Nhập tên hàng hoặc mã lô để tìm kiếm...">
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Mã Lô</th>
                            <th>Vị trí (Dãy-Kệ-Ô)</th>
                            <th>Hạn bảo hành (NCC)</th>
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
                                    <?php
                                        $capacity = isset($item['sucChuaToiDa']) ? (int)$item['sucChuaToiDa'] : 100;
                                        $totalAtPos = isset($item['totalAtPosition']) ? (int)$item['totalAtPosition'] : 0;
                                        $percent = ($capacity > 0) ? ($totalAtPos / $capacity) * 100 : 0;
                                        if ($percent > 90) {
                                            echo '<span class="badge bg-danger">Đầy (' . round($percent) . '%)</span>';
                                        } elseif ($percent == 0) {
                                            echo '<span class="badge bg-success">Trống</span>';
                                        } else {
                                            echo '<span class="badge bg-warning text-dark">' . round($percent) . '%</span>';
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Kho đang trống!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                // Bỏ qua dòng thông báo "Kho đang trống" nếu có
                if (row.cells.length <= 1) continue;

                const productCell = row.cells[0]; // Cột Sản phẩm
                const lotCell = row.cells[1];     // Cột Mã Lô

                if (productCell && lotCell) {
                    const productText = productCell.textContent || productCell.innerText;
                    const lotText = lotCell.textContent || lotCell.innerText;

                    if (productText.toLowerCase().indexOf(filter) > -1 || lotText.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            }
        });
    });
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>