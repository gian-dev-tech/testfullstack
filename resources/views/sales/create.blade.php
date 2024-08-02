<!DOCTYPE html>
<html>
<head>
    <title>TEST FULLSTACK</title>
    <!-- jQuery Full Version -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
      
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="cust_id">Customer</label>
                <select class="form-control" id="cust_id" name="cust_id" required>
                    @foreach(App\Models\Customer::all() as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tgl">Tanggal</label>
                <input type="datetime-local" name="tgl" class="form-control" required>
            </div>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#barangModal">Pilih Barang</button>
            <br><br>

            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Harga Bandrol</th>
                        <th>Diskon %</th>
                        <th>Diskon Rp</th>
                        <th>Harga Diskon</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="items">
                  
                </tbody>
            </table>

            <div class="form-group">
                <label for="subtotal">Subtotal</label>
                <input type="number" class="form-control" id="subtotal" name="subtotal" readonly>
            </div>
            <div class="form-group">
                <label for="diskon">Diskon</label>
                <input type="number" class="form-control" id="diskon" name="diskon" value="0" required>
            </div>
            <div class="form-group">
                <label for="ongkir">Ongkir</label>
                <input type="number" class="form-control" id="ongkir" name="ongkir" value="0" required>
            </div>
            <div class="form-group">
                <label for="total_bayar">Total Bayar</label>
                <input type="number" class="form-control" id="total_bayar" name="total_bayar" readonly>
            </div>

            <button type="submit" class="btn btn-success">Submit</button>
        </form>


        <div class="modal fade" id="barangModal" tabindex="-1" role="dialog" aria-labelledby="barangModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="barangModalLabel">Pilih Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table" id="barangTable">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $.ajax({
                    url: '/api/barang',
                    method: 'GET',
                    success: function(data) {
                        var tableBody = $('#barangTable tbody');
                        tableBody.empty();
                        $.each(data, function(index, barang) {
                            tableBody.append('<tr>' +
                                '<td>' + barang.kode + '</td>' +
                                '<td>' + barang.nama + '</td>' +
                                '<td>' + barang.harga + '</td>' +
                                '<td><button class="btn btn-primary btn-sm" onclick="selectBarang(' + barang.id + ', \'' + barang.kode + '\', \'' + barang.nama + '\', ' + barang.harga + ', ' + barang.stok + ')">Pilih</button></td>' +
                                '</tr>');
                        });
                    }
                });
            });

            function selectBarang(id, kode, nama, harga, stok) {
              
                var exists = false;
                $('#items tr').each(function() {
                    if ($(this).find('td:nth-child(2)').text() === kode) {
                        exists = true;
                        return false; 
                    }
                });

                if (exists) {
                    alert('Barang sudah masuk ke dalam tabel transaksi');
                } else {
                    var rowCount = $('#items tr').length;
                    $('#items').append('<tr>' +
                        '<td>' + (rowCount + 1) + '</td>' +
                        '<td>' + kode + '</td>' +
                        '<td>' + nama + '</td>' +
                        '<td><input type="number" name="items[' + rowCount + '][qty]" oninput="checkStock(this, ' + stok + ')" required></td>' +
                        '<td><input type="number" name="items[' + rowCount + '][harga_bandrol]" value="' + harga + '" readonly></td>' +
                        '<td><input type="number" name="items[' + rowCount + '][diskon_pct]" value="0" required></td>' +
                        '<td><input type="number" name="items[' + rowCount + '][diskon_nilai]" value="0" required></td>' +
                        '<td><input type="number" name="items[' + rowCount + '][harga_diskon]" value="' + harga + '" readonly></td>' +
                        '<td><input type="number" name="items[' + rowCount + '][total]" value="' + harga + '" readonly></td>' +
                        '<td><input type="hidden" name="items[' + rowCount + '][barang_id]" value="' + id + '"><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">Remove</button></td>' +
                        '</tr>');

                    $('#barangModal').modal('hide');
                    updateTotals();
                }
            }

           
            function checkStock(input, stok) {
                var qty = parseFloat(input.value) || 0;
                if (qty > stok) {
                    alert('Stok tidak mencukupi!');
                    input.value = '';
                } else {
                    updateTotals();
                }
            }

           
            function removeItem(button) {
                $(button).closest('tr').remove();
                updateItemNumbers();
                updateTotals();
            }

          
            function updateItemNumbers() {
                $('#items tr').each(function(index, row) {
                    $(row).find('td:first').text(index + 1);
                });
            }

            // Update totals
            function updateTotals() {
                var subtotal = 0;
                $('#items tr').each(function() {
                    var total = parseFloat($(this).find('input[name$="[total]"]').val()) || 0;
                    subtotal += total;
                });
                $('#subtotal').val(subtotal.toFixed(2));
                var diskon = parseFloat($('#diskon').val()) || 0;
                var ongkir = parseFloat($('#ongkir').val()) || 0;
                var total_bayar = subtotal - diskon + ongkir;
                $('#total_bayar').val(total_bayar.toFixed(2));
            }

            
            $(document).on('input', 'input[name$="[qty]"], input[name$="[diskon_pct]"], input[name$="[diskon_nilai]"]', function() {
                var row = $(this).closest('tr');
                var qty = parseFloat(row.find('input[name$="[qty]"]').val()) || 0;
                var harga_bandrol = parseFloat(row.find('input[name$="[harga_bandrol]"]').val()) || 0;
                var diskon_pct = parseFloat(row.find('input[name$="[diskon_pct]"]').val()) || 0;
                var diskon_nilai = parseFloat(row.find('input[name$="[diskon_nilai]"]').val()) || 0;

                var harga_diskon = harga_bandrol - (harga_bandrol * diskon_pct / 100) - diskon_nilai;
                row.find('input[name$="[harga_diskon]"]').val(harga_diskon.toFixed(2));
                var total = qty * harga_diskon;
                row.find('input[name$="[total]"]').val(total.toFixed(2));

                updateTotals();
            });
        </script>
    </div>
</body>
</html>
