<?php
// This file contains general functions that are used in more than one place, such as Bcrypt and the minecraft query. I've placed them here to both keep them out of the way and also to shorten the length of the PHP files.
include('includes/servers.php');
function pythagoras($a,$b,$c,$precision=4){
	($a) ? $a = pow($a,2) : $find .= 'a';
	($b) ? $b = pow($b,2) : $find .= 'b';
	($c) ? $c = pow($c,2) : $find .= 'c';
	
	switch ($find)
	{
		case 'a':
			return round(sqrt($c - $b),$precision);
		break;
		case 'b':
			return round(sqrt($c - $a),$precision);
		break;
		case 'c':
			return round(sqrt($a + $b),$precision);
		break;
	}
	
	return false;
}
function getPrimaryGroup($player){
	include('includes/mysql.php');
	$query = mysql_query("SELECT * FROM TrueGroups WHERE name='$player'") or die(mysql_error());
	$groups = array();
	while($row = mysql_fetch_array($query)){ array_push($groups, $row['group']); }
	$primaryGroup = "default";
	if(in_array('Veteran', $groups)){ $primaryGroup = "Veteran"; }
	if(in_array('Supporter', $groups)){ $primaryGroup = "Supporter"; }
	if(in_array('Moderator', $groups)){ $primaryGroup = "Moderator"; }
	if(in_array('Admin', $groups)){ $primaryGroup = "Admin"; }
	if(in_array('Head-Admin', $groups)){ $primaryGroup = "Head-Admin"; }
	return $primaryGroup;
}
function getFullName($player){
	switch(getPrimaryGroup($player)){
		case "Veteran":
			$prefix = "§2";
			break;
		case "Moderator":
			$prefix = "§a";
			break;
		case "Supporter":
			$prefix = "§1";
			break;
		case "Admin":
			$prefix = "§4";
			break;
		case "Head-Admin":
			$prefix = "§4";
			break;
		default:
			$prefix = "§b";
			break;
	}
	$playername = $prefix.$player;
	return $playername;
}
function sendMessageToChannel($channel, $message, $excluded=array()){
	// This function will send a message to all members of a channel while excluding any user names in the $excluded array. The message and channel are expected to be a string.
	if(!is_string($channel)){ die('$channel is not a string!'); }
	if(!is_string($message)){ die('$message is not a string!'); }
}
class Bcrypt {
	private $rounds;
	public function __construct($rounds = 12) {
		if(CRYPT_BLOWFISH != 1) { throw new Exception("bcrypt not supported in this installation. See http://php.net/crypt"); }
		$this->rounds = $rounds;
	}
	public function hash($input) {
		$hash = crypt($input, $this->getSalt());
		if(strlen($hash) > 13) {return $hash; }
		return false;
    }
	public function verify($input, $existingHash) {
		$hash = crypt($input, $existingHash);
		return $hash === $existingHash;
	}
	private function getSalt() {
    $salt = sprintf('$2a$%02d$', $this->rounds);

    $bytes = $this->getRandomBytes(16);

    $salt .= $this->encodeBytes($bytes);

    return $salt;
  }
	private $randomState;
	private function getRandomBytes($count) {
    $bytes = '';

    if(function_exists('openssl_random_pseudo_bytes') &&
        (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL slow on Win
      $bytes = openssl_random_pseudo_bytes($count);
    }

    if($bytes === '' && is_readable('/dev/urandom') &&
       ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
      $bytes = fread($hRand, $count);
      fclose($hRand);
    }

    if(strlen($bytes) < $count) {
      $bytes = '';

      if($this->randomState === null) {
        $this->randomState = microtime();
        if(function_exists('getmypid')) {
          $this->randomState .= getmypid();
        }
      }

      for($i = 0; $i < $count; $i += 16) {
        $this->randomState = md5(microtime() . $this->randomState);

        if (PHP_VERSION >= '5') {
          $bytes .= md5($this->randomState, true);
        } else {
          $bytes .= pack('H*', md5($this->randomState));
        }
      }

      $bytes = substr($bytes, 0, $count);
    }

    return $bytes;
  }
	private function encodeBytes($input) {
    // The following is code from the PHP Password Hashing Framework
    $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $output = '';
    $i = 0;
    do {
      $c1 = ord($input[$i++]);
      $output .= $itoa64[$c1 >> 2];
      $c1 = ($c1 & 0x03) << 4;
      if ($i >= 16) {
        $output .= $itoa64[$c1];
        break;
      }

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 4;
      $output .= $itoa64[$c1];
      $c1 = ($c2 & 0x0f) << 2;

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 6;
      $output .= $itoa64[$c1];
      $output .= $itoa64[$c2 & 0x3f];
    } while (1);

    return $output;
  }
}
class minecraft {

