<?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' ) ?>
<?php
$current_global_lap = pg_current_global_lap();
$current_global_stats = pg_global_stats_by_lap_number($current_global_lap['lap_number']);
$global_race = pg_global_race_stats();
?>

<section class="pb_cover_v1 text-left cover-bg-black cover-bg-opacity-4" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/map_background.jpg)" id="section-home">
    <div class="container">
        <div class="row align-items-center justify-content-end">
            <div class="col-md-6  order-md-1">
                <h2 class="heading mb-3">Cover the World in Prayer</h2>
                <div class="sub-heading">
                    <p class="mb-5">Community driven, movement-focused, saturation prayer.</p>
<!--                    <p><a href="/newest/lap/" style="background-color:rgba(255,255,255,.7);" role="button" class="btn smoothscroll pb_font-25 btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Start Praying</a></p>-->
                    <p><a href="/prayer_app/subscribe/" style="background-color:rgba(255,255,255,.7);" role="button" class="btn smoothscroll pb_font-25 btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Start Praying</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<section class="pb_section pb_section_v1" data-section="lap" id="section-lap">
    <div class="container">
        <div class="row">
            <div class="col-md text-center pb_sm_py_cover">
                <h2 class=" mb-3 heading" style="color:#212529">Current Lap</h2>
                <h3 class="mt-0 heading-border-top pb_font-30"><?php echo $current_global_stats['time_elapsed'] ?></h3>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-ios-body-outline"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45"><?php echo $current_global_stats['participants'] ?></h3>
                        <h3 class="mt-0 pb_font-20">Prayer Warriors</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-block mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-earth"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45"><?php echo $current_global_stats['completed'] ?></h3>
                        <h3 class="mt-0 pb_font-20">Covered</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center  mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-android-alarm-clock"></i></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45"><?php echo $current_global_stats['remaining'] ?></h3>
                        <h3 class="mt-0 pb_font-20">Remaining</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <br><br>
        </div>
        <div class="row">
            <div class="col-md text-center">
                <a href="/newest/map/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Current Map</a>
<!--                <a href="/newest/lap/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Start Praying</a>-->
                <a href="/prayer_app/subscribe/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Start Praying</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md text-center pb_sm_py_cover">
                <h2 class=" mb-3 heading" style="color:#212529">Global Race</h2>
                <h3 class="mt-0 heading-border-top pb_font-30"><?php echo $global_race['time_elapsed'] ?></h3>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-ios-body-outline"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45"><?php echo $global_race['participants'] ?></h3>
                        <h3 class="mt-0 pb_font-20">Prayer Warriors</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center  mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-android-alarm-clock"></i></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45"><?php echo $global_race['minutes_prayed'] ?></h3>
                        <h3 class="mt-0 pb_font-20">Minutes Prayed</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-block mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-earth"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45"><?php echo $current_global_stats['lap_number'] ?></h3>
                        <h3 class="mt-0 pb_font-20">Laps</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <br><br>
        </div>
        <div class="row">
            <div class="col-md text-center">
                <a href="/stats_app/big_map/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Big Map</a>
                <a href="/stats_app/big_list/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Big List</a>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<style>
    #video-link-icon {
        border:1px solid white;
        padding: 1.5rem 1.9rem;
        color:white;
        cursor: pointer;
    }
    #video-link-icon:hover {
        color: black;
        border-color: black;
        background-color: white;
    }
</style>
<section class="pb_sm_py_cover text-center cover-bg-black cover-bg-opacity-4" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/1900x1200_img_2.jpg)">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="heading mb-3">Moravian Prayer Challenge</h2>
                <p class="sub-heading mb-5 pb_color-light-opacity-8">What is the Moravian Prayer Challenge? Who are the Moravians? Watch this video and find out.</p>
                <div class="text-center">
                    <a data-toggle="modal" data-target="#demo_video"></a><i class="ion-videocamera pb_font-60 border-gray rounded-circle" id="video-link-icon"></i>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="demo_video" tabindex="-1" role="dialog" aria-labelledby="demo_video" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Prayer.Global Intro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END section -->

