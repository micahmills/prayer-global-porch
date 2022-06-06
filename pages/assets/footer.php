
<script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>js/popper.min.js"></script>
<script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>js/bootstrap.min.js"></script>
<script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>js/slick.min.js"></script>

<script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>js/jquery.waypoints.min.js"></script>
<script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>js/jquery.easing.1.3.js"></script>

<script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>js/main.js?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'js/main.js' ) ) ?>"></script>
<script>
    jQuery(document).ready(function(){
        jQuery('body').data('spy', 'scroll').data('target', '#pb-navbar').data('offset', '200')
    })
</script>
