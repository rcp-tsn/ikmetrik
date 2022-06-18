<div class="panel panel-bordered panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            Atanan Kişiler
        </h3>
        <div class="panel-actions">
            <a href="{{ $newUserRoute }}" class="btn btn-dark white">
                Yeni kişi atama
            </a>
        </div>
    </div>
    <div class="panel-body">
        {{ Form::open([
                    'method' => 'POST',
                    'url' => $bulkDestroyRoute,
                    'id' => 'select-users-bulk-destroy'
                    ]) }}
        @if($userList->count())
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" class="checkbox-custom" id="user-check-all" name="user-check-all">
                            <label for="user-check-all"></label>
                        </div>
                    </th>
                    <th>Adı</th>
                    <th>Kimlik Numarası</th>
                    <th>Ünvan</th>
                    <th>Departman</th>
                    <th style="width: 90px;"></th>
                </tr>
                </thead>
                <tbody id="user-select-list-table">
                {{ $slot }}
                </tbody>
            </table>

            <div class="clearfix">
                <div class="pull-left">
                    <a href="javascript:void(0);" onclick="return userSelectAllDelete()" class="btn btn-danger btn-xs">
                        <i class="icon wb-trash" aria-hidden="true"></i> Seçili olanları sil
                    </a>
                </div>
                <div class="pull-right">
                    {{ $userList->links() }}
                </div>
            </div>
            {{ Form::close() }}
        @else
            @include('partials.alerts.none', ['alert' => 'Atanmış kişi bulunamadı.'])
        @endif
    </div>
</div>