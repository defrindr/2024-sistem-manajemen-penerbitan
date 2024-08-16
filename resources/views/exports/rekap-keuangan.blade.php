<table class="table table-hover table-striped">
    <thead>
        <tr>
            <td>#</td>
            <td>Judul Cerita</td>
            <td>Pemasukan</td>
            <td>Biaya Produksi</td>
            <td>Role</td>
            <td>Nama</td>
            <td>Persentase</td>
            <td>Nilai Profit</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($pagination as $detail)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $detail->keuangan->title }}</td>
                <td>{{ App\Helpers\StrHelper::currency(intval($detail->keuangan->income), 'Rp') }}</td>
                <td>{{ App\Helpers\StrHelper::currency(intval($detail->keuangan->productionCost), 'Rp') }}</td>
                <td>{{ $detail->role }}</td>
                <td>{{ $detail->user ? $detail->user->name : '-' }}</td>
                <td>{{ $detail->percent }}</td>
                <td>{{ App\Helpers\StrHelper::currency(intval($detail->profit), 'Rp') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
