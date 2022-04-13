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

    jQuery('#location-name').html(location.full_name)

    div.empty()
    // location maps
    div.append(
      `<div class="row">
          <div class="col">
              <p class="text-md-center" id="location-map"></p>
              <p class="text-md-center">The ${location.admin_level_name} of <strong>${location.full_name}</strong> has a population of <strong>${location.stats.population}</strong> and is 1 of ${location.peer_locations} ${location.admin_level_name_plural} in ${location.parent_name}. We estimate ${location.name} has ${location.stats.believers} people who might know Jesus, ${location.stats.christian_adherants} people who might know about Jesus culturally, and ${location.stats.non_christians} people who do not know Jesus.</p>
          </div>
      </div><hr>`
    )
    // statements
    if ( content.statements.length > 0 ) {
      div.append(`<div class="row" id="statements-list"></div>`)
      let statements_list = jQuery('#statements-list')
      jQuery.each(content.statements, function(i,v) {
        statements_list.append(`<div class="col-12 mb-3 text-center one-em"><div style="max-width:600px;margin:0 auto;">${v}</div></div>`)
      })
    }
    // counters
    div.append(`<div class="row text-center" id="counters"></div><hr>`)
    // sections
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
    // cities
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
    // people groups
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
          pg_list.append(`<li>${v.name} (${v.AffinityBloc} who speak ${v.PrimaryLanguageName})</li>`)
        })
    }

    div.append(`<div class="row text-center"><div class="col">${content.grid_id}</</div>`)

    // process counters
    add_statements()
    add_map()
    add_counters()


    prayer_progress_indicator( window.time )
  }
  function add_statements(){
    let statements_div = jQuery('#prayer-statements')
    let content = window.current_content
    let stats = content.location.stats

    statements_div.append(`
      We estimate ${location.name} has ${stats.believers} people who might know Jesus, ${stats.christian_adherants} people who might know about Jesus culturally, and ${stats.non_christians} people who do not know Jesus.
    `)
  }
  function add_counters(){
    let counter_div = jQuery('#counters')
    let content = window.current_content
    let stats = content.location.stats
    let i = 0

    // divider
    counter_div.append(`
      <div class="col-md-12">
        <hr>
      </div>
    `)

    // circle charts
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Non Christians</p>
        <div class="pie" style="--p:${stats.percent_non_christians};--b:10px;--c:red;">${stats.percent_non_christians}%</div>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Cultural Christians</p>
        <div class="pie" style="--p:${stats.percent_christian_adherants};--b:10px;--c:orange;">${stats.percent_christian_adherants}%</div>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Believers</p>
        <div class="pie" style="--p:${stats.percent_believers};--b:10px;--c:green;">${stats.percent_believers}%</div>
      </div>
    `)

    // Faith
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Don't Know Jesus</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.non_christians}</p>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Know About Jesus</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.christian_adherants}</p>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Know Jesus</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.believers}</p>
      </div>
    `)

    // bar chart
    counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Know Jesus Personally</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" style="width:${stats.percent_non_christians}%">
              Don't Know
            </div>
            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:${stats.percent_christian_adherants}%">
              Know About
            </div>
            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:${stats.percent_believers}%">
              Know
            </div>
          </div>
        </p>
      </div>
    `)

    // 100 bodies percent count
    let bodies = ''
    i = 0
    while ( i < stats.percent_non_christians ) {
      bodies += '<i class="ion-ios-body red"></i>';
      i++;
    }
    i = 0
    while ( i < stats.percent_christian_adherants ) {
      bodies += '<i class="ion-ios-body orange"></i>';
      i++;
    }
    i = 0
    while ( i < stats.percent_believers ) {
      bodies += '<i class="ion-ios-body green"></i>';
      i++;
    }
    counter_div.append(`
      <div class="col-md-3"></div>
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Know Jesus Personally</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${bodies}
        </p>
      </div>
      <div class="col-md-3"></div>
    `)
    // end bodies

    // divider
    counter_div.append(`
      <div class="col-md-12">
        <hr>
      </div>
    `)

      // demographics
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Population</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.population}</p>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Population Growth</p>
        <p class="mt-0 mb-3 font-weight-normal two-em">${stats.population_growth_status}</p>
      </div>
    `)
    let pop_growth_icon = 'ion-android-arrow-up green'
    if ( stats.growth_rate <= 1 ) {
      pop_growth_icon = 'ion-android-arrow-down red'
    }
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Population Growth</p>
        <i class="${pop_growth_icon} six-em"></i>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Language</p>
        <p class="mt-0 mb-3 font-weight-normal two-em">${stats.primary_language}</p>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Dominant Religion</p>
        <p class="mt-0 mb-3 font-weight-normal two-em">${stats.primary_religion}</p>
      </div>
    `)
    counter_div.append(`
      <div class="col-md-4">
        <p class="mt-3 mb-0 font-weight-bold">Position</p>
        <p class="mt-0 mb-3 font-weight-normal two-em">1 of ${content.location.peer_locations} ${content.location.admin_level_name_plural} <br>in ${content.location.parent_name}</p>
      </div>
    `)



    // divider
    counter_div.append(`
      <div class="col-md-12">
        <hr>
      </div>
    `)


    let death_icons = ['ion-ios-contact-outline','ion-ios-contact','ion-woman', 'ion-man', 'ion-ios-body', 'ion-person','ion-ios-person','ion-sad']
    let death_icon = death_icons[Math.floor(Math.random() * death_icons.length)]

    let birth_icons = ['ion-social-reddit','ion-social-reddit', 'ion-home', 'ion-ios-heart', 'ion-ios-home']
    let birth_icon = birth_icons[Math.floor(Math.random() * birth_icons.length)]

    // Deaths
    let deaths_next_hour = parseFloat(stats.deaths_without_jesus_last_hour.replace(/,/g, ''))
    if ( deaths_next_hour < 200 && deaths_next_hour > 0 ) {
      deaths_next_hour = ''
      i = 0
      while ( i < stats.deaths_without_jesus_last_hour ) {
        deaths_next_hour += '<i class="'+death_icon+' red three-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus in an hour</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${deaths_next_hour}
        </p>
      </div>
    `)
    }
    let deaths_next_100 = parseFloat(stats.deaths_without_jesus_last_100.replace(/,/g, ''))
    if ( deaths_next_100 < 400 && deaths_next_100 > 0 ) {
      deaths_next_100 = ''
      i = 0
      while ( i < stats.deaths_without_jesus_last_100 ) {
        deaths_next_100 += '<i class="'+death_icon+' red two-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus in the next 100 hours</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${deaths_next_100}
        </p>
      </div>
    `)
    }
    let deaths_next_week = parseFloat(stats.deaths_without_jesus_last_week.replace(/,/g, ''))
    if ( deaths_next_week < 400 && deaths_next_week > 0 ) {
      deaths_next_week = ''
      i = 0
      while ( i < stats.deaths_without_jesus_last_week ) {
        deaths_next_week += '<i class="'+death_icon+' red two-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus next week</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${deaths_next_week}
        </p>
      </div>
    `)
    }
    let deaths_next_month = parseFloat(stats.deaths_without_jesus_last_month.replace(/,/g, ''))
    if ( deaths_next_month < 1000 && deaths_next_month > 0 ) {
      deaths_next_month = ''
      i = 0
      while ( i < stats.deaths_without_jesus_last_month ) {
        deaths_next_month += '<i class="'+death_icon+' red one-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus next month</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${deaths_next_month}
        </p>
      </div>
    `)
    }
    // numbers
    if ( stats.deaths_without_jesus_last_hour !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus in an hour</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.deaths_without_jesus_last_hour}</p>
      </div>
    `)
    }
    if ( stats.deaths_without_jesus_last_100 !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus in the next 100 hours</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.deaths_without_jesus_last_100}</p>
      </div>
    `)
    }
    if ( stats.deaths_without_jesus_last_week !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus next week</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.deaths_without_jesus_last_week}</p>
      </div>
    `)
    }
    if ( stats.deaths_without_jesus_last_month !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Dying without Jesus in the next month</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.deaths_without_jesus_last_month}</p>
      </div>
    `)
    }



    // divider
    counter_div.append(`
      <div class="col-md-12">
        <hr>
      </div>
    `)




    // Births

    let births_without_jesus_last_hour = parseFloat(stats.births_without_jesus_last_hour.replace(/,/g, ''))
    if ( births_without_jesus_last_hour < 300 && births_without_jesus_last_hour > 0 ) {
      births_without_jesus_last_hour = ''
      i = 0
      while ( i < stats.births_without_jesus_last_hour ) {
        births_without_jesus_last_hour += '<i class="'+birth_icon+' red three-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus in the last hour</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${births_without_jesus_last_hour}
        </p>
      </div>
    `)
    }
    let births_without_jesus_last_100 = parseFloat(stats.births_without_jesus_last_100.replace(/,/g, ''))
    if ( births_without_jesus_last_100 < 300 && births_without_jesus_last_100 > 0 ) {
      births_without_jesus_last_100 = ''
      i = 0
      while ( i < stats.births_without_jesus_last_100 ) {
        births_without_jesus_last_100 += '<i class="'+birth_icon+' red three-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus in the last 100 hours</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${births_without_jesus_last_100}
        </p>
      </div>
    `)
    }
    let births_without_jesus_last_week = parseFloat(stats.births_without_jesus_last_week.replace(/,/g, ''))
    if ( births_without_jesus_last_week < 300 && births_without_jesus_last_week > 0 ) {
      births_without_jesus_last_week = ''
      i = 0
      while ( i < stats.births_without_jesus_last_week ) {
        births_without_jesus_last_week += '<i class="'+birth_icon+' red two-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus in the last week</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${births_without_jesus_last_week}
        </p>
      </div>
    `)
    }
    let births_without_jesus_last_month = parseFloat(stats.births_without_jesus_last_month.replace(/,/g, ''))
    if ( births_without_jesus_last_month < 1000 && births_without_jesus_last_month > 0 ) {
      births_without_jesus_last_month = ''
      i = 0
      while ( i < stats.births_without_jesus_last_month ) {
        births_without_jesus_last_month += '<i class="'+birth_icon+' red two-em"></i>';
        i++;
      }
      counter_div.append(`
      <div class="col-md-12">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus in the last month</p>
        <p class="mt-0 mb-3 font-weight-normal grow">
          ${births_without_jesus_last_month}
        </p>
      </div>
    `)
    }
    // numbers
    if ( stats.births_without_jesus_last_hour !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus this hour</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.births_without_jesus_last_hour}</p>
      </div>
    `)
    }
    if ( stats.births_without_jesus_last_100 !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus in the last 100 hours</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.births_without_jesus_last_100}</p>
      </div>
    `)
    }
    if ( stats.births_without_jesus_last_week !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus last week</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.births_without_jesus_last_week}</p>
      </div>
    `)
    }
    if ( stats.births_without_jesus_last_month !== '0' ) {
      counter_div.append(`
      <div class="col-md-3">
        <p class="mt-3 mb-0 font-weight-bold">Births to families without Jesus last month</p>
        <p class="mt-0 mb-3 font-weight-normal three-em">${stats.births_without_jesus_last_month}</p>
      </div>
    `)
    }
    // end births

  }
  function add_map() {
    // @todo listing all maps

    wide_globe()
    zoom_globe()
    rotating_globe()

    // let location_map = jQuery('#location-map')
    // location_map.append(`<img style="width:600px;padding:.5em;" class="img-fluid" src="${jsObject.images_url + 'locations/0/' + window.current_content.grid_id + '.png' }" /><br>`)
    // location_map.append(`<img style="width:600px;padding:.5em;" class="img-fluid" src="${jsObject.images_url + 'locations/1/' + window.current_content.grid_id + '.png' }" /><br>`)

    // return

    // let rand = Math.floor(Math.random() * 3)
    // rand = 0
    // if ( 0 === rand ) {
    //   rotating_globe()
    // } else if ( 1 === rand ) {
    //   location_map.html(`<img style="width:600px;" class="img-fluid" src="${window.current_content.location.url}" />`)
    // } else if ( 2 === rand ) {
    //   zoom_globe()
    // } else if ( 3 === rand ) {
    //   wide_globe()
    // }
  }
  function wide_globe(){
    jQuery('#location-map').append(`<div class="chartdiv" id="wide_globe"></div>`)
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
    jQuery('#location-map').append(`<div class="chartdiv" id="zoom_globe"></div>`)
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
