<?php 

// Allow header partial to be overridden in individual actions
// Can be overridden by: slot('header', get_partial('module/partial'));
include_slot('header', get_partial('global/header'));
$currentYear = date('Y');
?>

    </head>
    <body>
      
        <div id="wrapper">
            
            <div id="branding">
                <img src="<?php echo theme_path('images/logo.png')?>" width="283" height="56" alt="OrangeHRM"/>
                <a href="http://www.excent.co" class="subscribe" target="_blank"><?php echo __('Visit Excent Public Page'); ?></a>
                <a href="#" id="welcome" class="panelTrigger"><?php echo __("Welcome %username%", array("%username%" => $sf_user->getAttribute('auth.firstName'))); ?></a>
                <div id="welcome-menu" class="panelContainer">
                    <ul>
                        <li><a href="<?php echo url_for('admin/changeUserPassword'); ?>"><?php echo __('Change Password'); ?></a></li>
                        <li><a href="<?php echo url_for('auth/logout'); ?>"><?php echo __('Logout'); ?></a></li>
                    </ul>
                </div>
                
            </div> <!-- branding -->      
            
            <?php include_component('core', 'mainMenu'); ?>

            <div id="content">

                  <?php echo $sf_content ?>

            </div> <!-- content -->
          
        </div> <!-- wrapper -->
        
        <div id="footer">
            Excent &copy; <a href="http://www.excent.co" target="_blank">Excent</a>. 2005 - <?php echo $currentYear?> All rights reserved.
        </div> <!-- footer -->        
        
        
<?php include_slot('footer', get_partial('global/footer'));?>