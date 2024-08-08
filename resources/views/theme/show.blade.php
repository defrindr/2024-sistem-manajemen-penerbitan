@extends('layouts.admin.main')

@section('title', 'Detail Judul')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Judul
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ route('theme.index') }}" class="btn btn-default">Kembali</a>

                    @admin(true)
                        {{-- @if ($theme->status == \App\Models\Theme::STATUS_CLOSE || $theme->status == \App\Models\Theme::STATUS_PUBLISH) --}}
                        @if ($theme->status == \App\Models\Theme::STATUS_CLOSE)
                            <a href="{{ route('theme.download-zip', compact('theme')) }}" class="btn btn-success"
                                target="_blank">
                                Download Zip
                            </a>
                            {{-- <a href="{{ route('theme.merge-documents', compact('theme')) }}" class="btn btn-success"
                                target="_blank">
                                Gabungkan Dokumen
                            </a> --}}
                        @endif
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            {{-- tampilkan setiap kolom --}}
                            <tr>
                                <th>Nama Judul</th>
                                <td>{{ $theme->name }}</td>
                            </tr>
                            {{-- tampilkan setiap kolom --}}
                            @if ($theme->isbn)
                                <tr>
                                    <th>ISBN</th>
                                    <td> {{ $theme->isbn }}</td>
                                </tr>
                            @endif
                            {{-- tampilkan setiap kolom --}}
                            <tr>
                                <th>Status</th>
                                <td> {{ $theme->statusFormatted }}</td>
                            </tr>

                            <tr>
                                <th>Reviewer 1</th>
                                <td>{{ $theme->reviewer1->name }}</td>
                            </tr>

                            <tr>
                                <th>Reviewer 2</th>
                                <td>{{ $theme->reviewer2->name }}</td>
                            </tr>

                            @if ($theme->status === \App\Models\Theme::STATUS_PUBLISH)
                                <tr>
                                    <th>Cover</th>
                                    <td>
                                        <img src="{{ $theme->pathToFile('cover') }}" alt="Cover" srcset=""
                                            class="img img-fluid" style="max-width: 250px">
                                    </td>
                                </tr>

                                <tr>
                                    <th>File</th>
                                    <td>
                                        <a href="{{ $theme->pathToFile('file') }}" target="_blank"
                                            rel="noopener noreferrer">Unduh</a>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">{{ $theme->description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-3">
            <div class="card card-default">
                <div class="card-header">
                    <h3>Sub Tema</h3>

                    @admin(true)
                        @if ($theme->status == \App\Models\Theme::STATUS_DRAFT)
                            <a href="{{ route('themes.subThemes.create', compact('theme')) }}" class="btn btn-success">
                                Tambah Sub Tema
                            </a>
                        @endif
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Sub Tema</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Aksi</th>
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
                                        <td>
                                            @admin(true)
                                                @if ($theme->status == \App\Models\Theme::STATUS_DRAFT)
                                                    <a href="{{ route('themes.subThemes.edit', compact('subTheme', 'theme')) }}"
                                                        class="btn btn-warning">
                                                        Edit
                                                    </a>
                                                    <form
                                                        action="{{ route('themes.subThemes.destroy', compact('subTheme', 'theme')) }}"
                                                        method="POST" class="d-inline-block"
                                                        onsubmit="return confirm('Yakin ingin menjalankan fungsi ini ?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-danger">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @elseif(!$subTheme->isNotDeadline())
                                                    <a href="{{ route('themes.subThemes.edit', compact('subTheme', 'theme')) }}"
                                                        class="btn btn-warning">
                                                        Deadline Perlu diatur ulang
                                                    </a>
                                                @endif
                                            @endadmin
                                            @author
                                            @if (!$subTheme->hasAuthorRegistered() && $subTheme->isThemeOpen() && $subTheme->isNotDeadline())
                                                <a href="{{ route('ebook.create', compact('theme', 'subTheme')) }}"
                                                    class="btn btn-primary">
                                                    Daftar Ke Judul
                                                </a>
                                            @endif
                                            @endauthor
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="card card-default">
                <div class="card-header">
                    <h3>Pendaftaran</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Author</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @if ($theme->hasEbook() == false)
                                <tr>
                                    <td colspan="6" class="text-center">Belum terdapat karya yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($theme->ebooks as $index => $ebook)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ebook->title }}</td>
                                        <td>{{ $ebook->author->name }}</td>
                                        <td>{{ $ebook->createdAtFormatted }}</td>
                                        <td>{{ $ebook->status }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
