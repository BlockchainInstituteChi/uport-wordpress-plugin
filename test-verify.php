<?php

require 'vendor/autoload.php';

use Blockchaininstitute\jwtTools as jwtTools;

$jwtTools = new jwtTools(null);

// Generate 

// Prepare the JWT Header
// 1. Initialize JWT Values
$jwtHeader = (object)[];
$jwtHeader->typ = 'JWT'; // ""
$jwtHeader->alg = 'ES256K'; // ""

// 2. Create JWT Object
$jwtHeaderJson = json_encode($jwtHeader, JSON_UNESCAPED_SLASHES);


// Prepare the JWT Body
// 1. Initialize JWT Values
$jwtBody = (object)[];

// "Client ID"
$signingKey  = 'cb89a98b53eec9dc58213e67d04338350e7c15a7f7643468d8081ad2c5ce5480'; // "Private Key"
// $signingKey = "601339e8cef49ebcf2a85ef6b91210f3c19fd220fb23d77050bbd15758e7f3cc";

$topicUrl = 'https://chasqui.uport.me/api/v1/topic/' . generate_string();

$time = time();
$jwtBody->iss         = '2ojEtUXBK2J75eCBazz4tncEWE18oFWrnfJ';
$jwtBody->iat 	      = $time;

$jwtBody->requested   = ['name'];
$jwtBody->callback    = $topicUrl;
$jwtBody->net      	  = "0x4";
$jwtBody->exp 	      = $time + 600;
$jwtBody->type 		  = "shareReq";

// 2. Create JWT Object
$jwtBodyJson = json_encode($jwtBody, JSON_UNESCAPED_SLASHES);

$jwt = $jwtTools->createJWT($jwtHeaderJson, $jwtBodyJson, $signingKey);

error_log('jwt generated ' . $jwt);


// Mine
	// $jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NksifQ.eyJpc3MiOiIyb2pFdFVYQksySjc1ZUNCYXp6NHRuY0VXRTE4b0ZXcm5mSiIsImlhdCI6MTU2MDk2NDcwMiwicmVxdWVzdGVkIjpbIm5hbWUiXSwiY2FsbGJhY2siOiJodHRwczovL2NoYXNxdWkudXBvcnQubWUvYXBpL3YxL3RvcGljL2JZbTIxZ2xiOGpjSWRNeUUiLCJuZXQiOiIweDQiLCJleHAiOjE1NjA5NjUzMDIsInR5cGUiOiJzaGFyZVJlcSJ9.N/qcrqI7S1I9kFtZufni4jXWLF0GNDF8ooYvuxMfvzZjJJj7tSNAIfcb+YUSiOR6rQqb3ZIg0J3TNovMJv0cIA";

	// uport mobile
	// $jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NksifQ.eyJpYXQiOjE1NjA5NjQ0NzgsImV4cCI6MTU2MTA1MDg3OCwiYXVkIjoiMm9qRXRVWEJLMko3NWVDQmF6ejR0bmNFV0UxOG9GV3JuZkoiLCJ0eXBlIjoic2hhcmVSZXNwIiwibmFkIjoiMm90MWhDdVZBTDZuUTNOUXJ5amtCQVJHdHNqNHJzYW81NzUiLCJvd24iOnsibmFtZSI6IkFsZXgiLCJlbWFpbCI6ImFsZXhAdGhlYmxvY2tjaGFpbmluc3RpdHV0ZS5vcmcifSwicmVxIjoiZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKRlV6STFOa3NpZlEuZXlKcFlYUWlPakUxTmpBNU5qSTBPVGdzSW5KbGNYVmxjM1JsWkNJNld5SnVZVzFsSWl3aVpXMWhhV3dpWFN3aVkyRnNiR0poWTJzaU9pSm9kSFJ3Y3pvdkwyTm9ZWE54ZFdrdWRYQnZjblF1YldVdllYQnBMM1l4TDNSdmNHbGpMMlJqT0dsQ2RVOTBNek5CV0c5YVQxQWlMQ0p1WlhRaU9pSXdlRFFpTENKMGVYQmxJam9pYzJoaGNtVlNaWEVpTENKcGMzTWlPaUl5YjJwRmRGVllRa3N5U2pjMVpVTkNZWHA2TkhSdVkwVlhSVEU0YjBaWGNtNW1TaUo5LmZTSm9iUGkydGU0eGtMcFNaOGtZYnpxUWFyMGZBZXcyTkRQdGl0SC1uSDdzZnhwdy0zSUxTS1FPeVdCcWNISUFTZFhUTXdqcS1feGJCb1YycWtHLVJ3IiwiaXNzIjoiMm90MWhDdVZBTDZuUTNOUXJ5amtCQVJHdHNqNHJzYW81NzUifQ.vq79al2TTExABAe-gorjAq5Iq56DBi-lMylUhS39UK5w0IzkFAfj8O6Gj-_DryOuBCZNJn8SyJYWmbQQwQU14Q";

	// uportjs
	// $jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NksifQ.eyJpYXQiOjE1NjA5NjI0OTgsInJlcXVlc3RlZCI6WyJuYW1lIiwiZW1haWwiXSwiY2FsbGJhY2siOiJodHRwczovL2NoYXNxdWkudXBvcnQubWUvYXBpL3YxL3RvcGljL2RjOGlCdU90MzNBWG9aT1AiLCJuZXQiOiIweDQiLCJ0eXBlIjoic2hhcmVSZXEiLCJpc3MiOiIyb2pFdFVYQksySjc1ZUNCYXp6NHRuY0VXRTE4b0ZXcm5mSiJ9.fSJobPi2te4xkLpSZ8kYbzqQar0fAew2NDPtitH-nH7sfxpw-3ILSKQOyWBqcHIASdXTMwjq-_xbBoV2qkG-Rw";


// Verify		

$isVerified = $jwtTools->verifyJWT($jwt);

error_log('isVerified ' . $isVerified); 



function generate_string() {
	$strength = 16;
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}
