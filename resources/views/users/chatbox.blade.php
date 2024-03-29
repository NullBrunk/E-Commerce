@php
    use \Carbon\Carbon;
@endphp

@extends("layout.base")

@section("title", "Chatbox")


@section("content")   
    
    <style>
        .swal2-popup {
            background: #3e404b !important;
            color: white !important;
        }        
    </style>

    <link rel="stylesheet" href="/assets/css/contact.css">
    
    <script src="/assets/vendor/htmx/htmx.js"></script>

    <style>
        body {
            background-color: #282b36 !important;
        }
    </style>

    <script>
        function menu(id){
            document.getElementById(id).classList.toggle("none")
        }


        function sendmsg(){
            Swal.fire({
                title: 'Enter the mail of the user',
                input: 'text',
                inputAttributes: {
                autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Contact',
            
            }).then((result) => {
                if (result.isConfirmed) {
                    return window.location.href = "/chatbox/" + result.value;
                }
            })
        }

        function confirm_delete(url, id){
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#293e61',
                cancelButtonColor: '#af2024',
                confirmButtonText: 'Yes, delete it!'

            }).then((result) => {

                if (result.isConfirmed) {
                    fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        },
                    }).then(() => {
                        document.getElementById(id).remove();
                    })
                }
            })
        }
    </script>
    
    <div class="chatbody">
        
        <div class="left"></div>

        <div class="chatcontacts">
            <div class="chatlist">
                
                <div class="chatheader">
                    
                    <div class="chatChat" style="user-select: none !important;">
                        Chats
                    </div>
                    <button class="chatbutton" onclick="sendmsg()">
                        <svg viewBox="64 64 896 896" focusable="false" data-icon="form" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M904 512h-56c-4.4 0-8 3.6-8 8v320H184V184h320c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V520c0-4.4-3.6-8-8-8z"></path><path d="M355.9 534.9L354 653.8c-.1 8.9 7.1 16.2 16 16.2h.4l118-2.9c2-.1 4-.9 5.4-2.3l415.9-415c3.1-3.1 3.1-8.2 0-11.3L785.4 114.3c-1.6-1.6-3.6-2.3-5.7-2.3s-4.1.8-5.7 2.3l-415.8 415a8.3 8.3 0 00-2.3 5.6zm63.5 23.6L779.7 199l45.2 45.1-360.5 359.7-45.7 1.1.7-46.4z"></path></svg>
                    </button>

                </div>


                @foreach($array_contacts as $contact)
                    @php($mail = $contact["user"]["mail"])

                    <div onclick='window.location.href = "{{route("contact.user", $mail )}}"' class="cardcontact @if(isset($user) && $user === $mail) selected @endif" style="user-select: none !important;">
                        <div class="cardIcon">
                            <img class="cardAvatar" src="{{ $contact["user"]["avatar"] }}" alt="">
                        </div>

                        <div class="cardTitle">{{ $mail }}</div>


                        @if($contact["readed"] === 0 && $contact["id_contacted"] === $_SESSION["id"] )
                            <div class="unread"></div>
                        @endif

                        
                        <div class="lastmsg">
                            <div class="lastmsgcontent">
                                @if($contact["type"] === "text")
                                    {!! style($contact["content"]) !!}
                                @else
                                    Image
                                @endif
                                
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>

        <div class="right">
            <div class="chatsmsgs" >

                @isset($user)

                    <div class="userheader">
                        <div class="pdp">
                            <img class="cardAvatar" src="{{ $avatar }}">
                        </div>
                        <div class="menubar">
                            <div class="name">
                                {{ $user }}
                            </div>
                        </div>

                        <div class="toolbar">
                            <span class="close" onclick="window.location.href = '/chatbox/close/{{ $user }}'" style="padding: 22px;">
                                    <i style="font-size: 27px;" class="bi bi-x-lg"></i>
                            </span>
                        </div>
                    </div>

                    <div class="messages"  id="chat" >
                        
                        <div  data-aos="fade-up" data-aos-duration="200">

                            @if(!empty($messages))

                                @foreach($messages as $msg)

                                    @php($me = $msg -> id_contactor === $_SESSION["id"])

                                    @if(
                                        !isset($old) or (
                                            isset($old) && 
                                            Carbon::parse($msg["time"]) -> format('d') 
                                                !== 
                                            Carbon::parse($old["time"]) -> format('d') 
                                            )
                                        )
                                        
                                        <div class="showtime" style="user-select: none !important;">{{ Carbon::parse($msg["time"]) -> format('d F, Y') }}</div>
                                    
                                        @endif


                                    <div class="content @if(!$me) his @endif" id="divmsg{{ $msg["id"] }}">
                                        <div class="contentc" style="display: flex;">

                                            @if(isset($old) and $old["me"] !== $me)
                                                <div style="margin-top:50px;"></div>
                                            @endif

                                            @if(!$me)

                                                <div class="hovershow" style="position: relative; width: calc(100% - 2px); display:flex;">
                                                    
                                                    <div style="display: inline-block; color: white; padding: 12px; font-size: 15px; font-family: Avenir; white-space: pre-line; background-color: #434756; overflow-wrap: anywhere; max-width: calc(100% - 148px); transition: all 0.33s ease 0s; border-radius: 0.3em 1.3em 1.3em 0.3em;">@if($msg["type"]==="text"){!! style($msg["content"])!!}@else<img src="/storage/{{$msg["content"]}}" style="max-height: 100%; max-width:100%;">@endif</div><style>p {margin-block-start: 0px; margin-block-end: 0px;}</style><div class="ce-avatar undefined" style="position: absolute; width: 44px; height: 44px; border-radius: 50%; background-repeat: no-repeat; background-position: center center; background-size: 48px; color: white; text-align: center; font-family: Avenir; font-size: 15px; line-height: 44px; font-weight: 600; background-color: rgb(70, 117, 153); bottom: 0px; left: 2px; display: none;">aaa<div class="ce-avatar-status" style="position: absolute; top: 0px; right: 0px; width: 8px; height: 8px; border-radius: 100%; border: 2px solid white; display: none; background-color: rgb(245, 34, 45);"></div></div>
                                                    <span class="options" style="padding-top: 10px !important; margin-left: 0px;">
                                                        <span style="padding-left: 10px; color: white; user-select: none !important;" >
                                                            {{ Carbon::parse($msg["time"]) -> format('H:i') }}
                                                        </span>
                                                    </span>
                                                </div>
                                            @else 
                                            

                                                <div class="hovershow" style="position: relative; width: calc(100% - 2px); display:flex;">
                                                    <div id="menu{{ $msg["id"] }}" class="none">
                                                        @if($msg["type"] === "text")
                                                            <button hx-target="#msg{{$msg['id']}}" hx-get="{{ route("contact.edit_form", $msg["id"]) }}" hx-swap="innerHTML" class="btn button-blue" style="margin: 0px; border: 1px solid #484883;;">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                        @endif
        
                                                        <button onclick='confirm_delete( "{{ route("contact.delete", $msg["id"]) }}", "divmsg{{$msg["id"]}}" )' class="btn button-red">
                                                            <i class="bi bi-trash2-fill"></i>
                                                        </button>  
                                                    </div>
                                                    
                                                    <span class="options">
                                                        <span style="padding-left: 10px; color: white; user-select: none !important;">
                                                            {{ Carbon::parse($msg["time"]) -> format('H:i') }}
                                                        </span>
                                                    </span>
                                                <div class="msg" id="msg{{ $msg['id'] }}" style="margin-left: auto; color: white; display: inline-block; background-color: rgb(24, 144, 255);  text-align: left; padding: 12px; font-size: 15px; font-family: Avenir; white-space: pre-line; overflow-wrap: anywhere; max-width: calc(100% - 100px); transition: all 0.33s ease 0s; border-bottom-left-radius: 1.3em; border-top-left-radius: 1.3em;">@if($msg["type"]==="text"){!!style($msg["content"])!!}@else<img src="/storage/{{$msg["content"]}}" style="max-height: 100%; max-width:100%;">@endif</div><style>p {margin-block-start: 0px; margin-block-end: 0px;}</style><div class="ce-avatar undefined" style="position: relative; width: 44px; height: 44px; border-radius: 50%; background-repeat: no-repeat; background-position: center center; background-size: 48px; color: white; text-align: center; font-family: Avenir; font-size: 15px; line-height: 44px; font-weight: 600; background-color: rgb(12, 170, 220); display: none;">AN<div class="ce-avatar-status" style="position: absolute; top: 0px; right: 0px; width: 8px; height: 8px; border-radius: 100%; border: 2px solid white; display: none; background-color: rgb(245, 34, 45);"></div></div>
                                                <p style="color: #282b36; padding: 6px; background: #1890ff; border-top-right-radius: 0.3em; border-bottom-right-radius: 0.3em;" onclick='menu("menu{{ $msg["id"] }}")'>
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </p>
                                            </div>

                                            @endif

                                        
                                        </div>
                                    </div>

                                    @php($old = $msg)
                                    @php($old["me"] = $me)
                                @endforeach
                            @endif
                            <div style="margin-top:20px;">

                            </div>
                            
                        </div>
                    
                    </div>

                    <form  class="textbar" method="post" action="{{route("contact.store")}}" id="formchat" enctype="multipart/form-data">
                        
                        @csrf

                        <input name="content" class="input-text" type="text" placeholder="Send a message to {{$user}}" autofocus>
                    
                        <input type="file" id="file-input" name="img" style="width: 0;" >
                        <label for="file-input">
                            <i class="bx bx-image"></i>
                        </label>

                        <button>
                            <i class="bx bx-send"></i>
                        </button>
                    

                    </form>
                @endisset

            </div>
        </div>

        <div class="bouchetrou"></div>

    </div>

    <script>
        var fileInput = document.getElementById('file-input');
        var form = document.getElementById('formchat');
        fileInput.addEventListener('change', function() {
            form.submit();
        });

        window.addEventListener('load', function() {
            var chat = document.getElementById("chat");
            chat.scrollTop = chat.scrollHeight; // Défilement vers le bas              
        });
             

        
    </script>

    {{-- Error --}}
    @error("contact_yourself")
        <script>salert("{{$message}}")</script>
    @enderror

    @error("contact_no_one")
            <script>salert("{{$message}}")</script>
    @enderror


@endsection