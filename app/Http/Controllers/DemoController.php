<?php

namespace App\Http\Controllers;

use App\Models\CrmSupport;
use App\User;
use Illuminate\Http\Request;
use Response;

class DemoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function requestDemo(){
        return view('newmetric.requestdemo');
    }
    public function requestDeneme(){
        return view('newmetric.requestdeneme');
    }
    public function requestDemoPost(Request $request){
        $messages = array(
            'name.required' => 'İsim soyisim bilgisi gereklidir.',
            'company.required' => 'Firma bilgisi gereklidir.',
            'phone.required' => 'Telefon bilgisi gereklidir.',
            'email.required' => 'E-posta bilgisi gereklidir.',

            'email.unique' => 'Bu e-posta ile daha önceden demo talebi alınmıştır.',

        );
        $this->validate($request, [
            'name' => 'required',
            'company' => 'required',
            'phone' => 'required',

            'email' => 'required|unique:crm_supports',
        ], $messages);
        //message
        $input['name'] = $request->name;
        $input['company'] = $request->company;
        $input['phone'] = $request->phone;
        $input['email'] = $request->email;
        $input['message'] = $request->message;
        $input['ip'] = request()->ip();
        $input['contact_by'] = 'DEMO';
        $input['status'] = 'DEMO ONAYLANDI';
        $input['is_customer'] = 0;

        //dd($input);
        $has = User::where('email', $request->email)->first();
        if ($has) {
            return  back()->with("message","E-posta adresi ile ilişkili bir kayıt bulunmaktadır. Lütfen destek@ikmetrik.com e-posta adresine bilgilendirmede bulununuz.");
        }
        $crm_support = CrmSupport::create($input);
        if ($crm_support) {
            return  back()->with("message","Demo isteğiniz başarılı bir şekilde kayıt edilmiştir. Demo erişim bilgileriniz belirttiğiniz e-posta adresine gönderilmiştir.");
        } else {
            return  back()->with("message","Demo isteği oluşturma esnasında sorun oluşmuştur. Lütfen tekrar deneyiniz.");
        }

    }

}
