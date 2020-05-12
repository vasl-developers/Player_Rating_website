<?php
$administrator="Doug Rimmer";
$adminemail="dougerimmer@gmail.com";
$headers = "From: dougerimmer@gmail.com";
if(isset($_POST['formRMD']) && $_POST['formRMD'] == 'Yes') {
    $person = trim($_POST["playername"]);
    $emailtosend = trim($_POST["emailadd"]);

    // the message
    $msg = "Hi" . " " . $person . " " . "You have asked for your data to be removed from the ASL Player Rating System. 
    Please contact " . $administrator . " at " . $adminemail . " to confirm your identity and obtain additional information";

// use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);

// send email
    if(!(mail($emailtosend,"Remove Data Request",$msg, $headers))){
        echo "some kind of error. Come on!";
    };
}

