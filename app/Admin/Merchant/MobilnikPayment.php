<?php

Admin::model(\App\Models\Merchant\MobilnikPayment::class)->title('Поступления с терминалов мобильника')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('created_at', 'desc');
    });
    $display->with(
        'user'
    );
    $display->filters([

    ]);
    $display->columns([
        Column::string('id')->label('#'),
        Column::string('query_id')->label('Номер транзакции'),
        Column::string('billing_account')->label('Лицевой счет'),
        Column::string('user.name')->label('Пользователь'),
        Column::string('amount')->label('Сумма'),
        Column::string('text')->label('Дополнительно'),
        Column::datetime('created_at')->label('Дата'),
    ]);
    return $display;
})->createAndEdit(null)->delete(null);