<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function SOC_activate_function(){
    flush_rewrite_rules();
}

function SOC_deactivate_function(){
    flush_rewrite_rules();
}

