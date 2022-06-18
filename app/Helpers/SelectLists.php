<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\SgkCompany;
use Auth;

trait SelectLists
{

    /**
     * Get department list as input select
     *
     * @return mixed
     */
    protected function getDepartmentSelectList()
    {
        return \App\Models\Department::pluck('title', 'id')->all();
    }

    /**
     * Get customer types list as input select
     *
     * @return mixed
     */
    protected function getCustomerTypeSelectList($customer = null)
    {
        if ($customer) {
            return $customer->customer_types->pluck('name', 'id')->all();
        }
        return null;
    }

    /**
     * Get sector list as input select
     *
     * @return array
     */
    protected function getSectorSelectList()
    {
        return \App\Models\Sector::pluck('title', 'id')->all();
    }

    /**
     * Get work title list as input select
     *
     * @return mixed
     */
    protected function getWorkTitleSelectList()
    {
        return \App\Models\WorkTitle::pluck('title', 'id')->all();
    }



    /**
     * Get company list as input select type
     *
     * @return array|string
     */
    protected function getCompanySelectList()
    {
        $user = \Auth::user();

        if ($user->hasRole('Admin')) {
            $companies = Company::pluck('name', 'id')->all();;

        } else {
            $companies = Company::where('id', $user->company_id)->pluck('name','id')->all();
        }

        return $companies;
    }

    /**
     * Get parent sgk companies list as input select
     *
     * @return mixed
     */
    protected function getCompaniesSelectList()
    {
        if (Auth::user()->company_id == config('app.main_company_id')) {
            $results = \App\Models\Company::pluck('name', 'id');
        } else {
            $results = \App\Models\Company::where('id', Auth::user()->company_id)->pluck('name', 'id');
        }

        $defaultSelection = ['' => 'Seçiniz'];
        $results = $defaultSelection + $results->toArray();
        return $results;
    }

    /**
     * @return array
     */
    protected function getSectorsSelectList()
    {
        $results = \App\Models\Sector::pluck('name', 'id');
        $defaultSelection = ['' => 'SEÇİNİZ'];
        $results = $defaultSelection + $results->toArray();
        return $results;
    }

    protected function getCitiesSelectList()
    {
        $results = \App\Models\City::pluck('name', 'id');
        $defaultSelection = ['' => 'SEÇİNİZ'];
        $results = $defaultSelection + $results->toArray();
        return $results;
    }






}
