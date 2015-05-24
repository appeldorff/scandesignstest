<?php

$input = [
'Ole' => 'P',
'Knud' => 'S',
'Morten' => 'r',
'Martin' => 'P',
'Mette' => 'R'
];

$winner = (new Appeldorff\RPS\RpsTournamentImpl($input))->findWinner();
echo $winner;