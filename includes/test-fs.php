<?php

$key       = get_or_create_encryption_key();

$plaintext = "cb89a98b53eec9dc58213e67d04338350e7c15a7f7643468d8081ad2c5ce5480";
$cipher    = "aes-128-gcm";
$ivlen     = openssl_cipher_iv_length($cipher);
$iv        = openssl_random_pseudo_bytes($ivlen);

$encrypted = openssl_encrypt( $plaintext, $cipher, $key, $options=0, $iv, $tag);

print_r('encrypted is ' . $encrypted . "\r\n");

$decrypted = openssl_decrypt($encrypted, $cipher, $key, $options=0, $iv, $tag );

print_r('decrypted is ' . $decrypted . "\r\n"); 



function get_or_create_encryption_key () {

    // $file = plugin_dir_path( __FILE__ ) . '/key.txt'; 
    $filename = "./key.txt";
    $filesize = filesize($filename);

    if ( 0 < $filesize ) {
	    $file = fopen( $filename, "r+" ); 
	    $contents = fread( $file, $filesize );
	    print_r( 'key found: ' . $contents . "\r\n" );
	    return $contents;
	    fclose( $file );
	} else {
		print_r( 'file is empty or corrupted' . "\r\n" );
		return create_new_key();
	}

}


function create_new_key () {

	$key      = openssl_random_pseudo_bytes(64);
    $filename = "./key.txt";
    $file     = fopen( $filename, "r+" ); 

    fputs( $file, $key );
    fclose( $file );

    print_r('created and saved new key ' . $key . "\r\n" );	

    return $key;

}


// function generate_random_string( $strength = 16 ) {
// 	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//     $input_length = strlen( $permitted_chars );
//     $random_string = '';
//     for($i = 0; $i < $strength; $i++) {
//         $random_character = $permitted_chars[mt_rand( 0, $input_length - 1 )];
//         $random_string    .= $random_character;
//     }
//     return $random_string;
// }