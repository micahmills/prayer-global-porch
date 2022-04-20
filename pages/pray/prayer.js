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

  let location_show_borders = jQuery('#show_borders')

  let i

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

    location_show_borders.show()
    add_map()

    // LOOP STACK
    jQuery.each(content.list, function(i,block) {
      get_template( block )
    })

    // FOOTER
    div.append(`<div class="row text-center"><div class="col">Location ID: ${content.location.grid_id}</</div>`)

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
    jQuery('#location-map').html(`<div class="chartdiv wide_globe" id="wide_globe"></div>`)
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
    jQuery('#location-map').html(`<div class="chartdiv rotating_globe" id="rotating_globe"></div>`)
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
    jQuery('#location-map').html(`<div class="chartdiv zoom_globe" id="zoom_globe"></div>`)
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
  function mapbox_border_map() {
    let content = jQuery('#location-map')
    let grid_row = window.current_content.location
    console.log(grid_row)

    content.empty().html(`
        <div id="map-wrapper"><div id='mabox-map'></div>
          <div id="style-menu">
          <input id="dt" type="radio" name="rtoggle" value="discipletools/cl1qp8vuf002l15ngm5a7up59" checked="checked">
          <label for="dt">light</label>
          <input id="outdoors-v11" type="radio" name="rtoggle" value="mapbox/outdoors-v11">
          <label for="outdoors-v11">outdoors</label>
          <input id="satellite-v9" type="radio" name="rtoggle" value="mapbox/satellite-v9">
          <label for="satellite-v9">satellite</label>
          <input id="streets-v11" type="radio" name="rtoggle" value="mapbox/streets-v11">
          <label for="streets-v11">streets</label>
          </div></div>`)
    jQuery('#style-menu input').on('change', function(e){
      console.log(e.target.value)
      window.load_map_with_style( e.target.value )
    })

    window.load_map_with_style = ( style ) => {
      let center = [grid_row.longitude, grid_row.latitude]
      mapboxgl.accessToken = jsObject.map_key;
      let map = new mapboxgl.Map({
        container: 'mabox-map',
        style: 'mapbox://styles/'+style,
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
              // 'maxzoom': 12,
              'filter': [ '==', ['get', 'grid_id'], grid_row.grid_id ],
              'paint': {
                'fill-color': '#0080ff',
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
            map.addLayer({
              'id': 'poi-labels',
              'type': 'symbol',
              'source': 'parent_collection',
              'filter': [ '==', ['get', 'grid_id'], grid_row.grid_id ],
              'layout': {
                'text-field': ['get', 'full_name'],
                'text-variable-anchor': ['top', 'bottom', 'left', 'right'],
                'text-radial-offset': 0.5,
                'text-justify': 'auto',
                'icon-image': ['get', 'icon']
              }
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
              [parseFloat( grid_row.west_longitude), parseFloat(grid_row.south_latitude)], // southwestern corner of the bounds
              [parseFloat(grid_row.east_longitude), parseFloat(grid_row.north_latitude)] // northeastern corner of the bounds
            ], {padding: 25, duration: 5000});

          })

      }) // map load
    }
    window.load_map_with_style('discipletools/cl1qp8vuf002l15ngm5a7up59') // initialize map
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
  location_show_borders.on('click', function(e) {
    mapbox_border_map()
  })


  /**
   * CELEBRATE FUNCTION
   */
  function celebrate(){
    div.empty()
    location_show_borders.hide()
    celebrate_panel.show()
  }

  /**
   * API HANDLERS
   */
  function log() {
    window.api_post( 'log', { grid_id: window.current_content.location.grid_id, pace: window.pace } )
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
    window.api_post( 'refresh', { grid_id: window.current_content.location.grid_id } )
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
      case '4_fact_blocks':
        _template_4_fact_blocks( block.data )
        break;
      case 'percent_3_circles':
        _template_percent_3_circles( block.data )
        break;
      case 'percent_3_bar':
        _template_percent_3_bar( block.data )
        break;
      case 'percent_2_circles':
        _template_percent_2_circles( block.data )
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
      case 'fact_block':
        _template_fact_block( block.data )
        break;
      case 'content_block':
        _template_content_block( block.data )
        break;
      case 'photo_block':
        _template_photo_block( block.data )
        break;
      default:
        break;
    }
  }
  function _template_percent_3_circles( data ) {
    div.append(
      `<div class="row">
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
      <div class="w-100"><hr></div>`
    )
  }
  function _template_percent_2_circles( data ) {
    div.append(
      `<div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
          <div class="col-md-3 col-lg-2">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_1}</p>
            <div class="pie" style="--p:${data.percent_1};--b:10px;--c:${data.color_1};">${data.percent_1}%</div>
            <p class="mt-3 mb-0 font-weight-normal one-em">${data.population_1}</p>
          </div>
          <div class="col-md-3 col-lg-2">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_2}</p>
            <div class="pie" style="--p:${data.percent_2};--b:10px;--c:green;">${data.percent_2}%</div>
            <p class="mt-3 mb-0 font-weight-normal one-em">${data.population_2}</p>
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
      <div class="w-100"><hr></div>`
    )
  }
  function _template_percent_3_bar( data ) {
    div.append(
      `<div class="row">
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
      <div class="w-100"><hr></div>`
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
      `<div class="row">
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
      <div class="w-100"><hr></div>`
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
      `<div class="row">
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
          </div>
          <div class="col-md-3 col-sm">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_2}</p>
            <p class="mt-0 mb-3 font-weight-normal">
              ${bodies_2}
            </p>
          </div>
          <div class="col-md-3 col-sm">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_3}</p>
            <p class="mt-0 mb-3 font-weight-normal">
              ${bodies_3}
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
      <div class="w-100"><hr></div>`
    )
  }
  function _template_population_change_icon_block( data ) {
    if( data.count === '0' || data.count.length > 3 ) {
      return
    }

    // icon types
    let icons = ''
    if ( 'deaths' === data.type ) {
      icons = ['ion-ios-contact-outline', 'ion-android-sad','ion-ios-contact', 'ion-ios-body', 'ion-person','ion-ios-person','ion-sad']
    } else {
      icons = ['ion-social-reddit','ion-social-reddit', 'ion-home', 'ion-ios-heart', 'ion-ios-home']
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

    // build icon list
    let icon_list = ''
    i = 0
    while ( i < data.count ) {
      icon_list += '<i class="'+icon+' '+icon_color+' '+icon_size+'"></i>';
      i++;
    }
    div.append(
      `<div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center justify-content-center">
          <div class="col-md-8 col-sm">
            <p class="mt-0 mb-1 font-weight-normal icon-block">
              ${icon_list} (${data.count})
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
      <div class="w-100"><hr></div>`
    )
  }
  function _template_4_fact_blocks( data ) {
    div.append(
      `<div class="row">
          <div class="col text-center ">
             <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center">
          <div class="col-md-3 col-sm-6">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_1}</p>
            <p class="mt-0 mb-3 font-weight-normal ${data.size_1}">${data.value_1}</p>
          </div>
          <div class="col-md-3 col-sm-6">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_2}</p>
            <p class="mt-0 mb-3 font-weight-normal ${data.size_2}">${data.value_2}</p>
          </div>
          <div class="col-md-3 col-sm-6">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_3}</p>
            <p class="mt-0 mb-3 font-weight-normal ${data.size_3}">${data.value_3}</p>
          </div>
          <div class="col-md-3 col-sm-6">
            <p class="mt-3 mb-0 font-weight-bold">${data.label_4}</p>
            <p class="mt-0 mb-3 font-weight-normal ${data.size_4}">${data.value_4}</p>
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
      <div class="w-100"><hr></div>`
    )
  }
  function _template_bullet_list_2_column( data ) {
    if ( data.values.length > 0 ) {
      let values_list = ''
      jQuery.each(data.values, function(i,v) {
        values_list += '<p>'+v+'</p>'
      })
      div.append(
        `<div class="row">
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
      <div class="w-100"><hr></div>`)
    }
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
      `<div class="row">
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
    <div class="w-100"><hr></div>`)
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
      `<div class="row">
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
      <div class="w-100"><hr></div>`)
  }
  function _template_photo_block( data ) {
    div.append(
      `<div class="row">
          <div class="col text-center ">
            <p class="mt-3 mb-3 font-weight-normal one-em uc">${data.section_label}</p>
          </div>
      </div>
      <div class="row text-center">
        <div class="col">
           <img src="${data.url}" class="img-fluid" alt="" />
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col">
            <p class="mt-3 font-weight-normal">${data.section_summary}</p>
        </div>
      </div>
      <div class="row text-center justify-content-center">
        <div class="col-md-8">
           <p class="mt-3 mb-3 font-weight-normal one-em">${data.prayer}</p>
        </div>
    </div>
      <div class="w-100"><hr></div>`)
  }
})
