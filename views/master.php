<?php
/*
  Master View
*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Project Title</title>
    
    <!--<script src="assets/js/jquery-1.7.2.min.js"></script>
    <script src="assets/js/jquery-ui-1.8.20.custom.min.js"></script>-->
    
    <link rel="stylesheet" href="assets/css/master.css" />


    <?php
    if(isset($styles)) {
        if(is_array($styles)) {
            foreach($styles as $style) {
                echo '<link rel="stylesheet" href="' . $style . '" />';
            }
        } else {
            echo '<link rel="stylesheet" href="' . $styles . '" />';	
        }
    }
    ?>

    <?php if(isset($scripts)) {
    if(is_array($scripts)) {
        foreach($scripts as $script) {
            echo '<script type="text/javascript" src="' . $script . '"></script>';
        }
    } else {
        echo '<script type="text/javascript" src="' . $scripts . '"></script>';
    }
    }?>

</head>
<body>
	
    <div id="container">
        <?php echo $view; ?>
    </div>
	
</body>
</html>