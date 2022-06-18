<?php

use App\Models\CompanyHasDocument;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceForm;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramEvalation;
use App\Models\PerformanceProgramType;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

if (!function_exists('pdkEmployeeTcPdf'))
{
    function pdkEmployeeTcPdf($html)
    {
        if (Auth::user()->company_id == 12 )
        {
            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);

            if (isset($html[2]))
            {
                foreach ($html[2] as $key => $personel) {

                    $tc = substr($personel, 0, 11);
                    $tc = trim($tc);

                    // $year_array = explode('-', $declaration->declarations_date);
                    if (strlen($tc) == 11) {
                        if (is_numeric($tc)) {
                            $identity_number = $tc;
                        }
                    }
                }
            }

            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;


        }
        if (Auth::user()->company_id == 211)
        {
            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">.*?([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])<\/p>/', $html, $html);

            $html = explode('&#160;',$html[0][6]);

//                    dd($html);
//                    $personels = [];
//                    foreach ($html[40] as $html1_key => $html_1) {
//                        $personels[$html_1][] = $html[1][$html1_key];
//                    }

            foreach ($html as $key => $personel) {

                $tc = substr($personel, 0, 11);
                $tc = trim($tc);

                // $year_array = explode('-', $declaration->declarations_date);
                if (strlen($tc) == 11) {
                    if (is_numeric($tc)) {
                        $identity_number = $tc;
                    }
                }
            }
            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;
        }
        if (Auth::user()->company_id == 213)
        {
            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">.*?([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])<\/p>/', $html, $html);




            foreach ($html[2] as $key => $personel) {

//                $tc = substr($personel, 0, 11);
                $tc = trim($personel);

                // $year_array = explode('-', $declaration->declarations_date);
                if (strlen($tc) == 11) {
                    if (is_numeric($tc)) {
                        $identity_number = $tc;
                    }
                }
            }
            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;
        }
    }
}


if (!function_exists('employeeTcPdf'))
{
    function employeeTcPdf($html)
    {


//        $sgk_company = getSgkCompany();

        if(Auth::user()->company_id == 157) // köfteci sebahattin
        {

            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">.*?([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]).*?<\/p>/', $html, $html);


            return $identity_number = $html[2][1];

        }

        if(Auth::user()->company_id == 158) // menemenci alaattin
        {

            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">.*?([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]).*?<\/p>/', $html, $html);


            return $identity_number = $html[2][0];

        }


        if ( Auth::user()->company_id == 128 || Auth::user()->company_id == 30 || Auth::user()->id == 278)
        {

            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">.*?([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]).*<\/p>/', $html, $html);

//            dd($html);
//            $html = explode('&#160;',$html[2][15]);
//            $html = strip_tags($html[1]);
//            dd($html);

//                    $personels = [];
//                    foreach ($html[2] as $html1_key => $html_1) {
//                        $personels[$html_1][] = $html[1][$html1_key];
//                    }
//                    dd($personels);

            foreach ($html[2] as $key => $personel) {

                $tc = substr($personel, 0, 11);
                $tc = trim($tc);


                // $year_array = explode('-', $declaration->declarations_date);
                if (strlen($tc) == 11) {
                    if (is_numeric($tc)) {
                        $identity_number = $tc;
                    }
                }
            }

            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;
        }
//        if (Auth::user()->company_id == 12)
//        {
//            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);
//
//
//            $html = explode('&#160;',$html[0][1]);
//
////                    dd($html);
////                    $personels = [];
////                    foreach ($html[40] as $html1_key => $html_1) {
////                        $personels[$html_1][] = $html[1][$html1_key];
////                    }
//
//            foreach ($html as $key => $personel) {
//
////                $tc = substr($personel, 0, 11);
//                $tc = trim($personel);
//
//                // $year_array = explode('-', $declaration->declarations_date);
//                if (strlen($tc) == 11) {
//                    if (is_numeric($tc)) {
//                        $identity_number = $tc;
//                    }
//                }
//            }
//            if (!isset($identity_number)){
//                $identity_number = 0;
//            }
//
//            return $identity_number;
//        }

        if ( Auth::user()->company_id == 63 || Auth::user()->company_id == 277) // Demo plastik A.Ş
        {

            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);

            if (isset($html[2]))
            {

                foreach ($html[2] as $key => $personel) {
                    $degers = explode('<b>',$personel);

                    foreach ($degers as $deger)
                    {

                        $tc = substr($deger, 0, 11);
                        $tc = trim($tc);

                        // $year_array = explode('-', $declaration->declarations_date);
                        if (strlen($tc) == 11) {
                            if (is_numeric($tc)) {
                                $identity_number = $tc;
                            }
                        }
                    }

                }
            }

            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;

        }

        if (Auth::user()->company_id == 280 || Auth::user()->company_id == 275 || Auth::user()->company_id == 276 || Auth::user()->company_id == 279 || Auth::user()->company_id == 272 ||  Auth::user()->company_id == 12 || Auth::user()->company_id == 63 || Auth::user()->company_id == 215 || Auth::user()->company_id == 218 || Auth::user()->company_id == 128 || Auth::user()->company_id == 220 || Auth::user()->company_id == 221  || Auth::user()->company_id == 227 || Auth::user()->company_id == 228 || Auth::user()->company_id == 229 || Auth::user()->company_id == 214 || Auth::user()->company_id == 230  || Auth::user()->company_id == 184 || Auth::user()->company_id == 232 || Auth::user()->company_id == 233 || Auth::user()->company_id == 216  | Auth::user()->company_id == 237|| Auth::user()->company_id == 238 || Auth::user()->company_id == 242 || Auth::user()->company_id == 243 || Auth::user()->company_id == 244 || Auth::user()->company_id == 246 || Auth::user()->company_id == 248 || Auth::user()->company_id == 251 || Auth::user()->company_id == 252 || Auth::user()->company_id == 253 || Auth::user()->company_id == 254 || Auth::user()->company_id == 255 || Auth::user()->company_id == 51 || Auth::user()->company_id == 261 || Auth::user()->company_id == 260 || Auth::user()->company_id == 262 || Auth::user()->company_id == 264 || Auth::user()->company_id == 265 || Auth::user()->company_id == 266 || Auth::user()->company_id == 268 || Auth::user()->company_id == 170 || Auth::user()->company_id == 269  || Auth::user()->company_id == 273 || Auth::user()->company_id == 274 )
        {

           preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);


            if (isset($html[2]))
            {

                foreach ($html[2] as $key => $personel) {

                    $tc = substr($personel, 0, 11);
                    $tc = trim($tc);

                    // $year_array = explode('-', $declaration->declarations_date);
                    if (strlen($tc) == 11) {
                        if (is_numeric($tc)) {
                            $identity_number = $tc;
                        }
                    }
                }
            }

            if (!isset($identity_number)){
                $identity_number = 0;
            }
//            dd($identity_number);
            return $identity_number;


        }
        if ( Auth::user()->company_id == 109)
        {
            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);

            if (isset($html[2]))
            {

                foreach ($html[2] as $key => $personel) {

                   // $tc = substr($personel, 0, 11);
                    $tc = trim($personel);

                    // $year_array = explode('-', $declaration->declarations_date);
                    if (strlen($tc) == 11) {
                        if (is_numeric($tc)) {
                            $identity_number = $tc;
                        }
                    }
                }
            }

            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;


        }
        if (Auth::user()->company_id == 211)
        {
            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);

            $html = explode('&#160;',$html[0][6]);

