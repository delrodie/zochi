jQuery(function ($) {
    'use strict';

    $(window).on('scroll', function () {
        // checks if window is scrolled more than 500px, adds/removes solid class
        if ($(this).scrollTop() > 60) {
            $('.navbar').addClass('affix');
            $('.scroll-to-target').addClass('open');
        } else {
            $('.navbar').removeClass('affix');
            $('.scroll-to-target').removeClass('open');
        }
    });

    // Count
    $('.count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 6000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    // DataTable
    $(document).ready(function() {
        var table = $('#participant').DataTable( {
            lengthChange: false,
            buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            }
        } );

        table.buttons().container()
            .appendTo( '#example_wrapper .col-md-6:eq(0)' );
    } );

    // Retour en haut
    if ($('.scroll-to-target').length) {
        $(".scroll-to-target").on('click', function () {
            var target = $(this).attr('data-target');
            // animate
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 500);

        });
    }

    // WOW
    new WOW().init();
});