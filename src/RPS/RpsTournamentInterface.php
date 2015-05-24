<?php
namespace Appeldorff\RPS;

interface RpsTournamentInterface
{
    const ROCK = 'R';
    const PAPER = 'P';
    const SCISSOR = 'S';
    /**
     * @param string $name
     * @param string $throw
     * @return void
     */
    public function addPlayer($name, $throw);
    /**
     * Gets the winner of the tournament
     *
     * @return string
     */
    public function getWinner();
}