<section class="pb_section" data-section="about" id="section-about">
    <div class="container">
        <div class="row justify-content-md-center text-center mb-5">
            <div class="col-lg-7">
                <h2 class="mt-0 heading-border-top font-weight-normal">What are Prayer Laps?</h2>
                <p>
                    One prayer lap equals a complete series of community prayers covering every populated place in the world.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="images right">
                    <img class="img1 img-fluid" src="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/pray-4770.jpg" alt="image">
                    <img class="img2" src="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/dark-map.jpg" alt="image">
                </div>
            </div>
            <div class="col-lg-5 pl-md-5 pl-sm-0">
                <div id="exampleAccordion" class="pb_accordion" data-children=".item">
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion1" aria-expanded="true" aria-controls="exampleAccordion1" class="pb_font-18">How it works</a>
                        <div id="exampleAccordion1" class="collapse show" role="tabpanel">
                            <p>
                                We have identified 4,770 administrative divisions into which the countries of the world are divided. This website guides prayer warriors to pray over these specific locations.
                            </p>
                            <p>
                                When our community prays for the "kingdom to come" over one of these divisions for one minute or more, then we mark that location as covered for that prayer lap.
                            </p>
                            <p>
                                Once all the divisions of the world have been prayed for, then we close that prayer lap, start a new lap, and reset the master clock.
                            </p>
                            <p>
                                We hope that our community completes each prayer lap faster than the previous. Can you imagine a global community praying forever place in the world every 10 minutes? We dream that.
                            </p>
                            <p>
                                <br>
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion2" aria-expanded="false" aria-controls="exampleAccordion2" class="pb_font-18">Moravian Prayer Challenge</a>
                        <div id="exampleAccordion2" class="collapse" role="tabpanel">
                            <p>
                                Inspired by the <a href="https://www.christianitytoday.com/history/issues/issue-1/prayer-meeting-that-lasted-100-years.html">Moravians</a>, who prayed non-stop for 100 years,
                                we have crafted this website to help the church pray for the entire world in measurable units, as a community, and to know at the end when we have finished ... and are ready to start
                                another lap.
                            </p>
                            <p>
                                Once every location in the world has been prayed for, we finish a lap. The prayer map resets and we try to pray over the world again
                                ... maybe faster.
                            </p>
                            <p>
                                The Moravians had one person praying every hour of every day for 100 years. This was roughly 876,000 hours of prayer, or 52,560,000 minutes of prayer for the world. We are humbled by this extraordinary commitment to praying for the world.
                            </p>
                            <p>
                                <br>
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion3" aria-expanded="false" aria-controls="exampleAccordion3" class="pb_font-18">Extraordinary Prayer for Movement</a>
                        <div id="exampleAccordion3" class="collapse" role="tabpanel">
                            <p>Based upon research, extraordinary prayer is found at the root of all modern disciple multiplying movements.</p>
                            <p>Prayer.Global seeks to encourage in creative ways extraordinary prayer for the fulfillment of the Great Commission in our generation.</p>
                            <p>
                                <br>
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion4" aria-expanded="false" aria-controls="exampleAccordion4" class="pb_font-18">Historic Moment</a>
                        <div id="exampleAccordion4" class="collapse" role="tabpanel">
                            <p>Never before in the history of the human race have we been able to coordinate a global community of people to pray for the kingdom to come on earth as it is in heaven IN REALTIME!</p>
                            <p>It has always pleased the Lord to use current technologies for the advance of his kingdom. Remembering, written language was a technology advancement. The printing press to distribute bibles was a technology advancement.</p>
                            <p>God is now using global travel and global communication to take the gospel to the ends of the earth. Our heart is to add to that globally coordinated prayer for the kingdom.</p>
                            <p>
                                <br>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- END section -->

<section class="pb_sm_py_cover text-center cover-bg-black cover-bg-opacity-4" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) ) ?>assets/images/1900x1200_img_3.jpg)">
    <div class="container">

        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="heading mb-3">Join Us</h2>
                <p class="sub-heading mb-5 pb_color-light-opacity-8">Helping the church pray for the entire world in measurable units as a community.</p>
