<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//Check if this request came from my own domain..
//$mydomain = '/http:\/\/(w{3}|w*).?vvcares.com/';
//if (!($_POST && preg_match($mydomain,$_SERVER['HTTP_REFERER']) == 1)) header("Location: ../");

//Check Vistor's Real-IP
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];}

$errors = '';
$myemail = 'online@vvcares.com';
//$myemail = 'srajansgp@gmail.com';

if(empty($_POST['name'])  ||
   empty($_POST['email']) ||
   empty($_POST['phone']) ||
   empty($_POST['message']) ||
   empty($_POST['referer']) ||
   !empty($_POST['website']))
{$errors .= "\n Error: all fields are required";}

echo ($_POST['name']);
echo ($_POST['email']);
echo ($_POST['phone']);
echo ($_POST['message']);
echo ($_POST['referer']);

$name = $_POST['name']; 
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];
$message = strip_tags($message);
$uri = 'Online Contact Form';
$referer = $_POST['referer'];

if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$/i", $email));if (preg_match('/mail.ru/', $email))
{$errors .= "\n Error: Invalid Email Address";}


if (!preg_match("/^[\+0-9\-\(\)\s]*$/",$phone))
{$errors .= "\n Error: Invalid Phone Number";}

if (preg_match("/^abc|jpg|png|dating|funding|exchange|inbound|http|www|viagra|porn|sexy|honey|traffic|game|и|д|й|л|à/i", $message)){$errors .= "\n Error: Spammy message";}

if( empty($errors))

//sending email//
{
	$to = $myemail; 
	$email_subject = "[VVCARES] Contact form submission: $name";
	$email_body = "We received a new message via our website contact form ".
	" \n \n Name: $name \n Email: $email \n Phone: $phone \n Message \n $message \n URL From: $referer"; 
	
	$headers = "From: $myemail \n"; 
	$headers .= "Reply-To: $email";
	
	mail($to,$email_subject,$email_body,$headers);
	//redirect to the 'thank you' page
	header('Location: thanks.php');
}

// formdata storing //
   //$linebreak .= "\r\n";
   //$linebreak .= "<br>";
   //$separate .=" | ";

   
if (preg_match("/^abc|jpg|png|dating|funding|exchange|inbound|http|www|viagra|porn|sexy|honey|traffic|game|и|д|й|л|à/i", $message)){$errors .= "\n Error: FormData";}
if( empty($errors)){
   $handle = fopen('1/formdata.php', 'a') or die("can't open file");
   $logtime = date("Y-m-d H:i:s,");
   fwrite($handle, "\n");
   //fwrite($handle, "\n");
   fwrite($handle, $logtime);
   fwrite($handle, "* ");
   fwrite($handle, $_SERVER['REMOTE_ADDR']);
   fwrite($handle, " *");
   fwrite($handle, $name);
   fwrite($handle, "|");
   fwrite($handle, $email);
   fwrite($handle, "|");
   fwrite($handle, $phone);
   fwrite($handle, "|");
   fwrite($handle, strip_tags($message));
   fwrite($handle, "<br>");
   fclose($handle);
}
?>

<?php
echo nl2br($errors);
?>