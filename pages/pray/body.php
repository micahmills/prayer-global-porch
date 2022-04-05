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
    #praying_button {
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
    #praying__close_button {
        font-size:2em;
    }
    #praying__open_options {
        font-size:2em;
    }
    .praying__progress {
        position: absolute;
        height: 100%;
        width: 0%;
        top: 0;
        left: 0;
        background: green;
        transition: width 0.3s;
        box-shadow: 0 10px 0 -2px rgb(0 0 0 / 15%);
    }
    .praying__text {
        position: relative;
    }
    .container {
        margin-bottom: .5em;
    }
    #decision-panel {
        display: none;
    }
    #question-panel {
        display: none;
    }
    #celebrate-panel {
        display: none;
    }
    .decision_button_group .btn  {
        width:100%;
    }
    .question_button_group .btn  {
        width:100%;
    }
    .question__yes {
        background:green !important;
    }
    .celebrate-image {

    }
    .pace-wrapper {
        width: 100%;
    }
    .btn-primary,
    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary:visited,
    .btn-primary:focus {
        background-color: green;
        border-color: white;
    }
</style>

<!-- navigation & widget -->
<nav class="navbar prayer_navbar fixed-top" id="pb-pray-navbar">
    <div class="container praying" id="praying-panel">
        <div class="btn-group praying_button_group" role="group" aria-label="Praying Button">
            <button type="button" class="btn praying" id="praying_button" data-percent="0" data-seconds="0">
                <div class="praying__progress"></div>
                <span class="praying__text"></span>
            </button>
            <button type="button" class="btn btn-secondary praying" id="praying__close_button"><i class="ion-close-circled"></i></button>
            <button type="button" class="btn btn-secondary settings" id="praying__open_options" data-toggle="modal" data-target="#option_filter"><i class="ion-android-options"></i></button>
        </div>
    </div>
    <div class="container question" id="question-panel">
        Did you pray for this location?
        <div class="btn-group question_button_group" role="group" aria-label="Praying Button">
            <button type="button" class="btn btn-secondary question" id="question__no">No</button>
            <button type="button" class="btn btn-secondary question question__yes" id="question__yes_done">Yes & Done</button>
            <button type="button" class="btn btn-secondary question question__yes" id="question__yes_next">Yes & Next</button>
        </div>
    </div>
    <div class="container celebrate " id="celebrate-panel">
        <div class="text-center">
            <img src="https://via.placeholder.com/600x400?text=Celebrate+Animation" class="img-fluid celebrate-image" alt="photo" />
        </div>
    </div>
    <div class="w-100" ></div>
    <div class="container decision" id="decision-panel">
        <div class="btn-group decision_button_group" role="group" aria-label="Decision Button">
            <button type="button" class="btn btn-secondary decision" id="decision__home">Home</button>
            <button type="button" class="btn btn-secondary decision" id="decision__continue">Continue</button>
            <button type="button" class="btn btn-secondary decision" id="decision__next">Next</button>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="option_filter" tabindex="-1" role="dialog" aria-labelledby="option_filter_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Adjust your prayer pace. Spend longer on each location.<br>
                <div class="btn-group-vertical pace-wrapper">
                    <button type="button" class="btn btn-secondary pace" id="pace__1" value="1">1 Minute</button>
                    <button type="button" class="btn btn-outline-secondary pace" id="pace__2" value="2">2 Minutes</button>
                    <button type="button" class="btn btn-outline-secondary pace" id="pace__3" value="3">3 Minutes</button>
                    <button type="button" class="btn btn-outline-secondary pace" id="pace__5" value="5">5 Minutes</button>
                    <button type="button" class="btn btn-outline-secondary pace" id="pace__10" value="10">10 Minutes</button>
                    <button type="button" class="btn btn-outline-secondary pace" id="pace__15" value="15">15 Minutes</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<!--                <button type="button" class="btn btn-primary" id="pace__save_changes">Save changes</button>-->
            </div>
        </div>
    </div>
</div>

<!-- content section -->
<section>
    <div class="container" id="content"></div>
</section>