//                    dd($html);
//                    $personels = [];
//                    foreach ($html[40] as $html1_key => $html_1) {
//                        $personels[$html_1][] = $html[1][$html1_key];
//                    }

            foreach ($html as $key => $personel) {

                $tc = substr($personel, 0, 11);
                $tc = trim($tc);

                // $year_array = explode('-', $declaration->declarations_date);
                if (strlen($tc) == 11) {
                    if (is_numeric($tc)) {
                        $identity_number = $tc;
                    }
                }
            }
            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;
        }
        if ( Auth::user()->company_id == 213)
        {
            preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);

            $html = explode('&#160;',$html[0][1]);

//                    dd($html);
//                    $personels = [];
//                    foreach ($html[40] as $html1_key => $html_1) {
//                        $personels[$html_1][] = $html[1][$html1_key];
//                    }

            foreach ($html as $key => $personel) {

//                $tc = substr($personel, 0, 11);
                $tc = trim($personel);

                // $year_array = explode('-', $declaration->declarations_date);
                if (strlen($tc) == 11) {
                    if (is_numeric($tc)) {
                        $identity_number = $tc;
                    }
                }
            }
            if (!isset($identity_number)){
                $identity_number = 0;
            }

            return $identity_number;
        }



    }
}



if (!function_exists('company_kvkk_file_create'))
{
    function company_kvkk_file_create($document_id,$company,$sgk_company_id)
    {
        Settings::setOutputEscapingEnabled(true);
        if ($document_id == 29) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/29.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);


        }

        if ($document_id == 2) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/2.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 3) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/3.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 4) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/4.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 5) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/5.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 6) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/6.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 7) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/7.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 8) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/8.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 9) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/9.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 10) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/10.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 11) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/11.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 12) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/12.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 13) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/13.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 14) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/14.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 15) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/15.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 16) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/16.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 17) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/17.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 18) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/18.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 19) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/19.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 20) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/20.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 21) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/21.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 22) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/22.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 23) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/23.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 24) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/24.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 25) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/25.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 26) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/26.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }
        if ($document_id == 27) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/27.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);
        }

        if ($document_id == 28) {
            $random = rand(0, mt_getrandmax());
            $template = new TemplateProcessor('documents/28.docx');
            $template->setValue('name', $company->name);
            $template->setImageValue('company_logo', array(public_path('/' . $company->logo), 'height' => 150, 'width' => 150));
            File::makeDirectory('company_kvkk_documents/' . $company->id, 0777, true, true);
            $template->saveAs('company_kvkk_documents/' . $company->id . '/' . $random . '.docx');
            $has_documnet = CompanyHasDocument::create([
                'sgk_company_id' => $sgk_company_id,
                'document_id' => $document_id,
                'document_file' => '/company_kvkk_documents/' . $company->id . '/' . $random . '.docx'
            ]);


        }

    }
}




