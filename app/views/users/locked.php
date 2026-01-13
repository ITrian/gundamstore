<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 text-danger">Tài khoản bị khóa</h1>
        
        <a href="<?php echo BASE_URL; ?>/user" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card shadow-sm border-danger">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ND</th>
                            <th>Tên ND</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>Tài khoản</th>
                            <th>Vai trò</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['users'])): ?>
                            <?php foreach($data['users'] as $user): ?>
                            <tr>
                                <td><?php echo $user['maND']; ?></td>
                                <td class="fw-bold"><?php echo $user['tenND']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['sdt']; ?></td>
                                <td><?php echo $user['taiKhoan']; ?></td>
                                <td>
                                    <?php 
                                        $badgeColor = ($user['maVaiTro'] == 'VT_ADMIN') ? 'bg-danger' : 'bg-primary';
                                        echo "<span class='badge $badgeColor'>{$user['tenVaiTro']}</span>"; 
                                    ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary"><i class="bi bi-lock-fill"></i> Bị khóa</span>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo BASE_URL; ?>/user/restore/<?php echo $user['maND']; ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Bạn muốn mở khóa tài khoản này?');">
                                        <i class="bi bi-unlock-fill"></i> Khôi phục
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">Không có tài khoản nào bị khóa.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
