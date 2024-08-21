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
                    <a href="{{ route('rekapitulasi.cetakan') }}" class="btn btn-danger">Kembali</a>

                    @admin(true)
                        @if ($theme->status == \App\Models\Theme::STATUS_CLOSE)
                            <a href="{{ route('theme.download-zip', compact('theme')) }}" class="btn btn-success"
                                target="_blank">
                                Download Zip
                            </a>
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
                            @if ($theme->isbn)
                                <tr>
                                    <th>ISBN</th>
                                    <td>{{ $theme->isbn }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Multi Author</th>
                                <td>{{ $theme->multipleAuthor ? 'Ya' : 'Tidak' }}</td>
                            </tr>

                            <!-- Menambahkan bagian untuk menampilkan semua penulis -->
                            <tr>
                                <th>Penulis</th>
                                <td>
                                    @php
                                        $authors = collect();
                                    @endphp
                                    @foreach ($theme->subThemes as $subTheme)
                                        @if ($subTheme->hasAuthorRegistered())
                                            @php
                                                $authorName = $subTheme->ebook()->first()?->author?->name;
                                                if (!$authors->contains($authorName)) {
                                                    $authors->push($authorName);
                                                }
                                            @endphp
                                        @endif
                                    @endforeach
                            
                                    @foreach ($authors as $author)
                                        <p>{{ $author }}</p>
                                    @endforeach
                                </td>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-3">
            <div class="card card-default">
                <div class="card-header">
                    <h3>Sinopsis</h3>
                </div>
                <div class="card-body">
                    <p>{{ $theme->description }}</p>
                </div>
            </div>
            <div class="card card-default mt-3">
                <div class="card-header">
                    <h3>Sub Judul</h3>

                    @admin(true)
                        @if ($theme->status == \App\Models\Theme::STATUS_DRAFT)
                            <a href="{{ route('themes.subThemes.create', compact('theme')) }}" class="btn btn-success">
                                Tambah Sub Judul
                            </a>
                        @endif
                    @endadmin
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Sub Judul</th>
                        </thead>
                        <tbody>
                            @if ($theme->subThemes()->count() == false)
                                <tr>
                                    <td colspan="2" class="text-center">Belum terdapat sub judul yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($theme->subThemes as $index => $subTheme)
                                    <tr @if (request()->get('highlight') == $subTheme->id) class="bg-red" @endif>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $subTheme->name }}</td>
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
