<?php

if (!isset($uport)) {
    error_log('init uport');
    $uport     = new Uport ();
}

/**
 * Provide a admin area view for the uPort plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://uport.me/
 * @since      1.0.0
 *
 * @package    uPort
 * @subpackage uPort/admin/partials
 */

function get_uport_option ( $name ) {
	error_log('getting option for ' . $name);
	$options = get_option('uport');
	if(isset($options['uport-' . $name])){
		error_log('found option ' . $options['uport-' . $name]);
		return $options['uport-' . $name];
	} else {
		error_log('not found');
		return "";
	}
}

$network_option = getSelectedNetwork();

function getSelectedNetwork () {

    $is_selected = [
        0 => "",
        1 => "",
        2 => "",
        3 => "",    
    ];

    $current_network = get_uport_option('network');

    if ( "" === $current_network ) {

    } else {

        $is_selected[((explode( 'x', $current_network ))[1] - 1)]  = "selected";
   
    }

    return $is_selected;

}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
 
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    
    <form method="post" name="my-rdm-quotes_options" action="options.php">
    
		<?php settings_fields($this->plugin_name); ?>
 	
        <!-- Optional title for quotes list -->
        <fieldset>
            <h2>Organization Credentials</h2>
            <legend class="screen-reader-text"><span>MNID</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-mnid">
                <input type="text" id="<?php echo $this->plugin_name; ?>-mnid" name="<?php echo $this->plugin_name; ?>[uport-mnid]" value="<?php echo get_uport_option('mnid'); ?>" />
                <span><?php esc_attr_e('Your Uport MNID', $this->plugin_name); ?></span>
            </label>
            <br>
            <legend class="screen-reader-text"><span>Authentication Key</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-key">
                <input type="text" id="<?php echo $this->plugin_name; ?>-key" name="<?php echo $this->plugin_name; ?>[uport-key]" value="<?php echo $uport->decrypt_stored_key( get_uport_option('key') ); ?>"/>
                <span><?php esc_attr_e('Your Uport Private Key (in Hex Format)', $this->plugin_name); ?></span>
            </label>
            <br>
            <legend class="screen-reader-text"><span>Login Redirect</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-login-url">
                <input type="text" id="<?php echo $this->plugin_name; ?>-key" name="<?php echo $this->plugin_name; ?>[uport-login-url]" value="<?php echo get_uport_option('login-url'); ?>"/>
                <span><?php esc_attr_e('A url to redirect to on successful login. (leave empty to set homepage)', $this->plugin_name); ?></span>
            </label>          
            <br>
            <legend class="screen-reader-text"><span>Login Redirect</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-login-url">
                <select id="<?php echo $this->plugin_name; ?>-network" name="<?php echo $this->plugin_name; ?>[uport-network]">
                    <option <?php echo $network_option[0]; ?> value="0x1">0x1 - Mainnet</option>
                    <option <?php echo $network_option[1]; ?> value="0x2">0x2 - </option>
                    <option <?php echo $network_option[2]; ?> value="0x3">0x3 - </option>
                    <option <?php echo $network_option[3]; ?> value="0x4">0x4 - Ropsten</option>
                </select>
                <span><?php esc_attr_e('Choose which Ethereum network to use.', $this->plugin_name); ?></span>
            </label>         
        </fieldset>
        <br><hr><br>
        <div>
        	<span><b><i>Don't have a uport organization set up yet?</i></b></span>
        		<br><br>
        		<a target="_blank" href="https://developer.uport.me/myapps/" >Make one here</a>
        </div>

        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
 
    </form>
 
</div>