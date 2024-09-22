<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\User;

class TwoFactorController extends Controller
{
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
}