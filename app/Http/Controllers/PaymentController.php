<?php

namespace App\Http\Controllers;

use App\Events\SendNotificationEvent;
use App\Http\Requests;
use App\Models\Merchant\MobilnikPayment;
use App\Models\Payment;
use App\Models\Widget;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nathanmac\Utilities\Parser\Parser;
use Event;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->getClientIp()) {
            case '213.145.147.131':
                $allow = true;
                break;
            case '127.0.0.1':
                $allow = true;
                break;
            default:
                $allow = false;
        };
        if (!$allow)
            return response('', 403);
        $parser = new Parser();
        $parsed = $parser->xml($request->getContent());
//        dd($parsed);
        $personal_bill = $parsed['BODY']['@attributes']['PARAM1'];
        $qid = $parsed['HEAD']['@attributes']['QID'];
        $op = array_get($parsed['HEAD']['@attributes'], 'OP');
        $qm = array_get($parsed['HEAD']['@attributes'], 'QM', 'TEST');
        $sum = array_get($parsed['BODY']['@attributes'], 'SUM');

        $user = User::wherePersonalBill($personal_bill)->first();
        if ($op == 'QE11') {
            if ($user)
                return response('<?xml version="1.0" encoding="utf-8"?><XML><HEAD DTS="' . Carbon::now() . '" OP="QE11" QID="' . $qid . '" SID="OSPP.KG" /><BODY STATUS="200" MSG="' . $user->name . '" /></XML>', 200, [
                    'Content-Type' => 'application/xml',
                ]);
            else
                return response('<?xml version="1.0" encoding="utf-8"?><XML><HEAD DTS="' . Carbon::now() . '" OP="QE11" QID="' . $qid . '" SID="OSPP.KG" /><BODY STATUS="420" MSG="Абонент не найден" /></XML>', 200, [
                    'Content-Type' => 'application/xml',
                ]);
        } elseif ($op == 'QE10') {
            $user = User::wherePersonalBill($personal_bill)->first();
            if (!$user) {
                return response('<?xml version="1.0" encoding="utf-8"?><XML><HEAD DTS="' . Carbon::now() . '" OP="QE10" QID="' . $qid . '" SID="OSPP.KG" /><BODY STATUS="420" ERR_MSG="Лицевой счет не найден" /></XML>', 200, [
                    'Content-Type' => 'application/xml',
                ]);
            }
            $query = MobilnikPayment::whereQueryId($qid)->first();
            if (!$query) {
                MobilnikPayment::create(['query_id' => $qid, 'amount' => $sum, 'text' => 'Платеж проведен', 'user_id' => $user->id, 'billing_account' => $user->personal_bill]);
                $user->balance += intval($sum);
                $user->save();

	            $request->merge([
		            'type' => 'fillUpBalance',
		            'recipientName' => $user->name,
		            'recipientEmail' => $user->email,
		            'user_bill' => $user->personal_bill,
		            'sum' => $sum,
		            'user_balance' => $user->balance,
	            ]);

	            Event::fire(new SendNotificationEvent($request->all()));
            }
            return response('<?xml version="1.0" encoding="utf-8"?><XML><HEAD OP="QE10" SID="OSPP.KG" DTS="' . Carbon::now() . '" QID="' . $qid . '" QM="' . $qm . '" /><BODY STATUS="250" MSG="Платеж проведен" /></XML>', 200, [
                'Content-Type' => 'application/xml'
            ]);
        }
        return response(['Access denied!'], 403);
    }

	public function store(Request $request) {
		$this->validate($request, [
			'name' => 'required|max:255',
			'post' => 'required|max:255',
			'INN' => 'required|max:255',
			'email' => 'required|max:255|email',
			'sum' => 'required|max:255',
		]);

		$request->merge([
			'user_id' => Auth::id(),
		]);
		$payment = Payment::create($request->all());

		$request->merge([
			    'type' => 'payment',
			    'recipientEmail' => Widget::whereKey('admin_email')->first()->value,
			    'info' => $payment,
		    ]);

		    Event::fire(new SendNotificationEvent($request->all()));

		return redirect()->route('employers.profile.fill_up_balance')->with('success', 'Информация о платеже отправлена администратору!');
	}
}
