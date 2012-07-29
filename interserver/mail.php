<?php
include('includes/functions.php');
class MailHandle{
	public $nick;
	protected $mailData;
	protected $sentData;
	public function __construct($nick){
		$sentData = array();
		$query = mysql_query("SELECT * FROM Mail WHERE name='$nick'");
		while($row = mysql_fetch_array($query)){
			$sentData[$row['msgid']] = array(
				'name' => $row['name'],
				'recipient' => $row['recipient'],
				'msgid' => $row['msgid'],
				'time' => $row['time'],
				'message' => $row['message'],
				'status' => $row['status']
			);
		}
		$mailData = array();
		$query = mysql_query("SELECT * FROM Mail WHERE recipient='$nick'");
		while($row = mysql_fetch_array($query)){
			$mailData[$row['msgid']] = array(
				'name' => $row['name'],
				'recipient' => $row['recipient'],
				'time' => $row['time'],
				'msgid' => $row['msgid'],
				'message' => $row['message'],
				'status' => $row['status']
			);
		}
		$this->mailData = $mailData;
		$this->sentData = $sentData;
		$this->nick = $nick;
	}
	public function handleCommand($args){
		if($args[0]!='mail'){ die('Uh....wut?'); }
		switch(strtolower($args[1])){
			case "write":
			case "send":
			case "compose":
				array_shift($args);
				array_shift($args);
				$recipient = offlinePlayer(array_shift($args));
				if($recipient==false){ die('Invalid player!'); }
				$message = implode(' ', $args);
				$this->sendMail($recipient, $message);
				break;
			case "read":
			case "open":
				if(!is_int($args[2])){ die('Invalid message ID!'); }
				if($this->isValidMessageID($args[2])){ die('Invalid message ID!'); }
				$this->readMail($msgid);
				break;
			case "list":
			case "show":
				$this->listMail($args[2]);
				break;
		}
	}
	public function readMail($msgid){
		// Read a specific mail message from the database.
                if(!is_int($msgid)){ die('Invalid message ID!'); }
                $mailData = $this->mailData[$msgid];
                if(!isset($this->mailData[$msgid]) && !isset($this->sentData[$msgid])){ die('Invalid message ID!'); }
                echo "---------------- Message ID ".$msgid." ----------------\n";
                echo "From ".getFullName($mailData['name'])."§f at ".date("F j, Y, g:i a", $mailData['time']).".\n";
                echo "Message reads: ".$mailData['message'];
                if($mailData['status']==1){ mysql_query("UPDATE Mail SET status='2' WHERE name='$mailData[name]' AND msgid='$mailData[msgid]'"); }
	}
	public function sendMail($recipient, $message){
		// Write a new mail message.
	}
	public function listMail($type){
		// List all of the mail messages waiting for the person.
		switch(strtolower($type)){
			case "":
			case "all":
				echo "-------- Listing all mail messages --------\n";
				foreach($this->mailData as $data){
					
				}
				break;
			case "new":
			case "unread":
				foreach($this->mailData as $data){
					if($data['status']=='1'){ echo ""; }
				}
				break;
			case "read":
			case "old":
				break;
			case "sent":
				break;
			default:
				die('Invalid type!');
		}
	}
	private function getMail($msgid){
		// Get a specific mail
	}
	private function isValidMessageID($msgid){
		foreach($this->mailData as $data){
			if($data['msgid']==$msgid){ return true; }
		}
		return false;
	}
}

$mailHandle = new MailHanel($_POST['player']);
$mailHanele->handleCommand($_POST['args']);
?>