<?php

namespace App\Jobs;

use App\Http\Helpers\MegaMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $seller;
    public $bs;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($seller, $bs)
    {
        $this->seller = $seller;
        $this->bs = $bs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail = new MegaMailer();
        $data = [
            'toMail' => $this->seller->email,
            'templateType' => 'membership_expired',
            'username' => $this->seller->username,
            'login_link' => '<a href="' . route('seller.login') . '">Login</a>',
            'website_title' => $this->bs->website_title,
        ];
        $mail->mailFromAdmin($data);
    }
}
