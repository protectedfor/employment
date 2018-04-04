@extends('layouts.app_no_banner')
@include('profiles.partials._worker_assets')
@section('content')
    <div class="room">
        <div class="container">
            <div class="row">
                @include('profiles.partials._worker_profile_block')
                <div class="col-md-9">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="profile">
                            <div class="title">
                                <h2>Данные</h2>
                            </div>

                            <form action="{{ route('workers.profile.edit.update') }}" class="form-horizontal" method="POST">
                                {!! csrf_field() !!}

                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}" id="">
                                    <label for="name" class="col-sm-3 control-label">Имя:</label>
                                    <div class="col-sm-9">
                                        <input name="name" value="{{ old('name') ? old('name') : $profile->name }}" type="text" class="form-control">
                                        @if($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>

	                            <div class="form-group {{ $errors->has('sname') ? 'has-error' : '' }}" id="">
		                            <label for="sname" class="col-sm-3 control-label">Фамилия:</label>
		                            <div class="col-sm-9">
			                            <input name="sname" value="{{ old('sname') ? old('sname') : $profile->sname }}" type="text" class="form-control">
			                            @if($errors->has('sname'))
				                            <span class="help-block">{{ $errors->first('sname') }}</span>
			                            @endif
		                            </div>
	                            </div>

	                            <div class="form-group {{ $errors->has('mname') ? 'has-error' : '' }}" id="">
		                            <label for="mname" class="col-sm-3 control-label">Отчество:</label>
		                            <div class="col-sm-9">
			                            <input name="mname" value="{{ old('mname') ? old('mname') : $profile->mname }}" type="text" class="form-control">
			                            @if($errors->has('mname'))
				                            <span class="help-block">{{ $errors->first('mname') }}</span>
			                            @endif
		                            </div>
	                            </div>

	                            <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
		                            <div class='input-group' style="width: 100%;overflow: hidden;">
			                            <label for="" class="col-sm-3 control-label">Дата рождения:</label>
			                            <input name="date_of_birth" value="{{ old('date_of_birth') ? old('date_of_birth') : ($profile->date_of_birth ? $profile->date_of_birth->format('d.m.Y') : '') }}" id='datetimepicker5' class="form-control date datepicker" type="text" style="width: 48%;margin-left: 15px;padding-left: 13px;">
		                                <span class="input-group-addon" style="position: absolute;top: 0;right: 192px;"><i class="glyphicon glyphicon-calendar" ></i></span>
			                            @if($errors->has('date_of_birth'))
				                            <span class="help-block">{{ $errors->first('date_of_birth') }}</span>
			                            @endif
		                            </div>
	                            </div>

                                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}" id="">
                                    <label for="" class="col-sm-3 control-label">Телефон:</label>
                                    <div class="col-sm-9">
                                        <input name="phone" value="{{ old('phone') ? old('phone') : $profile->phone }}" type="text" class="form-control">
                                        @if($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                        {{--<label class="label--checkbox">--}}
                                            {{--<input name="show_phone" value="1" @if(old('show_phone')) checked @elseif($profile->show_phone) checked @endif id="chexbox" type="checkbox" class="checkbox">--}}
                                            {{--Показать на сайте--}}
                                        {{--</label>--}}
                                    </div>
                                </div>

	                            <div class="form-group {{ $errors->has('citizenship_id') ? 'has-error' : '' }}">
		                            <label for="" class="col-sm-3 control-label">Гражданство:</label>
		                            <div class="col-sm-7">
			                            {!! Form::select('citizenship_id', $citizenships->lists('title', 'id'), $profile->citizenship_id, ['class' => 'form-control', 'placeholder' => 'Выберите гражданство']) !!}
			                            @if($errors->has('citizenship_id'))
				                            <span class="help-block">{{ $errors->first('citizenship_id') }}</span>
			                            @endif
	                                </div>
	                            </div>

                                <div class="form-group {{ $errors->has('about_me') ? 'has-error' : '' }}" id="textarea">
                                    <label for="about_me" class="col-sm-3 control-label">Подробная информация о Ваc:</label>
                                    <div class="col-sm-9">
                                        <textarea name="about_me" class="form-control" rows="12">{{ old('about_me') ? old('about_me') : $profile->about_me }}</textarea>
                                        @if($errors->has('about_me'))
                                            <span class="help-block">{{ $errors->first('about_me') }}</span>
                                        @endif
                                    </div>
                                    <div class="pull-right">
                                        <button type="submit" href="" class="safe">Сохранить</button>
                                    </div>
                                </div>

                            </form>

                        </div> <!-- dsfsdf -->
                    </div>
                </div>
            </div>
        </div>
    </div><!--  end room -->
@stop