if (!function_exists('sendSms'))
{
    function sendSms($id,$sms,$payroll = null,$working,$messages)
    {


        $company = Company::find($id);

        if($company->sms_company == 'GOLDMESAJ')

        {

       $request =      Curl::to('http://www.goldmesaj.com.tr/api/v1/sendsms')

                ->withData( [
                    'username' => $sms->username,
                    'password' => $sms->password,
                    'sdate' => null,
                    'speriod' => '48',
                    'message' =>
                        [
                            'sender' => $sms->sender,
                            'text' => $messages,
                            'utf8' => '1',
                            'gsm' => [
                                $working->mobile
                            ]
                        ]
                ])
                ->post();

//
//            $data = [
//                'username' => $sms->username,
//                'password' => $sms->password,
//                'sdate' => null,
//                'speriod' => '48',
//                'message' =>
//                    [
//                        'sender' => $sms->sender,
//                        'text' => $messages,
//                        'utf8' => '1',
//                        'gsm' => [
//                            $working->mobile
//                        ]
//                    ]
//            ];
//
//              Http::post('http://www.goldmesaj.com.tr/api/v1/sendsms',$data);


        }


        if($company->sms_company == 'TURKCELL')
        {
            $deger = Curl::to('https://mesajussu.turkcell.com.tr/api/api/integration/v2/login')
                ->withData([
                    "username" => $sms->username,
                    "password" => $sms->password
                ])
                ->asJson()
                ->post();

            foreach ($deger as $key => $a )
            {

                if ($key == "token")
                {
                    $api_key = $a;
                }

            }

            //  $api_key = "APIeyJhbGciOiJSUzI1NiJ9.eyJtc2lzZG4iOiI1MzI0MDg0NTg2IiwiaXAiOiIxODUuMTExLjIzNS4xNTAiLCJ1c2VySWQiOjI0NDIwNSwidmFsaWRpdHlUaW1lb3V0Ijo4NjQwMDAwMCwiY3JlYXRlRGF0ZSI6MTY0MzMwMzgxNTkwN30.R1VCTMKRIYFPiKiuGR1eZPIVfxdpiXhe8LloxVefPXcITsNyUqXDlR8f_0tIffsNlbaGLs76kR6tDwW6UnIDNZVR-_37WFE61yFjWCAVhIizJsuPXQ3BBWbE6xpQde2-CV1iXfkb9LV0eQiY3Wj2UYOMSsdRUlwY7dKe_bDizFONncL3lNc0wuvDbH89D3PXrIhrvDQi3Qb_cAEwKf_CwaAV-cOZDotdxOYjXa11ssHn6xd0F2we6NMQ_mjpq1F32NAQ41KtCPfNy4evv3nSxkV7Un9kXulX3hSXX9a00hElna38xmeDSvxzZ68r2qWHXSLx6wRjxoi77oJD-eLkTA";
            $deger2 = Curl::to('https://mesajussu.turkcell.com.tr/api/api/integration/v2/sms/send/oton')
                ->withHeaders(['token'=>$api_key])
                ->withData( [
                    "message" => $messages,
                    "sender"=> $sms->sender,
                    "etkFlag"=> false,
                    "receivers"=> [
                        $working->mobile
                    ],
                ])
                ->asJson()
                ->post();
        }


        if ($company->sms_company == 'TURATEL')
        {


            $xml  = '<?xml version="1.0" encoding="utf-8" ?>
                        <MainmsgBody>
                                <Command>0</Command>
                                <PlatformID>1</PlatformID>
                                <ChannelCode>'.$sms->vperiod.'</ChannelCode>
                                <UserName>'.$sms->username.'</UserName>
                                <PassWord>'.$sms->password.'</PassWord>
                                <Mesgbody>'.$messages.'</Mesgbody>
                                <Numbers>'.$working->mobile.'</Numbers>
                                <Originator>'.$sms->sender.'</Originator>
                                <Type>1</Type>
                        </MainmsgBody>';

//            $request = new \GuzzleHttp\Psr7\Request(
//                'POST',
//                'http://processor.smsorigin.com/xml/process.aspx',
//                ['Content-Type' => 'text/xml; charset=UTF8'],
//                $xml
//            );
//            dd($request);
            $client = new \GuzzleHttp\Client();
            $options = [
                'headers' => [
                    'Content-Type' => 'text/xml; charset=UTF8'
                ],
                'body' => $xml
            ];
            $response =  $client->request('POST','http://processor.smsorigin.com/xml/process.aspx',$options);

        }

        if ($company->sms_company == 'JETMESAJ')
        {


            $deger = Curl::to('https://ws.jetsms.com.tr/api/sendsms')
                ->withData([
                    "user"=>$sms->username,
                    "password"=>$sms->password,
                    "originator" => $sms->sender,
                    "reference" =>null,
                    "startdate"=>null,
                    "expiredate"=>null,
                    "exclusionstarttime"=>null,
                    "exclusionexpiretime"=>null,
                    "smsmessages" => [
                            [
                        "messagetext"=>$messages,
                        "receipent"=>$working->mobile,
                        "messageid"=> rand(0,9999999999999)
                            ]
                    ],
                    "channel"=>$sms->channel,
                    "blacklistfilter"=>null,
                    "iysfilter"=>null,
                    "iyscode"=>null,
                    "brandcode"=>null,
                    "retailercode"=>null,
                    "recipienttype"=>null,

//                    'state'=>'1',
//                    "totalcount"=>"7",
//                    "sequence"=>"1",
//                    "origpid"=>""
                ])

                ->asJson()
                ->post();
        }
    }
}

