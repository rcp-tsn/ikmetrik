<?php

namespace App\Observers;

use App\Helpers\ImageHelper;
use App\Mail\NewCrmSupportReplyMail;
use App\Mail\NewCrmSupportWelcomeMail;
use App\Mail\NewCrmSupportMail;
use App\Mail\NewDemoUserMail;
use App\Models\Company;
use App\Models\CompanyAssignment;
use App\Models\CrmSupport;
use App\Notifications\CrmSupportActivity;
use App\User;
use Mail;

class CrmSupportObserver
{
    /**
     * Handle the crm_support "created" event.
     *
     * @param  \App\CrmSupport  $crmSupport
     * @return void
     */
    public function created(CrmSupport $crmSupport)
    {

        try {
            Mail::to([config('app.destek')])->send(new NewCrmSupportMail(
                $crmSupport
            ));


        } catch (Swift_TransportException $e) {

        }
        foreach(config('app.destek_users') as $key => $value) {
            $user = User::find($value);
            $user->notify(new CrmSupportActivity($crmSupport));
        }

        if ($crmSupport->contact_by == 'DEMO') {
            if ($crmSupport->status == 'DEMO ONAYLANDI') {
                ///////
                $random_password = str_random(8);

                $user = User::create([
                    'name' => $crmSupport->name,
                    'email' => $crmSupport->email,
                    'password' => bcrypt($random_password),
                    'department_id' => 23,
                    'is_demo' => 1,
                    'company_id' => 134,
                ]);


                $user->roles()->sync([15]);

                $company = Company::find(134);

                CompanyAssignment::create([
                    'sgk_company_id' => 654,
                    'user_id' => $user->id
                ]);

                if ($user) {
                    $imageHelper = new ImageHelper();
                    $imageHelper->createUserImage($user);
                }
                try {
                    Mail::to([$user->email])->send(new NewDemoUserMail(
                        $company, $user, $random_password
                    ));
                } catch (Swift_TransportException $e) {

                }
                /////
            }
        }

    }

    /**
     * Handle the crm_support "updated" event.
     *
     * @param  \App\CrmSupport  $crmSupport
     * @return void
     */
    public function updated(CrmSupport $crmSupport)
    {
        if ($crmSupport->contact_by == 'DEMO') {
            if($crmSupport->status == 'DEMO ONAYLANDI') {
                ///////
                $random_password = str_random(8);

                $user = User::create([
                    'name' => $crmSupport->name,
                    'email' => $crmSupport->email,
                    'password' => bcrypt($random_password),
                    'department_id' => 23,
                    'is_demo' => 1,
                    'company_id' => 134,
                ]);


                $user->roles()->sync([15]);

                $company = Company::find(134);

                CompanyAssignment::create([
                    'sgk_company_id' => 654,
                    'user_id' => $user->id
                ]);

                if ($user) {
                    $imageHelper = new ImageHelper();
                    $imageHelper->createUserImage($user);
                }
                try {
                    Mail::to([$user->email])->send(new NewDemoUserMail(
                        $company, $user, $random_password
                    ));
                } catch (Swift_TransportException $e) {

                }
                /////
            }
        } else {
          $a = explode('[cevap]', $crmSupport->message);
          if (count($a) > 1) {
              if (strlen($a[1]) > 0) {
                  Mail::to([$crmSupport->email])->send(new NewCrmSupportReplyMail(
                      $crmSupport
                  ));
              }
          }
        }


    }

    /**
     * Handle the crm_support "deleted" event.
     *
     * @param  \App\CrmSupport  $crmSupport
     * @return void
     */
    public function deleted(CrmSupport $sgkCompany)
    {
        //
    }

    /**
     * Handle the crm_support "restored" event.
     *
     * @param  \App\CrmSupport  $crmSupport
     * @return void
     */
    public function restored(CrmSupport $crmSupport)
    {
        //
    }

    /**
     * Handle the crm_support "force deleted" event.
     *
     * @param  \App\CrmSupport  $crmSupport
     * @return void
     */
    public function forceDeleted(CrmSupport $crmSupport)
    {
        //
    }
}
