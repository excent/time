<?php
$imagePath = theme_path("images/login");
$version = '3.0';
$copyrightYear = date('Y');
?>

<style type="text/css">
    #divFooter {
        text-align: center;
    }
    
    #spanCopyright, #spanSocialMedia {
        padding: 20px 10px 10px 10px;
    }
    
    #spanSocialMedia a img {
		border: none;
    }

</style>
<div id="divFooter" >
    <span id="spanCopyright">
        <a href="http://www.excent.co" target="_blank">Excent</a> 
        &copy; 2007 - <?php echo $copyrightYear; ?> All rights reserved.
    </span>
    <span id="spanSocialMedia">
        <a href="http://www.linkedin.com/groups?home=&gid=891077" target="_blank">
            <img src="<?php echo "{$imagePath}/linkedin.png"; ?>" /></a>&nbsp;
        <a href="http://www.facebook.com/OrangeHRM" target="_blank">
            <img src="<?php echo "{$imagePath}/facebook.png"; ?>" /></a>&nbsp;
        <a href="http://twitter.com/orangehrm" target="_blank">
            <img src="<?php echo "{$imagePath}/twiter.png"; ?>" /></a>&nbsp;
        	
    </span>
    <br class="clear" />
</div>
