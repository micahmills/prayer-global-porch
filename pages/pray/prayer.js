jQuery(document).ready(function(){
  /**
   * Progress widget
   */
  let div = jQuery('#content')

  let praying_panel = jQuery('#praying-panel')
  let decision_panel = jQuery('#decision-panel')
  let question_panel = jQuery('#question-panel')
  let celebrate_panel = jQuery('#celebrate-panel')

  let praying_button = jQuery('#praying_button')
  let button_progress = jQuery('.praying__progress')
  let button_text = jQuery('.praying__text')
  let praying_close_button = jQuery('#praying__close_button')

  let decision_home = jQuery('#decision__home')
  let decision_continue = jQuery('#decision__continue')
  let decision_next = jQuery('#decision__next')

  let question_no = jQuery('#question__no')
  let question_yes_done = jQuery('#question__yes_done')
  let question_yes_next = jQuery('#question__yes_next')

  let pace_open_options = jQuery('#option_filter')
  let pace_buttons = jQuery('.pace')
  let pace_save = jQuery('#pace__save_changes')

  let percent = 0
  window.time = 0
  window.seconds = 60
  window.pace = 1

  let interval

  function prayer_progress_indicator( time_start ) {
    window.time = time_start
    interval = setInterval(function() {
      if (window.time <= window.seconds) {
        window.time++
        percent = 1.6666 * ( window.time / window.pace )
        if ( percent > 100 ) {
          percent = 100
        }
        button_progress.css('width', percent+'%' )
      }
      else {
        clearInterval(interval);
        praying_panel.hide()
        question_panel.show()
        button_text.html('Finished!')
      }
    }, 1000);
  }
  function initialize_location() {
    window.current_content = jsObject.start_content
    window.next_content = jsObject.next_content
    load_location()
  }
  initialize_location() // load prayer framework

  function load_location() {
    let content = window.current_content
    let location = content.location
    console.log(window.current_content)
    button_text.html('Keep Praying...')
    button_progress.css('width', '0' )

    praying_panel.show()
    decision_panel.hide()
    question_panel.hide()
    celebrate_panel.hide()

    div.empty()
    div.append(
      `<div class="row">
          <div class="col">
              <h3 class="mt-0 mb-3 font-weight-normal text-center">${location.full_name}</h3>
              <p class="text-md-center">
                <img style="width:600px;" class="img-fluid" src="${location.url}" />
              </p>
               <p class="text-center">
                  The ${location.admin_level_name} of ${location.full_name} has a population of <strong>${location.stats.population}</strong>.<br>
                  We estimate ${location.name} has ${location.stats.believers} people might know Jesus, ${location.stats.christian_adherants} people who might know about Jesus, and ${location.stats.non_christians} people who do not know Jesus.
              </p>
          </div>
      </div>
      <div class="row text-center">
        <div class="col-md">
            <p class="mt-3 mb-0 font-weight-bold">Population</p>
            <p class="mt-0 mb-3 font-weight-normal">${location.stats.population}</p>
          </div>
          <div class="col-md">
            <p class="mt-3 mb-0 font-weight-bold">Don't Know Jesus</p>
            <p class="mt-0 mb-3 font-weight-normal">${location.stats.non_christians}</p>
         </div>
         <div class="col-md">
            <p class="mt-3 mb-0 font-weight-bold">Know About Jesus</p>
            <p class="mt-0 mb-3 font-weight-normal">${location.stats.christian_adherants}</p>
         </div>
         <div class="col-md">
            <p class="mt-3 mb-0 font-weight-bold">Know Jesus</p>
            <p class="mt-0 mb-3 font-weight-normal">${location.stats.believers}</p>
          </div>
        </div>
      </div>
      <hr>`
    )
    jQuery.each(content.sections, function(i,v) {
      div.append(
        `<div class="row mb-1">
            <div class="col-md">
                <h3 class="mt-0 mb-3 font-weight-normal">${v.title}</h3>
            </div>
            <div class="col-md">
                <img src="${v.url}" class="img-fluid" alt="${v.title} photo" />
            </div>
            <div class="col-md">
                <p>
                    ${v.description}
                </p>
            </div>
        </div>`
      )
    })
    if ( content.cities.length > 0 ) {
      div.append(
        `<div class="row mb-1">
            <div class="col-md">
                <h3 class="mt-0 mb-3 font-weight-normal">Cities</h3>
            </div>
            <div class="col-md">
                <img src="https://via.placeholder.com/600x400?text=${content.grid_id}" class="img-fluid" alt="People Groups photo" />
            </div>
            <div class="col-md"><ul id="cities-list" style="padding-left: 1rem;"></ul></div>
        </div>`)
      let cities_list = jQuery('#cities-list')
      jQuery.each(content.cities, function(i,v) {
        cities_list.append(`<li>${v.name} (pop ${v.population})</li>`)
      })
    }
    if ( content.people_groups.length > 0 ) {
      div.append(
        `<div class="row mb-1">
            <div class="col-md">
                <h3 class="mt-0 mb-3 font-weight-normal">People Groups</h3>
            </div>
            <div class="col-md">
                <img src="https://via.placeholder.com/600x400?text=${content.grid_id}" class="img-fluid" alt="People Groups photo" />
            </div>
            <div class="col-md"><ul id="pg-list" style="padding-left: 1rem;"></ul></div>
        </div>`)
        let pg_list = jQuery('#pg-list')
        jQuery.each(content.people_groups, function(i,v) {
          pg_list.append(`<li>${v.name}</li>`)
        })
    }

    div.append(`<div class="row text-center"><div class="col">${content.grid_id}</</div>`)

    prayer_progress_indicator( window.time )
  }


  /**
   *  Listeners
   */
  praying_button.on('click', function( e ) {
    if ( percent < 100 ) {
      decision_panel.show()
      button_text.html('Praying Paused')
      clearInterval(interval);
    } else {
      console.log( 'finished' )
    }
  })
  praying_close_button.on('click', function( e ) {
    if ( percent < 100 ) {
      button_text.html('Praying Paused')
    } else {
      console.log( 'finished' )
    }
    decision_panel.show()
    clearInterval(interval);
  })
  decision_home.on('click', function( e ) {
    window.location = 'https://prayer.global'
  })
  decision_continue.on('click', function( e ) {
    praying_panel.show()
    decision_panel.hide()
    question_panel.hide()
    prayer_progress_indicator( window.time )
    button_text.html('Keep Praying...')
  })
  decision_next.on('click', function( e ) {
    button_text.html('Keep Praying...')
    button_progress.css('width', '0' )
    window.time = 0
    window.current_content = window.next_content
    load_location()
    refresh()
  })

  question_no.on('click', function( e ) {
    button_text.html('Keep Praying...')
    button_progress.css('width', '0' )
    window.time = 0
    decision_panel.show()
    decision_continue.show();
  })
  question_yes_done.on('click', function( e ) {
    decision_continue.hide();
    question_panel.hide()
    decision_panel.show()
    celebrate()
    log()
  })
  question_yes_next.on('click', function( e ) {
    celebrate()
    question_panel.hide()
    log()
    let next = setTimeout(
      function()
      {
        window.time = 0
        window.current_content = window.next_content
        load_location()
      }, 1200);
  })

  pace_buttons.on('click', function(e) {
    pace_buttons.removeClass('btn-secondary').addClass('btn-outline-secondary')
    jQuery('#'+e.currentTarget.id).removeClass('btn-outline-secondary').addClass('btn-secondary')

    window.pace = e.currentTarget.value
    window.seconds = e.currentTarget.value * 60
  })
  pace_open_options.on('show.bs.modal', function () {
    if ( percent < 100 ) {
      button_text.html('Praying Paused')
    } else {
      console.log( 'finished' )
    }
    clearInterval(interval);
  })
  pace_open_options.on('hide.bs.modal', function () {
    praying_panel.show()
    decision_panel.hide()
    question_panel.hide()
    prayer_progress_indicator( window.time )
    button_text.html('Keep Praying...')
  })


  function celebrate(){
    div.empty()
    celebrate_panel.show()
  }
  function log() {
    window.api_post( 'log', { grid_id: window.current_content.grid_id, pace: window.pace } )
      .done(function(location) {
        console.log(location)
        if ( location === false ) {
          window.location = '/prayer_app/'+jsObject.parts.type+'/'+jsObject.parts.public_key
        }
        window.current_content = window.next_content
        window.next_content = location
      })
  }
  function refresh() {
    window.api_post( 'refresh', { grid_id: window.current_content.grid_id } )
      .done(function(location) {
        console.log(location)
        if ( location === false ) {
          window.location = '/prayer_app/'+jsObject.parts.type+'/'+jsObject.parts.public_key
        }
        window.current_content = window.next_content
        window.next_content = location
      })
  }

  window.api_post = ( action, data ) => {
    return jQuery.ajax({
      type: "POST",
      data: JSON.stringify({ action: 'log', parts: jsObject.parts, data: data }),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      url: jsObject.root + jsObject.parts.root + '/v1/' + jsObject.parts.type,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-WP-Nonce', jsObject.nonce )
      }
    })
      .fail(function(e) {
        console.log(e)
      })
  }
})
