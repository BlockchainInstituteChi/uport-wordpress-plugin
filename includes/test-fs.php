<?php

get_or_create_encryption_key();

function get_or_create_encryption_key () {

    // $file = plugin_dir_path( __FILE__ ) . '/key.txt'; 
    $filename = "./key.txt";
    $filesize = filesize($filename);
    if ( 0 < $filesize ) {
	    $file = fopen( $filename, "r+" ); 
	    $contents = fread( $file, $filesize );
	    print_r( 'printing key: ' . $contents . "\r\n" );
	    return $contents;
	    fclose( $file );
	} else {
		print_r( 'file is empty or corrupted' . "\r\n" );
		return create_new_key();
	}

}

function create_new_key () {

	$key      = generate_random_string();
    $filename = "./key.txt";
    $file     = fopen( $filename, "r+" ); 

    fputs( $file, $key );
    fclose( $file );	

    return $key;

}


function generate_random_string() {
	$strength = 16;
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen( $permitted_chars );
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $permitted_chars[mt_rand( 0, $input_length - 1 )];
        $random_string    .= $random_character;
    }
    return $random_string;
}