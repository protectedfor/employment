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
                        <div role="tabpanel" class="tab-pane fade in active" id="profile">
                            <div class="title">
                                <h2>Профиль компании</h2>
                            </div>
	                        <div class="in hot">
		                        <a class="setting ajax_modal" href="" data-action="makeLeading" data-type="company" data-id="{{ $profile->id }}">Добавить в "Ведущие работодатели"</a>
	                        </div>
                            <form action="{{ route('employers.profile.edit.update') }}" class="form-horizontal" method="POST">
                                {!! csrf_field() !!}
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label for="" class="col-sm-3 control-label">Название организации:</label>
                                    <div class="col-sm-5">
                                        <input name="title" value="{{ old('title') ? old('title') : $profile->title }}" type="text" class="form-control">
                                        @if($errors->has('title'))
                                            <span class="help-block">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('scope_id') ? 'has-error' : '' }}">
                                    <label class="col-sm-3 control-label">Сфера деятельности:</label>
                                    <div class="col-sm-5">
                                        <select name="scope_id" class="form-control">
                                            <option value="">Выберите сферу деятельности</option>
                                            @foreach($scopes as $scope)
                                                <option @if((old('scope_id') ? old('scope_id') : $profile->scope_id) == $scope->id) selected @endif value="{{ $scope->id }}">{{ $scope->title }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('scope_id'))
                                            <span class="help-block">{{ $errors->first('scope_id') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Город/регион:</label>
                                    <div class="col-sm-5">
                                        <select name="city_id" class="form-control">
                                            <option value="">Выберите город/регион</option>
                                            @foreach($cities as $city)
                                                <option @if((old('city_id') ? old('city_id') : $profile->city_id) == $city->id) selected @endif value="{{ $city->id }}">{{ $city->title }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('city_id'))
                                            <span class="help-block">{{ $errors->first('city_id') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                    <label for="" class="col-sm-3 control-label">Адрес</label>
                                    <div class="col-sm-5">
                                        <input name="address" value="{{ old('address') ? old('address') : $profile->address }}" type="text" class="form-control">
                                        @if($errors->has('address'))
                                            <span class="help-block">{{ $errors->first('address') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
	                                <div id="ToToggle" class="cabinet">
		                                <?php isset($profile) ? $instance = $profile : '' ?>
		                                @include('admin.contacts.map')
		                                <input name="google_map_code" value="{{ isset($profile) ? $profile->google_map_code : (old('google_map_code') ? old('google_map_code') : '') }}" type="hidden" class="form-control">
	                                </div>
                                </div>

                                <div class="title">
                                    <h2>Контактные данные</h2>
                                </div>

                                <div class="form-group {{ $errors->has('fio') ? 'has-error' : '' }}" id="">
                                    <label for="" class="col-sm-3 control-label">ФИО:</label>
                                    <div class="col-sm-9">
                                        <input name="fio" value="{{ old('fio') ? old('fio') : $profile->fio }}" type="text" class="form-control">
                                        @if($errors->has('fio'))
                                            <span class="help-block">{{ $errors->first('fio') }}</span>
                                        @endif
                                        <label class="label--checkbox">
                                            <input name="show_fio" value="1" @if(old('show_fio')) checked @elseif($profile->show_fio) checked @endif id="chexbox" type="checkbox" class="checkbox">
                                            Показать на сайте
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}" id="">
                                    <label for="" class="col-sm-3 control-label">Телефон:</label>
                                    <div class="col-sm-9">
                                        <input name="phone" value="{{ old('phone') ? old('phone') : $profile->phone }}" type="text" class="form-control">
                                        @if($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                        <label class="label--checkbox">
                                            <input name="show_phone" value="1" @if(old('show_phone')) checked @elseif($profile->show_phone) checked @endif id="chexbox" type="checkbox" class="checkbox">
                                            Показать на сайте
                                        </label>
                                    </div>
                                </div>

	                            <div class="form-group {{ $errors->has('site') ? 'has-error' : '' }}" id="">
		                            <label for="" class="col-sm-3 control-label">Сайт компании:</label>
		                            <div class="col-sm-9">
			                            <input name="site" value="{{ old('site') ? old('site') : $profile->site }}" type="text" class="form-control">
			                            @if($errors->has('site'))
				                            <span class="help-block">{{ $errors->first('site') }}</span>
			                            @endif
			                            <label class="label--checkbox">
				                            <input name="show_site" value="1" @if(old('show_site')) checked @elseif($profile->show_site) checked @endif id="chexbox" type="checkbox" class="checkbox">
				                            Показать на сайте
			                            </label>
		                            </div>
	                            </div>

                                <div class="title">
                                    <h2>О компании</h2>
                                </div>

                                <div class="form-group b-about_company {{ $errors->has('about_company') ? 'has-error' : '' }}" id="textarea">
                                    <label for="" class="col-sm-3 control-label">Подробная информация о Вашей компании:
              <span>Данная информация будет
              отображаться на страницах с
              вашими вакансиями.</span>
                                    </label>

                                    <div class="col-sm-9">
                                        <textarea name="about_company" class="form-control ckeditor">{{ old('about_company') ? old('about_company') : $profile->about_company }}</textarea>
                                        @if($errors->has('about_company'))
                                            <span class="help-block">{{ $errors->first('about_company') }}</span>
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