    public $account;

    private function request($website, array $parameters) {
        $request = curl_init();
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        if ($parameters != null) {
            curl_setopt($request, CURLOPT_URL, $website.'?'.http_build_query($parameters, null, '&'));
        } else {
            curl_setopt($request, CURLOPT_URL, $website);
        }
        return curl_exec($request);
        curl_close($request);
    }

    public function signin($username, $password, $version) {
        $parameters = array('user' => $username, 'password' => $password, 'version' => $version);
        $request = $this->request('https://login.minecraft.net/', $parameters);
        $response = explode(':', $request);
        if ($request != 'Old version' && $request != 'Bad login') {
            $this->account = array(
                'current_version' => $response[0],
                'correct_username' => $response[2],
                'session_token' => $response[3],
                'premium_account' => $this->is_premium($username),
                'player_skin' => $this->get_skin($username),
                'request_timestamp' => date("dmYhms", mktime(date(h), date(m), date(s), date(m), date(d), date(y)))
            );
            return true;
        } else {
            return false;
        }
    }

    public function is_premium($username) {
        $parameters = array('user' => $username);
        return $this->request('https://www.minecraft.net/haspaid.jsp', $parameters);
    }

    public function get_skin($username) {
        if ($this->is_premium($username)) {
            $headers = get_headers('http://s3.amazonaws.com/MinecraftSkins/'.$username.'.png');
            if ($headers[7] == 'Content-Type: image/png' || $headers[7] == 'Content-Type: application/octet-stream') {
                return 'https://s3.amazonaws.com/MinecraftSkins/'.$username.'.png';
            } else {
                return 'https://s3.amazonaws.com/MinecraftSkins/char.png';
            }
        } else {
            return false;
        }
    }

    public function keep_alive($username, $session) {
        $parameters = array('name' => $username, 'session' => $session);
        $request = $this->request('https://login.minecraft.net/session', $parameters);
        return null;
    }

    public function join_server($username, $session, $server) {
        $parameters = array('user' => $username, 'sessionId' => $session, 'serverId' => $server);
        $request = $this->request('http://session.minecraft.net/game/joinserver.jsp', $parameters);
        if ($request != 'Bad login') {
            return true;
        } else {
            return false;
        }
    }

    public function check_server($username, $server) {
        $parameters = array('user' => $username, 'serverId' => $server);
        $request = $this->request('http://session.minecraft.net/game/checkserver.jsp', $parameters);
        if ($request == 'YES') {
            return true;
        } else {
            return false;
        }
    }

