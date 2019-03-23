
<?php
error_reporting(0);
echo "Marlboro.ID - Auto Login - YarzCode\n";
echo "Enter E-Mail: ";
$email = "adityamahendra187@gmail.com";
echo "Enter Password: \e[0;30m";
$password = "081365676926aA!";
echo "\e[0m\n";
echo "Start proccess...\n\n";

while(true)
{
	$login = login($email, $password);
	if(is_array($login))
	{
		echo "Login Success | Point saat ini: ".$login[1]."\n";
	} else {
		echo "Login Failed | Reason: ".$login."\n";
	}
	sleep(3);
}


function login($email, $pass)
{
	$cookie = "ngentod.txt";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.marlboro.id/auth/login?ref_uri=/profile');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	if(curl_error($ch))
	{
		die("Something error: ".curl_error($ch));
	}
	$ret = curl_exec($ch);
	curl_close($ch);

	if(strpos($ret, 'decide_csrf'))
	{
	    $header = array();
	    $header[] = 'Accept-Encoding: gzip, deflate, br';
	    $header[] = 'Accept-Language: en-US,en;q=0.9';
	    $header[] = 'Connection: keep-alive';
	    $header[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
	    $header[] = 'Host: www.marlboro.id';
	    $header[] = 'Origin: https://www.marlboro.id';
	    $header[] = 'Referer: https://www.marlboro.id/';
	    $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.119 Safari/537.36';
	    $header[] = 'X-Requested-With: XMLHttpRequest';
		preg_match('/<input type="hidden" name="decide_csrf" value="(.*?)"/', $ret, $decide);
		$somebody = array('email' => str_replace('@','%40',$email), 'password' => $pass, 'decide_csrf' => $decide[1], 'ref_uri' => urlencode('/profile'));
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.marlboro.id/auth/login');
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($somebody));
	    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	    if(curl_error($ch))
	    {
	    	die("Something error: ".curl_error($ch));
	    }
	    $ret = curl_exec($ch);
	    curl_close($ch);	
	    if(json_decode($ret,1)['error'] == true)
	    {
	    	return json_decode($ret,1)['error']['message'];
	    } else {
	    	 $ch = curl_init();
	         curl_setopt($ch, CURLOPT_URL, 'https://www.marlboro.id/profile');
	         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	         curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	         curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	         if(curl_error($ch))
	         {
	         	die("Something error: ".curl_error($ch));
	         }
	         $ret = curl_exec($ch);
	         curl_close($ch);	
	         preg_match('/<span class="point-place" data-current="(.*?)">/', $ret, $point);
	         if(isset($point[1]))
	         { 
	         	return array('success',$point[1]);
	         } else {
	         	return 'An Error Occured';
	         }
	    }
	} else {
		return 'Error to get csrf_token';
	}
	unlink($cookie);
}
