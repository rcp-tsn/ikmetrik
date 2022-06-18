<div class="panel panel-bordered panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            Kişi seçiniz
        </h3>
        <div class="panel-actions">
            <a href="{{ $backRoute }}" class="btn btn-dark white">
                Listeye geri dön
            </a>
            <a href="{{ $clearFilterRoute }}" type="button" class="btn btn-danger white">
                Filtreyi temizle
            </a>
        </div>
    </div>
    <div class="panel-body">
        {{ Form::open([
            'method' => 'POST',
            'url' => $bulkStoreRoute,
            'id' => 'select-users-bulk-store'
            ]) }}
        <table class="table table-bordered table-striped" id="js-add-users-table"
               data-add-url="{{ $storeRoute }}">
            <thead>
            <tr>
                <th>
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" class="checkbox-custom" id="user-check-all" name="user-check-all">
                        <label for="user-check-all"></label>
                    </div>
                </th>
                <th>Adı</th>
                <th style="width: 400px;">
                    @if(isset($listType))
                        T.C. No
                    @else
                        Süre
                    @endif
                </th>
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
                <a href="javascript:void(0);" onclick="return userSelectAllStore()" class="btn btn-success btn-sm">
                    <i class="icon fa-plus" aria-hidden="true"></i> Seçili olanları ekle
                </a>
            </div>
            <div class="pull-right">
                {{ $users->links() }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>