    public function render_skin($username, $render_type, $size) {
        if (in_array($render_type, array('head', 'body'))) {
            if ($render_type == 'head') {
                header('Content-Type: image/png');
                $canvas = imagecreatetruecolor($size, $size);
                $image = imagecreatefrompng($this->get_skin($username));
                imagecopyresampled($canvas, $image, 0, 0, 8, 8, $size, $size, 8, 8);
                return imagepng($canvas);
            } else if($render_type == 'body') {
                header('Content-Type: image/png');
                $scale = $size / 16;
                $canvas = imagecreatetruecolor(16*$scale, 32*$scale);
                $image = imagecreatefrompng($this->get_skin($username));
                imagealphablending($canvas, false);
                imagesavealpha($canvas,true);
                $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                imagefilledrectangle($canvas, 0, 0, 16*$scale, 32*$scale, $transparent);
                imagecopyresized  ($canvas, $image, 4*$scale,  0*$scale,  8,   8,   8*$scale,  8*$scale,  8,  8);
                imagecopyresized  ($canvas, $image, 4*$scale,  8*$scale,  20,  20,  8*$scale,  12*$scale, 8,  12);
                imagecopyresized  ($canvas, $image, 0*$scale,  8*$scale,  44,  20,  4*$scale,  12*$scale, 4,  12);
                imagecopyresampled($canvas, $image, 12*$scale, 8*$scale,  47,  20,  4*$scale,  12*$scale, -4,  12);
                imagecopyresized  ($canvas, $image, 4*$scale,  20*$scale, 4,   20,  4*$scale,  12*$scale, 4,  12);
                imagecopyresampled($canvas, $image, 8*$scale,  20*$scale, 7,   20,  4*$scale,  12*$scale, -4,  12);
                return imagepng($canvas);
            }
        } else {
            return false;
        }
    }

}
class MinecraftQueryException extends Exception{
	// Exception thrown by MinecraftQuery class
}
class MinecraftQuery{
	/*
	 * Class written by xPaw
	 *
	 * Website: http://xpaw.ru
	 * GitHub: https://github.com/xPaw/PHP-Minecraft-Query
	 */
	
	const STATISTIC = 0x00;
	const HANDSHAKE = 0x09;
	
	private $Socket;
	private $Players;
	private $Info;
	
	public function Connect( $Ip, $Port = 25565, $Timeout = 3 )
	{
		if( $this->Socket = FSockOpen( 'udp://' . $Ip, (int)$Port ) )
		{
			Socket_Set_TimeOut( $this->Socket, $Timeout );
			
			$Challenge = $this->GetChallenge( );
			
			if( $Challenge === false )
			{
				FClose( $this->Socket );
				throw new MinecraftQueryException( "Failed to receive challenge." );
			}
			
			if( !$this->GetStatus( $Challenge ) )
			{
				FClose( $this->Socket );
				throw new MinecraftQueryException( "Failed to receive status." );
			}
			
			FClose( $this->Socket );
		}
		else
		{
			throw new MinecraftQueryException( "Can't open connection." );
		}
	}
	
	public function GetInfo( )
	{
		return isset( $this->Info ) ? $this->Info : false;
	}
	
	public function GetPlayers( )
	{
		return isset( $this->Players ) ? $this->Players : false;
	}
	
	private function GetChallenge( )
	{
		$Data = $this->WriteData( self :: HANDSHAKE );
		
		return $Data ? Pack( 'N', $Data ) : false;
	}
	
	private function GetStatus( $Challenge )
	{
		$Data = $this->WriteData( self :: STATISTIC, $Challenge . Pack( 'c*', 0x00, 0x00, 0x00, 0x00 ) );
		
		if( !$Data )
		{
			return false;
		}
		
		$Last = "";
		$Info = Array( );
		
		$Data    = SubStr( $Data, 11 ); // splitnum + 2 int
		$Data    = Explode( "\x00\x00\x01player_\x00\x00", $Data );
		$Players = SubStr( $Data[ 1 ], 0, -2 );
		$Data    = Explode( "\x00", $Data[ 0 ] );
		
		// Array with known keys in order to validate the result
		// It can happen that server sends custom strings containing bad things (who can know!)
		$Keys = Array(
			'hostname'   => 'HostName',
			'gametype'   => 'GameType',
			'version'    => 'Version',
			'plugins'    => 'Plugins',
			'map'        => 'Map',
			'numplayers' => 'Players',
			'maxplayers' => 'MaxPlayers',
			'hostport'   => 'HostPort',
			'hostip'     => 'HostIp'
		);
		
		foreach( $Data as $Key => $Value )
		{
			if( ~$Key & 1 )
			{
				if( !Array_Key_Exists( $Value, $Keys ) )
				{
					$Last = false;
					continue;
				}
				
				$Last = $Keys[ $Value ];
				$Info[ $Last ] = "";
			}
			else if( $Last != false )
			{
				$Info[ $Last ] = $Value;
			}
		}
		
		// Ints
		$Info[ 'Players' ]    = IntVal( $Info[ 'Players' ] );
		$Info[ 'MaxPlayers' ] = IntVal( $Info[ 'MaxPlayers' ] );
		$Info[ 'HostPort' ]   = IntVal( $Info[ 'HostPort' ] );
		
		// Parse "plugins", if any
		if( $Info[ 'Plugins' ] )
		{
			$Data = Explode( ": ", $Info[ 'Plugins' ], 2 );
			
			$Info[ 'RawPlugins' ] = $Info[ 'Plugins' ];
			$Info[ 'Software' ]   = $Data[ 0 ];
			
			if( Count( $Data ) == 2 )
			{
				$Info[ 'Plugins' ] = Explode( "; ", $Data[ 1 ] );
			}
		}
		else
		{
			$Info[ 'Software' ] = 'Vanilla';
		}
		
		$this->Info = $Info;
		
		if( $Players )
		{
			$this->Players = Explode( "\x00", $Players );
		}
		
		return true;
	}
	
