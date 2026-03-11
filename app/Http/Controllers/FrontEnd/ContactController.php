<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Requests\MailFromUserRequest;
use App\Models\BasicSettings\Basic;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Throwable;
use Illuminate\Mail\Message;
use Mews\Purifier\Facades\Purifier;

class ContactController extends Controller
{
	public function contact()
	{
		$misc = new MiscellaneousController();

		$language = $misc->getLanguage();

		$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

		$queryResult['pageHeading'] = $misc->getPageHeading($language);

		$queryResult['breadcrumb'] = $misc->getBreadcrumb();

		$queryResult['info'] = Basic::query()->select('email_address', 'contact_number', 'address', 'google_recaptcha_status', 'latitude', 'longitude')->first();

		return view('frontend.contact', $queryResult);
	}

	public function sendMail(MailFromUserRequest $request)
	{
		// get the website title & mail's smtp information from db
		$info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'to_mail')
			->first();

		$toMail = Basic::query()->pluck('to_mail')->first();
		$from = $request->email;
		$name = $request->name;
		$to = $toMail;
		$subject = $request->subject;

		$messageText = '<p>A new quote request has been sent.<br/><strong>Client Name: </strong>' . $name . '<br/><strong>Client Mail: </strong>' . $request->email . '</p><p>Message : ' . nl2br(Purifier::clean($request->message, 'youtube')) . '</p>';

		if ($info->smtp_status == 1) {
			$smtp = [
				'transport' => 'smtp',
				'host' => $info->smtp_host,
				'port' => $info->smtp_port,
				'encryption' => $info->encryption,
				'username' => $info->smtp_username,
				'password' => $info->smtp_password,
				'timeout' => null,
				'auth_mode' => null,
			];
			Config::set('mail.mailers.smtp', $smtp);
		}


		// add other informations and send the mail
		try {
			Mail::send([], [], function (Message $message) use ($to, $from, $name, $subject, $messageText) {
				$fromMail = $from;
				$fromName = $name;
				$message->to($to)
					->subject($subject)
					->from($fromMail, $fromName)
					->replyTo($fromMail, $fromName)
					->html($messageText, 'text/html');
			});
			Session::flash('success', 'Mail has been sent.');
		} catch (Throwable $e) {
			Session::flash('error', 'Mail could not be sent!');;
		}
		return redirect()->back();
	}
}
