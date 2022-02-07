<script>
    let jsObject = [<?php echo json_encode([
        'map_key' => DT_Mapbox_API::get_key(),
        'mirror_url' => dt_get_location_grid_mirror( true ),
        'theme_uri' => trailingslashit( get_stylesheet_directory_uri() ),
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
        'parts' => $this->parts,
        'post_type' => $this->post_type,
        'trans' => [
            'add' => __( 'Add Magic', 'disciple_tools' ),
        ],
        'grid_data' => ['data' => [], 'highest_value' => 1 ],
        // 'grid_data' => $this->_initial_polygon_value_list(),
    ]) ?>][0]

    jQuery(document).ready(function(){
        clearInterval(window.fiveMinuteTimer)
    })
</script>