if (!function_exists('getAllEducationScopes')) {
    function getAllEducationScopes()
    {
        $departments = \App\Models\Department::where('company_id', auth()->user()->company_id)->pluck('name', 'id');
        $defaultSelection = ['0' => ' TÜM DEPARTMANLARA'];
        return $defaultSelection + $departments->toArray();

    }
}
if (! function_exists('snake_case')) {
    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    function snake_case($value, $delimiter = '_')
    {
        return Str::snake($value, $delimiter);
    }
}
if (!function_exists('random_password'))
{
    function random_password()
    {
//        $karakterler = "1234567890abcdefghijKLMNOPQRSTuvwxyzABCDEFGHIJklmnopqrstUVWXYZ0987654321";
//        $sifre = '';
//        for($i=0;$i<6;$i++)                    //Oluşturulacak şifrenin karakter sayısı 8'dir.
//        {
//            $sifre .= $karakterler{rand() % 72};    //$karakterler dizisinden ilk 72 karakter kullanılacak, yani hepsi.
//        }
//        return $sifre;
        $random_password = str_random(8);
       return  $random_password = bcrypt($random_password);

    }
}
if (! function_exists('education_analiz')) {

    function education_analiz($employee,$program_id = null)
    {
//        $educations_question = [];
//        $evalation_count = 1;
//        $ust = PerformanceProgramEvalation::where('evalation_id',$employee->id)
//            ->where('performance_program_id',$program_id)
//            ->where('type_id',2)
//            ->first();
//        $ustAidiyetToplamPuan = 0;
//        $ustTimeToplamPuan = 0;
//        $ustIletisimToplamPuan = 0;
//        if ($ust)
//        {
//            $evalation_count ++;
//            $question_forms = PerformanceForm::where('performance_program_evalation_id',$ust->id)->get();
//            foreach ($question_forms as $question_form)
//            {
//                $id =  $question_form->question->grup_type;
//                if ( $question_form->question->grup_type == 1)
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $ustAidiyetToplamPuan = 0;
//                    $ustAidiyetToplamPuan = array_sum($evalation_puan[$id]);
//                }
//                elseif ($question_form->question->grup_type == 2)
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $ustTimeToplamPuan = 0;
//                    $ustTimeToplamPuan = array_sum($evalation_puan[$id]);
//                }
//                else
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $ustIletisimToplamPuan = 0;
//                    $ustIletisimToplamPuan = array_sum($evalation_puan[$id]);
//                }
//
//            }
//        }
//
//
//        $ast = PerformanceProgramEvalation::where('evalation_id',$employee->id)
//            ->where('performance_program_id',$program_id)
//            ->where('type_id',1)
//            ->first();
//        $astAidiyetToplamPuan = 0;
//        $astIletisimToplamPuan = 0;
//        $astTimeToplamPuan = 0;
//
//        if ($ast)
//        {
//            $evalation_count ++;
//            $question_forms = PerformanceForm::where('performance_program_evalation_id',$ast->id)->get();
//            foreach ($question_forms as $question_form)
//            {
//
//
//                $id =  $question_form->question->grup_type;
//                if ( $question_form->question->grup_type == 1)
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $astAidiyetToplamPuan = 0;
//                    $astAidiyetToplamPuan = array_sum($evalation_puan[$id]);
//                }
//                elseif ($question_form->question->grup_type == 2)
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $astTimeToplamPuan = 0;
//                    $astTimeToplamPuan = array_sum($evalation_puan[$id]);
//                }
//                else
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $astIletisimToplamPuan = 0;
//                    $astIletisimToplamPuan = array_sum($evalation_puan[$id]);
//                }
//
//            }
//        }
//
//
//        $oz = PerformanceProgramEvalation::where('evalation_id',$employee->id)
//            ->where('performance_program_id',$program_id)
//            ->where('type_id',3)
//            ->first();
//
//        $ozAidiyetToplamPuan = 0;
//        $ozIletisimToplamPuan = 0;
//        $ozTimeToplamPuan = 0;
//
//        if ($oz)
//        {
//            $evalation_count ++;
//            $question_forms = PerformanceForm::where('performance_program_evalation_id',$oz->id)->get();
//            foreach ($question_forms as $question_form)
//            {
//
//                $id =  $question_form->question->grup_type;
//
//                if ( $question_form->question->grup_type == 1)
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $ozAidiyetToplamPuan = 0;
//                    $ozAidiyetToplamPuan = array_sum($evalation_puan[$id]);
//                }
//                elseif ($question_form->question->grup_type == 2)
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $ozTimeToplamPuan = 0;
//                    $ozTimeToplamPuan = array_sum($evalation_puan[$id]);
//                }
//                else
//                {
//                    $evalation_puan[$id][] = $question_form->puan;
//                    $ozIletisimToplamPuan = 0;
//                    $ozIletisimToplamPuan = array_sum($evalation_puan[$id]);
//                }
//
//            }
//        }
//        $genel_toplam_iletisim = $ozIletisimToplamPuan + $astIletisimToplamPuan + $ustIletisimToplamPuan;
//        $genel_toplam_aidiyet = $ozAidiyetToplamPuan + $astAidiyetToplamPuan + $ustAidiyetToplamPuan;
//        $genel_toplam_zaman = $ozTimeToplamPuan + $astAidiyetToplamPuan + $ustIletisimToplamPuan;
//
//        $ortalama_iletisim = ($genel_toplam_iletisim / $evalation_count);
//        $ortalama_Aidiyet = ($genel_toplam_aidiyet / $evalation_count);
//        $ortalama_Zaman = ($genel_toplam_zaman / $evalation_count);
//
//        $iletisim = 0;
//        $zaman = 0;
//        $aidiyet = 0;
//
//
//        if ($ustIletisimToplamPuan <= $ortalama_iletisim)
//        {
//            $iletisim ++;
//        }
//        if ($astIletisimToplamPuan <= $ortalama_iletisim)
//        {
//            $iletisim ++;
//        }
//        if ($ozIletisimToplamPuan <= $ortalama_iletisim)
//        {
//            $iletisim ++;
//        }
//        $a = ($evalation_count * 50)/100;
//
//        if ($iletisim >= $a )
//        {
//            $educations_question[] = array('sira' => 1,'type'=>'İLETİŞİM YÖNETİMİ','ortalama'=>$ortalama_iletisim,'sonuc'=>'İLETİŞİM Sorularında Ortalamanın Altında Kalmıştır');
//        }
//
//
//
//        if ($ustTimeToplamPuan <= $ortalama_Zaman)
//        {
//            $zaman ++;
//        }
//        if ($astTimeToplamPuan <= $ortalama_Zaman)
//        {
//            $zaman ++;
//        }
//        if ($ozTimeToplamPuan <= $ortalama_Zaman)
//        {
//            $zaman ++;
//        }
//        if ($zaman >= $a)
//        {
//            $educations_question[] = array('sira' =>2, 'type'=>'ZAMAN YÖNETİMİ','ortalama'=>$ortalama_Zaman,'sonuc'=>'ZAMAN Sorularında Ortalamanın Altında Kalmıştır');
//        }
//
//        if ($ustAidiyetToplamPuan <= $ortalama_Aidiyet)
//        {
//            $aidiyet ++;
//        }
//        if ($astAidiyetToplamPuan <= $ortalama_Aidiyet)
//        {
//            $aidiyet ++;
//        }
//        if ($ozAidiyetToplamPuan <= $ortalama_Aidiyet)
//        {
//            $aidiyet ++;
//        }
//
//        if ($zaman >= $a)
//        {
//            $educations_question[] = array('sira' => 3, 'type'=>'AİDİYET VE GÖREV BİLİNCİ','have_puan'=>$genel_toplam_aidiyet,'ortalama'=>$ortalama_Aidiyet,'sonuc'=>'AİDİYET VE GÖREV BİLİNCİ Sorularında Ortalamanın Altında Kalmıştır');
//        }
//
//        return $educations_question;


                $educations_question = [];
        $evalation_count = 1;
        $ust = PerformanceProgramEvalation::where('evalation_id',$employee->id)
            ->where('performance_program_id',$program_id)
            ->where('type_id',2)
            ->first();
        $toplamPuans['evalation_count'] = 0;
        $toplamPuans['evalation_type_sum'] = [];
//        $ustAidiyetToplamPuan = 0;
//        $ustTimeToplamPuan = 0;
//        $ustIletisimToplamPuan = 0;
        if ($ust)
        {
            $toplamPuans['evalation_count'] = $toplamPuans['evalation_count'] + 1;  //$evalation_count ++;
            $question_forms = PerformanceForm::where('performance_program_evalation_id',$ust->id)->get();
            foreach ($question_forms as $question_form)
            {
                if(isset($question_form->question))
                {
                    $id =  $question_form->question->grup_type;
                    if (isset($toplamPuans['evalation_type_sum']['ust'][$id]))
                    {
                        $toplamPuans['evalation_type_sum']['ust'][$id] = $toplamPuans['evalation_type_sum']['ust'][$id] + $question_form->puan;
                    }
                    else
                    {
                        $toplamPuans['evalation_type_sum']['ust'][$id] = $question_form->puan;
                    }
                }

                //  $evalation_puan[$id][] = $question_form->puan;
                // $ustAidiyetToplamPuan = 0;
                // $ustAidiyetToplamPuan = array_sum($evalation_puan[$id]);
            }
        }


        $ast = PerformanceProgramEvalation::where('evalation_id',$employee->id)
            ->where('performance_program_id',$program_id)
            ->where('type_id',1)
            ->first();
//        $astAidiyetToplamPuan = 0;
//        $astIletisimToplamPuan = 0;
//        $astTimeToplamPuan = 0;

        if ($ast)
        {
            $toplamPuans['evalation_count'] = $toplamPuans['evalation_count'] + 1;;
            $question_forms = PerformanceForm::where('performance_program_evalation_id',$ast->id)->get();
            foreach ($question_forms as $question_form)
            {
                if ($question_form->question)
                {
                    $id =  $question_form->question->grup_type;
                    if (isset($toplamPuans['evalation_type_sum']['ast'][$id]))
                    {
                        $toplamPuans['evalation_type_sum']['ast'][$id] = $toplamPuans['evalation_type_sum']['ast'][$id] + $question_form->puan;
                    }
                    else
                    {
                        $toplamPuans['evalation_type_sum']['ast'][$id] = $question_form->puan;
                    }
                }

            }
        }


        $oz = PerformanceProgramEvalation::where('evalation_id',$employee->id)
            ->where('performance_program_id',$program_id)
            ->where('type_id',3)
            ->first();

//        $ozAidiyetToplamPuan = 0;
//        $ozIletisimToplamPuan = 0;
//        $ozTimeToplamPuan = 0;

        if ($oz)
        {
            $toplamPuans['evalation_count'] = $toplamPuans['evalation_count'] + 1;
            $question_forms = PerformanceForm::where('performance_program_evalation_id',$oz->id)->get();
            foreach ($question_forms as $question_form)
            {

               if ($question_form->question)
               {
                   $id =  $question_form->question->grup_type;
                   if (isset($toplamPuans['evalation_type_sum']['oz'][$id]))
                   {
                       $toplamPuans['evalation_type_sum']['oz'][$id] = $toplamPuans['evalation_type_sum']['oz'][$id] + $question_form->puan;
                   }
                   else
                   {
                       $toplamPuans['evalation_type_sum']['oz'][$id] = $question_form->puan;
                   }
               }

            }
        }
        $question_type_sum_puan = [];
        foreach ($toplamPuans['evalation_type_sum'] as $key => $puans)
        {
            foreach ($puans as $id => $puan)
            {
                if (isset($question_type_sum_puan[$id]))
                {
                    $question_type_sum_puan[$id] = $question_type_sum_puan[$id] + $puan;
                }
                else
                {
                    $question_type_sum_puan[$id] = $puan;
                }
            }
        }
        $ortalamaPuan = [];
        if ( $toplamPuans['evalation_count'] == 0)
        {
            $toplamPuans['evalation_count'] = 1;
        }
        foreach ($question_type_sum_puan as $id => $sumPuan)
        {
            if (isset($ortalamaPuan[$id]))
            {
                $ortalamaPuan[$id] =   $sumPuan / $toplamPuans['evalation_count'];
            }
            else
            {
                $ortalamaPuan[$id] = $sumPuan / $toplamPuans['evalation_count'];
            }
        }


//        $genel_toplam_iletisim = $ozIletisimToplamPuan + $astIletisimToplamPuan + $ustIletisimToplamPuan;
//        $genel_toplam_aidiyet = $ozAidiyetToplamPuan + $astAidiyetToplamPuan + $ustAidiyetToplamPuan;
//        $genel_toplam_zaman = $ozTimeToplamPuan + $astAidiyetToplamPuan + $ustIletisimToplamPuan;
//
//        $ortalama_iletisim = ($genel_toplam_iletisim / $evalation_count);
//        $ortalama_Aidiyet = ($genel_toplam_aidiyet / $evalation_count);
//        $ortalama_Zaman = ($genel_toplam_zaman / $evalation_count);




        foreach ($toplamPuans['evalation_type_sum'] as $key => $toplams)
        {
            $count = 0;
            foreach ($toplams as $id => $puan)
            {

                if ($puan <= $ortalamaPuan[$id])
                {
                    $count = $count + 1;
                }
            }
            $a = ($toplamPuans['evalation_count'] * 50)/100;
            if ($count >= $a)
            {
                $educations_question[] = array('sira' => 1,'type'=>config('variables.question_grub_type')[$id],'ortalama'=>$ortalamaPuan[$id],'sonuc'=>config('variables.question_grub_type')[$id].'Sorularında Ortalamanın Altında Kalmıştır');

            }
        }

//        $iletisim = 0;
//        $zaman = 0;
//        $aidiyet = 0;
//
//
//        if ($ustIletisimToplamPuan <= $ortalama_iletisim)
//        {
//            $iletisim ++;
//        }
//        if ($astIletisimToplamPuan <= $ortalama_iletisim)
//        {
//            $iletisim ++;
//        }
//        if ($ozIletisimToplamPuan <= $ortalama_iletisim)
//        {
//            $iletisim ++;
//        }
//        $a = ($evalation_count * 50)/100;
//
//        if ($iletisim >= $a )
//        {
//            $educations_question[] = array('sira' => 1,'type'=>'İLETİŞİM YÖNETİMİ','ortalama'=>$ortalama_iletisim,'sonuc'=>'İLETİŞİM Sorularında Ortalamanın Altında Kalmıştır');
//        }
//
//
//
//        if ($ustTimeToplamPuan <= $ortalama_Zaman)
//        {
//            $zaman ++;
//        }
//        if ($astTimeToplamPuan <= $ortalama_Zaman)
//        {
//            $zaman ++;
//        }
//        if ($ozTimeToplamPuan <= $ortalama_Zaman)
//        {
//            $zaman ++;
//        }
//        if ($zaman >= $a)
//        {
//            $educations_question[] = array('sira' =>2, 'type'=>'ZAMAN YÖNETİMİ','ortalama'=>$ortalama_Zaman,'sonuc'=>'ZAMAN Sorularında Ortalamanın Altında Kalmıştır');
//        }
//
//        if ($ustAidiyetToplamPuan <= $ortalama_Aidiyet)
//        {
//            $aidiyet ++;
//        }
//        if ($astAidiyetToplamPuan <= $ortalama_Aidiyet)
//        {
//            $aidiyet ++;
//        }
//        if ($ozAidiyetToplamPuan <= $ortalama_Aidiyet)
//        {
//            $aidiyet ++;
//        }
//
//        if ($zaman >= $a)
//        {
//            $educations_question[] = array('sira' => 3, 'type'=>'AİDİYET VE GÖREV BİLİNCİ','have_puan'=>$genel_toplam_aidiyet,'ortalama'=>$ortalama_Aidiyet,'sonuc'=>'AİDİYET VE GÖREV BİLİNCİ Sorularında Ortalamanın Altında Kalmıştır');
//        }

        if (count($educations_question) == 0)
        {
            $educations_question[] = array('sira' => 1,'type'=>'Tüm Sorular','ortalama'=>100,'sonuc'=>'Personel Tüm Sorularda Ortalamanın Üstünde Kalmıştır');

        }

        return $educations_question;
    }



}


