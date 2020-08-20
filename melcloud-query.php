<?php 

// Retrieve device data from MELCloud (Mitsubishi electric A/C Cloud) for export to cacti
// Note that you must have connected your pump to the cloud. You should be able to query the 
// device from the cloud before using this.
// https://github.com/AmahaShadow
// based on the work of http://mgeek.fr/blog/un-peu-de-reverse-engineering-sur-melcloud
// who originally reversed engineer the API (french)
// this only returns the Actual Temp, Set Temp, Actual Fan Speed, and Energy Consumption
// which were what I wanted to check, but there are a lot more data available. 

//config
$mail='email@exemple.com';
$pass='password';

//for debugging purpose, uncomment the line at the end if you need it
$log='';	

// First, we need to login to the service and get the contextKey
$url='https://app.melcloud.com/Mitsubishi.Wifi.Client/Login/ClientLogin';
$loginData=array(
	'AppVersion' 		=> '1.9.3.0',
	'Language'	 		=> '7',
	'CaptchaChallenge'	=> '',
	'CaptchaResponse'	=> '',
	'Persist'	 		=> 'true',
	'Email'	 			=> $mail,
	'Password'	 		=> $pass
	
);
$c = curl_init($url);
$encodedData = json_encode($loginData);
curl_setopt($c, CURLOPT_RETURNTRANSFER , 1);
curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_POSTFIELDS, $encodedData);
curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
$result = curl_exec($c);
$log.="====== AUTH CALL =======\n\n".$result."\n\n\n";
curl_close($c);
$res=json_decode($result,true);
$cKey=$res['LoginData']['ContextKey'];
if (!$cKey)
	die("Error during auth, no ContextKey returned\n\n".$result);

// now we get the data. 
$url='https://app.melcloud.com/Mitsubishi.Wifi.Client/User/ListDevices';
$c = curl_init($url);
$encodedData = json_encode($loginData);
curl_setopt($c, CURLOPT_RETURNTRANSFER , 1);
curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json','X-MitsContextKey:'.$cKey)); 
$result = curl_exec($c);
$log.="====== DEVICE QUERY =======\n\n".$result."\n\n\n";
curl_close($c);

//data decoding
if (substr($result,0,1)=='[')
	$result=substr($result,1,-1);
$res=json_decode($result,true);
if (!$res['ID'])
	die("Error during data retrieval, format change ?\n\n".$result);


//The following assumes only one device, but the call actually returns everything, so you'll only need to iterate the devices to get the rest
$dev=$res['Structure']['Devices'][0]['Device'];

//Print the result
echo "temp:".$dev['RoomTemperature']." reqtemp:".$dev['SetTemperature']." fanspeed:".$dev['ActualFanSpeed']." energy:".$dev['CurrentEnergyConsumed'];

//If this line is uncommented, it will store all call results info into a text file
//file_put_contents('melcloud_last_transact.txt',$log);

?>
