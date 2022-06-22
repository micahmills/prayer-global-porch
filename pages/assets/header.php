<?php
global $wp;
$current_url = home_url( add_query_arg( array(), $wp->request ) );
?>
<meta name="apple-mobile-web-app-title" content="Prayer.Global">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/apple-touch-icon.png">

<link rel="icon" type="image/png" sizes="512x512" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/android-chrome-512x512.png">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/favicon-16x16.png">

<link rel="mask-icon" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/safari-pinned-tab.svg" color="#fff">
<link rel="shortcut icon" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/favicon.ico">

<meta name="msapplication-square512x512logo" content="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/android-chrome-512x512.png">
<meta name="msapplication-square192x192logo" content="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/android-chrome-192x192.png">

<meta name="theme-color" content="#fff">

<link rel="manifest" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/site.webmanifest">

<meta property="og:url"                content="<?php echo esc_url( $current_url ) ?>" />
<meta property="og:type"               content="app" />
<meta property="og:title"              content="Prayer.Global" />
<meta property="og:description"        content="Join us in covering the world in prayer for disciple making using a creative, community-driven prayer coordination app." />
<meta property="og:image"              content="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ); ?>assets/images/favicons/prayer-global-og.png" />
<meta name="description" content="Join us in covering the world in prayer for disciple making using a creative, community-driven prayer coordination app.">

<link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600|Montserrat:200,300,400" rel="stylesheet">

<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>css/bootstrap/bootstrap.css">
<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>fonts/ionicons/css/ionicons.min.css">

<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>fonts/fontawesome/css/font-awesome.min.css">


<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>css/slick.css">
<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>css/slick-theme.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'css/slick-theme.css' ) ) ?>">

<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>css/helpers.css">
<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>css/style.css">

<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>pg.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) )  . 'pg.css' ) ) ?>">

<script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>js/jquery.min.js"></script>

<link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/basic.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/basic.css' ) ) ?>" type="text/css" media="all">


