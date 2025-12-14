<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>
<h4 class="mb-3">Thêm sản phẩm</h4>

<form method="post" action="index.php?controller=product&action=store">
    <div class="mb-3">
        <label>Tên sản phẩm</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Số lượng</label>
        <input type="number" name="quantity" class="form-control" required>
    </div>

    <button class="btn btn-primary">Lưu</button>
    <a href="index.php?controller=product" class="btn btn-secondary">Quay lại</a>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
