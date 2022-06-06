<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<div class="wrap">
    <?php if( !isset($_GET['tab']) ) $_GET['tab'] = 'first_tab';?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=wp_soc&tab=first_tab"
           class="nav-tab<?php if( $_GET['tab'] == 'first_tab'){echo ' nav-tab-active';};?>">
            <?php echo __('General Settings', 'socialite_lan'); ?>
        </a>
        <a href="?page=wp_soc&tab=second_tab"
           class="nav-tab<?php if( $_GET['tab'] == 'second_tab'){echo ' nav-tab-active';};?>">
            <?php echo __('Others', 'socialite_lan'); ?>
        </a>
    </h2>

    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php
        if ( $_GET['tab'] == 'first_tab' ) {
            settings_fields("mainPage_settings_options");
            do_settings_sections("mainPage_settings");
        } elseif ($_GET['tab'] == 'second_tab') {
         //
        }


        submit_button();
        ?>
    </form>
</div>



