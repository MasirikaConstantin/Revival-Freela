<?php

namespace App\Jobs;

use App\Http\Helpers\MegaMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminderMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $seller;
    public $bs;
    public $expire_date;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($seller, $bs, $expire_date)
    {
        $this->seller = $seller;
        $this->bs = $bs;
        $this->expire_date = $expire_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailer = new MegaMailer();

        $data = [
            'toMail' => $this->seller->email,
            'toName' => $this->seller->username,
            'username' => $this->seller->username,
            'last_day_of_membership' => $this->expire_date,
            'login_link' => '<a href="' . route('seller.login') . '">Login</a>',
            'website_title' => $this->bs->website_title,
            'templateType' => 'membership_expiry_reminder'
        ];

        $mailer->mailFromAdmin($data);
    }
}
