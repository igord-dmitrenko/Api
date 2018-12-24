<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function routeNotFound()
    {
        return response()
            ->json([
                'success'  => false,
                'messages' => __('api.route_not_found')
            ], 404);
    }
}
