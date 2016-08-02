<?php
//Should not happen since this should be in a directory that does not ask for client certificates
if($_SERVER['SSL_CLIENT_S_DN_CN'])
	die("You are already authenticated as ".$_SERVER["SSL_CLIENT_S_DN_CN"]);
date_default_timezone_set('UTC');
$CAorg = 'MyApp';
$CAcountry = 'US';
$CAstate = 'CA';
$CAcity = 'Sacramento';
$confpath = '/var/www/auth/ca/ca.conf';
$cadb = '/var/www/auth/ca/index.txt'; //will need to be reset
$days = 3650;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $f = fopen($cadb, 'w'); //reset CA DB
  fclose($f);
  $uniqpath = tempnam('/tmp/','certreq');
  $username = $_POST['username']; //Validate this first!
  $CAmail = "test@example.com"; //This too! Make sure that's their email.
//If they're submitting a key, first save it to an spkac file
  $key = $_POST['pubkey'];
  if (preg_match('/\s/',$username) || preg_match('/\s/',$CAmail))
    die("Must not have whitespace in username or email!");
  $keyreq = "SPKAC=".str_replace(str_split(" \t\n\r\0\x0B"), '', $key);
  $keyreq .= "\nCN=".$username;
  $keyreq .= "\nemailAddress=".$CAmail;
  $keyreq .= "\n0.OU=".$CAorg." client certificate";
  $keyreq .= "\norganizationName=".$CAorg;
  $keyreq .= "\ncountryName=".$CAcountry;
  $keyreq .= "\nstateOrProvinceName=".$CAstate;
  $keyreq .= "\nlocalityName=".$CAcity;
  file_put_contents($uniqpath.".spkac",$keyreq);
//Now sign the file 
  $command = "openssl ca -config ".$confpath." -days ".$days." -notext -batch -spkac ".$uniqpath.".spkac -out ".$uniqpath.".out 2>&1";
  $output = shell_exec($command);
//And send it back to the user
  $length = filesize($uniqpath);
  header('Last-Modified: '.date('r+b'));
  header('Accept-Ranges: bytes');
  header('Content-Length: '.$length);
  header('Content-Type: application/x-x509-user-cert');
  readfile($uniqpath.".out");
  unlink($uniqpath.".out");
  unlink($uniqpath.".spkac");
  unlink($uniqpath);
  exit;
}
?>
<!DOCTYPE html>
<html>
<h1>Let's generate you a cert so you don't have to use a password!</h1>
 Hit the Generate button and then install the certificate it gives you in your browser.
 All modern browsers (except for Internet Explorer) should be compatible.
 <form method="post">
   <keygen name="pubkey" challenge="randomchars">
   The username I want: <input type="text" name="username" value="Alice">
   <input type="submit" name="createcert" value="Generate">
 </form>
 <strong>Wait a minute, then refresh this page over HTTPS to see your new cert in action!</strong>
</html>