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
    .decision_button_group .btn  {
        width:100%;
    }
    .question_button_group .btn  {
        width:100%;
    }
    .question__yes {
        background:green !important;
    }
</style>


<nav class="navbar prayer_navbar fixed-top" id="pb-pray-navbar">
    <div class="container praying" id="praying-panel">
        <div class="btn-group praying_button_group" role="group" aria-label="Praying Button">
            <button type="button" class="btn praying" id="praying_button" data-percent="0" data-seconds="0">
                <div class="praying__progress"></div>
                <span class="praying__text"></span>
            </button>
            <button type="button" class="btn btn-secondary praying" id="praying__close_button"><i class="ion-close-circled"></i></button>
        </div>
    </div>
    <div class="container question" id="question-panel">
        Did you pray for this location?
        <div class="btn-group question_button_group" role="group" aria-label="Praying Button">
            <button type="button" class="btn btn-secondary question" id="question__no">No</button>
            <button type="button" class="btn btn-secondary question question__yes" id="question__yes_done">Yes</button>
            <button type="button" class="btn btn-secondary question question__yes" id="question__yes_next">Yes & Next</button>
        </div>
    </div>
    <div class="container decision" id="decision-panel">
        <div class="btn-group decision_button_group" role="group" aria-label="Decision Button">
            <button type="button" class="btn btn-secondary decision" id="decision__home">Home</button>
            <button type="button" class="btn btn-secondary decision" id="decision__continue">Continue</button>
            <button type="button" class="btn btn-secondary decision" id="decision__next">Next</button>
        </div>
    </div>
</nav>


<section class="" data-section="states">
    <div class="container" id="content"></div>
</section>
