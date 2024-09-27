<div class="col-md-12 mb-2 mt-4">
    <div class="card card-default">
        <div class="card-header">
            <h3>Katalog Buku</h3>
        </div>
        <div class="card-body">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($listPublications as $indexPublication => $items)
                        <div class="carousel-item  @if ($indexPublication === 0) active @endif">
                            <div class="row justify-content-center">
                                @foreach ($items as $indexItem => $item)
                                    <div class="col-3">
                                        @php
                                            $theme = $item->theme;
                                            $publication = $item;
                                        @endphp
                                        <a
                                            href="{{ route('theme.publication.show', compact('theme', 'publication')) }}">
                                            <img src="{{ $item->coverLink }}" class="d-block w-100" alt="...">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @if (count($listPublications) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
