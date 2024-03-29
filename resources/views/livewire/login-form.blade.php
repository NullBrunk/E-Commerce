<div>
    <form method="post" class="login-form">
        
        @csrf

        <div class="form-group">
            
           

            @error("verify")
                <div style="text-align: center;color: #dc3545;">
                    {{ $message }}
                </div>
            @enderror

            @error("invalid")
                <div style="text-align: center;color: #dc3545;">
                    Invalid mail or password.
                </div>
            @else
                <div style="text-align: center;color: #dc3545;">
                </div>
            @endif

            
            <input wire:model="email" type="mail" id="email" name="email" class="form-control rounded-left @if($errors -> has('email') or $errors -> has('invalid') or $errors -> has('verify') ) is-invalid @elseif($email !== '') is-valid @endif" placeholder="E-mail" value="{{old("email")}}" autofocus>    
            <div class="invalid-feedback">
                @error("email")
                    {{ $message }}
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <input wire:model="pass" type="password" id="pass" name="pass" class="form-control rounded-left @error('pass') is-invalid @elseif($pass !== '') is-valid @endif" placeholder="Password">
            <div class="invalid-feedback">
                @error("pass")
                    {{ $message }}
                @enderror
            </div>
            
        </div>

        <div class="form-group">
            <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
        </div>

        <div class="d-flex justify-content-between">

            <a style="color:#007185 !important;margin-top: 3px;" href="{{ route("auth.forgot_form") }}">Forgot password</a>
            <a style="color:#007185 !important" href="{{ route("auth.signup") }}"> Sign Up</a>


        </div>
    </form>
</div>
