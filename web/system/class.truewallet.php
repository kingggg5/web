<?php
if (!isset($config)){exit;}
class TrueWallet{
	private $passhash;
	private $login_type = "email";
	private $api_signin = "https://api-ewm.truemoney.com/api/v1/signin?&";
	private $api_profile = "https://api-ewm.truemoney.com/api/v1/profile/";
	private $api_topup = "https://api-ewm.truemoney.com/api/api/v1/topup/mobile/";
	private $device_os = "android";
	private $device_id = "d520d0d12d0d48cb89394905168c6ed5"; 
	private $device_type = "CPH1611";
	private $device_version = "6.0.1";
	private $app_name = "wallet";
	private $app_version = "2.9.14";
	private $deviceToken = "fUUbZJ9nwBk:APA91bHHgBBHhP9rqBEon_BtUNz3rLHQ-sYXnezA10PRSWQTwFpMvC9QiFzh-CqPsbWEd6x409ATC5RVsHAfk_-14cSqVdGzhn8iX2K_DiNHvpYfMMIzvFx_YWpYj5OaEzMyIPh3mgtx";
	private $mobileTracking = "dJyFzn/GIq7lrjv2RCsZbphpp0L/W2 PsOTtOpg352mgWrt4XAEAAA==";
	private $walletToken = '';
	
	public function __construct() {
		global $config;
		$this->passhash = sha1($config['trueID'].$config['truePS']);
	}

	private function GetToken(){
		global $config;
		$url = $this->api_signin.'device_os='.$this->device_os.'&device_id='.$this->device_id.'&device_type='.$this->device_type.'&device_version='.$this->device_version.'&app_name='.$this->app_name.'&app_version='.$this->app_version;
		$header = array(
		    "Host: api-ewm.truemoney.com",
		    "Content-Type: application/json"
		);
		$postfield = array(
		    "username"=>$config['trueID'],
		    "password"=>$this->passhash,
		    "type"=>$this->login_type,
		    "deviceToken"=>$this->deviceToken,
		    "mobileTracking"=>$this->mobileTracking,
		);
		return $this->wallet_curl($url,json_encode($postfield),$header);
	}
	
	public function Profile(){
		$url = $this->api_profile.$this->walletToken.'?&device_os=android&device_id='.$this->device_id.'&device_type='.$this->device_type.'&device_version='.$this->device_version.'&app_name='.$this->app_name.'&app_version='.$this->app_version;
		$header = array("Host: api-ewm.truemoney.com");
		return $this->wallet_curl($url,false,$header);
	}
	
	private function wallet_curl($url,$data,$header){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		if($data){
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
		    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_USERAGENT,'');
		$result = curl_exec($ch);
		return json_decode($result,true);
	}
	
	public function Topup($card){
		$token = $this->GetToken();
	    $url = $this->api_topup.time()."/".$token['data']['accessToken']."/cashcard/".$card;
		$header = array("Host: api-ewm.truemoney.com");
		$topup = $this->wallet_curl($url,true,$header);
		
		if(!$topup){
			$status['isField'] = true;
		}
		else
		{
		$status['code'] = $topup['code'];
		$status['transactionId'] = $topup['transactionId'];
		$status['amount'] = $topup['amount'];
		}

		return $status; 
	}
    public function GetTime() {
            return date("Y-m-d H:i:s", time());
    }
}