<table class="table table-hover table-striped">
    <thead>
        <tr>
            <td>#</td>
            <td>Judul Cerita</td>
            <td>Ctk Ke</td>
            <td>Tahun</td>
            <td>Total Produksi</td>
            <td>Harga Produksi Buku</td>
            <td>Total Biaya</td>
        </tr>
    </thead>
    <tbody>
        @if ($paginations->count() == 0)
            <tr>
                <td colspan="10" class="text-center">Tidak ada data</td>
            </tr>
        @endif
        @foreach ($paginations as $item)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->numberOfPrinting }}</td>
                <td>{{ $item->productionYear }}</td>
                <td>{{ App\Helpers\StrHelper::currency($item->totalProduction) }}</td>
                <td>{{ App\Helpers\StrHelper::currency($item->price, 'Rp') }}</td>
                <td>{{ App\Helpers\StrHelper::currency($item->price * $item->totalProduction, 'Rp') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
