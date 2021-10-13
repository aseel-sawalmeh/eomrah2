<footer >
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->

    <script src="<?php echo base_url('public_designs/assets') ?>/js/popper.js" defer></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/bootstrap-4.4.1.js" defer></script>
    <script>
        var base_url = '<?= site_url() ?>';
    </script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/custom.js" defer></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/slick.min.js"></script>

    <script>
        var imgSliders = document.getElementsByClassName('image_slider');
        var htSliders = document.getElementsByClassName('hotel_slider');
        if (imgSliders.length > 0 || htSliders.length > 0) {
            $('.image_slider').slick({
                centerMode: true,
                centerPadding: '0px',
                slidesToShow: 4,
                autoplay: true,
                autoplaySpeed: 5000,
                <?php if (lngdir() == "rtl") : ?>
                    rtl: true,
                <?php else : ?>
                    rtl: false,

                <?php endif; ?>
                responsive: [{
                        breakpoint: 768,
                        settings: {
                            arrows: true,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 1
                        }
                    }
                ],
            });
        }
    </script>
    <script>
        window.scroll({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });
    </script>

</footer>
</body>

</html>