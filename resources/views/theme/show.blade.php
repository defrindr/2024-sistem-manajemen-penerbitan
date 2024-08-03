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
                            {{-- tampilkan setiap kolom --}}
                            <tr>
                                <td>Status :</td>
                                <td> {{ $theme->statusFormatted }}</td>
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
                        <a href="{{ route('themes.subThemes.create', compact('theme')) }}" class="btn btn-success">
                            Tambah Sub Tema
                        </a>
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Sub Tema</th>
                            <th>Reviewer 1</th>
                            <th>Reviewer 2</th>
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
                                        <td>{{ $subTheme->reviewer1->name }}</td>
                                        <td>{{ $subTheme->reviewer2->name }}</td>
                                        <td>{{ $subTheme->status }}</td>
                                        <td>
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
                    <h3>Daftar Karya Diajukan</h3>
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
