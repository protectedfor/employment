@extends('layouts.app_no_banner')
@include('profiles.partials._employer_assets')
@section('content')
    <div class="room">
        <div class="container">
            <div class="row">
                @include('profiles.partials._company_block')
                <div class="col-md-9">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="setting">
                            <div class="title">
                                <h2>Сменить пороль</h2>
                            </div>

	                        {!! Form::open(['route' => ['users.update', Auth::id()], 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}
		                        <div class="form-group">
			                        {!! Form::label('oldPassword', 'Старый пароль', ['class' => 'col-sm-3 control-label']) !!}
			                        <div class="col-sm-9">
			                            {!! Form::password('oldPassword', ['class' => 'form-control']) !!}
		                            </div>
		                        </div>
		                        <div class="form-group">
			                        {!! Form::label('newPassword', 'Новый пароль', ['class' => 'col-sm-3 control-label']) !!}
			                        <div class="col-sm-9">
			                            {!! Form::password('newPassword', ['class' => 'form-control']) !!}
		                            </div>
		                        </div>
		                        <div class="form-group">
			                        {!! Form::label('repeatedPassword', 'Повторите новый пароль', ['class' => 'col-sm-3 control-label']) !!}
			                        <div class="col-sm-9">
			                            {!! Form::password('repeatedPassword', ['class' => 'form-control']) !!}
		                            </div>
		                        </div>
		                        {!! Form::submit('Сохранить', ['class' => 'btn btn-primary save']) !!}
	                        {!! Form::close() !!}

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div><!--  end room -->
@stop