if (! function_exists('sidebar_performance')) {

        function sidebar_performance()
        {
            $performances = PerformanceProgram::where('company_id', Auth::user()->company_id)->where('status', '1')->get();

            if (!empty($performances)) {
                foreach ($performances as $performance) {
                    $control = PerformanceApplicant::where('employee_id', Auth::user()->employee_id)->where('performance_program_id', $performance->id)->first();

                    if ($control) {
                        $id = $performance->id;
                    }
                }
                if (isset($id)) {
                    $applicants = PerformanceApplicant::where('performance_program_id', $id)->get()->pluck('employee_id')->toArray();
                    $performance_types = PerformanceProgramType::where('performance_program_id', $id)->orderBy('performance_type_id', 'ASC')->get();

                } else {
                    $performance_types = [];
                }
            } else {
                $performance_types = [];
            }

            foreach ($performance_types as $type) {
                $deger = $type->performance_type_puan(Auth::user()->employee_id, $type->performance_program_id, $type->performance_type_id);
                if ((float)$deger) {
                    $a = str_replace(',', '.', $deger);

                    $toplam[] = $a;
                }

            }

            if (isset($toplam)) {
                if (count($toplam) > 0) {

                    session(['toplam_puan' => array_sum($toplam)]);
                }

                session(['performance_types' => $performance_types]);

            }
        }

    }



