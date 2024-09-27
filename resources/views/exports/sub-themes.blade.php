<table class="table table-striped table-hover">
    <thead>
        <th>#</th>
        <th>Sub Tema</th>
        <th>Deadline</th>
        <th>Status</th>
        <th>Author</th>
    </thead>
    <tbody>
        @if ($theme->subThemes()->count() == false)
            <tr>
                <td colspan="6" class="text-center">Belum terdapat sub tema yang diajukan</td>
            </tr>
        @else
            @foreach ($theme->subThemes as $index => $subTheme)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subTheme->name }}</td>
                    <td> {{ $subTheme->dueDateFormatted }}</td>
                    <td>
                        {{ $subTheme->hasAuthorRegistered() ? 'Sudah ada author' : 'Belum ada Author' }}
                    </td>
                    <td>
                        @if ($subTheme->hasAuthorRegistered())
                            {{ $subTheme->ebook()->first()?->author?->name }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>