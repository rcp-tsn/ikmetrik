<div class="row">
    <div class="col-sm-6">

        <div class="card card-custom gutter-b ">
            <div class="card-header">
                <h3 class="card-title">
                    Şirket Bilgileri
                </h3>
                <div class="card-toolbar"></div>
            </div>
            <div class="card-body">
                @include('companies._form_company')
            </div>
        </div>
        <div class="card card-custom gutter-b ">
            <div class="card-header">
                <h3 class="card-title">
                    Yönetici Bilgileri
                </h3>
                <div class="card-toolbar"></div>
            </div>
            <div class="card-body">
                @include('companies._form_user')
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card card-custom gutter-b ">
            <div class="card-header">
                <h3 class="card-title">
                    Paket Bilgileri
                </h3>
                <div class="card-toolbar"></div>
            </div>
            <div class="card-body">
                @include('companies._form_packet')
            </div>
        </div>
        <div class="card card-custom gutter-b ">
            <div class="card-header">
                <h3 class="card-title">
                    Fatura Bilgileri
                </h3>
                <div class="card-toolbar"></div>
            </div>
            <div class="card-body">
                @include('companies._form_billing_information')
            </div>
        </div>
    </div>
</div>

