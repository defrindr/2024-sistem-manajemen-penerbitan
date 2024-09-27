<table class="table table-hover table-striped">
    <thead>
        <tr>
            <td>#</td>
            <td>Judul Cerita</td>
            <td>Deskripsi</td>
            <td>Biaya Pendaftaran</td>
            <td>Status</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($pagination as $item)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->priceFormatted }}</td>
                <td>{{ $item->statusFormatted }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
