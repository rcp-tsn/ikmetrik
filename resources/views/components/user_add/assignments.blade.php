{{ $slot }}

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
            'id' => 'select-users-bulk-destroy' ])
            }}
        @if($assignments->count())
            @component('components.alert.warning')
                Silmek istediğiniz atama eğer başlamışsa silinmez, arşivlenir.
            @endcomponent
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
                    <th>Süre</th>
                    <th>Ünvan</th>
                    <th>Departman</th>
                    <th style="width: 90px;"></th>
                </tr>
                </thead>
                <tbody id="user-select-list-table">

                @foreach($assignments as $assignment)
                    <tr id="user-{{ $assignment->id }}" class="select-list">
                        <th scope="row">
                            <div class="checkbox-custom checkbox-primary">
                                <input type="checkbox" class="checkbox-custom" id="user[{{ $assignment->id }}]" name="user[{{ $assignment->id }}]">
                                <label for="user[{{ $assignment->id }}]"></label>
                            </div>
                        </th>
                        <td>{{ $assignment->user->name }}</td>
                        <td>
                            @if($assignment->indefinite)
                                Süresiz
                            @else
                                {{ $assignment->form_start_at }} -
                                {{ $assignment->form_end_at }}
                            @endif
                        </td>
                        <td>{{ $assignment->user->work_title }}</td>
                        <td>{{ $assignment->user->department->title }}</td>
                        <td style="text-align: center;">
                            @if(isset($assignment->is_archived) && $assignment->is_archived === 1)
                                Arşivlenmiş
                            @else
                            <a href="javascript:void(0);" data-url="{{ route($routePrefix . '.destroy', $assignment) }}"
                               onclick="return confirmSelectDelete({{ $assignment->id }})"
                               class="btn btn-danger btn-xs js-user-delete-{{ $assignment->id }}">
                                <i class="icon wb-trash" aria-hidden="true"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

            <div class="clearfix">
                <div class="pull-left">
                    <a href="javascript:void(0);" onclick="return userSelectAllDelete()" class="btn btn-danger btn-sm">
                        <i class="icon wb-trash" aria-hidden="true"></i> Seçili olanları sil
                    </a>
                </div>
                <div class="pull-right">
                    {{ $assignments->links() }}
                </div>
            </div>
            {{ Form::close() }}
        @else
            @include('partials.alerts.none', ['alert' => 'Atanmış kişi bulunamadı.'])
        @endif
    </div>
</div>