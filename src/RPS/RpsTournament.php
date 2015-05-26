<?php
namespace Appeldorff\RPS;

class RpsTournament implements RpsTournamentInterface
{
    const CANCELLED = 'Tournament Cancelled';
    const NOPLAYERS = 'There are no players in the tournament';
    
    private $players;
    private $isCancelled;
    
    public function __construct($input = array()) {
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
    public function addPlayer($name, $throw) {
        if(stripos('RPS',$throw) === false) {
            $this->isCancelled = true;
        }
        $this->players[$name] = strtoupper($throw);
    }

    /**
     * Gets the winner of the tournament
     *
     * @return string
     */
    public function getWinner() {
        if($this->isCancelled)
            return self::CANCELLED;
        if(count($this->players) == 0)
            return self::NOPLAYERS;
        $currentRound = array_keys($this->players);
        $nextRound = array();
        $currentPlayers = null;
        while ((count($currentRound) + count($nextRound)) > 1 ) {
            if(count($currentRound) == 0) {
                $currentRound = $nextRound;
                $nextRound = array();
            }
            elseif(count($currentRound)==1) {
                $nextRound[] = array_shift($currentRound);
                $currentRound = $nextRound;
                $nextRound = array();
            }
            else {
                $nextRound[] = $this->playMatch(array_shift($currentRound),array_shift($currentRound));
            }
            
        }
        return $nextRound[0];
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
        switch($this->players[$player1])
        {
            case RPSTournamentInterface::ROCK:
                if($opponentThrow == RPSTournamentInterface::PAPER)
                    return $player2;
                else
                    return $player1;
                
            case RPSTournamentInterface::PAPER:
                if($opponentThrow == RPSTournamentInterface::SCISSOR)
                    return $player2;
                else
                    return $player1;

            case RPSTournamentInterface::SCISSOR:
                if($opponentThrow == RPSTournamentInterface::ROCK)
                    return $player2;
                else
                    return $player1;

            default:
                return $player1;
        }

    }
}