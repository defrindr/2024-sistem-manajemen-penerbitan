@extends('layouts.admin.main')

@section('title', 'Karya Saya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Karya Saya
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>#</th>
                            <th>Topik</th>
                            <th>Judul</th>
                            <th>Draft</th>
                            <th>Status</th>
                            <th>Royalti</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @if ($pagination->count() <= 0)
                                <tr>
                                    <td colspan="8" class="text-center">Belum terdapat karya yang diajukan</td>
                                </tr>
                            @else
                                @foreach ($pagination->items() as $index => $ebook)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ebook->theme->name }}</td>
                                        <td>{{ $ebook->title }}</td>
                                        <td>
                                            @if ($ebook->status != 'pending')
                                                <a href="{{ $ebook->draftPath }}" target="_blank" rel="noopener noreferrer">
                                                    Buka
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ebook->status == 'review' || $ebook->status === \App\Models\Ebook::STATUS_NOT_ACCEPT)
                                                @if (count($ebook->reviews) >= 1)
                                                    Reviewer 1: <br>
                                                    {!! $ebook->reviews[0]?->statusDetail !!}<br>
                                                @else
                                                    {{ $ebook->status }}
                                                @endif
                                                @if (count($ebook->reviews) >= 2)
                                                    Reviewer 2:<br>
                                                    {!! $ebook->reviews[1]?->statusDetail !!}
                                                @endif
                                            @else
                                                {{ $ebook->status }}
                                            @endif
                                        </td>
                                        <td>{{ $ebook->royalty }}</td>
                                        <td>{{ $ebook->createdAtFormatted }}</td>
                                        <td>
                                            @if ($ebook->status !== 'pending')
                                                <a href="{{ route('ebook.progress', $ebook) }}" class="btn btn-primary">
                                                    Progress
                                                </a>
                                            @endif
                                            @if ($ebook->status === \App\Models\Ebook::STATUS_PAYMENT)
                                                <a href="{{ route('ebook.create', [
                                                    'theme' => $ebook->theme,
                                                    'subTheme' => $ebook->subTheme,
                                                ]) }}"
                                                    class="btn btn-warning">
                                                    Bayar
                                                </a>
                                            @endif
                                            @if ($ebook->status === \App\Models\Ebook::STATUS_NOT_ACCEPT)
                                                <a href="{{ route('ebook.edit', $ebook) }}" class="btn btn-warning">
                                                    Edit
                                                </a>
                                            @endif
                                            @if ($ebook->status === \App\Models\Ebook::STATUS_SUBMIT)
                                                <a href="{{ route('ebook.edit', $ebook) }}" class="btn btn-warning">
                                                    Edit
                                                </a>
                                                @if ($ebook->draft)
                                                    <form action="{{ route('ebook.konfirmasi-ajukan-action', $ebook) }}"
                                                        method="post" onsubmit="return confirm('Yakin ?')">
                                                        @csrf
                                                        <button class="btn btn-primary">Ajukan</button>
                                                    </form>
                                                @endif
                                                @if ($ebook->status === \App\Models\Ebook::STATUS_PAYMENT)
                                                    <a href="{{ route('ebook.create', [
                                                        'theme' => $ebook->theme,
                                                        'subTheme' => $ebook->subTheme,
                                                    ]) }}" class="btn btn-warning btn-sm">
                                                        Bayar
                                                    </a>
                                                @endif
                                                @if ($ebook->status === \App\Models\Ebook::STATUS_NOT_ACCEPT)
                                                    <a href="{{ route('ebook.edit', $ebook) }}" class="btn btn-warning btn-sm me-1">
                                                        Edit
                                                    </a>
                                                @endif
                                                @if ($ebook->status === \App\Models\Ebook::STATUS_ACCEPT && $ebook->haki == null)
                                                    <a href="{{ route('ebook.haki', $ebook) }}" class="btn btn-warning btn-sm me-1">
                                                        Haki
                                                    </a>
                                                @endif
                                                @if ($ebook->status === \App\Models\Ebook::STATUS_SUBMIT)
                                                    <a href="{{ route('ebook.edit', $ebook) }}" class="btn btn-warning btn-sm me-1">
                                                        Edit
                                                    </a>
                                                    @if ($ebook->draft)
                                                        <form action="{{ route('ebook.konfirmasi-ajukan-action', $ebook) }}" method="post" onsubmit="return confirm('Yakin ?')">
                                                            @csrf
                                                            <button class="btn btn-primary btn-sm me-1">Ajukan</button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if ($ebook->status === \App\Models\Ebook::STATUS_PUBLISH && $ebook->royalty == 0)
                                                    <a href="{{ route('ebook.atur-royalti', $ebook) }}" class="btn btn-warning btn-sm me-1">
                                                        Atur Royalti
                                                    </a>
                                                @endif
                                            </div>
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
