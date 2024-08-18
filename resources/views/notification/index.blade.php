@extends('layouts.admin.main')

@section('title', 'Notifikasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">
        Notifikasi
    </li>
@endsection

@section('content')
    <div class="row">
        @foreach ($notifications as $item)
            <div class="col-md-12 mb-2">
                <a href="{{ route('notification.read', $item) }}" target="_blank" style="text-decoration: none">
                    <div class="card card-default">
                        <div class="card-body">
                            <h5>
                                {{ $item->description }}
                            </h5>
                            <span>{{ $item->timelapse }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
        <div class="col-md-12 mb-2">
            <div class="card card-default">
                <div class="card-body">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
