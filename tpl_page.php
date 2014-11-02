<?php
//require 'require_session_req.php';
?>
<!DOCTYPE html>
<!--[if IE 7]> <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <title>Staff Testing</title>'
    <script src="js/fxn.js"></script>
</head>

<body class="home"> <!-- class="home" underlines the Home link in the top nav -->
<div id="su-wrap"> <!-- used to force footer to bottom of short pages -->
  <div id="su-content"> <!-- used to force footer to bottom of short pages --> 
    
    <!--=== Top ===-->
    <div id="top">
      <div class="container"> 
        <!--=== Skip links ===-->
        <div id="skip"> <a href="#content" onClick="$('#content').focus()">Skip to content</a> </div>
        <!-- /Skip links --> 
      </div>
    </div>
    <!--/top--> 
    
    <?php include 'include_header.php'; ?> 
    <?php include 'include_menu.php'; ?> 
    
    <div id="content" class="container" role="main" tabindex="0">
        <?php include 'tpl_page_'.$page_var;?>
    </div>
    
  </div>
  <!-- #su-content --> 
</div>
<!-- #su-wrap --> 

<?php include 'include_footer.php'; ?>
</body>
</html>
