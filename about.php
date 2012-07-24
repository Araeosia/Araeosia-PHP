<?php
include("includes/groups.php");
include("includes/mysql.php");
include('includes/functions.php');
serverCheck($server, true);

// Calculate total number of players seen


// Output to player
echo "Araeosia is a custom RPG server built over the course of a year.";
echo "Credits for Araeosia can be found on http://wiki.araeosia.com/Credits";
echo "There are a total of 13 cities, 5 dungeons, 58 quests, and 532 NPCs.";
echo "The server has seen " . $playersseen . " players, who have";
echo "killed " . $mobskilled . " monsters and have a cumulative amount";
echo "of " . $totalmoney . " dollars. They have also completed" . $totalquests . " quests and";
echo "have died " . $totaldeaths . " times. The richest person is " . $richestperson . " with " . $richestmoney . " dollars.";
?>
