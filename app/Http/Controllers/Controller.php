<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function permissionAuthorization($permissions)
    {
        foreach ($permissions as $action => $permission) {
            $this->middleware("permission:$permission")->only($action);
        }
    }
}
