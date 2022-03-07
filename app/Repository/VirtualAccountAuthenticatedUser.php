<?php 

namespace App\Repository;

use App\Models\Common\Common_account;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

final class VirtualAccountAuthenticatedUser implements AuthenticatedUser
{
    public function __construct(private Request $request)
    {
    }

    /**
     * @return User
     * @throws AuthorizationException
     */
    public function user()
    {
        $user = $this->request->user();

        return $user;
    }

    /**
     * @return int
     * @throws AuthorizationException
     */
    public function account()
    {
        $id = $this->request->user()?->id;
        $account=Common_account::where('UserId',$id)->get()->toArray();

        return $account;
    }

    public function virtualAccount()
    {
        $id = $this->request->user()?->id;
        if($account=Common_account::where([['UserId',$id],['AccountId',$this->request->header('Account')]])->first()){
            return $account;
        }
        return Common_account::first();
    }
}