if (!function_exists('getRemainingTime')) {
    function getRemainingTime($before_date)
    {
        \Carbon\Carbon::setLocale('tr');
        $bitis = new \Carbon\Carbon($before_date);
        $suan = \Carbon\Carbon::now(config('app.tz'));
        $fark = date_diff($suan, $bitis);
        $text = "";
        if ($fark->y > 0)
            $text = $fark->y.' yıl ';
        if ($fark->m > 0)
            $text .= $fark->m.' ay ';
        if ($fark->d > 0)
            $text .= $fark->d.' gün ';
        return $text;
    }
}

if (! function_exists('str_random')) {
    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int  $length
     * @return string
     *
     * @throws \RuntimeException
     */
    function str_random($length = 16)
    {
        return Str::random($length);
    }
}

if (! function_exists('str_slug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @param  string  $language
     * @return string
     */
    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }
}
if (! function_exists('camel_case')) {
    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    function camel_case($value)
    {
        return Str::camel($value);
    }
}
if (! function_exists('str_plural')) {
    /**
     * Get the plural form of an English word.
     *
     * @param  string  $value
     * @param  int     $count
     * @return string
     */
    function str_plural($value, $count = 2)
    {
        return Str::plural($value, $count);
    }
}


if (! function_exists('str_singular')) {
    /**
     * Get the singular form of an English word.
     *
     * @param  string  $value
     * @return string
     */
    function str_singular($value)
    {
        return Str::singular($value);
    }
}
if (! function_exists('ezey_get_dateformat')) {
    function ezey_get_dateformat($str, $type, $only = 'tarih_saat')
    {
        if (is_null($str))
            return egt_field_isnull($str);
        if (empty($str))
            return "";
        if ($type == "toThem") {
            //14/01/2006 gelecek
            //2006-01-14 dönecek
            $str = trim($str);
            $str_dizi = explode("/", $str);
            if (count($str_dizi) != 3) {
                return null;
            }
            $str_gun = $str_dizi[0];//0-gun;1-ay;2-yil
            $str_ay = $str_dizi[1];
            $str_yil = $str_dizi[2];
            $str = $str_yil . "-" . $str_ay . "-" . $str_gun;
            return $str;
        }
        if ($type == "toOur") {
            //2006-01-14 gelecek
            //14-01-2006 dönecek

            if (strstr($str, ' ')) {
                $str = explode(' ', $str);
                $str = trim($str[0]);
            } else {
                $str = trim($str);
            }

            $str_dizi = explode("-", $str);
            if (count($str_dizi) != 3) {
                return null;
            }
            $str_yil = $str_dizi[0];//0-yil;1-ay;2-gun
            $str_ay = $str_dizi[1];
            $str_gun = $str_dizi[2];
            $str = $str_gun . "/" . $str_ay . "/" . $str_yil;
            return $str;
        }
        if ($type == "LoginTime") {
            //2006-01-14 12:45:48 gelecek
            //14-01-2006 12:45:48 dönecek


            $str = trim($str);
            $str_dizi = explode(" ", $str);
            $str_tarih = ezey_get_dateformat($str_dizi[0], "toOur");//0-tarihl;1-saat;
            $str_saat = $str_dizi[1];
            if ($only == "tarih_saat")
                $str = $str_tarih . " " . $str_saat;
            else
                $str = $str_tarih;
            return $str;
        }
    }
}

