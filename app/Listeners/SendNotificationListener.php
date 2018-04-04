<?php

namespace App\Listeners;

use App\Events\SendNotificationEvent;
use App\Events\UserRegisteredEvent;
use App\Models\Widget;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendNotificationListener
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SendNotificationEvent $event
     */
    public function handle(SendNotificationEvent $event)
    {
	    $admin_email = Widget::whereKey('admin_email')->first()->value;
	    $data = array_get($event->message, 'data');

	    Mail::queue('emails.sendNotification', ['notification' => $event->message], function ($msg) use ($event, $admin_email, $data) {
		    $msg->to($event->message['recipientEmail']);
		    if($event->message['type'] == 'vacancy') {
		        $msg->subject('У вас новый отклик на вакансию "' . $event->message['title'] . '" от ' . $event->message['senderName']);
			    if(isset($event->message['form_from_file']) && $event->message['form_from_file'])
			        $msg->attach(public_path(config('admin.imagesUploadDirectory') . '/attached/' . $event->message['form_from_file']));
			    if(isset($event->message['file1']) && $event->message['file1'])
				    $msg->attach(public_path(config('admin.imagesUploadDirectory') . '/attached/' . $event->message['file1']));
			    if(isset($event->message['file2']) && $event->message['file2'])
				    $msg->attach(public_path(config('admin.imagesUploadDirectory') . '/attached/' . $event->message['file2']));
			    if(isset($event->message['file3']) && $event->message['file3'])
				    $msg->attach(public_path(config('admin.imagesUploadDirectory') . '/attached/' . $event->message['file3']));
		    }

		    elseif($event->message['type'] == 'informWorker') {
			    $msg->subject("Ваш отклик на вакансию «{$event->message['title']}» компании «{$data['vac']->user->company->title}» отправлен");}

		    elseif($event->message['type'] == 'vacancy_form_from_file') {
			    $msg->subject('У вас новый отклик на вакансию  "' . $event->message['title'] . '" от ' . $event->message['senderName']);
			    $msg->attach(public_path(config('admin.imagesUploadDirectory') . '/attached/' . $event->message['form_from_file']));}

		    elseif($event->message['type'] == 'resume') {
			    $msg->subject("«{$event->message['senderName']}» предлагает Вам работу «{$data['vac']->position}»");}

		    elseif($event->message['type'] == 'informEmployer') {
			    $msg->subject('Ваше предложение по вакансии "' . $data['vac']->position . '" отправлено соискателю ' . $event->message['recipientName']);}

		    elseif($event->message['type'] == 'training') {
			    $msg->cc($admin_email);
		        $msg->subject('У вас новая заявка на курс/тренинг "' . $event->message['title'] . '"');}

		    elseif($event->message['type'] == 'informApplicant') {
			    $msg->subject('Ваша заявка на курс/тренинг "' . $event->message['title'] . '" принята. Скоро с вами свяжутся');}

	        elseif($event->message['type'] == 'fillUpBalance')
			    $msg->subject('Баланс Вашего лицевого счета пополнен');

		    elseif($event->message['type'] == 'greetings')
			    $msg->subject('Теперь вы можете пользоваться всеми возможностями  employment.kg');

		    elseif($event->message['type'] == 'payment')
			    $msg->subject('Получена заявка на пополнение баланса в размере ' . $event->message['info']->sum . ' сом');
	    });
    }
}
