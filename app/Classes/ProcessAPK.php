<?php
namespace App\Classes;
use DB;
class ProcessAPK 
{
	//aapt
	public function parseAppLabel($str) {
		try{
		  $arr = explode("'", $str);
		  $arr_length = count($arr);
		  for ($i = 0; $i < $arr_length; $i++){
			  $pos = strpos($arr[$i], "application-label");
			  if(!$this->IsNullOrEmptyString($pos) && $pos >= 0 && ($i + 1) < $arr_length){
				return $arr[$i + 1];
			  }
		  }
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function IsNullOrEmptyString($question){
		return (!isset($question) || trim($question)==='');
	}

	public function parsePackage($str) {
		$pkg = array();
		try{
		  $arr = explode(" ", $str);
		  foreach ($arr as $value) {
			$pos = strpos($value, "name=");

			if(!$this->IsNullOrEmptyString($pos) && $pos >= 0){
			  $pkg['name'] = explode("'", explode("name=", $value)[1])[1];
			}

			$pos = strpos($value, "versionCode=");
			if(!$this->IsNullOrEmptyString($pos) && $pos >= 0){
			  $pkg['versionCode'] = explode("'", explode("versionCode=", $value)[1])[1];
			}

			$pos = strpos($value, "versionName=");
			if(!$this->IsNullOrEmptyString($pos) && $pos >= 0){
			  $pkg['versionName'] = explode("'", explode("versionName=", $value)[1])[1];
			}
		  }
		  return $pkg;
		} catch (Exception $e) {
		  echo 'Exception in parsing package: ',  $e->getMessage(), "\n";
		  return $e->getMessage();
		}
	}
	public function processApk($apk, $apkid, $userid){	
		//paths to aapt cmd
		$aapt = "/opt/android-sdk-linux/build-tools/23.0.3/aapt";
		//path to user apk
		//$appPath = '/var/www/vhosts/adsinapk.com/httpdocs/dev/uploads/27/67.apk';
		$appPath = $apk;

		//get app name
		$applicationLabelArr = shell_exec($aapt . ' dump badging ' . escapeshellarg($appPath) . ' | grep application-label 2>&1');
		$applicationLabel = $this->parseAppLabel($applicationLabelArr);
		//echo "App name:".$applicationLabel."<br>";

		//get app package
		$package = shell_exec($aapt . ' dump badging ' . escapeshellarg($appPath) . ' | grep package:\ name 2>&1');
		$package = $this->parsePackage($package);
		$package['name'] = $applicationLabel;
		$apk_details = DB::table('apk')->where('id', $apkid)->update(['app_name' => $applicationLabel, 'version_name' => $package['versionName'], 'version_code' => $package['versionCode']]);
		if($apk_details){		
		return $package;
		}
	}
	
}
?>
