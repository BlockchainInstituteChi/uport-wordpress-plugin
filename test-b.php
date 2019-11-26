<?php


require  'vendor/autoload.php';


include 'Signature.class.php';

	
$jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NkstUiJ9.eyJpYXQiOjE1NzQ4MDE2OTUsImV4cCI6MTU3NDg4ODA5NSwiYXVkIjoiMm9qRXRVWEJLMko3NWVDQmF6ejR0bmNFV0UxOG9GV3JuZkoiLCJ0eXBlIjoic2hhcmVSZXNwIiwibmFkIjoiMnAxcFJ0NldwUXZXbVpMVXk1dUZaZzRWYnhrQ2JFQ3FzeE4iLCJvd24iOnsibmFtZSI6ImFsZXhtb3JyaXMiLCJlbWFpbCI6IkFsZXhAd2V0ZWFjaGJsb2NrY2hhaW4uY29tIn0sInJlcSI6ImV5SjBlWEFpT2lKS1YxUWlMQ0poYkdjaU9pSkZVekkxTmtzaWZRLmV5SnBjM01pT2lJeWIycEZkRlZZUWtzeVNqYzFaVU5DWVhwNk5IUnVZMFZYUlRFNGIwWlhjbTVtU2lJc0ltbGhkQ0k2TVRVM05EZ3dNVFk1TUN3aWNtVnhkV1Z6ZEdWa0lqcGJJbTVoYldVaUxDSmxiV0ZwYkNKZExDSmpZV3hzWW1GamF5STZJbWgwZEhCek9pOHZZMmhoYzNGMWFTNTFjRzl5ZEM1dFpTOWhjR2t2ZGpFdmRHOXdhV012YlRjd1dHZGlUMHBpUzNkQ1pGTmpVaUlzSW01bGRDSTZJakI0TkNJc0ltVjRjQ0k2TVRVM05EZ3dNakk1TUN3aWRIbHdaU0k2SW5Ob1lYSmxVbVZ4SW4wLjJZUU1Wci1ZLVF0MXlqenhYSHJfeWtScUI4US0yRzh3VjZhMzh1V0h4Y2RqNDhiZ3FGUnR1aUg4Tm41ZUFCTkx6aFA0NjV4RkI2V3R0UXc4cE1PcFR3IiwiaXNzIjoiZGlkOmV0aHI6MHg0ZGNlMzJlOTM4MmM0ZmI4NzIyNTU1Mzk5ZmUzMTUxOTIwMTg2MzA5In0.goRcYam3-0cFJz3VRkvkT_bTXDVKlmu7VOO9rbKazf4eJEmJ4U4EtVM29fGTKM7BO0tnsJhlsnwce18CoakLOgA";


$messageHex       = $hashedMessageHex;
$messageByteArray = unpack('C*', hex2bin($messageHex));
$messageGmp       = gmp_init("0x" . $messageHex);

$r = $rHex;		//hex string without 0x
$s = $sHex; 	//hex string without 0x
$v = $vValue; 	//27 or 28

//with hex2bin it gives the same byte array as the javascript
$rByteArray = unpack('C*', hex2bin($r));
$sByteArray = unpack('C*', hex2bin($s));

$rGmp = gmp_init("0x" . $r);
$sGmp = gmp_init("0x" . $s);

$signature = array_merge($rByteArray, $sByteArray);

$recovery = $v - 27;
if ($recovery !== 0 && $recovery !== 1) {

    throw new Exception('Invalid signature v value');
}

$publicKey = Signature::recoverPublicKey($rGmp, $sGmp, $messageGmp, $recovery);

$publicKeyString = $publicKey["x"] . $publicKey["y"];

$match = $publicKeyString == $knownPublicKeyHex;