

                <div class="d-flex flex-column-fluid">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        <div class="card card-custom card-transparent">
                            <div class=" p-0">
                                <!--begin: Wizard-->
                                <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="true">
                                    <!--begin: Wizard Nav-->
                                    <div class="wizard-nav">
                                        <div class="wizard-steps" style="justify-content: center">
                                            <!--begin::Wizard Step 1 Nav-->
                                            <div class="wizard-step"  style="/*flex: auto;width: -webkit-fill-available" data-wizard-type="step" data-wizard-state="current">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-number">1</div>
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">Ayarlar</div>
                                                        <div class="wizard-desc">performans program ayarları</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Wizard Step 1 Nav-->
                                            <!--begin::Wizard Step 2 Nav-->
                                            <div class="wizard-step" data-wizard-type="step">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-number">2</div>
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">Program Tarihleri</div>
                                                        <div class="wizard-desc">Performans program tarihleri</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Wizard Step 2 Nav-->
                                            <!--begin::Wizard Step 3 Nav-->
                                            <div class="wizard-step" data-wizard-type="step">
                                                <div class="wizard-wrapper">
                                                    <div class="wizard-number">3</div>
                                                    <div class="wizard-label">
                                                        <div class="wizard-title">Kişiler</div>
                                                        <div class="wizard-desc">Programa Katılacak Kişiler</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Wizard Step 3 Nav-->
                                            <!--wizard step 5 Nav -->
                                          <!-- wizard step finish -->
                                        </div>
                                    </div>
                                    <!--end: Wizard Nav-->
                                    <!--begin: Wizard Body-->
                                    <div class="card card-custom card-shadowless rounded-top-0">
                                        <div class="card-body p-0">
                                            <div class=" justify-content-center py-8 px-8 py-lg-15 px-lg-10">

                                                        <!--end: Wizard Actions-->
                                                <div class="">
                                                    <!--begin: Wizard Form-->
                                                    <!--begin: Wizard Step 1-->
                                                    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">

                                                        <!--begin::Input-->
                                                        <!--end::Input-->
                                                        <!--begin::Input-->
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="mb-10" style="font-weight: bold;font-size: 15px;margin-left: 0px;">Değerlendirme Dönem Aralığı</label>
                                                                    <div class="radio-inline">
                                                                        @if(isset($performance))
                                                                        <label class="radio radio-lg radio-primary" style="font-weight: bold;font-size: 15px">
                                                                            {!! Form::radio('period','3', $performance->period == 3 ? true : false ,['class'=>'form-control period']) !!}
                                                                            <span></span>3.Ay</label>
                                                                        <label class="radio radio-lg radio-primary" style="font-weight: bold;font-size: 15px">
                                                                            {!! Form::radio('period','6', $performance->period == 6 ? true : false ,['class'=>'form-control period']) !!}
                                                                            <span></span>6.Ay</label>

                                                                        <label class="radio radio-lg radio-primary" style="font-weight: bold;font-size: 15px">
                                                                            {!! Form::radio('period','12', $performance->period == 12 ? true : false ,['class'=>'form-control period']) !!}
                                                                            <span></span>12.Ay</label>
                                                                        @else
                                                                            <label class="radio radio-lg radio-primary" style="font-weight: bold;font-size: 15px">
                                                                                {!! Form::radio('period','3' ,false ,['class'=>'form-control']) !!}
                                                                                <span></span>3.Ay</label>
                                                                            <label class="radio radio-lg radio-primary" style="font-weight: bold;font-size: 15px">
                                                                                {!! Form::radio('period','6',false,['class'=>'form-control']) !!}
                                                                                <span></span>6.Ay</label>

                                                                            <label class="radio radio-lg radio-primary" style="font-weight: bold;font-size: 15px">
                                                                                {!! Form::radio('period','12',false, ['class'=>'form-control']) !!}
                                                                                <span></span>12.Ay</label>
                                                                            @endif
                                                                    </div>

                                                                </div>
                                                                <span>Zorunlu Alan</span>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="mb-10" style="font-weight: bold;font-size: 15px;margin-left: 0px;">Değerlendirme Türü</label>
                                                                    <br>
                                                                    <div class="row">
                                                                        @foreach($performance_types as $key => $type )
                                                                            <div class="col-6">
                                                                        <label class="checkbox checkbox-rounded mb-4 mr-5" style="font-weight: bold;font-size: 15px" >
                                                                            <input type="checkbox" <?php if (isset($selectedProgramType)) { if (in_array($type->id,$selectedProgramType)) { echo 'checked'; } } ?> name="competence[]" data-name="{{$type->name}}" class="form-control performance_type" id="{{$type->slug_en}}">
                                                                            <span></span>{{$type->name}}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div id="performance_type">
                                                            <div class="form-group mb-8">
                                                                <div class="alert alert-custom alert-default" role="alert">
                                                                    <div class="alert-icon">
																<span class="svg-icon svg-icon-primary svg-icon-xl">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
																			<path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-8">
                                                                            <label for=""></label>
                                                                            <div style="font-weight: bold;font-size: 15px" class="alert-text">Aşşağıda Gireceğiniz Performans Değerlendirme Kriter Puanları Toplamı 100 Olmalıdır Aksi Taktirde Program Başlatılamaz!
                                                                                </div></div>
                                                                        <div class="col-lg-4">
                                                                            <div class="form-group">
                                                                                <label style="font-size: 15px;font-weight: bold">Toplam Değerlendirme Puanı</label>
                                                                                <input class="form-control" type="text" id="form_total" value="{{isset($toplam_puan) ? $toplam_puan : 0}}">
                                                                            </div>
                                                                            </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <br>


                                                                    <table id='type_' class='table'>
                                                                        <thead>
                                                                        <th></th>
                                                                        <th></th>
                                                                        </thead>
                                                                        <tbody>
                                                                        @if(isset($selectedProgramType))
                                                                        @foreach($selectedProgramTypes as $value)

                                                                        <tr id="type_{{$value->performance_type4()}}">
                                                                            <td style="text-align: -webkit-center">
                                                                                <div class='row' ><div class='col-lg-6'><div class='form-group'><label class='' style='font-weight: bold'>{{$value->performance_type()}}</label></div></div>
                                                                            </td>

                                                                            <td style="text-align: -webkit-center">
                                                                                <div class='col-lg-6'><div class='form-group'><label style='font-weight: bold'>Ağırlık Değerini Giriniz(100 üzerinden ağırlık puanı giriniz)</label><input class='form-control type_puans' value="{{$value->puan}}" type='text' name='performance_type[{{$value->performance_type_id}}]'></div></div>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                    </table>


                                                        </div>
                                                        <hr>
                                                        <div class="mb-5 font-weight-bold text-dark"><label style="font-weight: bold">Program Biilgileri</label></div>
                                                        <div class="row">
                                                            <div class="col-lg-2"></div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group" style="text-align: center">
                                                                    <label style="font-weight:bold;font-size: 15px; ">Program İsmi Giriniz</label>
                                                                    <span class="form-text text-muted">Örnek:Deneme Programı</span>
                                                                </div>

                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    {!! Form::text('program_name',isset($performance) ? $performance->name : null,['class'=>'form-control program_name','required'=>'required']) !!}
                                                                    <span class="form-text text-muted">Program Adını Giriniz</span>

                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>
                                                </div>

                                                <!--end: Wizard Step 1-->
                                                <!--begin: Wizard Step 2-->
                                                <div class="pb-5" data-wizard-type="step-content">
                                                    <div class="container-fluid mb-10">
                                                        <div class="card card-custom card-transparent">
                                                            <div class="card-body">
                                                                <div class="mb-10  text-dark" style="font-size: 15px;font-weight:bold">Program Tarihlerini Ayarlayınız</div>
                                                                <!--begin::Input-->
                                                                <div class="row">
                                                                    <div class="col-xl-4">
                                                                        <!--begin::Stats Widget 1-->
                                                                        <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(/assets/media/svg/shapes/abstract-4.svg)">
                                                                            <!--begin::Body-->
                                                                            <div class="card-body">
                                                                                <a href="#" class="card-title font-weight-bold  font-size-h3">Program Başlangıç Tarihi</a>
                                                                                <div class="font-weight-bold text-success mt-9 mb-5" id="start_performans" style="font-size: 17px;font-weight: bold"></div>
                                                                                <p class="text-dark-75 font-weight-bolder font-size-h5 m-0">{{isset($performance) ? $performance->start_date->format('d/m/Y') : null}} Tarihinde Başlatılıcaktır.</p>
                                                                            </div>
                                                                            <!--end::Body-->
                                                                        </div>
                                                                        <!--end::Stats Widget 1-->
                                                                    </div>
                                                                    <div class="col-xl-4">
                                                                        <!--begin::Stats Widget 2-->
                                                                        <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(/assets/media/svg/shapes/abstract-2.svg)">
                                                                            <!--begin::Body-->
                                                                            <div class="card-body">
                                                                                <a href="#" class="card-title font-weight-bold  font-size-h3">Program Bitiş Tarihi</a>
                                                                                <div class="font-weight-bold text-success mt-9 mb-5" id="finish_performans" style="font-size: 17px;font-weight: bold"></div>
                                                                                <p class="text-dark-75 font-weight-bolder font-size-h5 m-0"> {{isset($performance) ? $performance->finish_date->format('d/m/Y') : null}} Tarihinde Programınız Sonlanacaktır.</p>
                                                                            </div>
                                                                            <!--end::Body-->
                                                                        </div>
                                                                        <!--end::Stats Widget 2-->
                                                                    </div>
                                                                    <div class="col-xl-4">
                                                                        <!--begin::Stats Widget 3-->
                                                                        <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(/assets/media/svg/shapes/abstract-1.svg)">
                                                                            <!--begin::body-->
                                                                            <div class="card-body">
                                                                                <a href="#" class="card-title font-weight-bold  font-size-h3">Durum</a>
                                                                                <div class="font-weight-bold text-black mt-9 mb-5"><label style="font-weight: bold; font-size: 15px" id="ay_performans"></label><label>   </label><label style="font-weight: bold"> {{isset($performance) ? $performance->period : null }}
                                                                                        <label></label> Ay Sürecek Olan Programınız </label></div>
                                                                            </div>
                                                                            <!--end::Body-->
                                                                        </div>
                                                                        <!--end::Stats Widget 3-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-8">
                                                        <div class="alert alert-custom alert-default" role="alert">
                                                            <div class="alert-icon">
																<span class="svg-icon svg-icon-primary svg-icon-xl">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
																			<path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
                                                            </div>

                                                                    <div style="font-weight: bold;font-size: 15px" class="alert-text">Program Başlangıç Ve Bitiş Tarihlerini Değiştirmek İsterseniz Yeni Tarihleri Girebilirsiniz. <p>Aksi Taktirde Boş Bırakınız !</p>
                                                                    </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <!--end::Input-->
                                                    <div class="container-fluid mb-10">
                                                        <div class="card card-custom card-transparent">
                                                            <div class="card-body">
                                                                <div class="mb-10  text-dark" style="font-size: 15px;font-weight:bold">Program Başlangıç Tarihlerini Değiştirin</div>
                                                                <!--begin::Input-->
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="row">
                                                                                <div class="col-lg-6 mb-3">
                                                                                    {!! Form::date('program_start',isset($performance) ? $performance->start_date : null,['class'=>'form-control','id'=>'yetkinlik_start']) !!}
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    {!! Form::date('program_finish',isset($performance) ? $performance->finish_date : null,['class'=>'form-control','id'=>'yetkinlik_finish']) !!}
                                                                                </div>
                                                                            </div>
                                                                            <span class="form-text text-muted">Program Başlangıç Ve Bitiş Tarihlerini Değiştirmek İçin Doldurulabilir.</span>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="container-fluid mt-5">
                                                        <div class="form-group mb-8">
                                                            <div class="alert alert-custom alert-default" role="alert">
                                                                <div class="alert-icon">
																<span class="svg-icon svg-icon-primary svg-icon-xl">
																	<!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
																			<path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
                                                                </div>

                                                                <div style="font-weight: bold;font-size: 15px" class="alert-text">Program Değerlendirme Süresi Belirlemeniz Gerekmektedir. Çalışanlar Değerlendirmelerini Ne Kadar Süreyle Yapacaklar
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card card-custom card-transparent">

                                                            <div class="card-body">
                                                                <div class="mb-10  text-dark" style="font-size: 15px;font-weight:bold">Program Değerlendirme Süresi</div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">

                                                                                    {!! Form::select('evalation_time', ['1'=>'1-Ay','2'=>'2-Ay','3'=>'3-Ay'],isset($performance) ? $performance->evalation_time : null,['class'=>'form-control selectpicker','id'=>'evalation_time','required']) !!}
                                                                                </div>
                                                                            </div>
                                                                            <span class="form-text text-muted">Program Değerlendirme Süresi.</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end: Wizard Step 2-->
                                                <!--begin: Wizard Step 3-->
                                                <div class="pb-5" data-wizard-type="step-content">
                                                    <div class=" gutter-b card-stretch ">
                                                                <div class="card-toolbar mb-auto">
                                                                    <div class="row">
                                                                        <div class="col-lg-1"></div>
                                                                        <div class="col-lg-5">
                                                                            <div class="form-group">
                                                                                {!! Form::select('sgk_company_id', $sgk_companies ,null,['class'=>'form-control selectpicker','id'=>'sgk_company_filter','data-live-search'=>'true']) !!}
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-5">
                                                                            <div class="form-group">
                                                                                {!! Form::select('employee_work_type', config('variables.employees.work_type') ,null,['class'=>'form-control selectpicker','id'=>'employee_work_type','data-live-search'=>'true']) !!}
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-1">
                                                                            <button type="button" id="btn-filter" class="btn btn-success">Filtrele</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <div class="row">
                                                            <div class="col-lg-1">
                                                                <div class="form-group">
                                                                    <label>listeleme</label>
                                                                    <select  class="custom-select custom-select-sm form-control form-control-sm" name="" id="type">
                                                                        <option value="">4</option>
                                                                        <option value="">20</option>
                                                                        <option value="">40</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-9"></div>
                                                            <div class="col-lg-2">
                                                                <div class="form-group" >
                                                                    <label for="search">Personel Arama : </label>
                                                                    <input type="text" name="search" id="search" class="form-control search">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <table class="table table-bordered applicant_employee" id="applicant_employee">

                                                            <thead>
                                                            <tr>
                                                                <th>
                                                                    <label class="checkbox checkbox-rounded" style="display:initial">
                                                                        <input id="allSelect"  type="checkbox">
                                                                        <span></span></label>
                                                                </th>
                                                                <th>Çalışanlar</th>
                                                                <th>Üst</th>
                                                                <th>Ast</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>


                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="program-start-date" name="program_start_date" value="">
                                                <input type="hidden" id="program-finish-date" name="program_finish_date" value="">
                                                <input type="hidden" id="now_date" value="{{\Illuminate\Support\Facades\Date::now()->format('Y-m-d')}}">
                                                <input type="hidden" id="now_date2" value="{{\Illuminate\Support\Facades\Date::now()->format('d/m/Y')}}">
                                                <!--end: Wizard Step 3-->
                                                <!--begin: Wizard Step 4-->
                                                <div class="pb-5" data-wizard-type="step-content">
                                                    <div class="mb-10 font-weight-bold text-dark">ÜST AST VE ÖZ DEĞERLENDİRME SORULARINI BELİRLEYİNİZ</div>

                                                        <div class="row mb-15">
                                                            <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="">Ast Amir Değerlendirme Formu</label>
                                                                {!! Form::select('ast_form',  $ust_ast_question_forms  ,null,['class'=>'form-control selectpicker ','id'=>'ast_form_id','data-live-search'=>'true']) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                              <div class="form-group">
                                                                <label for="">Üst Ast Değerlendirme Formu</label>
                                                                  {!! Form::select('ust_form', $ast_ust_question_forms ,null,['class'=>'form-control selectpicker ','id'=>'ust_form_id','data-live-search'=>'true']) !!}
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="separator separator-dashed my-5"></div>
                                                    <!--end::Section-->

                                                </div>
                                                <!--end: Wizard Step 4-->
                                                <!--start Wizard Step 5 -->

                                                <!--end Wizard 5 -->
                                                <!--begin: Wizard Actions-->
                                                <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                                    <div class="mr-2">
                                                        <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">GERİ</button>
                                                    </div>
                                                    <div>
                                                        <button type="submit" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4 save" data-wizard-type="action-submit">YAYINLA</button>
                                                        <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" id="action-next" data-wizard-type="action-next">KAYDET/DEVAM ET</button>
                                                    </div>
                                                </div>
                                                    <!--end: Wizard Form-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end: Wizard Bpdy-->
                                </div>
                                <!--end: Wizard-->
                            </div>
                        </div>
                    </div>
                    <!--end::Container-->

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" crossorigin="anonymous"></script>
    <script src="/assets/performance_js/performance.js"></script>
    <script src="/assets/js/pages/crud/forms/widgets/form-repeater.js"></script>

    <script>

            $( "#btn-filter" ).click(function() {
             var  id =  $("#sgk_company_filter").val();
             var performance_id = {{isset($id) ? $id : 0 }};
             var work_type = $("#employee_work_type").val();
             var sayi = $(".type_applicant").length;

             if(sayi > 0 )
             {
                 $(".type_applicant").append().remove();
             }

                $.ajax({
                    type: "GET",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '/employee-sgk_company-filter/'+ id +'/'+performance_id+'/'+work_type,
                    success: function (datas) {
                        var items = '';
                        $.each(datas, function (i, item) {
                            $('#applicant_employee tbody').append(item);
                        });
                    },
                });
            });

    </script>

    <script>

        $('#question-save2').click(function(event) {  //on click
            var  name =  $("#question_name").val();
            var  type =  $("#question_type").val()
            var sorular = [];
            $( "input[name*='questions']" ).each(function (index){
                var input = 0;
                input = $(this).val();
                sorular[index] = [type,input,name];
            });
            ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/questions/company/create',
                data: {
                    name: name,
                    sorular:sorular,
                },
                success: function (alert) {
                    if (type == 1)
                    {
                        $('#ast_form_id').append(alert.addValue);
                    }
                    else
                    {
                        $('#ust_form_id').append(alert.addValue);
                    }


                    $('#exampleModal').modal('toggle');
                    Swal.fire({
                        position: "top-right",
                        icon: "success",
                        title: "Kayıt Başarılı",
                        showConfirmButton: false,
                        timer: 3500
                    });
                },
            });
        });

    </script>
    <script>
        $(document).ready(function ()
        {
           $("#_questions").hide();
        });
        $("#_showQuestion").click(function ()
        {
            $("#_questions").toggle(100);
        });
    </script>

     <script>
            "use strict";
            var KTDatatablesAdvancedColumnRendering = function() {

                var init = function() {
                    var table = $('#kt_datatable');

                    // begin first table
                    table.DataTable({
                        responsive: true,
                        paging: false
                    });

                    $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
                };

                return {

                    //main function to initiate the module
                    init: function() {
                        init();
                    }
                };
            }();

            jQuery(document).ready(function() {
                KTDatatablesAdvancedColumnRendering.init();
            });
        </script>

    <script>
        $(document).ready(function ()
        {
            var checked = 0;
            $(".performance_type").click(function() {
                if(this.checked) {
                    var name = $(this).attr('id');

                    var label = $(this).data('name');
                checked = checked +1;
                var sayi = $("#type_"+name).length;

                if (sayi == 0)
                {
                    $("#type_").append("<tr style='border: ridge' id='type_" + name + "'><td><div class='row' ><div class='col-lg-6'><div class='form-group'><label class='' style='font-weight: bold;margin-top: 30px;font-size: 16px;'>"+ label +"</label></div></div></td><td><div class='col-lg-6' style='margin-top: 15px'><div class='form-group'><label style='font-weight: bold'>Ağırlık Değerini Giriniz(100 üzerinden ağırlık puanı giriniz)</label><input class='form-control type_puans' id='_type_puans'  type='text' name='performance_type["+ name +"]'></div></div></td></tr>");

                }
                }
                else
                {
                        var name = $(this).attr('id');
                        checked = checked -1;
                        $('#type_'+name).append().remove();
                }
            });
        });

    </script>
    <script>
        var id = 0;
        $("#add_repater").click(function ()
        {
            id = id + 1;
            $(".kt_repeater").append('<div data-repeater-item="" class="form-group row append_delete" id="delete_append' + id + '"> <div class="col-lg-6"> <div class="input-group"> <div class="input-group-prepend"> <span class="input-group-text"> <i class="la la-phone"></i> </span> </div> <input type="text" id="meslekQuestions" name="meslekQuestions" class="form-control" placeholder="Soru" value=""> </div> </div> <div class="col-lg-4">  {!! Form::select("company_department_type" , $departments , null , ["class"=>"form-control " , "data-live-search" => "true","id"=>"meslekQuestionDepartment"]) !!}   </div> <div class="col-lg-2"> <a href="javascript:;" id="delete'+ id +'" data-id="'+ id +'"  class="btn font-weight-bold btn-danger btn-icon mt-1 delete"> <i class="la la-remove"></i></a></div></div>');
        });
        $('.kt_repeater').on('click', '.delete', function(e) {
            var ids = $(this).data('id');
            alert(ids);
           $("#delete_append"+ids).remove();
        });

    </script>


    <script>
        $('#meslekQuestionsSave').click(function(event) {  //on click
            var questions = [];
            var sorular = [];
            $("input[name*='meslekQuestions']").each(function (index) {
                 var input = 0;
                 input = $(this).val();
                $('#meslekQuestionDepartment  > option:selected').each(function() {
                       var department = 0;
                      department = $(this).val();
                      sorular[index] = [department,input];

                });
            });
            var sgk_company_id = $("select[name*='sgk_company_meslek']").val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/questions/meslek/company/create',
                data: {
                    sgk_company_id:sgk_company_id,
                    sorular:sorular
                },
                success: function (alert) {
                    Swal.fire({
                        position: "top-right",
                        icon: "success",
                        title: "Kayıt Başarılı",
                        showConfirmButton: false,
                        timer: 3500
                    });
                },
            });
        });

    </script>
    <script>
        $('body').on('change', '.type_puans', function() {
            var toplam = 0;
            $("#form_total").val(0);
            $('.type_puans').each(function(i, obj) {

                toplam = parseFloat($("#form_total").val()) + parseFloat($(this).val());
                $("#form_total").val(toplam);
            });

            $("#action-next").on('click',function ()
            {
                var a =  $("#form_total").val();
               if (a < 100)
               {
                   Swal.fire({
                       position: "top-right",
                       icon: "error",
                       title: "Performans Değerlendirme Puanların Toplamı 100 Değildir ",
                       showConfirmButton: false,
                       timer: 3500
                   });
               }
            });
        });
    </script>
    <script>
        $('#search').keyup(function () {

            var tg = $('.type_applicant').length;
            $("tr.type_applicant div:gt(" + tg +  ")").show();

            if ($('#search').val().length < 1) {
                tg = $('.type_applicant');
                $("tr.item_employee div:gt(" + tg +  ")").hide();
                var veriSayisi = 13;
                var sayi = 4;
                $("tr.type_applicant tr").hide();
                var indis = 1;
                var deger = veriSayisi * sayi;
                var gt = deger * indis;
                for (var i = gt - deger ; i < gt; i++ )
                {
                    $("tr.type_applicant tr:eq(" + i +  ")").show();
                }
                return;
            }
            $('.type_applicant').hide();

            var txt = $('#search').val();
            $('.type_applicant').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                    $(this).show();
                }
            });
            var t = $('.type_applicant:visible');
            $(".counter").html("Toplam <strong>" + t.length + "</strong> kişi gösteriliyor");
        });
    </script>

    <script>
        // Class definition
        var KTFormControls = function () {
            var say = 0;
            // Private functions
            var _initDemo1 = function () {
                FormValidation.formValidation(
                    document.getElementById('kt_form'),
                    {
                        fields: {
                            period: {
                                validators: {
                                    notEmpty: {
                                        message: 'Dönem Aralığı Zorunludur'
                                    }
                                }
                            },
                            program_name: {
                                validators: {
                                    notEmpty: {
                                        message: 'Program İsim Alanı  Gerekli'
                                    }
                                }
                            },
                            competence: {
                                validators: {
                                    notEmpty: {
                                        message: 'Program Kriter Alanı Zorunludur '
                                    }
                                }
                            },

                        },

                        plugins: { //Learn more: https://formvalidation.io/guide/plugins
                            trigger: new FormValidation.plugins.Trigger(),
                            // Bootstrap Framework Integration
                            bootstrap: new FormValidation.plugins.Bootstrap(),
                            // Validate fields when clicking the Submit button
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            // Submit the form when all fields are valid
                            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        }
                    }
                );
            }


            return {
                // public functions
                init: function() {
                    _initDemo1();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTFormControls.init();
        });


    </script>
    <script >

        $(".save").on('click',function ()
        {

            var say = 0;
            var period = "";
            var program_name = $(".program_name").val();

                 period = $("input[name='period']:checked").val();



            if (program_name == "" || period == "")
            {
                swal("ÜZGÜNÜZ!", "Doldurulması Zorunlu Alanlar Doldurulmadı!", "error");

            }
        })
    </script>
@endsection
