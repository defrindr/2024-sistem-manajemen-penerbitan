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
                    <a href="{{ route('theme.index') }}" class="btn btn-danger">Kembali</a>

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
                            <tr>
                                <th>Multi Author</th>
                                <td>{{ $theme->multipleAuthor ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <th>Penulis Utama</th>
                                <td>{{ $theme->authorUtama?->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat Penulis</th>
                                <td>{{ $theme->authorUtama?->address ?? '' }}</td>
                            </tr>
                            @if ($theme->multipleAuthor)
                                <tr>
                                    <th>Penulis Lain</th>
                                    <td>{{ $theme->authors }}</td>
                                </tr>
                            @endif

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
                    <h3>Sub Judul</h3>

                    @admin(true)
                        @if ($theme->status == \App\Models\Theme::STATUS_DRAFT)
                            <a href="{{ route('themes.subThemes.create', compact('theme')) }}" class="btn btn-success">
                                Tambah Sub Judul
                            </a>
                        @endif
                        <a href="{{ route('themes.sub-theme.export', compact('theme')) }}" class="btn btn-primary"
                            style="float: right">
                            Export
                        </a>
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Sub Judul</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Reviews</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @if ($theme->subThemes()->count() == false)
                                <tr>
                                    <td colspan="7" class="text-center">Belum terdapat sub judul yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($theme->subThemes as $index => $subTheme)
                                    <tr @if (request()->get('highlight') == $subTheme->id) class="bg-red" @endif>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $subTheme->name }}</td>
                                        <td> {{ $subTheme->dueDateFormatted }}</td>
                                        <td>
                                            {{ $subTheme->hasAuthorRegistered() ? $subTheme->ebook()->first()->status : 'Belum ada Author' }}
                                        </td>
                                        <td>
                                            @if ($subTheme->hasAuthorRegistered())
                                                {{ $subTheme->ebook()->first()?->author?->name }} <br>
                                                Email: {{ $subTheme->ebook()->first()?->author?->email }} <br>
                                                HP: {{ $subTheme->ebook()->first()?->author?->phone }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($subTheme->ebook()->exists())
                                                @php
                                                    $ebook = $subTheme->ebook()->first();
                                                @endphp
                                                @if (count($ebook->reviews) >= 1)
                                                    <p>
                                                        Reviewer 1: <br>
                                                        {!! $ebook->reviews[0]?->statusDetail !!}
                                                    </p>
                                                @else
                                                    {{ $ebook->status }}
                                                @endif
                                                @if (count($ebook->reviews) >= 2)
                                                    <p>
                                                        Reviewer 2:<br>
                                                        {!! $ebook->reviews[1]?->statusDetail !!}
                                                    </p>
                                                @endif
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
                                                    @if ($theme->multipleAuthor == 0)
                                                        <a href="{{ route('theme.edit', compact('theme')) }}"
                                                            class="btn btn-warning">
                                                            Deadline Perlu diatur ulang
                                                        </a>
                                                    @else
                                                        <a href="{{ route('themes.subThemes.edit', compact('subTheme', 'theme')) }}"
                                                            class="btn btn-warning">
                                                            Deadline Perlu diatur ulang
                                                        </a>
                                                    @endif
                                                @endif
                                            @endadmin
                                            @author
                                            @if (!$subTheme->hasAuthorRegistered() && $subTheme->isThemeOpen() && $subTheme->isNotDeadline())
                                                <a href="{{ route('ebook.create', compact('theme', 'subTheme')) }}"
                                                    @if ($theme->multipleAuthor == 0) onclick="return confirm('Melanjutkan berarti mendaftar. Ketika anda mendaftar, maka anda akan mengakuisisi seluruh sub judul di judul ini. Harap melakukan pembayaran setelahnya')" 
                                                    @else 
                                                    onclick="return confirm('Melanjutkan berarti mendaftar. Harap melakukan pembayaran setelah ini')" @endif
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
    </div>
@endsection
