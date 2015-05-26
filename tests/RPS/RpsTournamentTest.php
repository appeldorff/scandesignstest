<?php
namespace Appeldorff\Test\RPS;

use \Appeldorff\RPS\RpsTournament;
use \PHPUnit_Framework_TestCase as TestCase;

class RpsTournamentTest extends TestCase
{
  
    public function testAddPlayer() {
    	$tournament = new RpsTournament();
    	$tournament->addPlayer('Jens',RpsTournament::ROCK);
    	$players = \PHPUnit_Framework_Assert::readAttribute($tournament
    		, 'players');
    	
		$this->assertArrayHasKey('Jens',$players);
		$this->assertEquals($players['Jens'],RpsTournament::ROCK);
    }
    
    public function testGetWinner() {
    	//Setup
    	$noplayers = array();
		$players = [
			'Ole' => 'P',
			'Knud' => 'S',
			'Morten' => 'r',
			'Martin' => 'P',
			'Mette' => 'R'
		];
		$players2 = [
			'Ole' => 'P',
			'Knud' => 'S',
			'Morten' => 'r',
			'Martin' => 'P',
			'Mette' => 'spock'
		];
		
		//Regular Tournament
		$tournament = new RpsTournament($players);
      $this->assertEquals($tournament->getWinner(),'Mette');
      //Someone trying to cheat
      $tournament = new RpsTournament($players2);
      $this->assertEquals($tournament->getWinner(),RpsTournament::CANCELLED);
      //No players
      $tournament = new RpsTournament($noplayers);
      $this->assertEquals($tournament->getWinner(),RpsTournament::NOPLAYERS);
    }
	
    public function testPlayMatch()
    {
		//Setup
		$players = [
			RpsTournament::ROCK => RpsTournament::ROCK,
			RpsTournament::SCISSOR => RpsTournament::SCISSOR,
			RpsTournament::PAPER => RpsTournament::PAPER
		];
		$tournament = new RpsTournament($players);
		
		//Do the right players win
		$this->assertEquals(
			$tournament->playMatch(RpsTournament::ROCK,RpsTournament::SCISSOR),
			RpsTournament::ROCK
		);
		$this->assertEquals(
			$tournament->playMatch(RpsTournament::ROCK,RpsTournament::PAPER),
			RpsTournament::PAPER
		);
		$this->assertEquals($tournament->playMatch
			(RpsTournament::PAPER,RpsTournament::SCISSOR),
			RpsTournament::SCISSOR
		);
		
		//Does the order matter? It shouldn't
		$this->assertEquals(
			$tournament->playMatch(RpsTournament::ROCK,RpsTournament::PAPER),
			$tournament->playMatch(RpsTournament::PAPER,RpsTournament::ROCK)
		);
		$this->assertEquals(
			$tournament->playMatch(RpsTournament::PAPER,RpsTournament::SCISSOR),
			$tournament->playMatch(RpsTournament::SCISSOR,RpsTournament::PAPER)
		);
		$this->assertEquals(
			$tournament->playMatch(RpsTournament::ROCK,RpsTournament::SCISSOR),
			$tournament->playMatch(RpsTournament::SCISSOR,RpsTournament::ROCK)
		);
    }
}