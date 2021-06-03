<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <meta charset="UTF-8">
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

            width: 960px !important;
            height: 600px !important;
        }

        .flipbook-viewport .flipbook {
            top: -300px !important;
            left: -475px !important;
        }
        .btn {
           
            transition: .5 background-color;
        }
        .btn-left {
            border-radius: 15px 0px 0px 15px ;
        }
        .btn-right {
            border-radius: 0px 15px 15px 0px ;
        }
        .btn:hover {
            background-color: black !important;
           
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
           
            </div>
        </div>

    </div>
    <div class="flipbook-viewport">
        <div class="container">
            <div class="flipbook">
                <!-- <div ignore="1" class="previous-button-2" style=" background:url(pics/arrows.png) no-repeat left center; background-color: #5c5e5c5c;
                position: absolute;top:50%; transform:translate(-100%,-50%);
    width: 40px;
    height: 100%;
    z-index: 600;"></div> -->
                <div ignore="1" class="previous-button btn btn-left" style=" background:url(pics/arrows.png) no-repeat left center; background-color: #5c5e5c5c;
                position: absolute;top:50%; transform:translate(-100%,-50%);
    width: 40px;
    height: 100%;
    z-index: 600;"></div>
                <!-- Previous button -->
                <div ignore="1" class="next-button btn btn-right" style="  background:url(pics/arrows.png) no-repeat right center;  background-color: #5c5e5c5c;
                position: absolute;
    width: 40px;
    height: 100%;top:50%; transform:translate(100%,-50%);
    z-index: 600; right:0"></div>
                <?php

                foreach ($files1 as $key => $file) {
                    $page = $dir . '/' . $file;
                    if ($key >  1) {

                        if ($key == 2 || $key == count($files1) - 1) {

                            echo "<div class='page' ><img style='width:100%; height:100%' src='$page'></div>";
                        } else {

                            echo "<div class='double'><img style='width:100%; height:100%' src='$page'></div>";
                        }
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
                when: {
                    turning: function(event, page, view) {

                        var book = $(this),
                            currentPage = book.turn('page'),
                            pages = book.turn('pages');

                        // Update the current URI

                        Hash.go('page/' + page).update();

                        // Show and hide navigation buttons

                        disableControls(page);


                        $('.thumbnails .page-' + currentPage).
                        parent().
                        removeClass('current');

                        $('.thumbnails .page-' + page).
                        parent().
                        addClass('current');



                    },
                }

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
            $('.next-button').bind($.mouseEvents.over, function() {

                $(this).addClass('next-button-hover');

            }).bind($.mouseEvents.out, function() {

                $(this).removeClass('next-button-hover');

            }).bind($.mouseEvents.down, function() {

                $(this).addClass('next-button-down');

            }).bind($.mouseEvents.up, function() {

                $(this).removeClass('next-button-down');

            }).click(function() {

                $('.flipbook').turn('next');

            });

            // Events for the next button

            $('.previous-button').bind($.mouseEvents.over, function() {

                $(this).addClass('previous-button-hover');

            }).bind($.mouseEvents.out, function() {

                $(this).removeClass('previous-button-hover');

            }).bind($.mouseEvents.down, function() {

                $(this).addClass('previous-button-down');

            }).bind($.mouseEvents.up, function() {

                $(this).removeClass('previous-button-down');

            }).click(function() {

                $('.flipbook').turn('previous');

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

                        $('.flipbook-viewport').zoom('zoomOut');
                        e.preventDefault();

                        break;
                }
            });


        }
        // if ($.isTouch)
        //     $('.flipbook-viewport').bind('zoom.doubleTap', zoomTo);
        // else
        //     $('.flipbook-viewport').bind('zoom.tap', zoomTo);

        // if ($.isTouch) {
        //     $('.flipbook').bind('touchstart', regionClick);
        // } else {
        //     $('.flipbook').click(regionClick);
        // }



        $('.zoom-icon').bind('mouseover', function() {

            if ($(this).hasClass('zoom-icon-in'))
                $(this).addClass('zoom-icon-in-hover');

            if ($(this).hasClass('zoom-icon-out'))
                $(this).addClass('zoom-icon-out-hover');

        }).bind('mouseout', function() {

            if ($(this).hasClass('zoom-icon-in'))
                $(this).removeClass('zoom-icon-in-hover');

            if ($(this).hasClass('zoom-icon-out'))
                $(this).removeClass('zoom-icon-out-hover');

        }).bind('click', function() {

            if ($(this).hasClass('zoom-icon-in'))
                $('.magazine-viewport').zoom('zoomIn');
            else if ($(this).hasClass('zoom-icon-out'))
                $('.magazine-viewport').zoom('zoomOut');

        });

        yepnope({
            test: Modernizr.csstransforms,
            yep: ['../../lib/turn.min.js'],
            nope: ['../../lib/turn.html4.min.js', 'css/jquery.ui.html4.css'],
            both: ['../../lib/zoom.min.js', '../../lib/scissor.min.js', 'css/double-page.css', 'css/jquery.ui.css', 'js/magazine.js', 'css/magazine.css'],
            complete: loadApp
        });
    </script>

</body>

</html>