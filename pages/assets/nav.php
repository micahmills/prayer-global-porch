<?php
$url = dt_get_url_path();

/**
 * Nav for Home Page
 */
if ( '' === $url ) { ?>
<nav class="navbar navbar-expand-lg navbar-dark pb_navbar pb_navbar_nav pb_scrolled-light" id="pb-navbar">
    <div class="container">
        <a class="navbar-brand" href="/">Prayer.Global</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#probootstrap-navbar" aria-controls="probootstrap-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span><i class="ion-navicon"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="probootstrap-navbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#section-home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#section-about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#section-challenge">Challenge</a></li>
                <li class="nav-item"><a class="nav-link" href="#section-lap">Status</a></li>
<!--                <li class="nav-item"><a class="nav-link btn smoothscroll pb_outline-dark" style="text-transform: capitalize;" href="/newest/lap/">Start Praying</a></li>-->
                <li class="nav-item"><a class="nav-link btn smoothscroll pb_outline-dark" style="text-transform: capitalize;" href="/prayer_app/subscribe/">Start Praying</a></li>
            </ul>
        </div>
    </div>
</nav>

<?php } else {
    /**
     * Nav for Inner Pages
     */
    ?>
    <nav class="navbar navbar-expand-lg navbar-light pb_navbar_light pb_navbar_nav pb_scrolled-light" id="pb-navbar">
        <div class="container">
            <a class="navbar-brand" href="/">Prayer.Global</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#probootstrap-navbar" aria-controls="probootstrap-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span><i class="ion-navicon"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="probootstrap-navbar">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#section-about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#section-challenge">Challenge</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#section-lap">Status</a></li>
<!--                    <li class="nav-item"><a class="nav-link btn smoothscroll pb_outline-dark" style="text-transform: capitalize;" href="/newest/lap/">Start Praying</a></li>-->
                    <li class="nav-item"><a class="nav-link btn smoothscroll pb_outline-dark" style="text-transform: capitalize;border:1px black solid; border-radius: 4px;" href="/prayer_app/subscribe/">Start Praying</a></li>
                </ul>
            </div>
        </div>
    </nav>

<?php } ?>
