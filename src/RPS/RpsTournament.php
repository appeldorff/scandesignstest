<?php
namespace Appeldorff\RPS;

class RpsTournament implements RpsTournamentInterface
{
    const CANCELLED = 'Tournament Cancelled';
    const NOPLAYERS = 'There are no players in the tournament';

    private $players;
    private $is_cancelled;

    public function __construct($input = array())
    {
        $players = array();
        foreach ($input as $player => $throw) {
            $this->addPlayer($player,$throw);
        }
    }

    /**
     * Adds a player to the tournament
     * @param string $name The name of the playMatch
     * @param string $throw The player's throw
     *
     * @return void
     */
    public function addPlayer($name, $throw)
    {
        if(stripos('RPS',$throw) === false) {
            $this->is_cancelled = true;
        }
        $this->players[$name] = strtoupper($throw);
    }

    /**
     * Gets the winner of the tournament
     *
     * @return string
     */
    public function getWinner()
    {
        if($this->is_cancelled) {
            return self::CANCELLED;
        }
        if(count($this->players) == 0) {
            return self::NOPLAYERS;
        }
        $current_round = array_keys($this->players);
        $next_round = array();
        while ((count($current_round) + count($next_round)) > 1 ) {
            if(count($current_round) == 0) {
                $current_round = $next_round;
                $next_round = array();
            } elseif(count($current_round) == 1) {
                $next_round[] = array_shift($current_round);
                $current_round = $next_round;
                $next_round = array();
            } else {
                $next_round[] = $this->playMatch(
                    array_shift($current_round),
                    array_shift($current_round)
                );
            }
        }
        return $next_round[0];
    }

    /**
     * Gets the winner of a match
     *
     * @param string $player1 Name of first contestant
     * @param string $player2 Name of second contestant
     * @return string
     */
    public function playMatch($player1, $player2)
    {
        $opponentThrow = $this->players[$player2];
        switch($this->players[$player1]) {
            case RPSTournamentInterface::ROCK:
                if($opponentThrow == RPSTournamentInterface::PAPER) {
                    return $player2;
                } else {
                    return $player1;
                }
            case RPSTournamentInterface::PAPER:
                if($opponentThrow == RPSTournamentInterface::SCISSOR) {
                    return $player2;
                } else {
                    return $player1;
                }
            case RPSTournamentInterface::SCISSOR:
                if($opponentThrow == RPSTournamentInterface::ROCK) {
                    return $player2;
                } else {
                    return $player1;
                }
            default:
                //This case should never be reached. This would indicate invalid values
                return $player1;
        }
    }
}