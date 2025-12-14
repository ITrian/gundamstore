    <?php include __DIR__ . '/../layouts/header.php'; ?>
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <h4 class="mb-3">Danh sách sản phẩm</h4>

    <a href="index.php?controller=product&action=create" class="btn btn-success mb-3">
        + Thêm sản phẩm
    </a>

    <table class="table table-bordered table-hover">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['name'] ?></td>
                        <td><?= $p['quantity'] ?></td>
                        <td>
                            <a class="btn btn-sm btn-danger"
                            href="index.php?controller=product&action=delete&id=<?= $p['id'] ?>">
                            Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
    <?php include __DIR__ . '/../layouts/footer.php'; ?>
