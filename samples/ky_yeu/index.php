<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <meta name="viewport" content="width = 1050, user-scalable = no" />
    <script type="text/javascript" src="../../extras/jquery.min.1.7.js"></script>
    <script type="text/javascript" src="../../extras/modernizr.2.5.3.min.js"></script>
    <script type="text/javascript" src="../../extras/jquery.min.1.7.js"></script>
    <script type="text/javascript" src="../../lib/hash.js"></script>
</head>

<body>
    <?php
    $dir    = 'pages';
    $files1 = scandir($dir);


    
    // print_r($files2);
    print_r($files1);
    sort($file1, SORT_NATURAL | SORT_FLAG_CASE);
    ?>



    <div class="zoom-icon zoom-icon-in"></div>

    <div class="magazine-viewport">
        <div class="container">
            <div class="magazine">
                <!-- Next button -->
                <div ignore="1" class="next-button"></div>
                <!-- Previous button -->
                <div ignore="1" class="previous-button"></div>
            </div>
        </div>
    </div>
    <div class="flipbook-viewport">
        <div class="container">
            <div class="flipbook">
                <?php foreach ($files1 as $key => $file) {

                    if ($key >  1) {
                        if($key == 2) {


                ?><div class="page" style="background-image:url('<?php echo $dir . '/' . $file ?>')"></div>
                <?php }else  ?>
                        <div class="double" style="background-image:url('<?php echo $dir . '/' . $file ?>')"></div>

                <?php 
                    }

                } ?>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function loadApp() {

            var flipbook = $('.flipbook');

            // Check if the CSS was already loaded

            if (flipbook.width() == 0 || flipbook.height() == 0) {
                setTimeout(loadApp, 10);
                return;
            }

            $('.flipbook .double').scissor();

            // Create the flipbook

            $('.flipbook').turn({
                // Elevation

                elevation: 50,

                // Enable gradients

                gradients: true,

                // Auto center this flipbook

                autoCenter: true

            });
        }

        // Load the HTML4 version if there's not CSS transform

        yepnope({
            test: Modernizr.csstransforms,
            yep: ['../../lib/turn.min.js'],
            nope: ['../../lib/turn.html4.min.js'],
            both: ['../../lib/scissor.min.js', 'css/double-page.css'],
            complete: loadApp
        });
        $(document).keydown(function(e) {

            var previous = 37,
                next = 39,
                esc = 27;

            switch (e.keyCode) {
                case previous:

                    // left arrow
                    $('.flipbook').turn('previous');
                    e.preventDefault();

                    break;
                case next:

                    //right arrow
                    $('.flipbook').turn('next');
                    e.preventDefault();

                    break;
                case esc:

                    $('.magazine-viewport').zoom('zoomOut');
                    e.preventDefault();

                    break;
            }
        });
    </script>

</body>

</html>