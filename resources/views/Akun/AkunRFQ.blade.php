@extends('layouts.master')
@section('content')
    <div class="container-fluid mb-3">
        <div class="form-group mt-4">
            <form action="{{ url('/Akun/RFQ/tanggal/') }}" method="POST" id="searchForm">
                {{ csrf_field() }}
                <label for="tqlawal">Pilih Rentang Waktu:</label>
                <select name="duration" id="duration">
                    <option selected>-- Pilih Rentang Waktu --</option>
                    <option value="1_minggu_terakhir">1 Minggu Terakhir</option>
                    <option value="1_bulan_terakhir">1 Bulan Terakhir</option>
                    <option value="semua">Semua Data</option>
                </select>
                <button class="btn btn-success bi bi-printer" onclick="cetakTabel()">    Print PDF</button>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#duration').change(function() {
                    $('#searchForm').submit();
                });
            });
        </script>
    </div>
    <div class="card">
        <div class="card-body">
            <br>
            <table class="table table-bordered" id="myTable" name="myTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Vendor</th>
                        <th scope="col">Tanggal Transaksi</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Pembayaran</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($rfqs->count())
                        @foreach ($rfqs as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_vendor }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>{{ $item->total_harga }}</td>
                                <td>
                                    @if ($item->pembayaran == 0)
                                        <span class="badge bg-secondary">Belum Dibuat</span>
                                    @elseif($item->pembayaran == 1)
                                        <span class="badge bg-primary">Cash</span>
                                    @elseif($item->pembayaran == 2)
                                        <span class="badge bg-primary">Transfer</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == 0)
                                        <span class="badge badge-secondary " style="color: black">Draft</span>
                                    @elseif($item->status == 1)
                                        <span class="badge badge-primary" style="color: black">Belum Dibayar</span>
                                    @elseif($item->status == 2)
                                        <span class="badge badge-warning" style="color: black">Menunggu Pembayaran</span>
                                    @elseif($item->status == 3)
                                        <span class="badge badge-secondary" style="color: black">Selesai Dibayar</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"> No record found </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <br>
            <div class="container-sm ">
                <div class="row"></div>
                <div class="row mt-auto p-2 bd-highlight">
                    <label for="text_harga" class="font-weight-bold"> Total Harga : </label>
                    @if ($tots == 0)
                        <label for="total_harga" id="val">xxxxxx</label>
                    @else
                        <label for="total_harga" id="val">{{ $tots }}</label>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script>
        function cetakTabel() {
            var printWindow = window;
            printWindow.document.open();
            printWindow.document.write('<html><head><title>KonveksiKita</title>');
            // Sisipkan gaya langsung ke dalam HTML
            printWindow.document.write('<style>');
            printWindow.document.write('table { border-collapse: collapse; width: 100%; }');
            printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h2 class="text-center mb-4">Vendor Bill</h2>');
            printWindow.document.write('<table class="table table-bordered">');
            printWindow.document.write('<thead>');
            printWindow.document.write('<tr>');
            printWindow.document.write('<th scope="col">No</th>');
            printWindow.document.write('<th scope="col">Nama Vendor</th>');
            printWindow.document.write('<th scope="col">Tanggal Transaksi</th>');
            printWindow.document.write('<th scope="col">Total Harga</th>');
            printWindow.document.write('</tr>');
            printWindow.document.write('</thead>');
            printWindow.document.write('<tbody>');
            
            var totalHarga = 0; // Inisialisasi variabel totalHarga
    
            @if ($rfqs->count())
                @foreach ($rfqs as $item)
                    printWindow.document.write('<tr>');
                    printWindow.document.write('<th scope="row">{{ $loop->iteration }}</th>');
                    printWindow.document.write('<td>{{ $item->nama_vendor }}</td>');
                    printWindow.document.write('<td>{{ $item->tanggal }}</td>');
                    printWindow.document.write('<td>{{ $item->total_harga }}</td>');
                    printWindow.document.write('</tr>');
    
                    // Tambahkan total harga pada setiap iterasi
                    totalHarga += parseInt('{{ $item->total_harga }}');
                @endforeach
            @endif
    
            printWindow.document.write('</tbody>');
            printWindow.document.write('</table>');
    
            // Tampilkan total harga di bagian bawah tabel
            printWindow.document.write('<div>Total Pemasukan: ' + totalHarga + '</div>');
    
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
