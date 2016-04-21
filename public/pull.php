<?php
// =============================================================================
// Git Auto Pull
// =============================================================================
//
// 1. Create a 1-8 character password


// Copy and paste the results from `passgen` here
$salt = '991726351';
$pass = 'b39c6f814603e8bbb3ddfe34e3fa220b';
// End copy and paste


// Don't need to edit these lines
$remoteIP = $_SERVER['REMOTE_ADDR'];
$msg	  = 'Request came from '.$remoteIP.' - http://whois.arin.net/rest/ip/' . $remoteIP;


function commands() {
    echo '<pre>';
    echo `
        cd ..
        git fetch --all
        git reset --hard origin/master
        git pull
        echo 'Done'
    `;
}


if (isset($_GET['update'])) {

    // Check if genuine and if so run the `commands()` function.
    // -------------------------------------------------------------------------

    $givenPassword = md5(crypt($_GET['update'], $salt));

    if ($pass === $givenPassword) {
        commands();
    }

} elseif (isset($_GET['passgen'])) {

    // Give the password and return the secure bits.
    // -------------------------------------------------------------------------

    // We want to generate a salt and password
    $password	= $_GET['passgen'];
    $randSalt	= (string)rand();
    $generate	= crypt($password, $randSalt);
    $genPass	= md5($generate);

    $html = '<body style="width: 70%; margin: 20px auto; text-align: center; font-family: sans-serif; font-weight: 200; line-height: 3em">';
    $html .= '<p><label>Add the following code to <code>'.$_SERVER['SCRIPT_FILENAME'].'</code><br /><textarea rows="2" style="width: 500px; border: 1px solid #ccc; padding: 10px; font-family: monospace;">';
    $html .= '$salt = \''.$randSalt.'\';'."\n";
    $html .= '$pass = \''.$genPass.'\';';
    $html .= '</textarea></label></p>';

    $callURL = 'http';
    if (isset($_SERVER['HTTPS']))	$callURL .= 's';
    $callURL .= '://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?update='.$_GET['passgen'];
    $html .= '<p><label>Add this URL your project\'s "Post-Recieve URLs"<br /><input type="text" value="'.$callURL.'" style="width: 500px; border: 1px solid #ccc; text-align: center; padding: 10px; font-family: monospace;" /></label></p>';

    echo $html;

}
