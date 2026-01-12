<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/views/layouts/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo $title; ?></h1>
        <a href="<?php echo BASE_URL; ?>/user/create" class="btn btn-primary">
            + Thêm mới
        </a>
    </div>

    <div class="card shadow-sm">
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
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($users)): ?>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td><?php echo $user['maND']; ?></td>
                                <td class="fw-bold"><?php echo $user['tenND']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['sdt']; ?></td>
                                <td><?php echo $user['taiKhoan']; ?></td>
                                <td>
                                    <?php 
                                        $badgeColor = ($user['maVaiTro'] == 'VT_ADMIN') ? 'bg-danger' : 'bg-success';
                                        echo "<span class='badge $badgeColor'>{$user['tenVaiTro']}</span>"; 
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo BASE_URL; ?>/user/edit/<?php echo $user['maND']; ?>" class="btn btn-sm btn-outline-primary">
                                        Sửa
                                    </a>
                                    
                                    <?php if($user['maND'] != $_SESSION['user_id']): ?>
                                    <a href="<?php echo BASE_URL; ?>/user/delete/<?php echo $user['maND']; ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                        Xóa
                                    </a>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        Xóa
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Chưa có người dùng nào.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
