<table class="table table-hover table-striped">
    <thead>
        <tr>
            <td>#</td>
            <td>Sebagai</td>
            <td>Nama</td>
            <td>Profit</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($keuangan->details as $detail)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $detail->role }}</td>
                <td>{{ $detail->user ? $detail->user->name : '' }}</td>
                <td>{{ $detail->profit }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