if (!function_exists('getSgkCompany')) {
    function getSgkCompany()
    {
        if (!session()->has('selectedCompany')) {
            return false;
        }
        $selectedCompanyID = session()->get('selectedCompany')['id'];
        $selectedCompany = \App\Models\SgkCompany::find($selectedCompanyID);
        if (!$selectedCompany) {
            return false;
        }
        return $selectedCompany;
    }
}
if (!function_exists('back_law_win'))
{
    function back_law_win($approvide,$tck)
    {
        if (!empty($approvide->id) and !empty($tck)) {
            if ($approvide->law_no == 6111) {
                if (!empty($approvide->id) and !empty($tck)) {
                    $deger = \App\Models\LeakIncentiveService::where('tck', $tck)
                        ->where('approved_incentive_id', $approvide->id)->first();
                   $a = $deger->ucret_tl + $deger->ikramiye_tl;
                   $back_win = ($a * 15.5)/100;

                    return $back_win;
                } else {
                    return $back_win = 0;
                }
            } elseif ($approvide->law_no == 27103) {
                $deger = \App\Models\LeakIncentiveService::where('tck', $tck)
                    ->where('approved_incentive_id', $approvide->id)->first();
                $a = $deger->ucret_tl + $deger->ikramiye_tl;
                $back_win = ($a * 37.5)/100;

                if ($back_win > 1341.56) {
                    return $back_win = 1341.56;
                } else {
                    return $back_win;
                }
            } elseif ($approvide->law_no == 17103) {

                $deger = \App\Models\LeakIncentiveService::where('tck', $tck)
                    ->where('approved_incentive_id', $approvide->id)->first();
                $a = $deger->ucret_tl + $deger->ikramiye_tl;
                $back_win = ($a * 37.5)/100;
                if ($back_win > 3577.50) {
                    return $back_win = 3577.50;
                } else {
                    return $back_win;
                }
            } elseif ($approvide->law_no == 17103) {
                if (!empty($approvide->id) and !empty($tck)) {
                    $deger = \App\Models\LeakIncentiveService::where('tck', $tck)
                        ->where('approved_incentive_id', $approvide->id)->first();
                    $back_win = $deger->gun * 44.72;

                    return $back_win;
                } else {
                    return $back_win = 0;
                }
            }
        }
        else
        {
            return $back_win = 0;
        }
    }

}
if (!function_exists('Account27103'))
{
    function Account27103($approvide,$tck)
    {
        if (!empty($approvide->id) and !empty($tck))
        {
            $deger =  \App\Models\LeakIncentiveService::where('tck',$tck)
                ->where('approved_incentive_id',$approvide->id)->first();
            $a = $deger->ucret_tl + $deger->ikramiye_tl;
            $hesap = ($a * 37.5)/100;
            if ($hesap > 1341.56)
            {
                return $hesap = 1341.56;
            }
            else
            {
                return $hesap;
            }
        }
        else
            {
                return $hesap = 0;
            }

    }
}

if (!function_exists('Account17103'))
{
    function Account17103($approvide,$tck)
    {
        if (!empty($approvide->id) and !empty($tck))
        {
            $deger =  \App\Models\LeakIncentiveService::where('tck',$tck)
                ->where('approved_incentive_id',$approvide->id)->first();
            $a = $deger->ucret_tl + $deger->ikramiye_tl;
            $hesap = ($a * 37.5)/100;
            if ($hesap > 3577.50)
            {
                return $hesap = 3577.50;
            }
            else
            {
                return $hesap;
            }
        }
        else
        {
            return $hesap = 0;
        }

    }
}

if (!function_exists('Account6111'))
{
    function Account6111($approvide,$tck)
    {
        if (!empty($approvide->id) and !empty($tck)) {
            $deger = \App\Models\LeakIncentiveService::where('tck', $tck)
                ->where('approved_incentive_id', $approvide->id)->first();
            $a = $deger->ucret_tl + $deger->ikramiye_tl;
            $hesap = ($a * 15.5)/100;

            return $hesap;
        }
        else
            {
                return $hesap = 0;
            }
    }
}

if (!function_exists('Account7252'))
{
    function Account7252($approvide,$tck)
    {
        if (!empty($approvide->id) and !empty($tck)) {
            $deger = \App\Models\LeakIncentiveService::where('tck', $tck)
                ->where('approved_incentive_id', $approvide->id)->first();
            $hesap = $deger->gun  * 44.72;

            return $hesap;
        }
        else
        {
            return $hesap = 0;
        }
    }
}