<!--                <p><a href="/newest/lap/" role="button" class="btn smoothscroll pb_outline-light btn-xl p-4 rounded-0 pb_font-13 pb_letter-spacing-2">Start Praying</a></p>-->
                <p><a href="/prayer_app/subscribe/" role="button" class="btn smoothscroll pb_outline-light btn-xl p-4 rounded-0 pb_font-13 pb_letter-spacing-2">Start Praying</a></p>
            </div>
        </div>

    </div>
</section>
<!-- END section -->

<section class="pb_section bg-light"  >
    <div class="container">
        <div class="row justify-content-md-center text-center mb-5">
            <div class="col-lg-7">
                <h2 class="mt-0 heading-border-top font-weight-normal">FAQs</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-heart"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Loving</h3>
                        <p class="pb_font-14">Prayer.Global loves God, loves people, and helps Christians fulfill the Great Commission by mobilizing prayer.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-android-restaurant"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Expectant</h3>
                        <p class="pb_font-14">Prayer.Global strives to neither under- nor over-estimate man’s role in disciple multiplication movements. God declared prayer as the vehicle for seeking and receiving his kingdom.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-android-restaurant"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Open</h3>
                        <p class="pb_font-14">Prayer.Global welcomes prayer collaboration from all followers of Jesus.</p>
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-search"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Kingdom Focused</h3>
                        <p class="pb_font-14">Prayer.Global recognizes there are many good things to pray for, but our purpose is to pray for the kingdom to come and the gospel to reach every place in the world. Gospel poverty is the great poverty and injustice of our generation.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-stats-bars"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Strategic</h3>
                        <p class="pb_font-14">Prayer.Global promotes strategic prayer for movement, knowing that ( based upon research ) extraordinary prayer is found at the root of all modern movements.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-ios-bookmarks"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Word-Centric</h3>
                        <p class="pb_font-14">Prayer.Global seeks to guide prayer warriors to the bible as the source for knowing God's heart and modeling for how to pray.</p>
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-plane"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Mobilizing</h3>
                        <p class="pb_font-14">Prayer.Global asks everyone to not only pray but also to mobilize prayer through relationships and opportunities God provides.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-ios-locked"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Safe</h3>
                        <p class="pb_font-14">Prayer.Global will never ask for money. This tool is free to the church.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg">
                <div class="media pb_media_v1 mb-5">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 text-primary"><i class="ion-thumbsdown"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-17">Not Political</h3>
                        <p class="pb_font-14">Prayer.Global is not a political agenda, rather an effort to pray for the kingdom of God to come in every place for every people. This is the only kingdom in which we have hope for the salvation of mankind.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="pb_footer bg-light" role="contentinfo">
    <hr style="padding-bottom: 5rem;">
    <div class="container">
        <div class="row " >

            <div class="col-md-3">
                <ul>
                    <li><a href="#">Contact Us</a></li>
<!--                    <li><a href="#">Report a Correction</a></li>-->
                    <li><a href="https://gospelambition.org/donate/">Donate</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul>
                    <li><a href="#">Download Materials</a></li>
                    <li><a href="/prayer_app/subscribe/">Subscribe for News</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul>
                    <li><a href="/newest/lap/">Start Praying</a></li>
                    <li><a href="/contacts">Login</a></li>
                    <li><a href="/wp-admin">Admin</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <p class="pb_font-14">
                    Made with &#10084;&#65039; by <a href="https://pray4movements.org">Pray4Movement</a><br>
                    Powered by <a href="https://disciple.tools">Disciple.Tools</a>
                </p>
            </div>
        </div>


        <div class="row" style="padding-top: 5rem;">
            <div class="col text-center">
                <p class="pb_font-14">&copy; <script>document.write(new Date().getFullYear())</script>. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- loader -->
<div id="pb_loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#FDA04F"/></svg></div>
