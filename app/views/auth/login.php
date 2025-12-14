<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height:100vh;">
        <div class="col-md-4">
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">ĐĂNG NHẬP HỆ THỐNG</h4>
                </div>

                <div class="card-body">

                    <?php if (!empty($data['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $data['error']; ?>
                        </div>
                    <?php endif; ?>

                   <form method="post" action="index.php?controller=auth&action=login">
                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Đăng nhập
                        </button>
                    </form>
                </div>

                <div class="card-footer text-center">
                    <small class="text-muted">© 2025 – Quản lý kho hàng gia dụng</small>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
