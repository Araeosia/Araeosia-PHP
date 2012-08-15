<?php
include('includes/functions.php');
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$args = $_POST['args'];
serverCheck($server, array('Freebuild', 'Eco'));
if($args[1]!='global'){
	echo "§cRules specific to §b".$world." §care listed below. All global rules apply in this world, and can be viewed with §e/globalrules§c.\n";
	switch($world){
		case "Main":
		case "Main_nether":
		case "Main_the_end":
		case "Wilds":
		case "Space":
			echo "§b1. §f- §aAbsolutely no griefing.\n§b2. §f- §aAbsolutely no duping.\n§b3. §f- §aNo theft. This includes all forms of theft.\n§b4. §f- §aDo not trespass anywhere. This includes player houses.\n§b5. §f- §aDo not break in-game community rules.\n§b6. §f- §aDo not create pixel art outside of a walled community.\n§b7. §f- §aNo one block wide towers or tunnels.\n§b8. §f- §aCut down trees entirely, not half-way.\n§b9. §f- §aMake your buildings look reasonable.\n§b10. §f- §aIf you are going to build near someones property, ask.\n§b11. §f- §aNo using potions to harm people.";
			break;
		case "Malevola":
			echo "§aDrSilverbullet §bis god. Obey her.\n";
			break;
		case "Flatlands":
		case "Creative":
			echo "§b1. §f- §aAbsolutely no griefing.\n§b2. §f- §aDo not trespass anywhere. This includes player houses.\n§b3. §f- §aDo not break in-game community rules.\n§b4. §f- §aNo one block wide towers or tunnels.\n§b5. §f- §aCut down trees entirely, not half-way.\n§b6. §f- §aMake your buildings look reasonable.\n§b7. §f- §aIf you are going to build near someones property, ask.\n§b8. §f- §aNo using potions to harm people.";
			break;
		case "Survival":
			echo "No rules over than global rules!\n";
			break;
                case "Eco":
                        echo "§b1. §f- §aNo complaining about the rules.\n§b2. §f- §aPVP is not allowed unless it is wartime.\n§b3. §f- §aWhen robbing someone, you cannot kill if they give you what they want.\n§b4. §f- §aWartime may go on at any time.\n§b5. §f- §aDo not complain about what happens to you.\n§b6. §f- §aNo not try to take over the server.\n§b7. §f- §aNo complaining about the rules.";
                        break;
		default:
			echo "This world doesn't have any specific rules for some reason, but all global rules apply.\n";
			break;
	}
}else{
	echo "§4-------- Global Rules --------\n§cThese rules apply in all worlds to all players.\n§b1. §f- §aNo hacking, cheating, or exploiting of any kind. Defined by staff members.\n";
	echo "§b2. §f- §aNo disrespecting other players. This includes racism, harassment, and name-calling.\n";
	echo "§b3. §f- §aFollow staff instructions. Their word is law. If they're abusing their power, report them to AgentKid.\n";
	echo "§b4. §f- §aDo not speak in any other language but English.\n";
	echo "§b5. §f- §aDo not argue in global chat.\n";
	echo "§b6. §f- §aShould you have a problem, consult a moderator.\n";
	echo "§b7. §f- §aShould you have a problem with a moderator, consult an Admin.\n";
	echo "§b7. §f- §aNo advertising non-Araeosia servers.\n";
}
?>