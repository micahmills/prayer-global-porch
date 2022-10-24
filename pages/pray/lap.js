jQuery(document).ready(function(){
  /**
   * API HANDLERS
   */
  window.api_post = ( action, data ) => {
    return jQuery.ajax({
      type: "POST",
      data: JSON.stringify({ action: action, parts: jsObject.parts, data: data }),
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
  function load_next_content() {
    window.api_post( 'refresh', { grid_id: window.current_content.location.grid_id, favor: window.favor } )
      .done(function(location) {
        if ( location === false ) {
          window.location = '/'+jsObject.parts.root+'/'+jsObject.parts.type+'/'+jsObject.parts.public_key
        }
        window.next_content = location
      })
  }
  function advance_to_next_location() {
    toggle_timer( false )
    button_progress.css('width', '0' )
    window.time = 0
    load_location()
  }
  function ip_location() {
    window.api_post( 'ip_location', [] )
      .done(function(location) {
        window.user_location = []
        if ( location ) {
          // persist user identity hash
          let pg_user_hash = Cookies.get('pg_user_hash')
          if ( ! pg_user_hash ) {
            Cookies.set('pg_user_hash', location.hash )
          } else {
            location.hash = pg_user_hash
          }
          window.user_location = location
        }
      })
  }


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
  let praying_continue_button = jQuery('#praying__continue_button')

  let decision_home = jQuery('#decision__home')
  let decision_map = jQuery('#decision__map')
  let decision_next = jQuery('#decision__next')

  // let question_no = jQuery('#question__no')
  let question_yes_done = jQuery('#question__yes_done')
  let question_yes_next = jQuery('#question__yes_next')

  let pace_open_options = jQuery('#option_filter')
  let open_welcome = jQuery('#welcome_screen')
  let pace_buttons = jQuery('.pace')
  let favor_buttons = jQuery('.favor')

  let location_map_wrapper = jQuery('#location-map')

  let more_prayer_fuel = jQuery('#more_prayer_fuel')
  let i

  window.previous_grids = []
  window.interval = false
  window.percent = 0
  window.time = 0
  window.seconds = 60
  window.pace = Cookies.get('pg_pace')
  if ( typeof window.pace === 'undefined' ) {
    window.pace = 1
    Cookies.set('pg_pace', 1)
  }
  window.favor = Cookies.get('pg_favor')
  if ( typeof window.favor === 'undefined' ) {
    window.favor = 'guided'
    Cookies.set('pg_favor', 'guided' )
  }
  window.viewed = Cookies.get('pg_viewed')
  window.items = parseInt( window.pace ) + 6
  window.report_content = []


  /**
   * INITIALIZE
   */
  function initialize_location() {
    setup_listeners()

    // set options fields
    pace_buttons.removeClass('btn-secondary').addClass('btn-outline-secondary')
    jQuery('#pace__'+window.pace).removeClass('btn-outline-secondary').addClass('btn-secondary')
    favor_buttons.removeClass('btn-secondary').addClass('btn-outline-secondary')
    jQuery('.favor__'+window.favor).removeClass('btn-outline-secondary').addClass('btn-secondary')

    // load current location
    window.api_post( 'refresh', { favor: window.favor } )
      .done( function(l1) {
        window.report_content = window.current_content = test_for_redundant_grid( l1 )
        load_location()
        if ( typeof window.viewed === 'undefined' ) {
          toggle_timer( true )
          open_welcome.modal('show')
          Cookies.set('pg_viewed', true, { expires: 90 } )
        } else {
          setTimeout(function() {
            jQuery('.tutorial').animate({
              opacity: "toggle"
            })
          }, 5000);
        }
      })

    // load ip tracking
    ip_location()

    // load next location
    window.api_post('refresh', { favor: window.favor } )
      .done( function(l2) {
        window.next_content = test_for_redundant_grid( l2 )
      })

    more_prayer_fuel.on('click', function(){
      jQuery('.container.block').show()
      jQuery('#more_prayer_fuel').hide()
    })
  }
  initialize_location() // initialize prayer framework
  function test_for_redundant_grid( content ) {
    if ( window.previous_grids.includes( content.location.grid_id ) ) {
      window.api_post('refresh', { favor: window.favor } )
        .done( function(new_content) {
          // return test_for_redundant_grid( new_content )
          if ( typeof window.test_for_redundant === 'undefined' ) {
            window.test_for_redundant = 0
          }
          if ( window.test_for_redundant < 3 ) {
            window.test_for_redundant++
            return test_for_redundant_grid( new_content )
          }
        })
    } else {
      window.test_for_redundant = 0
      window.previous_grids.push(content.location.grid_id )
      return content
    }
  }
  /**
   * Widget Listeners
   */
  function setup_listeners() {
    praying_button.off('click')
    praying_button.on('click', function( e ) {
      toggle_timer()
    })
    praying_close_button.off('click')
    praying_close_button.on('click', function( e ) {
      toggle_timer( true )
    })
    praying_continue_button.off('click')
    praying_continue_button.on('click', function( e ) {
      toggle_timer( false )
    })
    decision_home.off('click')
    decision_home.on('click', function( e ) {
      if ( jsObject.is_custom ) {
        window.location.href = jsObject.map_url
      } else {
        window.location.href = '/'
      }
    })
    decision_map.off('click')
    decision_map.on('click', function( e ) {
      window.location = jsObject.map_url
    })
    decision_next.off('click')
    decision_next.on('click', function( e ) {
      window.api_post( 'refresh', { favor: window.favor } )
        .done( function(l1) {
          window.report_content = window.current_content = test_for_redundant_grid( l1 )
          load_next_content()
          advance_to_next_location()
        })
    })
    question_yes_done.off('click')
    question_yes_done.on('click', function( e ) {
      question_panel.hide()
      celebrate()
      setTimeout(
        function()
        {
          window.location = jsObject.map_url
        }, 3000);
    })
    question_yes_next.off('click')
    question_yes_next.on('click', function( e ) {
      question_panel.hide()
      celebrate()
      setTimeout(
        function()
        {
          advance_to_next_location()
        }, 3000);
    })
    pace_buttons.off('click')
    pace_buttons.on('click', function(e) {
      pace_buttons.removeClass('btn-secondary').addClass('btn-outline-secondary')
      jQuery('.'+e.currentTarget.id).removeClass('btn-outline-secondary').addClass('btn-secondary')

      window.pace = e.currentTarget.value
      window.seconds = e.currentTarget.value * 60

      Cookies.set( 'pg_pace', window.pace )

      window.items = parseInt( window.pace ) + 6

      jQuery('.container.block').show()
      jQuery('.container.block:nth-child(+n+' + window.items + ')').hide()
    })
    favor_buttons.off('click')
    favor_buttons.on('click', function(e) {

      favor_buttons.removeClass('btn-secondary').addClass('btn-outline-secondary')
      let item_id = jQuery(this).data('item-id')
      jQuery('.'+item_id).removeClass('btn-outline-secondary').addClass('btn-secondary')

      window.favor = e.currentTarget.value

      Cookies.set( 'pg_favor', window.favor )

      window.api_post( 'refresh', { favor: window.favor } )
        .done(function(x) {
          window.next_content = x
        })

    })
    pace_open_options.off('show.bs.modal')
    pace_open_options.on('show.bs.modal', function () {
      toggle_timer( true )
    })
    pace_open_options.off('hide.bs.modal')
    pace_open_options.on('hide.bs.modal', function () {
      toggle_timer( true )
    })
    open_welcome.off('hide.bs.modal')
    open_welcome.on('hide.bs.modal', function () {
      toggle_timer( false )
      setTimeout(function() {
        jQuery('.tutorial').animate({
          opacity: "toggle"
        })
      }, 5000);
    })
  }
  function toggle_timer( set_to_pause = false ) {
    if ( typeof window.paused === 'undefined' || window.paused === '' || set_to_pause ) {
      // console.log('pausing')
      praying_close_button.hide()
      praying_continue_button.show()

      decision_panel.show()

      button_text.html('Praying Paused')
      clearInterval(window.interval)
      window.paused = true
    } else {
      // console.log('activating')
      praying_close_button.show()
      praying_continue_button.hide()

      praying_panel.show()
      decision_panel.hide()
      question_panel.hide()

      button_text.html('Keep Praying...')
      prayer_progress_indicator( window.time )
      window.paused = ''
    }
  }

  /**
   * FRAMEWORK LOADERS
   */
  function load_location( ) {
    let content = window.report_content = window.current_content
    if ( typeof content === 'undefined' ) {
      window.current_content = window.next_content
      content = window.next_content
    }

    button_text.html('Keep Praying...')
    button_progress.css('width', '0' )

    praying_panel.show()
    decision_panel.hide()
    question_panel.hide()
    celebrate_panel.hide()

    jQuery('#location-name').html( content.location.admin_level_name_cap + ' of ' + content.location.full_name)
    div.empty()

    location_map_wrapper.show()
    mapbox_border_map()

    // LOOP STACK
    jQuery.each(content.list, function(i,block) {
      get_template( block )
    })

    // FOOTER
    jQuery('.container.block:nth-child(+n+' + window.items + ')').hide()

    prayer_progress_indicator( window.time ) // SETS THE PRAYER PROGRESS WIDGET

    window.load_report_modal()
  }
  function prayer_progress_indicator( time_start ) {
    window.time = time_start
    if ( window.interval ) {
      clearInterval(window.interval)
    }
    window.interval = setInterval(function() {
      if (window.time <= window.seconds) {
        window.time = window.time + .1
        window.percent = 1.6666 * ( window.time / window.pace )
        if ( window.percent > 100 ) {
          window.percent = 100
        }
        // console.log( window.time + ' ' + window.percent )
        button_progress.css('width', window.percent+'%' )
      }
      else {
        window.api_post( 'log', { grid_id: window.current_content.location.grid_id, pace: window.pace, user: window.user_location, favor: window.favor } )
          .done(function(x) {
            if ( ! x ) {
              window.location.href = jsObject.map_url
              return
            }
            console.log(x)
            window.current_content = false
            window.current_content = window.next_content
            window.next_content = false
            window.next_content = test_for_redundant_grid( x )
          })
        praying_panel.hide()
        question_panel.show()
        button_progress.css('width', '0' )
        button_text.html('Keep Praying...')
        clearInterval(window.interval);
      }
    }, 100);
  }



  /**
   * CELEBRATE FUNCTION
   */
  function celebrate(){
    div.empty()
    location_map_wrapper.hide()
    more_prayer_fuel.show()

    let rint = Math.floor(Math.random() * 4 ) + 1
    celebrate_panel.html(`<p style="padding-top:2em;"><h1>Great Job!<br>Prayer Added!</h1></p>
    <p><img width="400px" src="${jsObject.image_folder}celebrate${rint}.gif" class="img-fluid celebrate-image" alt="photo" /></p>`).show()
  }

  /**
   * Maps
   */
  function mapbox_border_map() {
    let content = jQuery('#location-map')
    let grid_row = window.current_content.location
    console.log(grid_row)

    content.empty().html(`
        <div id="map-wrapper"><div id='mapbox-map'></div></div><div class="text-center p-3"><i class="ion-ios-body red"></i> ${grid_row.non_christians} | <i class="ion-ios-body orange"></i> ${grid_row.christian_adherents} | <i class="ion-ios-body green"></i> ${grid_row.believers}</div>`)

    window.load_map_with_style = ( ) => {
      let center = [grid_row.p_longitude, grid_row.p_latitude]
      mapboxgl.accessToken = jsObject.map_key;
      let map = new mapboxgl.Map({
        container: 'mapbox-map',
        style: 'mapbox://styles/discipletools/cl1qp8vuf002l15ngm5a7up59',
        center: center,
        minZoom: 0,
        zoom: 1
      });
      map.dragRotate.disable();
      map.touchZoomRotate.disableRotation();
      map.addControl(new mapboxgl.NavigationControl());

      map.on('load', function() {

        jQuery.ajax({
          url: jsObject.mirror_url + 'collection/'+grid_row.parent_id+'.geojson',
          dataType: 'json',
          data: null,
          cache: true,
          beforeSend: function (xhr) {
            if (xhr.overrideMimeType) {
              xhr.overrideMimeType("application/json");
            }
          }
        })
          .done(function (geojson) {
            map.addSource('parent_collection', {
              'type': 'geojson',
              'data': geojson
            });
            map.addLayer({
              'id': 'parent_collection_lines',
              'type': 'line',
              'source': 'parent_collection',
              'paint': {
                'line-color': '#0080ff',
                'line-width': 1
              }
            });
            map.addLayer({
              'id': 'parent_collection_fill',
              'type': 'fill',
              'source': 'parent_collection',
              'filter': [ '==', ['get', 'grid_id'], grid_row.grid_id ],
              'paint': {
                'fill-color': 'red',
                'fill-opacity': 0.75
              }
            });
            map.setPaintProperty('parent_collection_fill', 'fill-opacity', [
              'interpolate',
              ['exponential', 0.5],
              ['zoom'],
              12,
              0.75,
              17,
              .05
            ]);
            map.addLayer({
              'id': 'parent_collection_fill_click',
              'type': 'fill',
              'source': 'parent_collection',
              'paint': {
                'fill-color': 'white',
                'fill-opacity': 0
              }
            });


            let point_geojson = {
              'type': 'FeatureCollection',
              'features': [
                {
                  'type': 'Feature',
                  'properties': {
                    'full_name': grid_row.full_name
                  },
                  'geometry': {
                    'type': 'Point',
                    'coordinates': [ grid_row.longitude, grid_row.latitude ]
                  }
                }]
            }
            console.log(point_geojson)
            map.addSource('point_geojson', {
              'type': 'geojson',
              'data': point_geojson
            });
            map.addLayer({
              'id': 'poi-labels',
              'type': 'symbol',
              'source': 'point_geojson',
              'layout': {
                'text-field': ['get', 'full_name'],
                'text-variable-anchor': ['top', 'bottom', 'left', 'right'],
                'text-radial-offset': 0.5,
                'text-justify': 'auto',
                'text-allow-overlap': false,
                'text-size': 26
              },
              "paint": {
                "text-color": "#202",
                "text-halo-color": "#dbe9f4",
                "text-halo-width": 3
              },
            });

            map.on('click', 'parent_collection_fill_click', function (e) {
              new mapboxgl.Popup()
                .setLngLat(e.lngLat)
                .setHTML(e.features[0].properties.full_name)
                .addTo(map);
            });
            map.on('mouseenter', 'parent_collection_fill_click', function () {
              map.getCanvas().style.cursor = 'pointer';
            });

            map.on('mouseleave', 'parent_collection_fill_click', function () {
              map.getCanvas().style.cursor = '';
            });

            map.fitBounds([
              [parseFloat( grid_row.p_west_longitude), parseFloat(grid_row.p_south_latitude)], // southwestern corner of the bounds
              [parseFloat(grid_row.p_east_longitude), parseFloat(grid_row.p_north_latitude)] // northeastern corner of the bounds
            ], {padding: 25, duration: 5000});

          })

        if ( grid_row.level >= 2 ) {
          jQuery.ajax({
            url: jsObject.mirror_url + 'low/'+grid_row.admin0_grid_id+'.geojson',
            dataType: 'json',
            data: null,
            cache: true,
            beforeSend: function (xhr) {
              if (xhr.overrideMimeType) {
                xhr.overrideMimeType("application/json");
              }
            }
          })
            .done(function (geojson) {
              map.addSource('country_outline', {
                'type': 'geojson',
                'data': geojson
              });
              map.addLayer({
                'id': 'country_outline_lines',
                'type': 'line',
                'source': 'country_outline',
                'paint': {
                  'line-color': '#0080ff',
                  'line-width': 2
                }
              });
            })
        }

      }) // map load
    }
    window.load_map_with_style() // initialize map
  }


  /**
   * TEMPLATE LOADER
   */
  function get_template( block ) {
    switch(block.type) {
      case '4_fact_blocks':
        _template_4_fact_blocks( block.data )
        break;
      case 'percent_3_circles':
        _template_percent_3_circles( block.data )
        break;
      case 'percent_3_bar':
        _template_percent_3_bar( block.data )
        break;
      case '100_bodies_chart':
        _template_100_bodies_chart( block.data )
        break;
      case '100_bodies_3_chart':
        _template_100_bodies_3_chart( block.data )
        break;
      case 'population_change_icon_block':
        _template_population_change_icon_block( block.data )
        break;
      case 'bullet_list_2_column':
        _template_bullet_list_2_column( block.data )
        break;
      case 'people_groups_list':
        _template_people_groups_list( block.data )
        break;
      case 'least_reached_block':
        _template_least_reached_block( block.data )
        break;
      case 'fact_block':
        _template_fact_block( block.data )
        break;
      case 'content_block':
        _template_content_block( block.data )
        break;
      case 'photo_block':
        _template_photo_block( block.data )
        break;
      case 'verse_block':
        _template_verse_block( block.data )
        break;
      case 'prayer_block':
        _template_prayer_block( block.data )
        break;
      case 'basic_block':
        _template_basic_block( block.data )
        break;
      case 'lost_per_believer':
        _template_lost_per_believer_block( block.data )
        break;
      default:
        break;
    }
  }
  function _template_percent_3_circles( data ) {
    div.append(
      `<div class="container block">
          <div class="row">
              <div class="col text-center ">
                 <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
              </div>
          </div>
          <div class="row text-center justify-content-center">
              <div class="col-md-3 col-lg-2">
                <p class="mt-3 mb-0 font-weight-bold">${data.label_1}</p>
                <div class="pie" style="--p:${data.percent_1};--b:10px;--c:red;">${data.percent_1}%</div>
                <p class="mt-3 mb-0 font-weight-normal one-em">${data.population_1}</p>
              </div>
              <div class="col-md-3 col-lg-2">
                <p class="mt-3 mb-0 font-weight-bold">${data.label_2}</p>
                <div class="pie" style="--p:${data.percent_2};--b:10px;--c:orange;">${data.percent_2}%</div>
                <p class="mt-3 mb-0 font-weight-normal one-em">${data.population_2}</p>
              </div>
              <div class="col-md-3 col-lg-2">
                <p class="mt-3 mb-0 font-weight-bold">${data.label_3}</p>
                <div class="pie" style="--p:${data.percent_3};--b:10px;--c:green;">${data.percent_3}%</div>
                <p class="mt-3 mb-0 font-weight-normal one-em">${data.population_3}</p>
              </div>
          </div>
          <div class="row text-center">
            <div class="col">
               <p class="font-weight-normal">${data.section_summary}</p>
            </div>
          </div>
          <div class="row text-center justify-content-center">
            <div class="col-md-8">
               <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
            </div>
          </div>
      <div class="w-100"><hr></div>
    </div>`
    )
  }
  function _template_percent_3_bar( data ) {
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center">
          <div class="col-md-12">
            <p class="mt-0 mb-3 font-weight-normal">
              <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" style="width:${data.percent_1}%">
                  ${data.label_1}
                </div>
                <div class="progress-bar progress-bar-warning" role="progressbar" style="width:${data.percent_2}%">
                  ${data.label_2}
                </div>
                <div class="progress-bar progress-bar-danger" role="progressbar" style="width:${data.percent_3}%">
                 ${data.label_3}
                </div>
              </div>
            </p>
          </div>
      </div>
      <div class="row text-center">
        <div class="col">
           <p class="font-weight-normal">${data.section_summary}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`
    )
  }
  function _template_100_bodies_chart( data ) {
    let bodies = ''
    let i = 0
    i = 0
    while ( i < data.percent_1 ) {
      bodies += '<i class="ion-ios-body red two-em"></i>';
      i++;
    }
    i = 0
    while ( i < data.percent_2 ) {
      bodies += '<i class="ion-ios-body orange two-em"></i>';
      i++;
    }
    i = 0
    while ( i < data.percent_3 ) {
      bodies += '<i class="ion-ios-body green two-em"></i>';
      i++;
    }
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
            <p class="mt-0 mb-3 font-weight-normal grow">
              ${bodies}
            </p>
        </div>
      </div>
      <div class="row text-center">
        <div class="col">
           <p class="font-weight-normal">${data.section_summary}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`
    )
  }
  function _template_100_bodies_3_chart( data ) {
    let bodies_1 = ''
    let bodies_2 = ''
    let bodies_3 = ''
    i = 0
    while ( i < data.percent_1 ) {
      bodies_1 += '<i class="ion-ios-body red two-em"></i>';
      i++;
    }
    i = 0
    while ( i < data.percent_2 ) {
      bodies_2 += '<i class="ion-ios-body orange two-em"></i>';
      i++;
    }
    i = 0
    while ( i < data.percent_3 ) {
      bodies_3 += '<i class="ion-ios-body green two-em"></i>';
      i++;
    }
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
          <div class="col-md-3 col-sm">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_1}</p>
            <p class="mt-0 mb-3 font-weight-normal">
              ${bodies_1}
            </p>
            <p class="mt-3 mb-0 font-weight-normal">${data.population_1}</p>
          </div>
          <div class="col-md-3 col-sm">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_2}</p>
            <p class="mt-0 mb-3 font-weight-normal">
              ${bodies_2}
            </p>
            <p class="mt-3 mb-0 font-weight-normal ">${data.population_2}</p>
          </div>
          <div class="col-md-3 col-sm">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_3}</p>
            <p class="mt-0 mb-3 font-weight-normal">
              ${bodies_3}
            </p>
            <p class="mt-3 mb-0 font-weight-normal">${data.population_3}</p>
          </div>
      </div>
      <div class="row text-center">
        <div class="col">
           <p class="font-weight-normal">${data.section_summary}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
          <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`
    )
  }
  function _template_population_change_icon_block( data ) {
    if( data.count === '0' || data.count.length > 3 ) {
      return
    }

    // icon types
    let icons = ''
    if ( 'deaths' === data.type ) {
      icons = [ 'ion-sad']
    } else {
      icons = ['ion-happy']
    }
    let icon = icons[Math.floor(Math.random() * icons.length)]

    // icon color
    let icon_color = 'red'
    if ( 'christian_adherents' === data.group ) {
      icon_color = 'orange'
    }
    if ( 'believers' === data.group ) {
      icon_color = 'orange'
    }

    // icon size
    let icon_size = 'three-em'
    if ( 2 === data.size ) {
      icon_size = 'two-em'
    }

    let font_size = '2em'
    if ( data.count > 1000 ) {
      font_size = '1em'
    } else if ( data.count < 20 ) {
      font_size = '3em'
    }

    // build icon list
    let icon_list = ''
    i = 0
    while ( i < data.count ) {
      icon_list += '<i class="'+icon+' '+icon_color+'"></i>';
      i++;
    }
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="font-weight-normal">${data.section_summary}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
          <div class="col-md-8 col-sm">
            <p class="mt-0 mb-1 font-weight-normal icon-block" style="font-size: ${font_size};">
              ${icon_list} <span style="font-size:.5em;vertical-align:middle;">(${data.count})</span>
            </p>
          </div>
      </div>

      <div class="row text-center justify-content-center">
        <div class="col-md-8">
            <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`
    )
  }
  function _template_4_fact_blocks( data ) {
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
             <p class="mt-3 mb-3 font-weight-bold two-em">${data.focus_label}</p>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
            <div class="row text-center">
              <div class="col-6">
                <p class="mt-3 mb-0 font-weight-bold">${data.label_1}</p>
                <p class="mt-0 mb-3 font-weight-normal ${data.size_1}">${data.value_1}</p>
              </div>
              <div class="col-6">
                <p class="mt-3 mb-0 font-weight-bold">${data.label_2}</p>
                <p class="mt-0 mb-3 font-weight-normal ${data.size_2}">${data.value_2}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row text-center">
                <div class="col-6">
                  <p class="mt-3 mb-0 font-weight-bold">${data.label_3}</p>
                  <p class="mt-0 mb-3 font-weight-normal ${data.size_3}">${data.value_3}</p>
                </div>
                <div class="col-6">
                  <p class="mt-3 mb-0 font-weight-bold">${data.label_4}</p>
                  <p class="mt-0 mb-3 font-weight-normal ${data.size_4}">${data.value_4}</p>
                </div>
            </div>
          </div>
      </div>

      <div class="row text-center">
        <div class="col">
           <p class="font-weight-normal">${data.section_summary}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`
    )
  }
  function _template_bullet_list_2_column( data ) {
    if ( data.values.length > 0 ) {
      let values_list = ''
      jQuery.each(data.values, function(i,v) {
        values_list += '<p>'+v+'</p>'
      })
      div.append(
        `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
        </div>
        <div class="row text-center">
          <div class="col">
             ${values_list}
          </div>
        </div>
        <div class="row text-center">
          <div class="col">
             <p class="font-weight-normal">${data.section_summary}</p>
          </div>
        </div>
        <div class="row text-center justify-content-center">
          <div class="col-md-8">
             <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
          </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`)
    }
  }
  function _template_people_groups_list( data ) {
    let values_list = ''
    let image = ''
    jQuery.each(data.values, function(i,v) {
      if ( v.image_url ) {
        image = '<div style="background-image:url('+v.image_url+'); width:200px; height:200px;background-size: cover;background-repeat: no-repeat;" class="img-fluid"></div>'
      } else {
        image = '<div style=" height:200px;"><img class="img-fluid" src="'+jsObject.nope+'" alt="" /></div>'
      }
      values_list += '<div class="col-6 col-md-4 col-lg-2 mb-1"><p class="mb-2 text-center">'+image+'</p><p class="text-center"><img src="'+v.progress_image_url+'" class="img-fluid" alt="" /></p><p class="text-center">'+v.description+'</p></div>'
    })
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
        </div>
        <div class="row text-center justify-content-center">
          ${values_list}
        </div>
        <div class="row text-center">
          <div class="col">
             <p class="font-weight-normal">${data.section_summary}</p>
          </div>
        </div>
        <div class="row text-center justify-content-center">
          <div class="col-md-8">
             <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
          </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`)
  }
  function _template_least_reached_block( data ) {
    let image
    if ( data.image_url ) {
      image = '<p class="mt-3 mb-3"><img src="'+data.image_url+'" class="img-fluid" alt="" /></p>'
    } else {
      image = '<p class="mt-3 mb-3 font-weight-bold six-em"><i class="ion-android-warning red"></i></p>'
    }
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
             <p class="mt-3 mb-3 font-weight-bold two-em">${data.focus_label}</p>
            ${image}
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-bold two-em">${data.prayer}</p>
        </div>
    </div>
    <div class="w-100"><hr></div>
    </div>`)
  }
  function _template_fact_block( data ) {
    let icon = ''
    if ( typeof data.icon !== 'undefined' ) {
      let iclass = 'ion-android-warning'
      if ( data.icon ) {
        iclass = data.icon
      }
      let icolor = 'red'
      if ( data.color ) {
        icolor = data.color
      }
      icon = '<p class="mt-3 mb-3 font-weight-bold six-em"><i class="'+iclass+' '+icolor+'"></i></p>'
    }
    div.append(
      `<div class="container block">
          <div class="row">
            <div class="col text-center ">
               <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
               <p class="mt-3 mb-3 font-weight-bold two-em">${data.focus_label}</p>
              ${icon}
            </div>
          </div>
          <div class="row text-center justify-content-center">
            <div class="col-md-8">
                <p class="mt-3 mb-3 font-weight-normal one-em">${data.section_summary}</p>
            </div>
          </div>
          <div class="row text-center justify-content-center">
            <div class="col-md-8">
               <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
            </div>
        </div>
    <div class="w-100"><hr></div>
    </div>
    `)
  }
  function _template_content_block( data ) {
    let icon = ''
    if ( typeof data.icon !== 'undefined' ) {
      let iclass = 'ion-android-warning'
      if ( data.icon ) {
        iclass = data.icon
      }
      let icolor = 'red'
      if ( data.color ) {
        icolor = data.color
      }
      icon = '<p class="mt-3 mb-3 font-weight-bold six-em"><i class="'+iclass+' '+icolor+'"></i></p>'
    }
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
            <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
             <p class="mt-3 mb-3 font-weight-bold two-em">${data.focus_label}</p>
            ${icon}
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-normal one-em">${data.section_summary}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-bold two-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`)
  }
  function _template_prayer_block( data ) {
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
            <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
            <p class="mt-3 mb-3"><i class="ion-android-people ${data.icon_color} six-em" /> <i class="ion-android-people ${data.icon_color} six-em" /> <i class="ion-android-people ${data.icon_color} six-em" /></p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-bold two-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`)
  }
  function _template_verse_block( data ) {
    let icons = ['ion-android-sync']
    let icon_name = icons[Math.floor(Math.random() * icons.length)]
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
            <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
            <p class="mt-3 mb-3"><img src="${jsObject.image_folder}bible-${data.icon_color}.svg" alt="icon" /></p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-0 font-weight-bold two-em font-italic">${data.verse}</p>
           <p class="mt-0 mb-3 font-italic">${data.reference}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
    </div>
    <div class="w-100"><hr></div>
    </div>`)
  }
  function _template_lost_per_believer_block( data ) {
      let bodies_1 = ''
      i = 0
      while ( i < data.lost_per_believer ) {
        bodies_1 += '<i class="ion-ios-body red"></i>';
        i++;
      }
      let font_size = '2em'
      if ( data.lost_per_believer > 1000 ) {
        font_size = '1em'
      } else if ( data.lost_per_believer < 20 ) {
        font_size = '3em'
      }
      div.append(
        `<div class="container block">
          <div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
          <div class="col-md-9 col-sm">
            <p class="mt-3 mb-0  one-em">${data.label_1}</p>
            <p class="mt-0 mb-0 font-weight-normal">
              <i class="ion-ios-body green three-em"></i>
            </p>
            <p class="mt-0 mb-3 font-weight-normal" style="font-size: ${font_size};">
              ${bodies_1}
            </p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
          <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`
    )
  }
  function _template_photo_block( data ) {
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center ">
            <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center">
        <div class="col">
           <p><img src="${data.url}" class="img-fluid" alt="prayer photo" style="max-height:700px" /></p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-0 mb-3 font-weight-normal">${data.section_summary}</p>
           <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
    </div>
    <div class="w-100"><hr></div>
    </div>
      `)
  }
  function _template_basic_block( data ) {
    let display = 'none'
    if ( data.reference ) {
      display = 'block'
    }
    let icon = 'none'
    if ( data.icon ) {
      icon = 'block'
    }
    div.append(
      `<div class="container block">
          <div class="row">
          <div class="col text-center">
            <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
            <p class="mt-3 mb-3" style="display: ${icon};"><i class="${data.icon} six-em" /></p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-bold two-em">${data.prayer}</p>
        </div>
      </div>

      <div class="row text-center justify-content-center ${data.id}" style="display:${display}">
        <div class="col mt-3 mb-3 font-weight-bold text-center">
           <button type="button" class="btn btn-outline-dark btn-sm" onclick="jQuery('#${data.id}').show();jQuery('.${data.id}').hide();" >${data.reference}</button>
        </div>
      </div>
       <div class="row text-center justify-content-center" style="display:none;" id="${data.id}" >
        <div class="col-md-8">
           <p class="mt-3 mb-0 font-weight-normal font-italic two-em">${data.verse}</p>
           <p class="mt-0 mb-3 font-weight-normal">${data.reference}</p>
        </div>
      </div>
      <div class="w-100"><hr></div>
    </div>`)
  }

})
