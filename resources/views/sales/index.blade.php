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
                        <a class="nav-link" href="#">{{ auth()->user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sales.create') }}">POS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sales.index') }}">Riwayat transaksi</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
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
                @foreach ($data as $index => $transaction)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $transaction['no_transaksi'] }}</td>
                        <td>{{ $transaction['tanggal'] }}</td>
                        <td>{{ $transaction['nama_customer'] }}</td>
                        <td>{{ $transaction['jumlah_barang'] }}</td>
                        <td>
                            @foreach ($transaction['barang'] as $barang)
                                {{ $barang['nama_barang'] }} ({{ $barang['subtotal'] }})<br>
                            @endforeach
                        </td>
                        <td>{{ $transaction['subtotal'] }}</td>
                        <td>{{ $transaction['diskon'] }}</td>
                        <td>{{ $transaction['ongkir'] }}</td>
                        <td>{{ $transaction['total_bayar'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="text-right"><strong>Grand Total:</strong></td>
                    <td>{{ $grand_total }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
