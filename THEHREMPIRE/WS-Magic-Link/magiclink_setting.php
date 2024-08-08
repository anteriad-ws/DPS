<?php
function ml_add_settings_page() {
    add_options_page('Magic Link plugin', 'Magic Link', 'manage_options', 'dbi-example-plugin', 'ml_render_plugin_settings_page');
    add_options_page('Magic Link plugin', 'Magic Link setting', 'manage_options', 'magic_link_setting', 'ml_render_plugin_settings_page');
}

?>