<?php


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
		echo $options['uport-' . $name];
	} else {
		error_log('not found');
		echo "";
	}
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
                <input type="text" id="<?php echo $this->plugin_name; ?>-mnid" name="<?php echo $this->plugin_name; ?>[uport-mnid]" value="<?php get_uport_option('mnid'); ?>" />
                <span><?php esc_attr_e('Your Uport MNID', $this->plugin_name); ?></span>
            </label>
            <br>
            <legend class="screen-reader-text"><span>Authentication Key</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-key">
                <input type="text" id="<?php echo $this->plugin_name; ?>-key" name="<?php echo $this->plugin_name; ?>[uport-key]" value="<?php get_uport_option('key'); ?>"/>
                <span><?php esc_attr_e('Your Uport Private Key (in Hex Format)', $this->plugin_name); ?></span>
            </label>
            <br>
            <legend class="screen-reader-text"><span>Login Redirect</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-login-url">
                <input type="text" id="<?php echo $this->plugin_name; ?>-key" name="<?php echo $this->plugin_name; ?>[uport-login-url]" value="<?php get_uport_option('login-url'); ?>"/>
                <span><?php esc_attr_e('A url to redirect to on successful login. (leave empty to set homepage)', $this->plugin_name); ?></span>
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