<?php

namespace App\Http\Controllers;

use App\Services\PlayerService;

class PlayerController extends Controller
{
    /**
     * @var PlayerService|null
     */
    private $playerService = null;

    /**
     * PlayerController constructor.
     *
     * @param PlayerService $playerService
     */
    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->playerService->show($id);
        $code   = $result->isStatusSuccess() ? 200 : 404;

        return response()->json($result->toArray(), $code);
    }
}
