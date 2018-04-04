<div class="category">
    <h2>Вакансии по отраслям</h2>
    <ul>
        @foreach($fields as $field)
            <li><a href="{{ route('vacancies.index', ['scope_id' => $field->id]) }}"><span class="pull-right">{{ $field->vacancies->where('moderated', 1)->count() }}</span>{{ $field->title }}</a></li>
        @endforeach
    </ul>
</div>