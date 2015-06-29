<?php
/*
 * Header strony
 */
?>

<?php doctype('html5'); ?>

<html lang="pl">
<head>
  <meta charset="utf-8">

  <title><?php echo $title ?></title>
  
  <?php
    $meta = array(
        array('name' => 'JB', 'content' => 'User database system')
    );
    
    //<meta name="JB" content="Users database">
    echo meta($meta);
  ?>
  
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>