	private function WriteData( $Command, $Append = "" )
	{
		$Command = Pack( 'c*', 0xFE, 0xFD, $Command, 0x01, 0x02, 0x03, 0x04 ) . $Append;
		$Length  = StrLen( $Command );
		
		if( $Length !== FWrite( $this->Socket, $Command, $Length ) )
		{
			return false;
		}
		
		$Data = FRead( $this->Socket, 1440 );
		
		if( StrLen( $Data ) < 5 || $Data[ 0 ] != $Command[ 2 ] )
		{
			return false;
		}
		
		return SubStr( $Data, 5 );
	}
}
class JSONAPI {
/**
 * A PHP class for access Minecraft servers that have Bukkit with the {@link http://github.com/alecgorge/JSONAPI JSONAPI} plugin installed.
 * 
 * This class handles everything from key creation to URL creation to actually returning the decoded JSON as an associative array.
 * 
 * @author Alec Gorge <alecgorge@gmail.com>
 * @version Alpha 5
 * @link http://github.com/alecgorge/JSONAPI
 * @package JSONAPI
 * @since Alpha 5
 */
	public $host;
	public $port;
	public $salt;
	public $username;
	public $password;
	private $urlFormats = array(
		"call" => "http://%s:%s/api/call?method=%s&args=%s&key=%s",
		"callMultiple" => "http://%s:%s/api/call-multiple?method=%s&args=%s&key=%s"
	);
	
	/**
	 * Creates a new JSONAPI instance.
	 */
	public function __construct ($host, $port, $uname, $pword, $salt) {
		$this->host = $host;
		$this->port = $port;
		$this->username = $uname;
		$this->password = $pword;
		$this->salt = $salt;
		
		if(!extension_loaded("cURL")) {
			throw new Exception("JSONAPI requires cURL extension in order to work.");
		}
	}
	
	/**
	 * Generates the proper SHA256 based key from the given method suitable for use as the key GET parameter in a JSONAPI API call.
	 * 
	 * @param string $method The name of the JSONAPI API method to generate the key for.
	 * @return string The SHA256 key suitable for use as the key GET parameter in a JSONAPI API call.
	 */
	public function createKey($method) {
		if(is_array($method)) {
			$method = json_encode($method);
		}
		return hash('sha256', $this->username . $method . $this->password . $this->salt);
	}
	
	/**
	 * Generates the proper URL for a standard API call the given method and arguments.
	 * 
	 * @param string $method The name of the JSONAPI API method to generate the URL for.
	 * @param array $args An array of arguments that are to be passed in the URL.
	 * @return string A proper standard JSONAPI API call URL. Example: "http://localhost:20059/api/call?method=methodName&args=jsonEncodedArgsArray&key=validKey".
	 */
	public function makeURL($method, array $args) {
		return sprintf($this->urlFormats["call"], $this->host, $this->port, rawurlencode($method), rawurlencode(json_encode($args)), $this->createKey($method));
	}
	
