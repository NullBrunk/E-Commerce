<div class="row portfolio-container">
    @foreach($products as $d)

        <div class="col-md-3 portfolio-item {{ $d['class'] }}">
            <div class="portfolio-wrap" style="flex-direction: column;">
                <a href="/details/{{ $d['id'] }}">
                    <img src="/storage/product_img/{{ $d['img'] }}" class="img-fluid imgpres" style="user-select: none !important;">
                </a>
            </div>

            <div class="products">

                <div class="categ">
                    {{ ucfirst($d["class"]) }}
                </div>

                <div class="title">
                    <a href="{{route("details", $d['id'])}}">{{ $d["name"] }}</a>
                </div>
            
                <div class="pricepr">                 
                    {{ $d -> format_price() }} <span>$</span>

                    <p class="pr_stars" id="stars-{{$d['id']}}"></p>

                    <script>
                        showrating(location.protocol + "//" + window.location.hostname + ":8000/api/rating/{{ $d['id'] }}", {{$d['id']}});
                    </script>                                      
                </div>

            </div>
        </div>

    @endforeach
</div>

@if(isset($search) && $products -> nextPageUrl() !== null)

    <button class="buttonpag" hx-get="{{ $products -> nextPageUrl() . "&q=" . $search }}" hx-swap="outerHTML" hx-trigger="revealed">
        <span class="paginationbutton">
            <span class="spinner-border spinner-border-sm htmx-indicator" role="status" aria-hidden="true"></span>
        </span>
    </button>

@elseif($products -> nextPageUrl() !== null)

    <button class="buttonpag" hx-get="{{ $products -> nextPageUrl() }}" hx-swap="outerHTML" hx-trigger="revealed">
        <span class="paginationbutton">
            <span class="spinner-border spinner-border-sm htmx-indicator" role="status" aria-hidden="true"></span>
        </span>
    </button>

    @endif