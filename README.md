# 2FA AUthentication in LaraveL

Install google2fa-laravel & bacon/bacon-qr-code library by composer.
```
composer require pragmarx/google2fa-laravel

composer require bacon/bacon-qr-code
```
Publish the Configuration
```
php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider"
```

Create a New Migration to add column in users table
```
php artisan make:migration add_google2fa_to_users_table --table=users
```
Update the Migration File
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('google2fa_secret')->nullable()->after('password');
});
```
Run the Migration
```
php artisan migrate
```

Create Controllers by the following command
```
php artisan make:controller AuthController
php artisan make:controller TwoFactorController

```
Add the necessary routes to handle enabling and verifying 2FA:
```php
Route::get('/login', [AuthController::class,'index'])->name('login');
Route::post('/login', [AuthController::class,'loginAction']);
Route::get('/login-2fa', [AuthController::class,'login2fa'])->name('2fa');
Route::post('/login-2fa', [AuthController::class, 'verify'])->name('2fa.verify');

Route::group(['middleware'=>['auth']],function(){
    Route::get('/logout', [AuthController::class,'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::post('/enable-2fa', [TwoFactorController::class,'enable2Fa'])->name('enable-2fa');
    Route::post('/verify-2fa', [TwoFactorController::class,'verify2Fa'])->name('verify-2fa');
});

```

Add Namespace in Auth Controller
```php
use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Validation\ValidationException;
```

Add the following Auth Code in Your Controller
```php

public function loginAction(Request $request){
    $request->validate(['email'=>'required|email|exists:users,email','password'=>'required']);

    if(auth()->attempt($request->only('email', 'password'),true)){
        
        $google2fa = new Google2FA();

        $user = auth()->user();
        if($user->google2fa_secret){

            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:user:credentials', $request->only('email', 'password'));
            $request->session()->put('2fa:auth:attempt', true);
            $otp_secret = $user->google2fa_secret;
            $google2fa->getCurrentOtp($otp_secret);

            auth()->logout();
            return redirect()->route('2fa');
        }else{
            return redirect()->route('dashboard');
        }
    }
    return redirect()->back()->withErrors(['email' => 'Invalid Credentials']);
}
public function login2fa(Request $request){
    $user_id = $request->session()->get('2fa:user:id');
    if(!$user_id){
        return redirect()->route('login');
    }
    return view('login2fa');
}

public function verify(Request $request)
{
    $request->validate([
        'one_time_password' => 'required|string',
    ]);

    $user_id = $request->session()->get('2fa:user:id');
    $credentials = $request->session()->get('2fa:user:credentials');
    $attempt = $request->session()->get('2fa:auth:attempt', false);

    if (!$user_id || !$attempt) {
        return redirect()->route('login');
    }

    $user = User::find($user_id);

    if (!$user) {
        return redirect()->route('login');
    }

    $google2fa = new Google2FA();
    $otp_secret = $user->google2fa_secret;

    if (!$google2fa->verifyKey($otp_secret, $request->one_time_password)) {
        throw ValidationException::withMessages([
            'one_time_password' => [__('The one time password is invalid.')],
        ]);
    }

    $guard = config('auth.web.guard');        
    if ($attempt) {
        $guard = config('auth.web.attempt_guard', $guard);
    }
    
    if (auth()->attempt($credentials, true)) {
        $request->session()->remove('2fa:user:id');
        $request->session()->remove('2fa:user:credentials');
        $request->session()->remove('2fa:auth:attempt');
    
        return redirect()->route('dashboard');
    }
    
    return redirect()->route('login')->withErrors([
        'password' => __('The provided credentials are incorrect.'),
    ]);
}
```
Add the following Code in `TwoFactorController`
```php
//Add Namespace
use PragmaRX\Google2FAQRCode\Google2FA;

//Add the following functions in your controller
public function enable2Fa(Request $request){
    if($request->ajax()){
        $user = auth()->user();
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        $qrCode = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secretKey
        ); 

        return response()->json(['status'=>true,'message'=>'OK','data'=>['qr'=>$qrCode,'secretKey'=>$secretKey]]);
    }
}
public function verify2Fa(Request $request){
    if($request->ajax()){
        $authId = auth()->id();
        $user = User::find($authId);
        $user->google2fa_secret = $request->secretKey; 
        $user->save();
        return response()->json(['status'=>true,'message'=>'2 Factor Authentication added successfully']);
    }
}
```

Add The Following Javascript code to Your Blade File
```html
<div class="card">
    <div class="card-body">
        <div class="title pb-20">
            <h2 class="h3 mb-0">2FA - Verification</h2>
            <div class="mt-2">
                <input type="checkbox" {{auth()->user()->google2fa_secret?'checked':''}} id="js-switch" class="js-switch handle2fa" data-color="#0099ff" />
            </div>
        </div>
    </div>
</div>
```

```js

$(document).on('change','.handle2fa',function(){
    if(this.checked){
        var header=modalHeader('Enable 2Factor');
        var body=`
            <div class="text-center">
                <div class="spinner-border spinner-border-md text-dark" role="status">
                </div>
            </div>
        `;
        modal(header,body,'','modal-lg');
        $.ajax({
            url:'enable-2fa',
            datatype:'json',
            method:"post",
            success:function(response){
                if(response.status==true){
                    var body=`
                        <div class="text-center">
                            <img src="${response.data.qr}" />
                            <label>Please scan this Qr Code By Google Authenticator App. Please Click on verify is scanned.</label>
                        </div>
                        `;
                    $('#main-modal-body').html(body);
                    $('#main-modal-footer').html(`
                        <button type="button" class="btn btn-outline-secondary" onclick="Cancel2FaVerify()">Cancel</button>
                        <button type="button" class="btn btn-dark" onclick="Verify2Fa(this,'${response.data.secretKey}')">Verify 2Fa</button>
                    `);
                }
            },
            error: function(xhr, status, error) {
                
            }
        });
    }else{
        alert('Under Development');
    }
});

window.Cancel2FaVerify = function(){
    $('#main-modal').modal('hide');
    window.location.reload();
}

window.Verify2Fa=function(btn,secretKey){
    btn.disabled=true;
    $.ajax({
        url:'verify-2fa',
        datatype:'json',
        method:"POST",
        data:{secretKey},
        success:function(response){
            btn.disabled=false;
            $('#main-modal').modal('hide');
            swal({
                type: 'success',
                title: 'Verify 2fa',
                text: '2-factor verification added successfully.',
            });
        }
    })
}
```