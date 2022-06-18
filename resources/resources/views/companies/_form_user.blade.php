@if($user->id)
    {{ Form::bsSelect('users[id]', 'Seç', $users, $user->id, [], [], [
                'addButton' => [
                    'text' => 'Yeni',
                    'type' => 'user',
                    'selectorId' => 'users\\[id\\]',
                    'authorization' => ['role' => 'Admin']]
                ]) }}
@else
    {{ Form::hidden('users[id]', $user->id) }}
    <div class="form-group">
        <label>Ad Soyad</label>
        {!! Form::text("users[name]",$user->name,['class'=>'form-control form-control-solid','id'=>'user_name','required'=>'required']) !!}
    </div>
    <div class="form-group">
        <label>Email</label>
        {!! Form::text("users[email]",$user->email,['class'=>'form-control form-control-solid','id'=>'user_email','required'=>'required']) !!}
    </div>
@endif
