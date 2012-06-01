<?php

function pythagoras($a,$b,$c,$precision=4)
{
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
class Bcrypt {
  private $rounds;
  public function __construct($rounds = 12) {
    if(CRYPT_BLOWFISH != 1) {
      throw new Exception("bcrypt not supported in this installation. See http://php.net/crypt");
    }

    $this->rounds = $rounds;
  }

  public function hash($input) {
    $hash = crypt($input, $this->getSalt());

    if(strlen($hash) > 13)
      return $hash;

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
class MinecraftQueryException extends Exception
{
	// Exception thrown by MinecraftQuery class
}
class MinecraftQuery
{
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
?>
