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

  let interval
  let percent = 0
  window.time = 0
  window.seconds = 60
  window.pace = 1

  /**
   * INITIALIZE
   */
  function initialize_location() {
    window.current_content = jsObject.start_content
    window.next_content = jsObject.next_content
    load_location()
  }
  initialize_location() // initialize prayer framework

  /**
   * FRAMEWORK LOADERS
   */
  function load_location() {
    let content = window.current_content
    console.log(window.current_content)
    button_text.html('Keep Praying...')
    button_progress.css('width', '0' )

    praying_panel.show()
    decision_panel.hide()
    question_panel.hide()
    celebrate_panel.hide()

    jQuery('#location-name').html(content.location.full_name)
    div.empty()

    // MAP
    div.append(
      `<div class="row">
          <div class="col">
              <p class="text-md-center" id="location-map"></p>
              <p class="text-md-center">The ${content.location.admin_level_name} of <strong>${content.location.full_name}</strong> has a population of <strong>${content.location.population}</strong> and is 1 of ${content.location.peer_locations} ${content.location.admin_level_name_plural} in ${content.location.parent_name}. We estimate ${content.location.name} has ${content.location.believers} people who might know Jesus, ${content.location.christian_adherents} people who might know about Jesus culturally, and ${content.location.non_christians} people who do not know Jesus.</p>
          </div>
      </div>`
    )
    add_map()

    // LOOP STACK
    jQuery.each(content.list, function(i,block) {
      get_template( block )
    })

    // FOOTER
    div.append(`<div class="row text-center"><div class="col"><hr>Location ID: ${content.grid_id}</</div>`)

    prayer_progress_indicator( window.time ) // SETS THE PRAYER PROGRESS WIDGET
  }

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

  /**
   * BLOCK TEMPLATES
   */
  function add_map() {
    let rand_select = Math.floor(Math.random() * 3)
    switch( rand_select ) {
      case 0:
        wide_globe()
        break;
      case 1:
        // @todo islands don't look good with this map
        zoom_globe()
        break;
      case 2:
        // @todo the green dot doesn't show up in the front for asia countries
        rotating_globe()
        break;
    }
  }
  function wide_globe(){
    jQuery('#location-map').append(`<div class="chartdiv wide_globe" id="wide_globe"></div>`)
    let content = window.current_content
    // https://www.amcharts.com/demos/rotating-globe/
    am5.ready(function() {

      var root = am5.Root.new("wide_globe");

      root.setThemes([
        am5themes_Animated.new(root)
      ]);

      var chart = root.container.children.push(am5map.MapChart.new(root, {
        panX: "rotateX",
        projection: am5map.geoNaturalEarth1(),
        paddingBottom: 20,
        paddingTop: 20,
        paddingLeft: 20,
        paddingRight: 20,
        wheelY: 'none'
      }));

      var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
        geoJSON: am5geodata_worldLow
      }));

      polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}",
        toggleKey: "active",
        interactive: true
      });

      polygonSeries.mapPolygons.template.states.create("hover", {
        fill: root.interfaceColors.get("primaryButtonHover")
      });

      var backgroundSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {}));
      backgroundSeries.mapPolygons.template.setAll({
        fill: root.interfaceColors.get("alternativeBackground"),
        fillOpacity: 0.1,
        strokeOpacity: 0
      });
      backgroundSeries.data.push({
        geometry: am5map.getGeoRectangle(90, 180, -90, -180)
      });

      var graticuleSeries = chart.series.push(am5map.GraticuleSeries.new(root, {}));
      graticuleSeries.mapLines.template.setAll({ strokeOpacity: 0.1, stroke: root.interfaceColors.get("alternativeBackground") })

      chart.animate({
        key: "rotationX",
        from: 0,
        to: 360,
        duration: 60000,
        loops: Infinity
      });

      chart.appear(1000, 100);

      let cities = {
        "type": "FeatureCollection",
        "features": [{
          "type": "Feature",
          "properties": {
            "name": content.location.full_name
          },
          "geometry": {
            "type": "Point",
            "coordinates": [content.location.longitude, content.location.latitude]
          }
        }]
      };

      let pointSeries = chart.series.push(
        am5map.MapPointSeries.new(root, {
          geoJSON: cities
        })
      );

      pointSeries.bullets.push(function() {
        return am5.Bullet.new(root, {
          sprite: am5.Circle.new(root, {
            radius: 30,
            fill: 'green',
          })
        });
      });

      chart.seriesContainer.draggable = false;
      chart.seriesContainer.resizable = false;

    }); // end am5.ready()
  }
  function rotating_globe(){
    jQuery('#location-map').append(`<div class="chartdiv" id="rotating_globe"></div>`)
    let content = window.current_content
    // https://www.amcharts.com/demos/rotating-globe/
    am5.ready(function() {

      var root = am5.Root.new("rotating_globe");

      root.setThemes([
        am5themes_Animated.new(root)
      ]);

      var chart = root.container.children.push(am5map.MapChart.new(root, {
        panX: "rotateX",
        projection: am5map.geoOrthographic(),
        paddingBottom: 20,
        paddingTop: 20,
        paddingLeft: 20,
        paddingRight: 20,
        wheelY: 'none'
      }));

      var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
        geoJSON: am5geodata_worldLow
      }));

      polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}",
        toggleKey: "active",
        interactive: true
      });

      polygonSeries.mapPolygons.template.states.create("hover", {
        fill: root.interfaceColors.get("primaryButtonHover")
      });

      var backgroundSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {}));
      backgroundSeries.mapPolygons.template.setAll({
        fill: root.interfaceColors.get("alternativeBackground"),
        fillOpacity: 0.1,
        strokeOpacity: 0
      });
      backgroundSeries.data.push({
        geometry: am5map.getGeoRectangle(90, 180, -90, -180)
      });

      var graticuleSeries = chart.series.push(am5map.GraticuleSeries.new(root, {}));
      graticuleSeries.mapLines.template.setAll({ strokeOpacity: 0.1, stroke: root.interfaceColors.get("alternativeBackground") })


      chart.animate({
        key: "rotationX",
        from: 0,
        to: 360,
        duration: 60000,
        loops: Infinity
      });

      chart.appear(1000, 100);

      let cities = {
        "type": "FeatureCollection",
        "features": [{
          "type": "Feature",
          "properties": {
            "name": content.location.full_name
          },
          "geometry": {
            "type": "Point",
            "coordinates": [content.location.longitude, content.location.latitude]
          }
        }]
      };

      let pointSeries = chart.series.push(
        am5map.MapPointSeries.new(root, {
          geoJSON: cities
        })
      );

      pointSeries.bullets.push(function() {
        return am5.Bullet.new(root, {
          sprite: am5.Circle.new(root, {
            radius: 30,
            fill: 'green',
          })
        });
      });
      chart.deltaLongitude = content.location.longitude;

    }); // end am5.ready()
  }
  function zoom_globe(){
    jQuery('#location-map').append(`<div class="chartdiv zoom_globe" id="zoom_globe"></div>`)
    let content = window.current_content
    // https://www.amcharts.com/demos/rotating-globe/
    am5.ready(function() {

      var root = am5.Root.new("zoom_globe");

      // root.setThemes([
      //   am5themes_Animated.new(root)
      // ]);

      var chart = root.container.children.push(am5map.MapChart.new(root, {
        panX: "rotateX",
        panY: "rotateY",
        projection: am5map.geoNaturalEarth1(),
        paddingBottom: 20,
        paddingTop: 20,
        paddingLeft: 20,
        paddingRight: 20,
        homeZoomLevel: 3.5,
        homeGeoPoint: { longitude: content.location.longitude, latitude: content.location.latitude },
        wheelY: 'none'
      }));

      var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
        geoJSON: am5geodata_worldLow
      }));

      polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}",
        toggleKey: "active",
        interactive: true
      });

      polygonSeries.mapPolygons.template.states.create("hover", {
        fill: root.interfaceColors.get("primaryButtonHover")
      });

      var backgroundSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {}));
      backgroundSeries.mapPolygons.template.setAll({
        fill: root.interfaceColors.get("alternativeBackground"),
        fillOpacity: 0.1,
        strokeOpacity: 0
      });
      backgroundSeries.data.push({
        geometry: am5map.getGeoRectangle(90, 180, -90, -180)
      });

      var graticuleSeries = chart.series.push(am5map.GraticuleSeries.new(root, {}));
      graticuleSeries.mapLines.template.setAll({ strokeOpacity: 0.1, stroke: root.interfaceColors.get("alternativeBackground") })

      chart.appear(1000, 100);

      let cities = {
        "type": "FeatureCollection",
        "features": [{
          "type": "Feature",
          "properties": {
            "name": content.location.full_name
          },
          "geometry": {
            "type": "Point",
            "coordinates": [content.location.longitude, content.location.latitude]
          }
        }]
      };

      let pointSeries = chart.series.push(
        am5map.MapPointSeries.new(root, {
          geoJSON: cities
        })
      );

      pointSeries.bullets.push(function() {
        return am5.Bullet.new(root, {
          sprite: am5.Circle.new(root, {
            radius: 30,
            fill: 'green',
          })
        });
      });

      polygonSeries.events.on("datavalidated", function() {
        chart.goHome();
      });

    }); // end am5.ready()
  }


  /**
   *  LISTENERS FOR CLICKS
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


  /**
   * CELEBRATE FUNCTION
   */
  function celebrate(){
    div.empty()
    celebrate_panel.show()
  }

  /**
   * API HANDLERS
   */
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

  /**
   * TEMPLATE LOADER
   */
  function get_template( block ) {
    switch(block.type) {
      case 'counter':
        _template_counter( block.data )
        break;
      // @todo
      default:
        sample_template(block_name)
        break;
    }
  }
  function _template_counter( data ) {
    div.append(
      `<div class="row">
          <div class="col-md-12">
              <hr>
              <h3 class="mt-3 font-weight-normal text-center">${data.label}</h3>
              <p class="mt-3 mb-3 font-weight-normal">${data.value}</p>
              <p class="mt-3 mb-3 font-weight-normal">${data.prayer}</p>
          </div>
      </div>`
    )
  }
  function sample_template(name){
    div.append(
      `<div class="row">
          <div class="col-md-12">
              <hr>
              <h3 class="mt-3 font-weight-normal text-center">${name}</h3>
              <p class="mt-3 mb-3 font-weight-normal"></p>
          </div>
      </div>`
    )
  }
})
