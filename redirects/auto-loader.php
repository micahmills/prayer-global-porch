<?php
$auto_load_dir = scandir( plugin_dir_path( __FILE__ ) );
dt_write_log($auto_load_dir);
if ( ! empty( $auto_load_dir ) ) {
    foreach ( $auto_load_dir as $file ) {
        if ( substr( $file, -4, '4' ) === '.php' && 'auto-loader.php' !== $file ) {
            require_once( plugin_dir_path( __FILE__ ) . '/' . $file );
        }
    }
}
