<?php

namespace App\Mail\auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifiedEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email,string $name)
    {
        $this->email=$email;
        $this->name=$name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email,$this->name)
                    ->subject("アカウント登録完了")
                    ->view('email/auth/VerifiedEmail')
                    ->with([
                        "email"=>$this->email,
                        "name"=>$this->name
                    ]);
    }
}
