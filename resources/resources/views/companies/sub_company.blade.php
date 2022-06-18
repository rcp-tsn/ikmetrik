@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası', 'customCreateNew' => $customCreateNew ])
    @endcomponent

    @php $routeParts = explode('.', Route::currentRouteName()); @endphp
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ __($routeParts[0].'.create_form_title') }}
                            </h3>
                            <div class="card-toolbar"></div>
                        </div>

                        <!--end:: Card header-->

                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <td>Firma Adı</td>
                                        <td>İşlemler</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($object as $node)
                                    <tr>
                                        <td></td>
                                        <td>{{ $node->name }}</td>
                                        <td>
                                            <span class="pull-right">
                @include('partials.ops', [
                    'resource' => 'companies',
                    'id' => createHashId($node->id),
                    'class' => 'inline',
                    'hashId' => false,
                    'column_permission' => ['edit' => null, 'delete' => null]
                    ]
                )
            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
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