	/**
	 * Generates the proper URL for a multiple API call the given method and arguments.
	 * 
	 * @param array $methods An array of strings, where each string is the name of the JSONAPI API method to generate the URL for.
	 * @param array $args An array of arrays, where each array contains the arguments that are to be passed in the URL.
	 * @return string A proper multiple JSONAPI API call URL. Example: "http://localhost:20059/api/call-multiple?method=[methodName,methodName2]&args=jsonEncodedArrayOfArgsArrays&key=validKey".
	 */
	public function makeURLMultiple(array $methods, array $args) {
		return sprintf($this->urlFormats["callMultiple"], $this->host, $this->port, rawurlencode(json_encode($methods)), rawurlencode(json_encode($args)), $this->createKey($methods));
	}
	
	/**
	 * Calls the single given JSONAPI API method with the given args.
	 * 
	 * @param string $method The name of the JSONAPI API method to call.
	 * @param array $args An array of arguments that are to be passed.
	 * @return array An associative array representing the JSON that was returned.
	 */
	public function call($method, array $args = array()) {
		if(is_array($method)) {
			return $this->callMultiple($method, $args);
		}
		
		$url = $this->makeURL($method, $args);

		return json_decode($this->curl($url), true);
	}
	
	private function curl($url) {
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_PORT, $this->port);
		curl_setopt($c, CURLOPT_HEADER, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_TIMEOUT, 10);		
		$result = curl_exec($c);
		curl_close($c);
		return $result;
	}
	
