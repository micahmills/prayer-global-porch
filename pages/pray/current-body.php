<style>
    .navbar.prayer_navbar {
        border-bottom:1px solid lightgrey;
        box-shadow: 0 1px 10px -2px rgb(0 0 0 / 15%);
        background:white;
        /*height:100px;*/
    }
    section {
        margin-top:120px;
    }
    .btn-group {
        width: 100%;
    }
    #praying-button {
        width:100%;
        border: none;
        background: darkgrey;
        color: #ffffff;
        padding: 10px 16px;
        position: relative;
        cursor: pointer;
        font-family: "Fira Sans", sans-serif;
        overflow: hidden;
        border-radius: 5px 0 0 5px;
    }
    #praying-close-button {
        font-size:2em;
    }
    .button__progress {
        position: absolute;
        height: 100%;
        width: 0%;
        top: 0;
        left: 0;
        background: green;
        transition: width 0.3s;
        box-shadow: 0 10px 0 -2px rgb(0 0 0 / 15%);
    }
    .button__text {
        position: relative;
    }
    .container {
        margin-bottom: .5em;
    }
    #decision-panel {
        display: none;
    }
    .decision-buttons-group .btn  {
        width:100%;
    }
</style>


<nav class="navbar prayer_navbar fixed-top" id="pb-pray-navbar">
    <div class="container">
        <div class="btn-group" role="group" aria-label="Progress Button">
            <button type="button" class="btn decision" id="praying-button" data-percent="0" data-seconds="0">
                <div class="button__progress"></div>
                <span class="button__text">Keep Praying...</span>
            </button>
            <button type="button" class="btn btn-secondary decision" id="praying-close-button"><i class="ion-close-circled"></i></button>
        </div>
    </div>
    <div class="container" id="decision-panel">
        <div class="btn-group decision-buttons-group" role="group" aria-label="Progress Button">
            <button type="button" class="btn btn-secondary" id="decision__home">Home</button>
            <button type="button" class="btn btn-secondary" id="decision__continue">Continue</button>
            <button type="button" class="btn btn-secondary" id="decision__next">Next</button>
        </div>
    </div>
</nav>

<script>
    jQuery(document).ready(function(){
        let button_progress = jQuery('.button__progress')
        let button_text = jQuery('.button__text')
        let praying_button = jQuery('#praying-button')
        let close_button = jQuery('#praying-close-button')
        let decision_panel = jQuery('#decision-panel')
        let decision_home = jQuery('#decision__home')
        let decision_continue = jQuery('#decision__continue')
        let decision_next = jQuery('#decision__next')
        let percent = 0
        let time = 0
        let interval
        function prayer_progress_indicator( time_start ) {
            time = time_start
            interval = setInterval(function() {
                if (time <= 62) {
                    time++
                    percent = 1.6 * time
                    if ( percent > 100 ) {
                        percent = 100
                    }
                    button_progress.css('width', percent+'%' )
                }
                else {
                    clearInterval(interval);
                    button_text.html('Next Location!')
                }
            }, 1000);
        }
        prayer_progress_indicator( 0 )


        praying_button.on('click', function( e ) {
            console.log( percent )
            if ( percent < 100 ) {
                console.log('not finished')
                decision_panel.show()
                button_text.html('Praying Paused')
                clearInterval(interval);
            } else {
                console.log( 'finished' )
            }
        })
        close_button.on('click', function( e ) {
            console.log( percent )
            if ( percent < 100 ) {
                console.log('not finished')
                decision_panel.show()
                button_text.html('Praying Paused')
                clearInterval(interval);
            } else {
                console.log( 'finished' )
            }
        })
        decision_home.on('click', function( e ) {
            window.location = 'https://prayer.global'
        })
        decision_continue.on('click', function( e ) {
            decision_panel.hide()
            prayer_progress_indicator( time )
            button_text.html('Keep Praying...')
        })
        decision_next.on('click', function( e ) {
            console.log( 'next location' )

        })

    })
</script>


<section class="" data-section="states">
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <h3 class="mt-0 mb-3 font-weight-normal">Colorado, United States</h3>
            </div>
            <div class="col-sm">
                <img src="https://via.placeholder.com/500x200" class="img-fluid" />
            </div>
            <div class="col-sm">
                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <h3 class="mt-0 mb-3 font-weight-normal">Praise</h3>
            </div>
            <div class="col-sm">
                <img src="https://via.placeholder.com/500x200" class="img-fluid" />
            </div>
            <div class="col-sm">
                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <h3 class="mt-0 mb-3 font-weight-normal">Kingdom Come</h3>
            </div>
            <div class="col-sm">
                <img src="https://via.placeholder.com/500x200" class="img-fluid" />
            </div>
            <div class="col-sm">
                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <h3 class="mt-0 mb-3 font-weight-normal">Pray the Book of Acts</h3>
            </div>
            <div class="col-sm">
                <img src="https://via.placeholder.com/500x200" class="img-fluid" />
            </div>
            <div class="col-sm">
                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries
                </p>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

