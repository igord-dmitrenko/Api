<?php

namespace App\Http\Controllers;

use App\Services\MatchService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * @var MatchService
     */
    private $matchService = null;

    /**
     * MatchController constructor.
     *
     * @param MatchService $matchService
     */
    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->query();

        $result = null;
        if (empty($query)) {
            $result = $this->matchService->showAll();
        }

        if (isset($query['player']) && (count($query) == 1)) {
            $result = $this->matchService->showAllByPlayerId($query['player']);
        }

        if ((count($query) == 2) && (isset($query['startedFrom'])) && (isset($query['startedTo']))) {
            $result = $this->matchService->showAllByStartTimestampDiapason($query['startedFrom'], $query['startedTo']);
        }

        if (!is_null($result)) {
            $code = $result->isStatusSuccess() ? 200 : 404;

            return response()->json($result->toArray(), $code);
        }

        return response()->json([
            'success'  => false,
            'messages' => [__('api.not_right_query', ['params' => $request->getQueryString()])]
        ], 422);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->matchService->show($id);
        $code   = $result->isStatusSuccess() ? 200 : 404;

        return response()->json($result->toArray(), $code);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = $this->matchService->store($request->all());
        if (!$result->isStatusSuccess()) {
            return response()->json($result->toArray(), 400);
        }

        $location = url()->current() . '/' . $result->getData()->id;

        return response()
            ->json($result->toArray(), 201)
            ->header('Location', $location);
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $result = $this->matchService->update($id, $request->all());
        $code   = $result->isStatusSuccess() ? 200 : 400;

        return response()->json($result->toArray(), $code);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->matchService->delete($id);
        if ($result->isStatusSuccess()) {
            return response()->json(null, 204);
        }

        return response()->json($result->toArray(), 404);
    }
}
