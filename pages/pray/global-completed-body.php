<?php
$current_lap = pg_current_global_lap();
$lap_parts = $this->parts;
$lap_post_id = $lap_parts['post_id'];

$this_lap_number = get_post_meta( $lap_post_id, 'global_lap_number', true );

?>

<nav class="navbar navbar-expand-lg navbar-dark pb_navbar pb_navbar_nav pb_scrolled-light" id="pb-navbar">
    <div class="container">
        <a class="navbar-brand" href="/">Prayer.Global</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#probootstrap-navbar" aria-controls="probootstrap-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span><i class="ion-navicon"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="probootstrap-navbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="/#section-home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/#section-lap">Current Lap</a></li>
                <li class="nav-item"><a class="nav-link" href="/#section-about">About</a></li>
                <li class="nav-item"><a class="nav-link btn smoothscroll pb_outline-dark" style="text-transform: capitalize;" href="/newest/lap/">Start Praying</a></li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .pb_cover_v1.completed-lap .container .row {
        height: 10vh;
        padding-top:10vh;
    }
</style>
<section class="pb_cover_v1 completed-lap text-left cover-bg-black cover-bg-opacity-4" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/map_background.jpg)" id="section-home">
    <div class="container">
        <div class="row ">
            <div class="col text-center">
                <h2 class="heading mb-5">Lap <?php echo esc_attr( $this_lap_number ) ?> Completed!</h2>
                <a href="/newest/lap/" style="background-color:rgba(255,255,255,.7);" role="button" class="btn smoothscroll pb_font-25 btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Go To The Current Lap <?php echo esc_attr( $current_lap['lap_number'] ) ?></a><br>
                <hr style="border:1px solid white;margin-top:5vh;">
            </div>
            <div class="w-100"></div>
            <div class="col-md-6 justify-content-end">
                <h2 class="heading mb-3">Prayer</h2>
                <div class="sub-heading pl-4">
                    <p class="mb-0">4770+ Minutes of Prayer</p>
                    <p class="mb-0">All Populated Places in the World Covered</p>
                    <p class="mb-0">213 Prayer Warriors Participated</p>
                </div>
            </div>
            <div class="col-md-6 justify-content-end">
                <h2 class="heading mb-3">Pace</h2>
                <div class="sub-heading pl-4">
                    <p class="mb-0">Start: 6-12-2022</p>
                    <p class="mb-0">End: 6-22-2022</p>
                    <p class="mb-0">10 days, 10 hours, 5 minutes</p>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col justify-content-end">
                <h2 class="heading mb-3">Participants</h2>
            </div>
            <div class="w-100"></div>
            <div class="col-md-6">
                <div class="sub-heading pl-4">
                    <p class="mb-2"><u>Top Countries</u></p>
                    <p class="mb-0">United States</p>
                    <p class="mb-0">Brazil</p>
                    <p class="mb-0">Spain</p>
                    <p class="mb-0">Ghana</p>
                    <p class="mb-0">Nigeria</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sub-heading pl-4">
                    <p class="mb-2"><u>Top Commitments</u></p>
                    <p class="mb-0">2 prayed for 300 minutes</p>
                    <p class="mb-0">10 prayed for 110 minutes</p>
                    <p class="mb-0">100 prayed for 30 minutes</p>
                    <p class="mb-0">45 prayed for 20 minutes</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END section -->
