<div id="page-preloader"><span class="spinner"></span></div>
<div class="modal-dialog" role="document">
	<div class="modal-content">

		@if($type == 'text' && $action == 'show')
			<div class="mod_login">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="container-fluid login">
						<h1 style="margin-top: 20px; font-size: 18px">Пожалуйста, авторизуйтесь как работодатель, чтобы иметь возможность просматривать резюме!</h1>
					</div>
				</div>
			</div>
		@endif

		@if($type == 'password' && $action == 'reset')
			<div class="mod_login">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="container-fluid login">
					<h1 style="margin-top: 20px;">Сбросить пароль</h1>
					{!! Form::open(['route' => ['password.reset'], 'class' => 'b-reset_form col-md-4  col-md-offset-4 ajax_form', 'style' => 'width: 80%; float: none; margin: 0 auto;']) !!}
						<input type="hidden" name="token" value="{{ $parameters }}">
						<div class="form-group" style="width: 100%; margin: 0;">
							<label for="email">Email</label>
							<input type="email" name="email" class="form-control"  value="{{ old('email') }}">
						</div>
						<div class="form-group pass" style="width: 100%; margin: 0;">
							<label for="password">Пароль</label>
							<input type="password" class="form-control" name="password">
						</div>
						<div class="form-group pass" style="width: 100%; margin: 0;">
							<label for="password_confirmation">Повторите пароль</label>
							<input type="password" class="form-control" name="password_confirmation">
						</div>
						<br>
						<div style="text-align: center;">
							<button type="submit" style="float: none; display: inline-block;" class="btn btn-success">Подтвердить</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		@endif

		@if($type == 'password' && $action == 'forget_pass')
			<div class="mod_login">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="restart-pass">
						<div class="container-fluid">
							{!! Form::open(['route' => ['password.email'], 'class' => 'col-lg-offset-2 col-lg-8  ajax_form']) !!}
								<h2 style="margin-top: 20px;">Восстановление пароля</h2>
								<div>
									<label for="email">Email</label>
									<input class="form-control"  type="email" name="email" value="{{ old('email') }}">
								</div>
								<br>
								<div>
									<button type="submit" class="btn btn-success">Восстановить</button>
								</div>
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
		@endif

		@if($type == 'auth' && $action == 'login')
			<div class="mod_login">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="login">
						<div class="container-fluid">
							<div class="row">
								@if($parameters === 'headerText')
									<h1 style="font-size: 20px;color: #ff4800;margin-top: 0;">Для просмотра резюме вам необходимо  авторизоваться как Работодатель</h1>
								@endif
								<h1>Войти</h1>
								{!! Form::open(['route' => ['auth.login'], 'class' => 'col-md-offset-4 col-md-4 col-lg-offset-2 col-lg-8 ajax_form']) !!}
									<div class="form-group">
										<label for="email">ВАШ E-MAIL</label>
										<input type="email" name="email" value="{{ old('email') }}" class="form-control" id="email" placeholder="Email">
									</div>
									<div class="form-group pass" style="width: 45%; margin-right: 6%;">
										<label for="password">ПАРОЛЬ</label>
										<input type="password" name="password" class="form-control" id="password" placeholder="Password">
									</div>
									<button type="submit" class="btn btn-default" style="width: 49%;">Войти</button>
								{!! Form::close() !!}
								<div class="clearfix"></div>
								<div class="col-md-offset-2 col-md-4 сol-lg-offset-2 col-lg-8 ">
									<div class="b-login-forget">
										<a href="#" class="ajax_modal" data-action="forget_pass" data-type="password">Забыли пароль?</a>
									</div>
									<div style="margin:36px 0;">
										<h5 style="margin-bottom:4px;">Быстрая авторизация через</h5>
										@include('auth.partials._social_auth_icons')
									</div>
									<p>У вас еще нет аккаунта?</p>
									<a href="#" class="reg_button ajax_modal" data-action="register" data-type="auth">регистрация</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if($type == 'auth' && $action == 'register')
			<div class="mod_login">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body modal-body_regist">
					<div class="registration">
						<div class="container-fluid">
							<div class="row">
								<h1>Регистрация</h1>
								{!! Form::open(['route' => ['auth.postRegister'], 'class' => 'col-lg-offset-2 col-lg-8  ajax_form']) !!}
								<div class="checks">
									<div class="card">
										<label class="radio">
											<input type="radio" name="role" value="workers" @if(old('role') == 'workers') checked @endif>
											<span class="outer"><span class="inner"></span></span>
											Я соискатель
										</label>
										<label class="radio">
											<input type="radio" name="role" value="employers" @if(old('role') == 'employers') checked @endif>
											<span class="outer"><span class="inner"></span></span>
											Я работодатель
										</label>
									</div>
								</div>
								@ReplaceBlock('auth.partials._registerBlock')
								<div class="clearfix"></div>
								<div class="text-center" style="margin-top:36px;">
									<h5 style="margin-bottom:4px;">Подставить данные из</h5>
									@include('auth.partials._social_auth_icons')
								</div>
								<button type="submit" class="btn btn-default">Регистрация</button>
								{!! Form::close() !!}
								<div class="clearfix"></div>
								<div class="col-md-offset-4 col-md-4 text-center">
									<div class="b-login-forget">
										<a href="#" class="ajax_modal" data-action="forget_pass" data-type="password">Обновить пароль</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if($type == 'company' && $action == 'makeLeading')
			<?php $duration = head(array_keys(array_get($billingVars->{$action}, $type))) ?>
			<?php $allRates = array_get($billingVars->{$action}, $type) ?>
			<?php $rate = array_get($allRates, $duration) ?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Эта функция является платной! За добавление вашей организации в категорию «Ведущие работодатели» на {{ $duration }} дней, с вашего счёта будет снято {{ $rate }} сом</h4>
			</div>
			@if(Auth::check() && Auth::user()->hasRoleFix('employers') && (Auth::user()->balance >= $rate) &&
				($item->billingFilter($action)->count() > 0 ? \Carbon\Carbon::now() > $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) : true))
				<div class="modal-body">
					<div class="form-group">
						<p>Деньги будут сняты с вашего счёта и компания добавлена в "Ведущие работодатели" сразу после активации администратором</p>
					</div>
				</div>
				{!! Form::open(['route' => ['companies.makeLeading', $item->id, 'type' => $type, 'duration' => $duration], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
					<div class="modal-footer">
						<a href="{{ url('https://employment.kg/images/uploads/top_employers.png') }}" target="_blank">Посмотреть пример</a>
						<button type="submit" style="margin-top: 10px!important; width: 210px; outline: none!important" class="btn btn-primary send_response">Отправить на активацию</button>
					</div>
				{!! Form::close() !!}
			@else
				<div class="modal-body">
					<div class="form-group">
						@if($item->billingFilter($action)->count() > 0 && \Carbon\Carbon::now() < $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration))
							<p>Вы уже использовали эту функцию за последний месяц. В следующий раз её можно использовать не ранее {{ $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) }}</p>
						@elseif(Auth::user()->balance < $rate)
							<?php $noMoney = true ?>
							<p>Ваш баланс составляет <strong>{{ Auth::user()->balance }} сом</strong>. Чтобы оплатить данную услугу, необходимо иметь <strong>{{ $rate }} сом</strong> на балансе</p>
						@else
							<p>Пожалуйста, авторизуйтесь как соискатель чтобы использовали эту функцию</p>
						@endif
					</div>
				</div>
				<div class="modal-footer">
					@if(isset($noMoney))
						<a href="{{ route('employers.profile.fill_up_balance') }}" class="btn btn-primary send_response " style="background: #ff4800;border-color: #ff4800;">Пополнить баланс</a>
					@endif
					<a href="{{ url('https://employment.kg/images/uploads/top_employers.png') }}" target="_blank">Посмотреть пример</a>
				</div>
			@endif
		@endif

		@if($type == 'company' && $action == 'getContacts')
			<?php $allRates = array_get($billingVars->{$action}, $type) ?>
			<?php $rate = head($allRates) ?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Вы можете получить доступ к контактным данным ВСЕХ СОИСКАТЕЛЕЙ на ограниченный период</h4>
			</div>
			{!! Form::open(['route' => ['companies.getContacts', Auth::user()->company->id, 'type' => $type], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
				<div class="modal-body">
					<div class="form-group">
						<p style="margin: 0;">Выберите нужный период доступа и оплатите:</p>
					</div>
					<?php $counter = 0 ?>
					@foreach($allRates as $key => $sum)
						<?php $counter++ ?>
						<div class="form-group">
							<input type="radio" name="duration" value="{{ $key }}" @if($counter == 1) checked @endif>
							<label class="radio" style="display: inline-block">На {{ $key }} дней - {{ $sum }} сом</label>
						</div>
					@endforeach
				</div>
				@if(Auth::check() && Auth::user()->hasRoleFix('employers') && (Auth::user()->balance >= $rate))
					<div class="modal-footer">
						<button type="submit" style="margin-top: 10px; outline: none" class="btn btn-primary send_response">Оплатить</button>
					</div>
				@else
					<div class="modal-body">
						<div class="form-group">
							@if(Auth::check() && Auth::user()->hasRoleFix('employers') && Auth::user()->balance < $rate)
								<?php $noMoney = true ?>
								<p>Ваш баланс составляет <strong>{{ Auth::user()->balance }} сом</strong>. Чтобы оплатить данную услугу, необходимо иметь минимум <strong>{{ $rate }} сом</strong> на балансе</p>
							@else
								<p>Пожалуйста, авторизуйтесь как работодатель чтобы использовать эту функцию</p>
							@endif
						</div>
					</div>
					<div class="modal-footer">
						@if(isset($noMoney))
							<a href="{{ route('employers.profile.fill_up_balance') }}" class="btn btn-primary send_response " style="background: #ff4800;border-color: #ff4800;">Пополнить баланс</a>
						@endif
						<button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
					</div>
				@endif
			{!! Form::close() !!}
		@endif

		@if($type == 'vacancy' && $action == 'makeInPriority')
			<?php $duration = head(array_keys(array_get($billingVars->{$action}, $type))) ?>
			<?php $allRates = array_get($billingVars->{$action}, $type) ?>
			<?php $rate = array_get($allRates, $duration) ?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Эта функция является платной! За одно поднятие вакансии в начало списка всех вакансий с вашего счёта будет снято {{ $rate }} сом</h4>
			</div>
			@if(Auth::check() && Auth::user()->hasRoleFix('employers') && (Auth::user()->balance >= $rate))
				<div class="modal-body">
					<div class="form-group">
						<p style="margin: 0;">Вы готовы оплатить?</p>
					</div>
				</div>
				{!! Form::open(['route' => ['vacancies.makeInPriority', $item->id, 'type' => $type, 'duration' => $duration], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
					<div class="modal-footer">
						<a href="{{ url('https://employment.kg/images/uploads/up_vacancy1.png') }}" target="_blank">Посмотреть пример</a>
						<button type="submit" style="margin-top: 10px; outline: none" class="btn btn-primary send_response">Оплатить</button>
					</div>
				{!! Form::close() !!}
			@else
				<div class="modal-body">
					<div class="form-group">
						@if(Auth::check() && Auth::user()->balance < $rate)
							<?php $noMoney = true ?>
							<p>Ваш баланс составляет <strong>{{ Auth::user()->balance }} сом</strong>. Чтобы оплатить данную услугу, необходимо иметь <strong>{{ $rate }} сом</strong> на балансе</p>
						@else
							<p>Пожалуйста, авторизуйтесь как работодатель чтобы использовать эту функцию</p>
						@endif
					</div>
				</div>
				<div class="modal-footer">
					@if(isset($noMoney))
						<a href="{{ route('employers.profile.fill_up_balance') }}" class="btn btn-primary send_response " style="background: #ff4800;border-color: #ff4800;">Пополнить баланс</a>
					@endif
					<button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
				</div>
			@endif
		@endif

		@if($type == 'vacancy' && $action == 'makeFixed')
			<?php $duration = head(array_keys(array_get($billingVars->{$action}, $type))) ?>
			<?php $allRates = array_get($billingVars->{$action}, $type) ?>
			<?php $rate = array_get($allRates, $duration) ?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Эта функция является платной! За то, чтобы прикрепить вакансию в списке с вашего счёта будет снято {{ $rate }} сом</h4>
			</div>
			@if(Auth::check() && Auth::user()->hasRoleFix('employers') && (Auth::user()->balance >= $rate) &&
				($item->billingFilter($action)->count() > 0 ? \Carbon\Carbon::now() > $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) : true))
				<div class="modal-body">
					<div class="form-group">
						<p style="margin: 0;">Вы готовы оплатить?</p>
					</div>
				</div>
					{!! Form::open(['route' => ['vacancies.makeFixed', $item->id, 'type' => $type, 'duration' => $duration], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
						<div class="modal-footer">
							<a href="{{ url('https://employment.kg/images/uploads/up_vacancy.png') }}" target="_blank">Посмотреть пример</a>
							<button type="submit" style="margin-top: 0; outline: none" class="btn btn-primary send_response">Оплатить</button>
						</div>
					{!! Form::close() !!}
			@else
				<div class="modal-body">
					<div class="form-group">
						@if($item->billingFilter($action)->count() > 0 && \Carbon\Carbon::now() < $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration))
							<p>Вы уже использовали эту функцию за последнюю неделю. В следующий раз её можно использовать не ранее {{ $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) }} </p>
						@elseif(Auth::check() && Auth::user()->balance < $rate)
							<?php $noMoney = true ?>
							<p>Ваш баланс составляет <strong>{{ Auth::user()->balance }} сом</strong>. Чтобы оплатить данную услугу, необходимо иметь <strong>{{ $rate }} сом</strong> на балансе</p>
						@else
							<p>Пожалуйста, авторизуйтесь как работодатель чтобы использовать эту функцию</p>
						@endif
					</div>
				</div>
				<div class="modal-footer">
					@if(isset($noMoney))
						<a href="{{ route('employers.profile.fill_up_balance') }}" class="btn btn-primary send_response " style="background: #ff4800;border-color: #ff4800;">Пополнить баланс</a>
					@endif
					<button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
				</div>
			@endif
		@endif

		@if($type == 'vacancy' && $action == 'makeHot')
			<?php $duration = head(array_keys(array_get($billingVars->{$action}, $type))) ?>
			<?php $allRates = array_get($billingVars->{$action}, $type) ?>
			<?php $rate = array_get($allRates, $duration) ?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Эта функция является платной! За добавление вакансию в категорию «горячие» на {{ $duration }} дней, с вашего счёта будет снято {{ $rate }} сом</h4>
			</div>
			@if(Auth::check() && Auth::user()->hasRoleFix('employers') && (Auth::user()->balance >= $rate) &&
				($item->billingFilter($action)->count() > 0 ? \Carbon\Carbon::now() > $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) : true))
				<div class="modal-body">
					<div class="form-group">
						<p>Деньги будут сняты с вашего счёта и вакансия добавлена в горячие сразу после активации администратором</p>
					</div>
				</div>
				{!! Form::open(['route' => ['vacancies.makeHot', $item->id, 'type' => $type, 'duration' => $duration], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
					<div class="modal-footer">
						<a href="{{ url('https://employment.kg/images/uploads/hot_vacancies.png') }}" target="_blank">Посмотреть пример</a>
						<button type="submit" style="margin-top: 10px; width: 200px; outline: none;" class="btn btn-primary send_response">Отправить на активацию</button>
					</div>
				{!! Form::close() !!}
			@else
				<div class="modal-body">
					<div class="form-group">
						@if($item->billingFilter($action)->count() > 0 && \Carbon\Carbon::now() < $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration))
							<p>Вы уже использовали эту функцию за последнюю неделю. В следующий раз её можно использовать не ранее {{ $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) }} </p>
						@elseif(Auth::check() && Auth::user()->balance < $rate)
							<?php $noMoney = true ?>
							<p>Ваш баланс составляет <strong>{{ Auth::user()->balance }} сом</strong>. Чтобы оплатить данную услугу, необходимо иметь <strong>{{ $rate }} сом</strong> на балансе</p>
						@else
							<p>Пожалуйста, авторизуйтесь как работодатель чтобы использовать эту функцию</p>
						@endif
					</div>
				</div>
				<div class="modal-footer">
					@if(isset($noMoney))
						<a href="{{ route('employers.profile.fill_up_balance') }}" class="btn btn-primary send_response " style="background: #ff4800;border-color: #ff4800;">Пополнить баланс</a>
					@endif
					<button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
				</div>
			@endif
		@endif

		@if($type == 'training' && $action == 'publish')
			<?php $duration = head(array_keys(array_get($billingVars->{$action}, $type))) ?>
			<?php $allRates = array_get($billingVars->{$action}, $type) ?>
			<?php $rate = array_get($allRates, $duration) ?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Стоимость размещения курса/тренинга в разделе «Обучение» составляет {{ $rate }} сом/месяц. С Вашего счёта будет снято {{ $rate }} сом</h4>
			</div>
			@if(Auth::check() && Auth::user()->hasRoleFix('employers') && (Auth::user()->balance >= $rate) && (!$item->moderated ||
				($item->billingFilter($action)->count() > 0 ? \Carbon\Carbon::now() > $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) : true)))
				<div class="modal-body">
					<div class="form-group">
						<p>Ваш курс/тренинг будет размещен сразу же после оплаты</p>
					</div>
				</div>
				{!! Form::open(['route' => ['trainings.publish', $item->id, 'type' => $type, 'duration' => $duration], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
					<div class="modal-footer">
						<button type="submit" style="margin-top: 0; outline: none" class="btn btn-primary send_response">Оплатить</button>
					</div>
				{!! Form::close() !!}
			@else
				<div class="modal-body">
					<div class="form-group">
						@if($item->billingFilter($action)->count() > 0 && \Carbon\Carbon::now() < $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration))
							<p>Вы уже использовали эту функцию за последний месяц. В следующий раз её можно использовать не ранее {{ $item->billingFilter($action)->sortByDesc('updated_at')->first()->updated_at->addDays($duration) }} </p>
						@elseif(Auth::user()->balance < $rate)
							<?php $noMoney = true ?>
							<p>Ваш баланс составляет <strong>{{ Auth::user()->balance }} сом</strong>. Чтобы оплатить данную услугу, необходимо иметь <strong>{{ $rate }} сом</strong> на балансе</p>
						@else
							<p>Пожалуйста, авторизуйтесь как соискатель чтобы использовать эту функцию</p>
						@endif
					</div>
				</div>
				<div class="modal-footer">
					@if(isset($noMoney))
						<a href="{{ route('employers.profile.fill_up_balance') }}" class="btn btn-primary send_response " style="background: #ff4800;border-color: #ff4800;">Пополнить баланс</a>
					@endif
					<button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
				</div>
			@endif
		@endif

		@if($action == 'delete')

			<?php
				$type == 'vacancy' ? $route = 'vacancie' : $route = $type;
				if($parameters == 'favourites')
					$text = [
							'resume' => 'запись о сохранённом резюме',
							'vacancy' => 'запись о сохранённой вакансии',
					];
				elseif($parameters == 'responses')
					$text = [
							'resume' => 'запись о предложении вакансии',
							'vacancy' => 'отклик на вакансию',
					];
				else
					$text = [
							'resume' => 'резюме',
							'vacancy' => 'вакансию',
					]
			?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Вы уверены, что хотите удалить {{ array_get($text, $type) }} безвозвратно?</h4>
			</div>
			@if($parameters == 'favourites')
				<div class="modal-footer">
					<a class="btn btn-primary send_response" href="{{ route($route . 's.toFavourites', [$item->id, 'action' => 'remove']) }}">Удалить</a>
				</div>
			@elseif($parameters == 'responses')
				{!! Form::open(['route' => [$route . 's.response.destroy', $type . '_id' => $item->id , $forType . '_id' => $forItem->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary send_response">Удалить</button>
					</div>
				{!! Form::close() !!}
			@else
				{!! Form::open(['route' => [$route . 's.delete', $item->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'response_form']) !!}
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary send_response">Удалить</button>
					</div>
				{!! Form::close() !!}
			@endif
		@endif
	</div>
</div>