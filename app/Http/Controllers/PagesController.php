<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Company;
use App\Models\Mailing;
use App\Models\MailingEmail;
use App\Models\Page;
use App\Models\Training;
use App\Models\Vacancies\Vacancy;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;
use Swift_Mailer;
use Swift_SmtpTransport;

class PagesController extends BaseController
{
    /**
     * Generates home page
     */
    public function getHome()
    {
        $vacancies = Vacancy::moderatedHot()->get();
        $companies = Company::activated()->get();
        $articles = Article::active()->orderBy('created_at', 'desc')->take(8)->get();
        $trainings = Training::moderated()->orderBy('updated_at', 'desc')->take(4)->get();

        return view('home', compact('vacancies', 'fields', 'companies', 'articles', 'trainings'));
    }

    /**
     * Generates articles index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticles()
    {
        $articles = Article::active()->orderBy('created_at', 'desc')->paginate(15);
        $companies = Company::activated()->get();

        return view('articles.index', compact('articles', 'fields', 'companies'));
    }

    /**
     * Generates articles show page
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticle($id)
    {
        $article = Article::active()->findOrFail($id);
        $companies = Company::activated()->get();
        return view('articles.show', compact('article', 'companies'));
    }

    public function getPage($slug)
    {
        $pages = Page::findBySlugOrFail($slug);

        return view('pages', compact('pages'));
    }

    public function addToMailings(Request $request)
    {
        $this->validate($request, [
            'mailing_email' => 'required|email|max:255',
        ]);

        $mailing_email = MailingEmail::where('email', $request->get('mailing_email'))->first();

        if (!$mailing_email && !\Auth::check())
            MailingEmail::create(['email' => $request->get('mailing_email'), 'subscribed' => true]);
        elseif (!$mailing_email && \Auth::check())
            MailingEmail::create(['user_id' => \Auth::id(), 'email' => $request->get('mailing_email'), 'subscribed' => true]);
        elseif ($mailing_email && $mailing_email->subscribed == false)
            $mailing_email->update(['subscribed' => true]);
        elseif ($mailing_email && $mailing_email->subscribed == true)
            return redirect()->back()->with('error', 'Вы уже подписаны на рассылку!');

        return redirect()->back()->with('success', 'Вы успешно подписались на рассылку!');
    }

    public function mailing(Request $request)
    {
        $mailing = Mailing::findOrFail($request->get('mailing_id'));

        // Backup your default mailer
//        $backup = Mail::getSwiftMailer();
        // Setup your gmail mailer
//        $transport = Swift_SmtpTransport::newInstance('smtp.elasticemail.com', 2525);
//        $transport->setUsername('protected.for@gmail.com');
//        $transport->setPassword('8561a73e-1fe5-46c7-8e0a-fdc113cc2047');
        // Any other mailer configuration stuff needed...
//        $elastic_mail = new Swift_Mailer($transport);

        // Set the mailer as gmail
//        Mail::setSwiftMailer($elastic_mail);
        $emails = MailingEmail::whereIn('email', ['adibahon.frn@gmail.com', 'protected.for@gmail.com', 'info@employment.kg'])->get(['email']);
        $users = User::whereIn('email', $emails)->whereHas('roles', function ($q) {
//            $q->where('name', 'employers');
        })->get();
//        dd($users);
//        dd($users);
        foreach ($users->chunk(2000) as $chunk) {

            foreach ($chunk as $user) {

                // Send your message
                Mail::queue('emails.newsletter', ['data' => $mailing->description], function ($msg) use ($mailing, $user) {
                    $msg->to($user->email);
                    $msg->subject($mailing->title);
                });
            }

            $mailing->update(['sending_date' => Carbon::now()]);
            foreach ($chunk as $mailing_email) {
                $mailing_email->update(['last_title' => $mailing->id]);
            }
        }
// Restore your original mailer
//        Mail::setSwiftMailer($backup);
        return redirect()->back()->with('success', 'Рассылка успешно отправлена!');
    }

}
