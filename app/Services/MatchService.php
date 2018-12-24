<?php

namespace App\Services;

use App\Http\Validation\Match\Store;
use App\Helpers\Rating\Elo as EloRating;
use App\Http\Validation\Match\Update;
use App\Repositories\MatchPlayerRepository;
use App\Repositories\MatchRepository;
use App\Repositories\PlayerRepository;
use App\Response\Result;
use App\Http\Resources\Match as MatchResource;

class MatchService
{
    /**
     * @var MatchRepository|null
     */
    private $matchRepository = null;

    /**
     * @var PlayerRepository|null
     */
    private $playerRepository = null;

    /**
     * @var MatchPlayerRepository|null
     */
    private $matchPlayerRepository = null;

    /**
     * @var Result|null
     */
    private $result = null;

    /**
     * @var Store|null
     */
    private $storeValidation = null;

    /**
     * @var Update|null
     */
    private $updateValidation = null;

    /**
     * @var EloRating|null
     */
    private $eloRating = null;

    /**
     * MatchService constructor.
     *
     * @param MatchRepository       $matchRepository
     * @param PlayerRepository      $playerRepository
     * @param MatchPlayerRepository $matchPlayerRepository
     * @param Result                $result
     * @param Store                 $storeValidation
     * @param Update                $updateValidation
     * @param EloRating             $eloRating
     */
    public function __construct(
        MatchRepository $matchRepository,
        PlayerRepository $playerRepository,
        MatchPlayerRepository $matchPlayerRepository,
        Result $result,
        Store $storeValidation,
        Update $updateValidation,
        EloRating $eloRating
    ) {
        $this->matchRepository       = $matchRepository;
        $this->playerRepository      = $playerRepository;
        $this->matchPlayerRepository = $matchPlayerRepository;
        $this->result                = $result;
        $this->storeValidation       = $storeValidation;
        $this->updateValidation      = $updateValidation;
        $this->eloRating             = $eloRating;
    }

    /**
     * @return Result
     */
    public function showAll()
    {
        $matches = $this->matchRepository->all();
        if (empty($matches)) {
            return $this->result
                ->setStatus(true)
                ->addMessageRecord(__('api.matches_not_found'));
        }

        $data = MatchResource::collection($matches);

        return $this->result
            ->setStatus(true)
            ->addMessageRecord(__('api.matches_all'))
            ->setData($data);
    }

    /**
     * @param $id
     *
     * @return Result
     */
    public function showAllByPlayerId($id)
    {
        $matchesIds = $this->matchPlayerRepository->getMatchesIdsPlayerId($id);
        if (empty($matchesIds)) {
            return $this->result
                ->setStatus(false)
                ->addMessageRecord(__('api.matches_not_found_player', ['id' => $id]));
        }

        $matches = $this->matchRepository->show($matchesIds);
        $data    = MatchResource::collection($matches);

        return $this->result
            ->setStatus(true)
            ->addMessageRecord(__('api.matches_found_player', ['id' => $id]))
            ->setData($data);
    }

    /**
     * @param integer $from
     * @param integer $to
     *
     * @return Result
     */
    public function showAllByStartTimestampDiapason($from, $to)
    {
        $matches = $this->matchRepository->getAllByStartTimestampDiapason($from, $to);
        if (empty($matches)) {
            return $this->result
                ->setStatus(false)
                ->addMessageRecord(__('api.matches_not_found_timestamp', [
                    'from' => $from,
                    'to'   => $to
                ]));
        }

        $data = MatchResource::collection($matches);

        return $this->result
            ->setStatus(true)
            ->addMessageRecord(__('api.matches_found_timestamp', [
                'from' => $from,
                'to'   => $to
            ]))
            ->setData($data);
    }

    /**
     * @param integer $id
     *
     * @return Result
     */
    public function show($id)
    {
        $match = $this->matchRepository->show($id);
        if (empty($match)) {
            return $this->result
                ->setStatus(false)
                ->addMessageRecord(__('api.match_not_found', ['id' => $id]));
        }

        $data = new MatchResource($match);

        return $this->result
            ->setStatus(true)
            ->addMessageRecord(__('api.match_found', ['id' => $id]))
            ->setData($data);
    }

    /**
     * @param array $data
     *
     * @return Result|null
     */
    public function store(array $data)
    {
        $this->storeValidation->validate($data);
        if ($this->storeValidation->hasErrors()) {
            return $this->result
                ->setStatus(false)
                ->setMessages($this->storeValidation->getErrorMessages());
        }

        $players = [];
        foreach ($data['players'] as $id) {
            $player = $this->playerRepository->show($id);
            if (empty($player)) {
                $player = $this->playerRepository->create([
                    'id'     => $id,
                    'rating' => EloRating::DEFAULT_VALUE
                ]);
            }

            $players[] = [
                'id'           => $player->id,
                'rating'       => $player->rating,
                'matchesCount' => $this->matchPlayerRepository->getCountByPlayerId($player->id)
            ];
        }

        unset($data['players']);
        $match = $this->matchRepository->create($data);

        $players = $this->eloRating->calculateRating($players, $data['winner_id']);
        foreach ($players as $player) {
            unset($player['matchesCount']);
            $this->playerRepository->update($player, $player['id']);
            $this->matchPlayerRepository->create([
                'match_id'  => $match->id,
                'player_id' => $player['id']
            ]);
        }

        $data = new MatchResource($match);

        return $this->result
            ->setStatus(true)
            ->addMessageRecord(__('api.match_added'))
            ->setData($data);
    }

    /**
     * @param       $id
     * @param array $data
     *
     * @return Result
     */
    public function update($id, array $data)
    {
        $match = $this->matchRepository->show($id);
        if (empty($match)) {
            return $this->result
                ->setStatus(false)
                ->addMessageRecord(__('api.match_not_found', ['id' => $id]));
        }

        $this->updateValidation->validate($data);
        if ($this->updateValidation->hasErrors()) {
            return $this->result
                ->setStatus(false)
                ->setMessages($this->updateValidation->getErrorMessages());
        }

        if (isset($data['players'])) {
            $matchPlayers = $this->matchPlayerRepository->getPlayersByMatchId($id);
            foreach ($matchPlayers as $matchPlayer) {
                $this->matchPlayerRepository->delete($matchPlayer->id);
            }

            foreach ($data['players'] as $playerId) {
                $this->matchPlayerRepository->create([
                    'match_id'  => $id,
                    'player_id' => $playerId
                ]);
            }

            unset($data['players']);
        }

        $this->matchRepository->update($data, $id);
        $data = new MatchResource($this->matchRepository->show($id));

        return $this->result
            ->setStatus(true)
            ->addMessageRecord(__('api.match_updated', ['id' => $id]))
            ->setData($data);
    }

    /**
     * @param $id
     *
     * @return Result
     */
    public function delete($id)
    {
        if ($this->matchRepository->delete($id)) {
            return $this->result->setStatus(true);
        }

        return $this->result
            ->setStatus(false)
            ->addMessageRecord(__('api.match_not_found', ['id' => $id]));
    }
}
