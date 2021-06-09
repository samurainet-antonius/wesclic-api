<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Job
{
    // const label
    const NAMA = 'nama';
    const EMAIL = 'email';
    const SUBJECT = 'subject';
    const VIEW = 'view';

    // protected variable
    protected $data;

    public function __construct($data){
      $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = $this->data;
        // send mail
        Mail::send(['html' => $request[self::VIEW]], $request, function($message) use($request) {
          $message->to($request[self::EMAIL], $request[self::NAMA])->subject($request[self::SUBJECT]);
        });
    }
}
