<?php
$questA = explode(".",$currentquestperm);
$questParent = $questA[4];
$questChild = $questA[3];
if ( $currentquestperm == "quest.current.name.number.objective") {
        $questname = "Testing Quest, Part 1";
        $questparent = "Testing Quest";
        $questchild = "Part 1";
        $questhelp = "Try checking with THIS_NPC to finish the mission.
        He's over at StrongPort.";
        $questgiver = "Ranger_Joe";
        $questlocation = "Araeos City";
}
if ( $questParent == "tutorial" && $questChild = 1) {
        $questname = "The Tutorial";
        $questparent = "The Tutorial";
        $questchild = "";
        $questhelp = "Check with Gordon Cassidy to complete this mission.";
        $questgiver = "Gordon Cassidy";
        $questlocation = "Tutorial Island";
}
?>
