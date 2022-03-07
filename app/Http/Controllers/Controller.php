<?php

namespace App\Http\Controllers;

use App\Repository\AuthenticatedUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $user;
    public $account;
    public $authenticated;
    public $virtualAccount;
    public function __construct(private AuthenticatedUser $authenticatedUser)
    {
        $this->authenticated=$authenticatedUser;
        $this->user=$authenticatedUser->user()?->toArray();
        $this->account=$authenticatedUser->account();
        $this->virtualAccount=$authenticatedUser->virtualAccount();
    }
}
