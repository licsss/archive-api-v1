<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $email;
    private $ResetId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name,string $email,string $ResetId)
    {
        $this->name=$name;
        $this->email=$email;
        $this->ResetId=$ResetId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email,$this->name)
                    ->subject("パスワードリセット申請")
                    ->view('email/auth/PasswordReset')
                    ->with([
                        "email"=>$this->email,
                        "name"=>$this->name,
                        "ResetId"=>$this->ResetId
                    ]);
    }
}
