<?php
/**
* Adds a submenu page under a custom post type parent.
*/
add_action('admin_menu', 'resources_register_ref_page');

function resources_register_ref_page() {
    add_submenu_page(
        'edit.php?post_type=resources',
        __( 'Resource Settings', text_Domain ),
        __( 'Resource Settings', text_Domain ),
        'manage_options',
        'resources-settings',
        'resources_ref_page_callback'
    );
	
	//call register settings function
	add_action( 'admin_init', 'register_resources_settings' );
}

function register_resources_settings() {
	//register our settings
	register_setting( 'resources-settings-group', 'resource_page_title' );
	register_setting( 'resources-settings-group', 'resource_page_description' );
	register_setting( 'resources-settings-group', 'resource_page_banner' );
	register_setting( 'resources-settings-group', 'resource_page_noContent_text' );
}

/**
* Display callback for the submenu page.
*/
function resources_ref_page_callback() { 
    ?>
	<style>
		/*for admin settings page of resources*/
		.field-width-100{
			width:100%;
		}
	</style>
    <div class="wrap form-fields-100">
        <h1><?php _e( 'Resources Settings', text_Domain ); ?></h1>
        <div class="wrap">

		<form method="post" action="options.php">
			<?php settings_fields( 'resources-settings-group' ); ?>
			<?php do_settings_sections( 'resources-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">Page Title</th>
				<td><input type="text" name="resource_page_title" class="field-width-100" value="<?php echo esc_attr( get_option('resource_page_title') ); ?>" /></td>
				</tr>
				 
				<tr valign="top">
				<th scope="row">Page Description</th>
				<td><textarea name="resource_page_description" class="field-width-100" ><?php echo esc_attr( get_option('resource_page_description') ); ?></textarea></td>
				</tr>
				
				<tr valign="top">
				<th scope="row">Page Banner</th>
				<td><input type="text" name="resource_page_banner" class="field-width-100" value="<?php echo esc_attr( get_option('resource_page_banner') ); ?>" /></td>
				</tr>
				
				<tr valign="top">
				<th scope="row">No Content Text</th>
				<td><input type="text" name="resource_page_noContent_text" class="field-width-100" value="<?php echo esc_attr( get_option('resource_page_noContent_text') ); ?>" /></td>
				</tr>
			</table>
			
			<?php submit_button(); ?>

		</form>
		</div>
    </div>
    <?php
}
?>