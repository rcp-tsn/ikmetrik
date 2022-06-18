<?php

namespace App\Observers;

use App\Mail\NewSgkCompanyMail;
use App\Mail\NewSgkCompanyWelcomeMail;
use App\Models\SgkCompany;
use App\Notifications\SgkCompanyActivity;
use App\User;
use Mail;

class SgkCompanyObserver
{
    /**
     * Handle the sgk company "created" event.
     *
     * @param  \App\SgkCompany  $sgkCompany
     * @return void
     */
    public function created(SgkCompany $sgkCompany)
    {
        try {
            Mail::to([config('app.destek')])->send(new NewSgkCompanyMail(
                $sgkCompany
            ));
            $user = User::find($sgkCompany->created_by);
            if ($user) {
                Mail::to([$user->email])->send(new NewSgkCompanyWelcomeMail(
                    $sgkCompany, $user
                ));
            }

        } catch (Swift_TransportException $e) {

        }
        /*
        foreach(config('app.destek_users') as $key => $value) {
            $user = User::find($value);
            $user->notify(new SgkCompanyActivity($sgkCompany));
        }
       */
    }

    /**
     * Handle the sgk company "updated" event.
     *
     * @param  \App\SgkCompany  $sgkCompany
     * @return void
     */
    public function updated(SgkCompany $sgkCompany)
    {
        //
    }

    /**
     * Handle the sgk company "deleted" event.
     *
     * @param  \App\SgkCompany  $sgkCompany
     * @return void
     */
    public function deleted(SgkCompany $sgkCompany)
    {
        //
    }

    /**
     * Handle the sgk company "restored" event.
     *
     * @param  \App\SgkCompany  $sgkCompany
     * @return void
     */
    public function restored(SgkCompany $sgkCompany)
    {
        //
    }

    /**
     * Handle the sgk company "force deleted" event.
     *
     * @param  \App\SgkCompany  $sgkCompany
     * @return void
     */
    public function forceDeleted(SgkCompany $sgkCompany)
    {
        //
    }
}
