<?php

namespace App\Services;

use App\Repositories\PlayerRepository;
use App\Http\Resources\Player as PlayerResource;
use App\Response\Result;

class PlayerService
{
    /** @var PlayerRepository|null */
    private $playerRepository = null;

    /** @var Result */
    private $result = null;

    /**
     * PlayerService constructor.
     *
     * @param PlayerRepository $playerRepository
     * @param Result           $result
     */
    public function __construct(
        PlayerRepository $playerRepository,
        Result $result
    ) {
        $this->playerRepository = $playerRepository;
        $this->result           = $result;
    }

    /**
     * @param $id
     *
     * @return Result
     */
    public function show($id)
    {
        $player = $this->playerRepository->show($id);
        if (empty($player)) {
            return $this->result
                ->setStatus(false)
                ->addMessageRecord(__('api.player_not_found', ['id' => $id]));
        }

        $data = new PlayerResource($player);

        return $this->result
            ->setStatus(true)
            ->addMessageRecord(__('api.player_found', ['id' => $id]))
            ->setData($data);
    }
}
