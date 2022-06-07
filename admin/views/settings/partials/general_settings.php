<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('admin_init',function (){
    add_settings_section('general_settings_options', __('Provider Info', 'socialite_lan'), null, 'general_settings');
    add_settings_field('setting_github_label', __('Github Settings', 'socialite_lan'), 'setting_github_label_callback'
        , 'general_settings', 'general_settings_options');
    add_settings_field('setting_github_client_id', __('Github Client Id', 'socialite_lan'), 'setting_github_client_id_callback'
        , 'general_settings', 'general_settings_options');
    register_setting('general_settings_options', 'setting_github_client_id', 'sanitize_text_field');
    add_settings_field('setting_github_client_secret', __('Github Client Secret', 'socialite_lan'), 'setting_github_client_secret_callback'
        , 'general_settings', 'general_settings_options');
    register_setting('general_settings_options', 'setting_github_client_secret', 'sanitize_text_field');
});


function setting_github_label_callback(){echo '</br><hr>';}
function setting_github_client_id_callback()
{
    echo '<input type="text" class="ltr left-align" id="setting_github_client_id" 
    name="setting_github_client_id" value="' . get_option('setting_github_client_id','') . '"> ';
}
function setting_github_client_secret_callback()
{
    echo '<input type="text" class="ltr left-align" id="setting_github_client_secret" 
    name="setting_github_client_secret" value="' . get_option('setting_github_client_secret','') . '"> ';
}