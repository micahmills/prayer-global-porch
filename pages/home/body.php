<?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' ) ?>

<section class="pb_cover_v1 text-left cover-bg-black cover-bg-opacity-4" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/map_background.jpg); height: 100vh;" id="section-home">
    <div class="container">
        <div class="row align-items-center justify-content-end">
            <div class="col-md-6  order-md-1">
                <h2 class="heading mb-3">Cover the World in Prayer</h2>
                <div class="sub-heading">
                    <p class="mb-5">Community driven, movement-focused, saturation prayer.</p>
                    <p><a href="/newest/lap/" style="background-color:rgba(255,255,255,.7);" role="button" class="btn smoothscroll pb_font-25 btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Start Praying</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<section class="pb_section" data-section="about" id="section-about">
    <div class="container">
        <div class="row justify-content-md-center text-center mb-5">
            <div class="col-lg-7">
                <h2 class="mt-0 heading-border-top font-weight-normal">About</h2>
                <p>
                    Prayer.Global seeks to encourage extraordinary prayer for the fulfillment of the Great Commission using technology.
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
                <div id="prayer_accordion_" class="pb_accordion" data-children=".item">
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#prayer_accordion_" href="#prayer_accordion_1" aria-expanded="true" aria-controls="prayer_accordion_1" class="pb_font-18 uppercase">Historic Moment</a>
                        <div id="prayer_accordion_1" class="collapse show" role="tabpanel">
                            <p>
                                We know three things about our moment in history:
                            </p>
                            <p>
                                (1) Never before in history have we been able to coordinate global prayer for the kingdom <u>IN REALTIME</u>!
                            </p>
                            <p>
                                (2) God has used technology for the advance of His kingdom (i.e. written language, Roman roads, printing presses, etc.),
                                and is now using the internet.
                            </p>
                            <p>
                                (3) "Extraordinary prayer" is a foundational mark of all modern disciple multiplying movements.
                            </p>
                            <p>
                                So, <strong>Prayer.Global</strong> exists to encourage extraordinary prayer for the fulfillment of the Great Commission using technology.
                            </p>
                            <p>
                                <br><hr>
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#prayer_accordion_" href="#prayer_accordion_2" aria-expanded="false" aria-controls="prayer_accordion_2" class="pb_font-18 uppercase">How it works</a>
                        <div id="prayer_accordion_2" class="collapse" role="tabpanel">
                            <p>
                                <span class="black">&#9312;</span> Click on "Start Praying". <a href="/newest/lap/"><i class="ion-android-open"></i></a>
                            </p>
                            <p>
                                <strong class="black">&#9313;</strong> Use the demographics, guided prayers, faith status, images, and maps to pray for the location for one minute.
                            </p>
                            <p>
                                <strong class="black">&#9314;</strong> Once the one-minute timer has ended, you will be asked if you prayed for this location, if you click "Yes", then your prayer will be added to the community prayer coverage for the world.
                            </p>
                            <p>
                                <strong class="black">&#9315;</strong> Pray for another location or end your prayer session.
                            </p>
                            <p>
                                <br><hr>
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#prayer_accordion_" href="#prayer_accordion_3" aria-expanded="false" aria-controls="prayer_accordion_3" class="pb_font-18 uppercase">Moravian Prayer Challenge</a>
                        <div id="prayer_accordion_3" class="collapse" role="tabpanel">
                            <p>
                                Inspired by the <a href="https://www.christianitytoday.com/history/issues/issue-1/prayer-meeting-that-lasted-100-years.html">Moravians</a>, who prayed non-stop for 100 years,
                                we have crafted this website to help the church pray for the entire world in measurable units, as a community, and to know at the end when we have finished ... and are ready to start
                                another lap.
                            </p>
                            <p>
                                Once every location in the world has been prayed for (we finish a lap), then the prayer map resets, and we try to pray over the world again
                                ... maybe faster.
                            </p>
                            <p>
                                The Moravians had one person praying every hour of every day for 100 years. This was roughly 876,000 hours of prayer, or 52,560,000 minutes of prayer for the world. We are humbled by this extraordinary commitment to praying for the world.
                            </p>
                            <p>
                                <br><hr>
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <a data-toggle="collapse" data-parent="#prayer_accordion_" href="#prayer_accordion_4" aria-expanded="false" aria-controls="prayer_accordion_4" class="pb_font-18 uppercase">Maps & Lists</a>
                        <div id="prayer_accordion_4" class="collapse" role="tabpanel">
                            <p>
                                <strong class="black">Current Map</strong> <a href="/newest/map"><i class="ion-android-open"></i></a>
                            </p>
                            <p>
                                The current map shows what has been covered so far in the active prayer map.
                            </p>
                            <p>
                                <strong class="black">Big Map</strong> <a href="/race_app/big_map/"><i class="ion-android-open"></i></a>
                            </p>
                            <p>
                                The Big map shows the number of laps, number of minutes, and number of prayer warriors for the entire challenge.
                            </p>
                            <p>
                                <strong class="black">Big List</strong> <a href="/race_app/big_list/"><i class="ion-android-open"></i></a>
                            </p>
                            <p>
                                The Big List shows each of the laps accomplished so far and some of the statistics for those individual laps.
                            </p>
                            <p>
                                <br><hr>
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
<!-- END section -->