if (!function_exists('decimalFormatMysql')) {
    function decimalFormatMysql($string)
    {
        if (strlen($string) == 0)
            return 0;
        if ($string === '')
            return 0;
        $string = trim(str_replace("TL", "", $string));
        $delimiters = ['.', ','];
        $format = ".";
        $return = "";
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        for ($i = 0; $i <= (count($launch) - 2); $i++) {
            $return .= trim($launch[$i]);
        }
        $return .= $format . trim($launch[count($launch) - 1]);
        return round($return, 2);
    }
}


if (!function_exists('getFullMonthName')) {
    function getFullMonthName($month_id)
    {

        //'Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağs', 'Eyl', 'Ekm', 'Kas', 'Ara'
        if ($month_id == 1 || $month_id == '01' || $month_id == 'January' || $month_id == 'Jan') {
            return 'Ocak';
        } else if ($month_id == '2' || $month_id == '02' || $month_id == 'February' || $month_id == 'Feb') {
            return 'Şubat';
        } else if ($month_id == '3' || $month_id == '03' || $month_id == 'March' || $month_id == 'Mar') {
            return 'Mart';
        } else if ($month_id == '4' || $month_id == '04' || $month_id == 'April' || $month_id == 'Apr') {
            return 'Nisan';
        } else if ($month_id == '5' || $month_id == '05' || $month_id == 'May' || $month_id == 'May') {
            return 'Mayıs';
        } else if ($month_id == '6' || $month_id == '06' || $month_id == 'June' || $month_id == 'Jun') {
            return 'Haziran';
        } else if ($month_id == '7' || $month_id == '07' || $month_id == 'July' || $month_id == 'Jul') {
            return 'Temmuz';
        } else if ($month_id == '8' || $month_id == '08' || $month_id == 'August' || $month_id == 'Aug') {
            return 'Ağustos';
        } else if ($month_id == '9' || $month_id == '09' || $month_id == 'September' || $month_id == 'Sep') {
            return 'Eylül';
        } else if ($month_id == '10' || $month_id == '10' || $month_id == 'October' || $month_id == 'Oct') {
            return 'Ekim';
        } else if ($month_id == '11' || $month_id == '11' || $month_id == 'November' || $month_id == 'Nov') {
            return 'Kasım';
        } else if ($month_id == '12' || $month_id == '12' || $month_id == 'December' || $month_id == 'Dec') {
            return 'Aralık';
        } else {
            return '';
        }
    }
}

function pdf_sayfa_no ($pdf)
{
    if ( isset($pdf) ) {
        $size = 9;
        $y = $pdf->get_height() - 17;
        $x = $pdf->get_width() - 70;
        $pdf->page_text($x, $y, 'Sayfa:{PAGE_NUM}/{PAGE_COUNT}', '', $size);
    }
}

function remove_last_string($text,$string){
    if (substr($text, -1) == $string) {
        $text = substr($text, 0, strlen($text)-1);
    }
    return $text;
}


function clearText($str)
{
    $table = MB_CONVERT_ENCODING($str, "UTF-8", "ISO-8859-9");
    $table = str_replace("ý", "ı", $table);
    $table = str_replace("\r", "", $table);
    $table = str_replace("\n", "", $table);
    $table = str_replace("    ", "", $table);
    return $table;
}


if (!function_exists('getMetricGroups')) {
    function getMetricGroups()
    {
        return \App\Models\MetricReportGroup::orderBy('sort', 'ASC')->get();
    }
}



if (!function_exists('metricFlowOk')) {
    function metricFlowOk($type)
    {
        //metrikflow__step--active
        $company_id = Auth::user()->company_id;
        $sgk_companies = \App\Models\SgkCompany::where('company_id', $company_id)->get();
        $sgk_company_ids = \App\Models\SgkCompany::where('company_id', $company_id)->pluck('id');


        $sgk_ok = count($sgk_companies) > 0 ? true : false;
        $select_ok = getSgkCompany() ? true : false;
        $assign_ok = false;
        $constant_ok = false;
        $definition_ok = false;
        $total = 0;


        if ($sgk_ok) {
            $total = 1;
        }


        if ($sgk_ok) {
            $selected_sgk_company = getSgkCompany();
            if ($selected_sgk_company) {
                $has_assign = \App\Models\CompanyAssignment::where('sgk_company_id', $selected_sgk_company->id)->first();
                if ($has_assign) {
                    $assign_ok = true;
                    $total = 2;
                }
            } else {
                $has_assign = \App\Models\CompanyAssignment::where('user_id', Auth::user()->id)->first();
                if ($has_assign) {
                    $assign_ok = true;
                    $total = 2;
                }
            }

        }

        if ($sgk_ok && $select_ok && $assign_ok) {
            $total = 3;
        }








        if ($sgk_ok && $select_ok && $assign_ok) {

            $constants = \App\Models\MetrikConstant::where('sgk_company_id', $selected_sgk_company->id)->groupBy('type')->get();
            if (count($constants) == 4) {
                $constant_ok = true;
                $total = 4;
            }
        }

        if ($sgk_ok && $select_ok && $assign_ok && $constant_ok) {
            $selected_sgk_company = getSgkCompany();
            $first = Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d');
            $second =  Carbon::now()->startOfMonth()->subMonth(2)->format('Y-m-d');
            $date_array = array($first, $second);
            $has_def = \App\Models\ApprovedIncentive::where('sgk_company_id', $selected_sgk_company->id)->whereIn('accrual', $date_array)->first();
            if ($has_def) {
                $definition_ok = true;
                $total = 5;
            }
        }

        if ($total >= $type) {
            return 'metrikflow__step--active';
        } else {
            return '';
        }

    }
}



function varmi($tck, $gelenler) {
    $dizi = array('6111', '27103', '17103', '7103', '14857');
    foreach($dizi as $key => $kanun) {
        if (array_key_exists($tck, $gelenler[$kanun])) {
            return true;
        }
    }

}
?>
