@extends("layout.base")

@section("title", "Sell a product")


@section("content")
    <body>

        <link rel="stylesheet" href="/assets/vendor/filepond/filepond.css">
        <link rel="stylesheet" href="/assets/vendor/filepond/filepond-plugin-image-preview.css">

        <script>
            $(function() {        
                $('#textbar').markItUp(
                    mySettings
                );
            }) 
        </script>

        <main id="main">

            <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 100px; padding-bottom: 0px !important;">
                <div class="container">
                    <ol></ol>
                    <h2></h2>
                </div>
            </section>

            <section id="portfolio-details" class="portfolio-details" style="padding: 0px;">
            
                <form method="post" action="{{ route("product.store") }}" enctype="multipart/form-data">  
                    
                    <div class="col-lg-4 margin50"  style="color: white; background-color: #324769 !important; border-radius: 12px; margin: auto; margin-bottom: 86px;">
                        <div class="portfolio-info" style="padding-bottom: 10px;" >
                            <h2>Sell a product</h2>
                            <hr>
                                       
                            <ul>
                                <li class="li"><strong style="display: flex; ">Name:  <input  placeholder="Brown Mushroom (Max: 24 characters)" class="input-beautify" type="text" name="name" value="{{old("name")}}" autofocus></strong></li>
                                <li class="li"><strong style="display: flex; ">Price: <input  placeholder="From 0.00 to 999md " class="input-beautify" type="text" name="price" value="{{old("price")}}"></strong></li>
                                <li class="li">
                                    <strong style="display: flex; ">Category: 

                                        <x-select-category />

                                    </strong>
                                </li>                

                            </ul>

                            <strong style="display: flex;">
                                Main image: 
                            </strong>
                            <input id="mainimg" type="file" class="filepond" data-allow-reorder="true" data-max-file-size="3MB" name="mainimg">

                            <strong style="display: flex;">
                                Other images:
                            </strong>
                            
                            <input id="otherimgs" type="file" class="filepond" multiple data-allow-reorder="true" data-max-file-size="3MB" name="otherimg">
                            @if(count($errors) > 0)
                                {{ session(["error" => true]) }}
                            @endif
                        </div>

                        <div class="portfolio-info">
                            <p  style="background: #fff;">
                                <textarea id="textbar" placeholder="Description of the product" class="textarea-beautify" type="text" name="description">{{old("description")}}</textarea>      
                            </p> 
                        </div>
                    
                        @csrf    
                        <div style="display: flex; width: 100%;">
                            <input class="addtocart" style="margin-bottom: 3%;" name="submit" type="submit" value="Sell !">
                        </div>  
                    </div>
                </form>
        
            </section>

            <script src="/assets/vendor/filepond/filepond.js"></script>
            <script src="/assets/vendor/filepond/filepond-plugin-image-preview.js"></script>

            <script>

                document.getElementById("textbar").style.height = "14vh" 
            

                FilePond.registerPlugin(FilePondPluginImagePreview);
                FilePond.create(document.getElementById("mainimg"));
                FilePond.create(document.getElementById("otherimgs"));

                FilePond.setOptions({
                    server : {
                        process : "{{ route('tmp.store') }}",
                        revert : "{{ route('tmp.delete') }}",
                        headers : {
                            "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                        }
                    },
                })

            </script>


        </main>

        {{-- Gestion des erreurs --}}

        @error("mainimg")
                <script>salert("{{$message}}")</script>
        @enderror

        @error("name")
            <script>
                salert("{{$message}}")
            </script>
        @enderror

        @error("price")
            <script>
                salert("{{$message}}")
            </script>
        @enderror

        @error("description")
            <script>
                salert("{{$message}}")
            </script>
        @enderror

@endsection