<!-- Video modal -->
<section class="pb_sm_py_cover text-center cover-bg-black cover-bg-opacity-4" id="section-challenge" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/1900x1200_img_2.jpg)">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="heading mb-5">Moravian Prayer Challenge</h2>
                <p class="sub-heading mb-5 pb_color-light-opacity-8" style="border: 1px solid white;">Prayer for the World<br> x <br> 24 hours a day / 7 days a week / 365 days a year <br>x<br> 100 years <br>=<br>52.56 million minutes of prayer</p>
                <p class="sub-heading mb-5 pb_color-light-opacity-8">Who are the Moravians? <br>What is the Moravian Prayer Challenge?<br> How are we going to accept the challenge? <br> Watch this video.</p>
                <div class="text-center">
                    <img class="img-fluid video-image-link" src="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/moravian-video.jpg" id="video-image-link" />
                </div>
                <div class="text-center mt-3"></div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="demo_video"  tabindex="-1" role="dialog" aria-labelledby="demo_video" aria-hidden="true">
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


<section class="pb_section pb_section_v1" data-section="lap" id="section-lap">
    <div class="container">
        <div class="row">
            <div class="col-md text-center pb_sm_py_cover">
                <h2 class=" mb-3 heading" style="color:#212529">Current Lap</h2>
                <h3 class="mt-0 heading-border-top pb_font-30" id="current_time_elapsed"><span class="loading-spinner active"></span></h3>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-ios-body-outline"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45" id="current_participants"><span class="loading-spinner active"></span></h3>
                        <h3 class="mt-0 pb_font-20">Prayer Warriors</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-block mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-earth"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45" id="current_completed"><span class="loading-spinner active"></span></h3>
                        <h3 class="mt-0 pb_font-20">Covered</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center  mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-android-alarm-clock"></i></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45" id="current_remaining"><span class="loading-spinner active"></span></h3>
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
                <a href="/newest/map/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2" data-reverse-color>Current Map</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md text-center pb_sm_py_cover">
                <h2 class=" mb-3 heading" style="color:#212529">Global Race</h2>
                <h3 class="mt-0 heading-border-top pb_font-30" id="global_time_elapsed"><span class="loading-spinner active"></span></h3>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-ios-body-outline"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45" id="global_participants"><span class="loading-spinner active"></span></h3>
                        <h3 class="mt-0 pb_font-20">Prayer Warriors</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center  mb-3">
                    <div class="icon border border-gray rounded-circle d-flex mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-android-alarm-clock"></i></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45" id="global_minutes_prayed"><span class="loading-spinner active"></span></h3>
                        <h3 class="mt-0 pb_font-20">Minutes Prayed</h3>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="media pb_media_v2 d-block text-center mb-3">
                    <div class="icon border border-gray rounded-circle d-block mr-3 display-4 mx-auto mb-4"><i class="text-primary ion-earth"></i></div>
                    <div class="media-body">
                        <h3 class="mt-0 pb_font-45" id="global_lap_number"><span class="loading-spinner active"></span></h3>
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
                <a href="/race_app/big_map/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2" data-reverse-color>Big Map</a>
                <a href="/race_app/big_list/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2" data-reverse-color>Big List</a>
            </div>
        </div>
    </div>
</section>
<!-- END section -->


<style>
    .hover-box {
        max-width:400px;
        border: 1px solid white;
        vertical-align:middle;
        margin: .5em auto;
        padding:.7em;
        border-radius: 15px;
    }
    .hover-box:hover {
        background-color: white;
        color: black !important;
    }
    .hover-box:hover a {
        color: black !important;
    }
</style>
<section class="pb_sm_py_cover text-center cover-bg-black cover-bg-opacity-4" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) ) ?>assets/images/1900x1200_img_3.jpg)">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="heading mb-3"><a href="/qr/app/" style="color:white;">Get the Mobile App</a></h2>
                <a class="white" href="https://apps.apple.com/us/app/prayer-global/id1636889534?uo=4" style="font-size:2em;">
                    <div class="hover-box">
                        <i class="ion-social-apple" ></i>
                        iPhone/iPad App
                    </div>
                </a>
                <a class="white" href="https://play.google.com/store/apps/details?id=app.global.prayer" style="font-size:2em;">
                    <div class="hover-box">
                        <i class="ion-social-android"></i>
                        Android App
                    </div>
                </a>

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



<?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
