<!DOCTYPE html>
<html>

<head>
    <title>Riwayat Transaksi</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?php echo e(auth()->user()->name); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('sales.create')); ?>">POS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('sales.index')); ?>">Riwayat transaksi</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Transaksi</th>
                    <th>Tanggal</th>
                    <th>Nama Customer</th>
                    <th>Jumlah Barang</th>
                    <th>Barang</th>
                    <th>Subtotal</th>
                    <th>Diskon</th>
                    <th>Ongkir</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($transaction['no_transaksi']); ?></td>
                        <td><?php echo e($transaction['tanggal']); ?></td>
                        <td><?php echo e($transaction['nama_customer']); ?></td>
                        <td><?php echo e($transaction['jumlah_barang']); ?></td>
                        <td>
                            <?php $__currentLoopData = $transaction['barang']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($barang['nama_barang']); ?> (<?php echo e($barang['subtotal']); ?>)<br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td><?php echo e($transaction['subtotal']); ?></td>
                        <td><?php echo e($transaction['diskon']); ?></td>
                        <td><?php echo e($transaction['ongkir']); ?></td>
                        <td><?php echo e($transaction['total_bayar']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-right"><strong>Grand Total:</strong></td>
                    <td><?php echo e($grand_total); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\test_fullstack\resources\views/sales/index.blade.php ENDPATH**/ ?>