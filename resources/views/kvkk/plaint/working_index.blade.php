@extends('layouts.app')
@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('kvkk'))
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    @endif
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Kvkk Talep / Şikayet Oluştur</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="col-md-12 center">

                            <div class="col-lg-6">

                            <form class="" action="{{route('request.working.add')}}" method="POST" role="form">
                                @csrf
                                <div class="form-group form-group-default required ">
                                    <label>Adı Soyadı</label>
                                    <input type="text" class="form-control" name="name" required="" value="{{$working->employee->first_name . $working->employee->last_name}}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default required">
                                            <label>Tc Kimlik No</label>
                                            <input type="text"  class="form-control" maxlength="11" name="no" required="" value="{{$working->identity_number}}">
                                        </div>
                                    </div>
                                    <input type="hidden" name="working_id" value="{{$working->employee_id}}">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label>Adres</label>
                                            <input type="text" name="address" required class="form-control" value="{{$working->employee->address}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-default required">
                                    <label>Cep No</label>
                                    <input type="text" name="phone" maxlength="11" class="form-control" required="" value="{{$working->employee->mobile}}">
                                </div>
                                <div class="form-group  form-group-default required">
                                    <label>E-Posta</label>
                                    <input type="email" name="email" class="form-control" placeholder="ex: some@example.com" required="" value="{{$working->employee->email}}">
                                </div>
                                <div class="form-group  form-group-default required">
                                    <label>Başvuru Tarihi</label>
                                    <input type="date" name="date" class="form-control"  required="">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="company_id" value="{{$companies->id}}">

                                    <div class="form-group form-group-default required">
                                        <label>Şikayetçinin Talebi </label>
                                        <select name="back" class="form-control" data-placeholder="Select Country" data-init-plugin="select2" tabindex="-1" aria-hidden="true">
                                            <option value="Cevabın Başvuru Formunda belirtmiş olduğum adresime gönderilmesini talep ederim.">Cevabın Başvuru Formunda belirtmiş olduğum adresime gönderilmesini talep ederim.</option>
                                            <option value="Cevabın Başvuru Formunun belirtmiş olduğum elektronik posta adresime gönderilmesini talep ederim. (E-posta yöntemini seçmeniz halinde size daha hızlı yanıt verebileceğiz.)">Cevabın Başvuru Formunun belirtmiş olduğum elektronik posta adresime gönderilmesini talep ederim. (E-posta yöntemini seçmeniz halinde size daha hızlı yanıt verebileceğiz.)</option>
                                            <option value="Elden teslim almak istiyorum. (Vekaleten teslim alınması durumunda noter tasdikli vekaletname veya noter tasdikli yetki belgesi olması gerekmektedir. Kişinin eşi, babası gibi yakınlarına asla bilgi verilmemektedir.)">Elden teslim almak istiyorum. (Vekaleten teslim alınması durumunda noter tasdikli vekaletname veya noter tasdikli yetki belgesi olması gerekmektedir. Kişinin eşi, babası gibi yakınlarına asla bilgi verilmemektedir.)</option>
                                        </select>
                                    </div>
                                </div>



                                <div class=" form-group">
                                    <label for="">c</label><br>
                                    <div   class="" tabindex="0" style="">
                                        <select  name="company_contact_type" class="form-control" data-init-plugin="cs-select">
                                            <option value="Personel">Personel</option>
                                            <option value="Ziyaretçi">Ziyaretçi</option>
                                            <option value="Müşteri">Müşteri</option>
                                            <option value="Diğer">Diğer</option>
                                        </select><div class="cs-backdrop" style="transform: scale3d(1, 1, 1);"></div></div></div>




                                <div class="form-group">
                                    <label for="">Şirketle İlişkiniz Devam Ediyormu</label><br>
                                    <div class="" tabindex="0" style="">
                                        <select name="company_contact" class="form-control" data-init-plugin="cs-select">
                                            <option value="1">Evet</option>
                                            <option value="0">Hayır</option>
                                        </select><div class="cs-backdrop" style="transform: scale3d(1, 1, 1);"></div></div></div>

                                <div class="form-group">
                                    <div class="form-group form-group-default">
                                        <label>Şikayetinizi Açıklayınız</label>
                                        <input name="customer_request" type="text" style="height: 120px;"  class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-12 ustten_bosluk">
                                    <div class="form-group ">
                                        <button type="submit" class="btn btn-success">Formu Gönder</button>
                                    </div>
                                </div>
                            </form>

                        </div>

                        </div>
                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>

            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Entry-->
@stop

@push('scripts')


@endpush

