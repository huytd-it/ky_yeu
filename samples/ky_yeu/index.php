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
    <script type="text/javascript" src="../../extras/jquery-ui-1.8.20.custom.min.js"></script>
    <script type="text/javascript" src="../../lib/hash.js"></script>
    <style>
        body {
            background-color: gainsboro;
        }

        .flipbook-viewport .double {
            overflow-y: auto;
            width: 960px !important;
            height: 600px !important;
        }

        .flipbook-viewport .flipbook {
            top: -300px !important;
            left: -475px !important;
        }
    </style>
</head>

<body>
    <?php
    $dir    = 'pages';
    $files1 = scandir($dir);
    sort($files1, SORT_NATURAL | SORT_FLAG_CASE);
 
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
                        if ($key == 2) {

                            echo '<div class="page" style="background-image:url(' . $dir . '/' . $file . ')"></div>';
                        } else
                            echo '<div class="double" style="background-image:url(' . $dir . '/' . $file . ')"></div>';
                    }
                } ?>
            </div>
        </div>
        <div class="bottom">
            <div id="slider-bar" class="turnjs-slider">
                <div id="slider"></div>
            </div>
        </div>
      
    </div>


    <script type="text/javascript">
        function loadApp() {

            var flipbook = $('.flipbook');
            if (flipbook.width() == 0 || flipbook.height() == 0) {
                setTimeout(loadApp, 10);
                return;
            }
            $('.flipbook .double').scissor();
            $('.flipbook').turn({
                // Elevation
                width: 960,
                height: 600,
                elevation: 50,
                gradients: true,
                duration: 1000,
                autoCenter: true,
            });
            Hash.on('^page\/([0-9]*)$', {
                yep: function(path, parts) {
                    var page = parts[1];

                    if (page !== undefined) {
                        if ($('.flipbook').turn('is'))
                            $('.flipbook').turn('page', page);
                    }

                },
                nop: function(path) {

                    if ($('.flipbook').turn('is'))
                        $('.flipbook').turn('page', 1);
                }
            });
            // Load the HTML4 version if there's not CSS transform
            $("#slider").slider({
                min: 1,
                max: numberOfViews(flipbook),

                start: function(event, ui) {

                    if (!window._thumbPreview) {
                        _thumbPreview = $('<div />', {
                            'class': 'thumbnail'
                        }).html('<div></div>');
                        setPreview(ui.value);
                        _thumbPreview.appendTo($(ui.handle));
                    } else
                        setPreview(ui.value);

                    moveBar(false);

                },

                slide: function(event, ui) {

                    setPreview(ui.value);

                },

                stop: function() {

                    if (window._thumbPreview)
                        _thumbPreview.removeClass('show');

                    $('.flipbook').turn('page', Math.max(1, $(this).slider('value') * 2 - 2));

                }
            });


        }
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

                    $('.flipbook-viewport').zoom('zoomOut');
                    e.preventDefault();

                    break;
            }
        });
        yepnope({
            test: Modernizr.csstransforms,
            yep: ['../../lib/turn.min.js'],
            nope: ['../../lib/turn.html4.min.js', 'css/jquery.ui.html4.css'],
            both: ['../../lib/zoom.min.js', '../../lib/scissor.min.js', 'css/double-page.css', 'css/jquery.ui.css','js/magazine.js','css/magazine.css'],
            complete: loadApp
        });
    </script>

</body>

</html>