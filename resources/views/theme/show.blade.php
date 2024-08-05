@extends('layouts.admin.main')

@section('title', 'Detail Topik')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Topik
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card card-default">
                <div class="card-header">
                    {{-- Tombol kembali --}}
                    <a href="{{ route('theme.index') }}" class="btn btn-default">Kembali</a>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            {{-- tampilkan setiap kolom --}}
                            <tr>
                                <td>Nama Topik :</td>
                                <td>{{ $theme->name }}</td>
                            </tr>
                            {{-- tampilkan setiap kolom --}}
                            <tr>
                                <td>Deadline :</td>
                                <td> {{ $theme->dueDateFormatted }}</td>
                            </tr>
                            <tr>
                                <td>ISBN :</td>
                                <td> {{ $theme->isbn }}</td>
                            </tr>
                            {{-- tampilkan setiap kolom --}}
                            <tr>
                                <td>Status :</td>
                                <td> {{ $theme->statusFormatted }}</td>
                            </tr>

                            <tr>
                                <td>Reviewer 1 :</td>
                                <td>{{ $theme->reviewer1->name }}</td>
                            </tr>

                            <tr>
                                <td>Reviewer 2 :</td>
                                <td>{{ $theme->reviewer2->name }}</td>
                            </tr>

                            <tr>
                                <td colspan="2">Deskripsi :</td>
                            </tr>
                            <tr>
                                <td colspan="2">{{ $theme->description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
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
                                                @endif
                                            @endadmin
                                            @author
                                            @if (!$subTheme->hasAuthorRegistered() && $subTheme->isThemeOpen())
                                                <a href="{{ route('ebook.create', compact('theme', 'subTheme')) }}"
                                                    class="btn btn-primary">
                                                    Daftar Ke Topik
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
