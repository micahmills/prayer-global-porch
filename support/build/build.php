<?php
// @phpcs:disable
// Builds a balanced states view of the world.
// Extend PHP limits for large processing
ini_set( 'memory_limit', '50000M' );

// define database connection
if ( ! file_exists( 'connect_params.json' ) ) {
    $content = '{"host": "","username": "","password": "","database": ""}';
    file_put_contents( 'connect_params.json', $content );
}
$params = json_decode( file_get_contents( "connect_params.json" ), true );
if ( empty( $params['host'] ) ) {
    print 'You have just created the connect_params.json file, but you still need to add database connection information.
Please, open the connect_params.json file and add host, username, password, and database information.' . PHP_EOL;
    die();
}
$con = mysqli_connect( $params['host'], $params['username'], $params['password'], $params['database'] );
if ( !$con) {
    echo 'mysqli Connection FAILED. Check parameters inside connect_params.json file.' . PHP_EOL;
    die();
}

print 'BEGIN' . PHP_EOL;

/**
 * Core tables that make dependent tables
 * - birth_rate
 * - death_rate
 * => growth_rate
 *
 * percent_believers
 * percent_christian_adherents
 * => percent_non_christian
 * => believers
 * => christian_adherents
 * => non_christians
 */
// create growth rate
$gr = mysqli_query( $con,
    "
        UPDATE location_grid_facts lgf
        SET lgf.growth_rate = ( ( lgf.birth_rate - lgf.death_rate ) * .01 ) + 1
        WHERE lgf.level = 0;
        " );
print 'gr:' . $con->affected_rows . PHP_EOL;
if ( $con->affected_rows < 0 ) {
    print_r( $con );
}

// create percent_non_christians
$pnc = mysqli_query( $con,
    "
        UPDATE location_grid_facts lgf
        SET lgf.percent_non_christians = ROUND( 100 - lgf.percent_believers - lgf.percent_christian_adherents, 5)
        WHERE lgf.level = 0;
        " );
print 'pnc:' . $con->affected_rows . PHP_EOL;
if ( $con->affected_rows < 0 ) {
    print_r( $con );
}

// create believers
$b = mysqli_query( $con,
    "
        UPDATE location_grid_facts lgf
        JOIN wp_dt_location_grid lg ON lg.grid_id=lgf.grid_id
        SET lgf.believers = IF( lg.population > 0, ROUND( lg.population * ( lgf.percent_believers * .01), 0 ), 0 )
        WHERE lgf.level = 0
        " );
print 'b:' . $con->affected_rows . PHP_EOL;
if ( $con->affected_rows < 0 ) {
    print_r( $con );
}

// create christian_adherents
$ca = mysqli_query( $con,
    "
        UPDATE location_grid_facts lgf
        JOIN wp_dt_location_grid lg ON lg.grid_id=lgf.grid_id
        SET lgf.christian_adherents = IF( lg.population > 0, ROUND( lg.population * ( lgf.percent_christian_adherents * .01), 0 ), 0 )
        WHERE lgf.level = 0
        " );
print 'ca:' . $con->affected_rows . PHP_EOL;
if ( $con->affected_rows < 0 ) {
    print_r( $con );
}

// create non_christians
$nc = mysqli_query( $con,
    "
        UPDATE location_grid_facts lgf
        JOIN wp_dt_location_grid lg ON lg.grid_id=lgf.grid_id
        SET lgf.non_christians = IF( lg.population, ROUND( lg.population * ( lgf.percent_non_christians * .01), 0 ), 0 )
        WHERE lgf.level = 0
        " );
print 'nc:' . $con->affected_rows . PHP_EOL;
if ( $con->affected_rows < 0 ) {
    print_r( $con );
}

// update all children
// copy growth_rate, death_rate, official language, official religion, percent_believers, percent_christian_adherents
$sub = mysqli_query( $con,
    "
        UPDATE location_grid_facts lgf
        JOIN location_grid_facts lgf2 ON lgf2.grid_id=lgf.admin0_grid_id
        JOIN wp_dt_location_grid lg ON lg.grid_id=lgf.grid_id
        SET
            lgf.birth_rate = lgf2.birth_rate,
            lgf.death_rate = lgf2.death_rate,
            lgf.growth_rate = lgf2.growth_rate,
            lgf.believers = IF( lg.population, ROUND( lg.population * ( lgf2.percent_believers * .01 ) ), 0 ),
            lgf.christian_adherents = IF( lg.population, ROUND( lg.population * ( lgf2.percent_christian_adherents * .01 ) ), 0 ),
            lgf.non_christians = IF( lg.population, ROUND( lg.population * ( lgf2.percent_non_christians * .01 ) ), 0 ),
            lgf.primary_language = lgf2.primary_language,
            lgf.primary_religion = lgf2.primary_religion,
            lgf.percent_believers = lgf2.percent_believers,
            lgf.percent_christian_adherents = lgf2.percent_christian_adherents,
            lgf.percent_non_christians = lgf2.percent_non_christians
        WHERE lgf.level != 0;
        " );
print 'sub:' . $con->affected_rows . PHP_EOL;
if ( $con->affected_rows < 0 ) {
    print_r( $con );
}

print 'END' . PHP_EOL;

mysqli_close( $con );
