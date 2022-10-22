var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
  || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
  isMobile = true;
}

jQuery(document).ready(function($){

  window.get_page = (action) => {
    return jQuery.ajax({
      type: "POST",
      data: JSON.stringify({ action: action, parts: jsObject.parts }),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      url: jsObject.root + jsObject.parts.root + '/v1/' + jsObject.parts.type + '/' + jsObject.parts.action,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-WP-Nonce', jsObject.nonce )
      }
    })
      .fail(function(e) {
        console.log(e)
        jQuery('#error').html(e)
      })
  }
  window.get_data_page = (action, data ) => {
    return jQuery.ajax({
      type: "POST",
      data: JSON.stringify({ action: action, parts: jsObject.parts, data: data }),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      url: jsObject.root + jsObject.parts.root + '/v1/' + jsObject.parts.type + '/' + jsObject.parts.action,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-WP-Nonce', jsObject.nonce )
      }
    })
      .fail(function(e) {
        console.log(e)
        jQuery('#error').html(e)
      })
  }

  jQuery('#custom-style').empty().append(`
      #wrapper {
          height: ${window.innerHeight}px !important;
      }
      #map-wrapper {
          height: ${window.innerHeight}px !important;
      }
      #map {
          height: ${window.innerHeight}px !important;
      }
      #initialize-screen {
          height: ${window.innerHeight}px !important;
      }
      #welcome-modal {
          height: ${window.innerHeight - 30}px !important;
      }
      #map-sidebar-wrapper {
          height: ${window.innerHeight}px !important;
      }
`)

  let initialize_screen = jQuery('.initialize-progress')

  // preload all geojson
  let asset_list = []
  var i = 1;
  while( i <= 10 ){
    asset_list.push(i+'.geojson')
    i++
  }

  let loop = 0
  let list = 0
  window.load_map_triggered = 0
  window.get_page('get_grid')
    .done(function(x){
      list = 1

      jsObject.grid_data = x.grid_data
      jsObject.stats = x.stats

      if ( loop > 9 && list > 0 && window.load_map_triggered !== 1 ){
        window.load_map_triggered = 1
        load_map()
      }
    })
    .fail(function(){
      console.log('Error getting grid data')
      jsObject.grid_data = {'data': {}, 'highest_value': 1 }
    })
  let data = {
    hash: Cookies.get('pg_user_hash')
  }


  let map
  jQuery.each(asset_list, function(i,v) {
    jQuery.ajax({
      url: jsObject.mirror_url + 'tiles/world/flat_states/' + v,
      dataType: 'json',
      data: null,
      cache: true,
      beforeSend: function (xhr) {
        if (xhr.overrideMimeType) {
          xhr.overrideMimeType("application/json");
        }
      }
    })
      .done(function(x){
        loop++
        initialize_screen.val(loop)

        if ( 1 === loop ) {
          jQuery('#initialize-people').show()
        }

        if ( 3 === loop ) {
          jQuery('#initialize-activity').show()
        }

        if ( 5 === loop ) {
          jQuery('#initialize-coffee').show()
        }

        if ( 8 === loop ) {
          jQuery('#initialize-dothis').show()
        }

        if ( loop > 7 && list > 0 && window.load_map_triggered !== 1 ){
          window.load_map_triggered = 1
          load_map()
        }

      })
      .fail(function(){
        loop++
      })
  })

  function load_map() {
    jQuery('#initialize-screen').hide()
    jQuery('.loading-spinner').removeClass('active')

    let center = [0, 20]
    let zoom = 1.5

    mapboxgl.accessToken = jsObject.map_key;
    map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/discipletools/cl2ksnvie001i15qm1h5ahqea',
      center: center,
      minZoom: 1,
      maxZoom: 12,
      zoom: zoom,
      maxBounds: [ [-170, -85], [180, 85] ]
    });
    map.dragRotate.disable();
    map.touchZoomRotate.disableRotation();

    let nav = new mapboxgl.NavigationControl({
      showCompass: false,
      showZoom: true
    });

    map.on('load', function() {
      load_grid()
    })
  }

  function load_grid() {
    jQuery.each(asset_list, function(i,file){

      jQuery.ajax({
        url: jsObject.mirror_url + 'tiles/world/flat_states/' + file,
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

          /* load prayer grid layer */

            jQuery.each(geojson.features, function (i, v) {
              if (typeof jsObject.grid_data.data[v.id] !== 'undefined' ) {
                geojson.features[i].properties.value = jsObject.grid_data.data[v.id]
              } else {
                geojson.features[i].properties.value = 0
              }
            })

            map.addSource(i.toString(), {
              'type': 'geojson',
              'data': geojson
            });
            map.addLayer({
              'id': i.toString()+'line',
              'type': 'line',
              'source': i.toString(),
              'paint': {
                'line-color': 'white',
                'line-width': .5
              }
            });
            map.addLayer({
              'id': i.toString() + 'fills_heat',
              'type': 'fill',
              'source': i.toString(),
              'paint': {
                'fill-color': {
                  property: 'value',
                  stops: [[0, 'rgba(255,0,0, .7)'], [1, 'rgba(0,128,0, .9)']]
                },
                'fill-opacity': 0.75,
                'fill-outline-color': 'black'
              }
            },'waterway-label' )

        }) /* ajax call */

    }) /* for each loop */

    // add stats
    jQuery('.completed').html( jsObject.stats.completed )
    jQuery('.completed_percent').html( jsObject.stats.completed_percent )
    jQuery('.remaining').html( jsObject.stats.remaining )
    jQuery('.time_elapsed').html( jsObject.stats.time_elapsed_small )
    jQuery('.prayer_warriors').html( jsObject.stats.participants )
    jQuery('.lap_pace').html( jsObject.stats.lap_pace_small )

    jQuery('#head_block').show()
    jQuery('#foot_block').show()

    const red = 'rgba(255,0,0, .7)'
    const green = 'rgba(0,128,0, .9)'

    const layers = [
      {
        label: 'Places Remaining',
        color: red,
      },
      {
        label: 'Covered in Prayer',
        color: green,
      }
    ]
    const legendDiv = document.getElementById('map-legend');
    loadLegend( legendDiv, layers )
  } /* .loadgrid */

  function loadLegend(legendDiv, layers) {
    layers.forEach( ({ label, color }) => {
      const container = document.createElement('div')
      container.classList.add('map-legend__layer')

      const colorSwatch = document.createElement('div')
      colorSwatch.classList.add('map-legend__color-swatch')
      colorSwatch.style.backgroundColor = color

      const text = document.createElement('span')
      text.classList.add('map-legend__label')
      text.innerHTML = label

      container.appendChild(colorSwatch)
      container.appendChild(text)

      legendDiv.appendChild(container)
    })
  }

  setInterval(function(){
    window.get_page('get_grid')
      .done(function(x){
        console.log('reload')
        // add stats
        jsObject.stats = x.stats
        jQuery('.completed').html( jsObject.stats.completed )
        jQuery('.completed_percent').html( jsObject.stats.completed_percent )
        jQuery('.remaining').html( jsObject.stats.remaining )
        jQuery('.time_elapsed').html( jsObject.stats.time_elapsed_small )
        jQuery('.prayer_warriors').html( jsObject.stats.participants )
        jQuery('.lap_pace').html( jsObject.stats.lap_pace_small )

        jsObject.grid_data = x.grid_data
        reload_load_grid()
      })
  }, 60000 )

  function reload_load_grid() {
    jQuery.each(asset_list, function(i,file){

      jQuery.ajax({
        url: jsObject.mirror_url + 'tiles/world/flat_states/' + file,
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

          /* load prayer grid layer */
          jQuery.each(geojson.features, function (i, v) {
            if (typeof jsObject.grid_data.data[v.id] !== 'undefined' ) {
              geojson.features[i].properties.value = jsObject.grid_data.data[v.id]
            } else {
              geojson.features[i].properties.value = 0
            }
          })

          map.getSource(i.toString()).setData(geojson);

        }) /* ajax call */

    }) /* for each loop */

    // add stats
    jQuery('.completed').html( jsObject.stats.completed )
    jQuery('.completed_percent').html( jsObject.stats.completed_percent )
    jQuery('.remaining').html( jsObject.stats.remaining )
    jQuery('.time_elapsed').html( jsObject.stats.time_elapsed_small )
    jQuery('.prayer_warriors').html( jsObject.stats.participants )
    jQuery('.lap_pace').html( jsObject.stats.lap_pace_small )

    jQuery('#head_block').show()
    jQuery('#foot_block').show()
  } /* .loadgrid */

})