	/**
	 * Calls the given JSONAPI API methods with the given args.
	 * 
	 * @param array $methods An array strings, where each string is the name of a JSONAPI API method to call.
	 * @param array $args An array of arrays of arguments that are to be passed.
	 * @throws Exception When the length of the $methods array and the $args array are different, an exception is thrown.
	 * @return array An array of associative arrays representing the JSON that was returned.
	 */
	public function callMultiple(array $methods, array $args = array()) {
		if(count($methods) !== count($args)) {
			throw new Exception("The length of the arrays \$methods and \$args are different! You need an array of arguments for each method!");
		}
		
		$url = $this->makeURLMultiple($methods, $args);

		return json_decode($this->curl($url), true);
	}
}
class MCFunctions {
	private function compass($degrees){
		$rounded = round($degrees/45);
		switch($rounded){
			case 0:
				$direction = "East";
				break;
			case 1:
				$direction = "Northeast";
				break;
			case 2:
				$direction = "North";
				break;
			case 3:
				$direction = "Northwest";
				break;
			case 4:
				$direction = "West";
				break;
			case 5:
				$direction = "Southwest";
				break;
			case 6:
				$direction = "South";
				break;
			case 7:
				$direction = "Southeast";
				break;
			case 8:
				$direction = "East";
				break;
			default:
				$direction = "Unknown Direction";
				break;
		}
		return $direction;
	}
	private function getangle($X1, $Z1, $X2, $Z2){
		
	}
	public function getdist($X1, $Z1, $X2, $Z2, $precision=2){
		$distprecise = sqrt(pow(($X2-$X1), 2)+pow(($Z2-$Z1), 2));
		$dist = round($distprecise, $precision);
		return $dist;
	}
	public function respawncoords($P, $X, $Z, $W='Araeosia'){
		$RespawnCoords = array(
			'Araeos City' => array( 'X' => -212.5, 'Y' => 73, 'Z' => -183.5, 'name' => 'Araeos City', 'world' => 'Araeosia' ),
			'Everstone City' => array( 'X' => 486.5, 'Y' => 68, 'Z' => -125.5, 'name' => 'Everstone City', 'world' => 'Araeosia' ),
			'Crystalton' => array( 'X' => -962.5, 'Y' => 73, 'Z' => 989.5, 'name' => 'Crystalton', 'world' => 'Araeosia' ),
			'Darmouth' => array( 'X' => -234.5, 'Y' => 70, 'Z' => 213.5, 'name' => 'Darmouth', 'world' => 'Araeosia' ),
			'Talltree Point' => array( 'X' => -260.5, 'Y' => 76, 'Z' => 677.5, 'name' => 'Talltree Point', 'world' => 'Araeosia' ),
			'Strongport' => array( 'X' => 729.5, 'Y' => 68, 'Z' => 700.5, 'name' => 'Strongport', 'world' => 'Araeosia' ),
			'Coalmoor' => array( 'X' => 242.5, 'Y' => 74, 'Z' => -899.5, 'name' => 'Coalmoor', 'world' => 'Araeosia' ),
			'Westcliff Plains Village' => array( 'X' => -636.5, 'Y' => 74, 'Z' => -167.5, 'name' => 'Westcliff Plains Village', 'world' => 'Araeosia' ),
			'Fivepiece Island' => array( 'X' => 454, 'Y' => 73, 'Z' => -723, 'name' => 'Fivepiece Island', 'world' => 'Araeosia' ),
			'Cle Elum' => array( 'X' => 262, 'Y' => 74, 'Z' => 211, 'name' => 'Cle Elum', 'world' => 'Araeosia' ),
			'The Bridge' => array( 'X' => 770, 'Y' => 78, 'Z' => -21, 'name' => 'The Bridge', 'world' => 'Araeosia' ));
		switch($W){
			case "Araeosia":
				$mins = array();
				foreach($RespawnCoords as $RespawnCoord){
					$min = $this->getdist($X, $Z, $RespawnCoord['X'], $RespawnCoord['Z'], 0);
					array_push($mins, $min);
					$minnames[$min]=$RespawnCoord['name'];
				}
				$RespawnArray = $RespawnCoords[$minnames[min($mins)]];
				$RespawnArray['dist'] = min($mins);
				break;
			case "Araeosia_tutorial2":
				$RespawnArray = array('X'=>-300.5,'Y'=>69,'Z'=>-52.5,'name'=>'The Tutorial','world'=>'Araeosia_tutorial2');
				break;
			case "Araeosia_instance":
				$quest = $this->getquest($P);
				$RespawnArray = $quest['RespawnArray'];
				break;
		}
		return $RespawnArray;
	}
	public function getloc($P, $X, $Z, $W='Araeosia'){
		switch($W){
			case "Araeosia_tutorial2":
				die("§cYou are currently in §bThe Tutorial§c.\n");
				break;
			case "Araeosia_instance":
				$quest = $this->getquest($P);
				$quest = $quest['RespawnArray'];
				die("§cYou are currently in §b".$quest['name']."§c.\n");
				break;
			case "Araeosia":
				$city = $this->respawncoords($P, $X, $Z, $W);
				$city = $city['RespawnArray'];
				$direction = compass(getangle($X, $Z, $city['X'], $city['Z'], 0));
				die("§cThe closest city is §b".$city['name']."§c.\n§aIt is roughly ".$city['dist']." meters ".$direction." of you.\n");
				break;
		}
	}
	public function tpplayer($player, $X, $Y, $Z, $W){
		
	}
	public function msgplayer($player, $msg){
		
	}
	public function getquest($player){
		$quest = mysql_query("SELECT * FROM permission WHERE permission LIKE quest.current.%.%.%");
		$quest = $quest['permission'];
		$questdata = array(
		"quest.current.dungeon.5.1" => array(
			"RespawnArray" => array( 'X' => -0.5, 'Y' => 64, 'Z' => 42.5, 'name' => 'The Dungeon', 'world' => 'Araeosia_instance' ),
			"Giver" => "Adventurer Finn?",
			"Name" => "The Dungeon, Part 5",
			"Part" => 5,
		),
		"quest.current.archeologist.4.1" => array(
			"RespawnArray" => array( 'X' => -314.5, 'Y' => 64, 'Z' => -59.5, 'name' => 'The Ruins', 'world' => 'Araeosia_instance' ),
			"Giver" => "The Archeologist?",
			"Name" => "The Archeologist, Part 4",
			"Part" => 4
		)
		);
		if($quest!=false){
			return $questdata[$quest];
		}else{
			return null;
		}
	}
}
?>