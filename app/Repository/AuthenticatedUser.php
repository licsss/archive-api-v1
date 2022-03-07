<?php

namespace App\Repository;

use App\Models\User;

interface AuthenticatedUser
{
    public function user();

    public function account();

    public function virtualAccount();

}