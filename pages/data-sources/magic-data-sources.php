<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Data_Source extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Global Prayer - Data Sources';
    public $root = 'content_app';
    public $type = 'data_sources';
    public $type_name = 'Global Prayer - Data Sources';
    public static $token = 'content_app_data_sources';
    public $post_type = 'laps';

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        $url = dt_get_url_path();
        if ( ( $this->root . '/' . $this->type ) === $url ) {

            $this->magic = new DT_Magic_URL( $this->root );
            $this->parts = $this->magic->parse_url_parts();


            // register url and access
            add_action( "template_redirect", [ $this, 'theme_redirect' ] );
            add_filter( 'dt_blank_access', function (){ return true;
            }, 100, 1 );
            add_filter( 'dt_allow_non_login_access', function (){ return true;
            }, 100, 1 );
            add_filter( 'dt_override_header_meta', function (){ return true;
            }, 100, 1 );

            // header content
            add_filter( "dt_blank_title", [ $this, "page_tab_title" ] ); // adds basic title to browser tab
            add_action( 'wp_print_scripts', [ $this, 'print_scripts' ], 1500 ); // authorizes scripts
            add_action( 'wp_print_styles', [ $this, 'print_styles' ], 1500 ); // authorizes styles


            // page content
            add_action( 'dt_blank_head', [ $this, '_header' ] );
            add_action( 'dt_blank_footer', [ $this, '_footer' ] );
            add_action( 'dt_blank_body', [ $this, 'body' ] ); // body for no post key

            add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
            add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );
        }

    }

    public function dt_magic_url_base_allowed_js( $allowed_js ) {
        return [];
    }

    public function dt_magic_url_base_allowed_css( $allowed_css ) {
        return [];
    }

    public function header_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/header.php' );
        ?>
        <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600|Montserrat:200,300,400" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/fonts/ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/basic.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/basic.css' ) ) ?>" type="text/css" media="all">
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' ) ?>

        <section class="pb_section" >
            <div class="container">
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="mt-0 heading-border-top font-weight-normal">Data Sources</h2>
                    </div>
                </div>
                <div class="grid-x grid-margin-x grid-padding-y">

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <p>
                            We acknowledge that there is no way to possess 100% accurate knowledge of the faith status or location status of
                            every person in the world.
                        </p>
                        <p>
                            No government has this exact number, no business, ... nobody but God has the facts of a person's true faith or whereabouts. Therefore, every demographic fact
                            is a mathematical deduction or extrapolation. (Sorry friends who like exact numbers.)
                        </p>
                        <p>
                            But leveraging the best data sources we can access, we have created a prayer tool to offer
                            informative prayer guidance featuring a unique, close-up location breakdown of the world.
                        </p>
                    </div>

                    <div style="padding:1em;border:2px solid gray;">
                        <h4>Recommended Resource: <a href="https://locationgrid.app">Location Grid Project</a></h4>
                        <p>
                            Prayer.Global is build on Disciple.Tools open source software, and Disciple.Tools mapping and
                            geolocation system is an open source project called Location Grid Project. The Location Grid Project
                            is a geographic framework of world locations and polygons for disciple making movement saturation efforts.
                        </p>
                    </div>

                    <div class="row justify-content-md-center text-center mb-5">
                        <div class="col-lg-7">
                            <h2 class="mt-0 pt-5 font-weight-normal">Data and Methodology</h2>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Population Data</h3>
                        <p>The population data was acquired and cross-checked through multiple sources; country level data
                            is easy to find; most state level data is relatively easy to find; but county level data for non-western countries
                            often required significant research to compile recent census data.
                        </p>

                        <u>Country Level Population</u>
                        <ul>
                            <li><a href="https://data.un.org/">UN Data</a> - United Nations is a recognized source of
                                reliable population data and provided highest level country population.</li>
                        </ul>
                        <u>State Level Population</u><br>
                        <ul>
                            <li><a href="http://www.citypopulation.de/">City Population</a></li>
                            <li><a href="https://worldpopulationreview.com/">World Population Review</a></li>
                            <li>Various census reports published by governments.</li>
                        </ul>
                        <u>County Level Population</u><br>
                        Many western countries have organized, open census websites and population for county (administrative level 2) locations
                        are readily available. There are a number of governments that either deliberately do not easily publish their population data
                        or are not organized to distribute it easily. These countries required significant research to get population data.
                        <ul>
                            <li><a href="http://www.citypopulation.de/">City Population</a></li>
                            <li><a href="https://geonames.org">GeoNames</a></li>
                            <li>Various census reports published by governments</li>
                            <li>Various population reports published by humanitarian agencies</li>
                            <li>Wikipedia</li>
                            <li>State websites</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Faith Status Data</h3>
                        <p>
                            <u>Categories</u><br>
                        </p>
                        <ul>
                            <li>Non-Christian<br><i>Someone who is far from a true knowledge Jesus Christ and far from a commitment to Jesus Christ.</i></li>
                            <li>Cultural Christian <br><i>(aka. Christian Adherent) Someone who has access to the knowledge of Jesus Christ through a cultural presence of the Church, but may or may not have been encouraged to seek Jesus in a personal, intimate way.</i></li>
                            <li>Believer<br><i>(aka. Disciple, Christian) Someone who has accepted in faith the knowledge of Jesus Christ and who has surrendered their life to Jesus as a personal savior.</i></li>
                        </ul>
                        <p>
                            <u><strong>Source and Method of Calculation</strong></u><br>
                        </p>
                        <ul>
                            <li>
                                <u>Source</u><br>
                                <a href="https://joshuaproject.net/">Joshua Project</a> - Values for "Christian Adherent" and "Evangelical"
                                (which determine unreached status) are often informed estimates, some more accurate than others at the country level.
                            </li>
                            <li>
                                <u>Extrapolation</u><br>
                                Prayer.Global has used the population data at the county and state levels from the Location Grid project and
                                divided percentages by the location population to arrive at the faith status population estimates.
                            </li>
                            <li>
                                <u>Acknowledged Weakness</u><br>
                                <p>
                                    At best, this methodology can offer a general, estimated target for the faith status of various locations. Actual faith status for each location
                                    could vary enormously above or below our estimates.
                                </p>
                                <p>
                                    All calculations of faith status data anywhere suffer the same limitations ... no matter the source ... they are all generalizations.
                                </p>
                                <p>
                                    Our belief is that the estimated numbers for non-christian, cultural christian, and disciple help the person praying to better understand the challenge facing the church
                                    in that location. It helps answer questions like: Is this a weak and small church surrounded by a dominant religion? Is this church holding out against an atheist culture?
                                    Is this a church trying to be faithful while surrounded by a lukewarm historic cultural church?
                                </p>
                                <p>
                                    More than the exact number, it is the distribution of knowledge of Jesus that is informative to the praying person.
                                </p>
                            </li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">People Group Data</h3>
                        <ul>
                            <li><a href="https://joshuaproject.net/">Joshua Project</a> - </li>
                            <li><a href="https://grd.imb.org/">International Mission Board - Global Research</a></li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Language and Religion Data</h3>
                        <ul>
                            <li><a href="https://joshuaproject.net/resources/datasets">Joshua Project</a> - Two columns from Joshua Project resources were used to identify Primary Language and Primary Religion. These were identified at the country level, and future versions of Prayer Global intend to push deeper and more accurately into the spoken languages per location.</li>
                        </ul>
                    </div>


                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Administrative Divisions and Polygon Resources</h3>
                        <p>
                            <u>Location Grid Project</u><br>
                            The Location Grid Project is an open source project born out of the <a href="https://disciple.tools">Disciple Tools</a> open source project. It was built to support a standardized geolocation grid for disciple making movements activity.  (<a href="https://locationgrid.app">Location Grid Project Website</a>) (<a href="https://github.com/DiscipleTools/location-grid-project">Github Project</a>)
                        </p>
                        <p>
                            <u>Location Grid - Data Sources</u><br>
                            The Location Grid database and polygon set are derived these data sources, although significant processing of original polygon files were implemented for lightweight web distribution:
                        </p>
                        <ul>
                            <li><a href="https://gadm.org/">GADM</a> - GADM is an academic project that provides administrative polygon boundaries. These boundaries provided the seed for the grid system.</li>
                            <li><a href="https://geonames.org">GeoNames</a> - GeoNames is an open source location database of over 11 million places. It has a redistributable polygon set and associated location name database that also served as a seed for the location grid system.</li>
                            <li><a href="https://www.openstreetmap.org/">Open Street maps</a> - Open Street Maps is the best open alternative to closed mapping systems like Google Maps or Apple Maps. Intentional coordination and polygon preparation was designed for use with this tiling system.</li>
                            <li><a href="https://www.mapbox.com/">Mapbox</a> - Mapbox is a service that uses Open Street Maps system but adds a developer friendly programming and hosting layer to mapping source.</li>
                            <li><a href="https://locationgrid.app/">Location Grid - Database and Public Mirror</a> - An enormous amount of custom processing, configuration, and web hosting is provided as a result of the Location Grid Project.</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">City Data</h3>
                        <ul>
                            <li><a href="https://geonames.org">GeoNames</a> - Geonames provided the original 200k city names, population, longitude, and latitude.</li>
                            <li><a href="https://locationgrid.app">Location Grid - Geocoding</a> - Populated cities from geonames were coded against the Location Grid using the longitude, latitude. Then they were crafted into a new resource table.</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Bible Citations</h3>
                        <ul>
                            <li>English - The <a href="https://www.zondervan.com/about-us/permissions/">NIV</a> is the primary translation used in the prayers. ESV used occasionally.</li>
                        </ul>
                    </div>

                    <hr>
                    <div class="row justify-content-md-center text-center mb-5">
                        <div class="col-lg-7">
                            <h2 class="mt-0 pt-5 font-weight-normal">Statistical Resources</h2>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <div style="padding-left:1em;">
                            <u>National Statistical Offices</u>
                            <table class="overview">
                                <tbody><tr data-value="('ref1')"><td class="short">ABW</td><td><a id="ref1" target="_blank" href="http://cbs.aw">Central Bureau of Statistics Aruba</a></td></tr>
                                <tr data-value="('ref2')"><td class="short">AFG</td><td><a id="ref2" target="_blank" href="http://www.cso.gov.af/">Central Statistics Office, Afghanistan</a></td></tr>
                                <tr data-value="('ref3')"><td class="short">AGO</td><td><a id="ref3" target="_blank" href="https://www.ine.gov.ao/">Instituto Nacional de Estatística, República de Angola</a></td></tr>
                                <tr data-value="('ref4')"><td class="short">AIA</td><td><a id="ref4" target="_blank" href="http://statistics.gov.ai/">Anguilla's Statistics Department</a></td></tr>
                                <tr data-value="('ref5')"><td class="short">ALB</td><td><a id="ref5" target="_blank" href="http://www.instat.gov.al/">Instituti i Statistikës, Tiranë</a></td></tr>
                                <tr data-value="('ref6')"><td class="short">AND</td><td><a id="ref6" target="_blank" href="http://www.estadistica.ad/">Departament d'Estatdística, Andorra</a></td></tr>
                                <tr data-value="('ref7')"><td class="short">ARE</td><td><a id="ref7" target="_blank" href="http://www.ded.rak.ae/">Ras Al Khaimah DED Knowledge Center</a></td></tr>
                                <tr data-value="('ref8')"><td class="short">ARE</td><td><a id="ref8" target="_blank" href="http://www.economy.ae/">Ministry of Economy, United Arab Emirates</a></td></tr>
                                <tr data-value="('ref9')"><td class="short">ARE</td><td><a id="ref9" target="_blank" href="http://www.scad.ae/">Statistics Center Abu Dhabi</a></td></tr>
                                <tr data-value="('ref10')"><td class="short">ARE</td><td><a id="ref10" target="_blank" href="https://www.fscfuj.gov.ae/">Fujairah Statistics Center</a></td></tr>
                                <tr data-value="('ref11')"><td class="short">ARE</td><td><a id="ref11" target="_blank" href="http://dsc.gov.ae/">Dubai Statistics Center</a></td></tr>
                                <tr data-value="('ref12')"><td class="short">ARE</td><td><a id="ref12" target="_blank" href="http://www.fcsa.gov.ae/">Federal Competiveness and Statistics Authority</a></td></tr>
                                <tr data-value="('ref13')"><td class="short">ARE</td><td><a id="ref13" target="_blank" href="https://scc.ajman.ae/">Ajman Statistics and Competitiveness Center</a></td></tr>
                                <tr data-value="('ref14')"><td class="short">ARE</td><td><a id="ref14" target="_blank" href="http://www.dscd.ae">Department of Statistics and Community Development Sharjah</a></td></tr>
                                <tr data-value="('ref15')"><td class="short">ARG</td><td><a id="ref15" target="_blank" href="https://www.indec.gob.ar/">Instituto Nacional de Estadística y Censos de la Republica Argentina</a></td></tr>
                                <tr data-value="('ref16')"><td class="short">ARM</td><td><a id="ref16" target="_blank" href="http://www.armstat.am/">National Statistical Service of the Republic of Armenia</a></td></tr>
                                <tr data-value="('ref17')"><td class="short">ASM</td><td><a id="ref17" target="_blank" href="http://doc.as.gov/research-and-statistics/">American Samoa Government: Research &amp; Statistics</a></td></tr>
                                <tr data-value="('ref18')"><td class="short">ATG</td><td><a id="ref18" target="_blank" href="https://statistics.gov.ag/">Statistics Division of Antigua and Barbuda</a></td></tr>
                                <tr data-value="('ref19')"><td class="short">AUS</td><td><a id="ref19" target="_blank" href="http://www.abs.gov.au">Australian Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref20')"><td class="short">AUT</td><td><a id="ref20" target="_blank" href="http://www.statistik.at">Statistik Austria</a></td></tr>
                                <tr data-value="('ref21')"><td class="short">AZE</td><td><a id="ref21" target="_blank" href="http://www.stat.gov.az/">State Statistical Committee of the Republic of Azerbaijan</a></td></tr>
                                <tr data-value="('ref22')"><td class="short">BDI</td><td><a id="ref22" target="_blank" href="http://www.isteebu.bi/">Institut de Statistiques et d'Etudes Economiques du Burundi</a></td></tr>
                                <tr data-value="('ref23')"><td class="short">BEL</td><td><a id="ref23" target="_blank" href="http://www.statbel.fgov.be/">Statistics Belgium, NIS-INS, Ministry of Economic Affairs</a></td></tr>
                                <tr data-value="('ref24')"><td class="short">BEN</td><td><a id="ref24" target="_blank" href="http://www.insae-bj.org/">Institut National de la Statistique Benin</a></td></tr>
                                <tr data-value="('ref25')"><td class="short">BFA</td><td><a id="ref25" target="_blank" href="http://www.insd.bf/">Institut National de la Statistique et de la Demographie, Burkina Faso</a></td></tr>
                                <tr data-value="('ref26')"><td class="short">BGD</td><td><a id="ref26" target="_blank" href="http://www.bbs.gov.bd/">Bangladesh Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref27')"><td class="short">BGR</td><td><a id="ref27" target="_blank" href="http://www.nsi.bg/">National Statistical Institute Bulgaria</a></td></tr>
                                <tr data-value="('ref28')"><td class="short">BHR</td><td><a id="ref28" target="_blank" href="https://www.data.gov.bh/">Bahrain Information and e-Government Authority</a></td></tr>
                                <tr data-value="('ref29')"><td class="short">BHS</td><td><a id="ref29" target="_blank" href="http://www.bahamas.gov.bs/statistics/">Department of Statistics, Bahamas</a></td></tr>
                                <tr data-value="('ref30')"><td class="short">BIH</td><td><a id="ref30" target="_blank" href="http://www.fzs.ba/">Federal Office of Statistics of the Federation of Bosnia &amp; Herzegovina</a></td></tr>
                                <tr data-value="('ref31')"><td class="short">BIH</td><td><a id="ref31" target="_blank" href="http://www.rzs.rs.ba">Republika Srpska Institute of Statistics</a></td></tr>
                                <tr data-value="('ref32')"><td class="short">BIH</td><td><a id="ref32" target="_blank" href="http://www.bhas.ba">Bosnia and Herzegovina Agency for Statistics</a></td></tr>
                                <tr data-value="('ref33')"><td class="short">BLR</td><td><a id="ref33" target="_blank" href="http://www.belstat.gov.by/en/">National Statistical Committee of the Republic of Belarus</a></td></tr>
                                <tr data-value="('ref34')"><td class="short">BLZ</td><td><a id="ref34" target="_blank" href="http://www.sib.org.bz/">Statistical Institute of Belize</a></td></tr>
                                <tr data-value="('ref35')"><td class="short">BMU</td><td><a id="ref35" target="_blank" href="http://www.gov.bm/portal/server.pt?space=CommunityPage&amp;control=SetCommunity&amp;CommunityID=227">Department of Statistics, Government of Bermuda</a></td></tr>
                                <tr data-value="('ref36')"><td class="short">BOL</td><td><a id="ref36" target="_blank" href="http://www.ine.gob.bo/">Instituto Nacional de Estadística, República de Bolivia</a></td></tr>
                                <tr data-value="('ref37')"><td class="short">BRA</td><td><a id="ref37" target="_blank" href="http://www.ibge.gov.br/">Instituto Brasileiro de Geografia e Estatistica</a></td></tr>
                                <tr data-value="('ref38')"><td class="short">BRB</td><td><a id="ref38" target="_blank" href="https://stats.gov.bb/">Barbados Statistical Service</a></td></tr>
                                <tr data-value="('ref39')"><td class="short">BRN</td><td><a id="ref39" target="_blank" href="https://deps.mofe.gov.bn/">Department of Economic Planning and Development, Brunei</a></td></tr>
                                <tr data-value="('ref40')"><td class="short">BTN</td><td><a id="ref40" target="_blank" href="http://www.nsb.gov.bt">National Statistics Bureau, Royal Government of Bhutan</a></td></tr>
                                <tr data-value="('ref41')"><td class="short">BWA</td><td><a id="ref41" target="_blank" href="http://www.cso.gov.bw/">Central Statistic Office, Republic of Botswana</a></td></tr>
                                <tr data-value="('ref42')"><td class="short">CAF</td><td><a id="ref42" target="_blank" href="https://icasees.org/">Institut Centralafrican des Statistiques et des Etudes Economiques et Sociales</a></td></tr>
                                <tr data-value="('ref43')"><td class="short">CAN</td><td><a id="ref43" target="_blank" href="http://www.statcan.ca/">Statistics Canada</a></td></tr>
                                <tr data-value="('ref44')"><td class="short">CHE</td><td><a id="ref44" target="_blank" href="https://www.bfs.admin.ch/bfs/de/home.html">Bundesamt für Statistik, Schweiz</a></td></tr>
                                <tr data-value="('ref45')"><td class="short">CHL</td><td><a id="ref45" target="_blank" href="http://www.ine.cl/">Instituto Nacional de Estadísticas, Santiago de Chile</a></td></tr>
                                <tr data-value="('ref46')"><td class="short">CHN</td><td><a id="ref46" target="_blank" href="http://www.stats.gov.cn">China National Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref47')"><td class="short">CIS</td><td><a id="ref47" target="_blank" href="http://www.cisstat.com">Interstate Statistical Committee of the Commonwealth of Independent States (CISStat)</a></td></tr>
                                <tr data-value="('ref48')"><td class="short">CMR</td><td><a id="ref48" target="_blank" href="http://www.bucrep.cm/">Bureau Central des Recensements et des Etudes de Population, Cameroun</a></td></tr>
                                <tr data-value="('ref49')"><td class="short">COD</td><td><a id="ref49" target="_blank" href="http://ins-rdc.org/">Institut National de la Statistique, République Démocratique du Congo</a></td></tr>
                                <tr data-value="('ref50')"><td class="short">COG</td><td><a id="ref50" target="_blank" href="https://ins-congo.cg/">Institute National de la Statistique, Republique du Congo</a></td></tr>
                                <tr data-value="('ref51')"><td class="short">COK</td><td><a id="ref51" target="_blank" href="http://www.mfem.gov.ck/statistics">Cook Islands Statistics Office</a></td></tr>
                                <tr data-value="('ref52')"><td class="short">COL</td><td><a id="ref52" target="_blank" href="http://www.dane.gov.co/">Departamento Administrativo Nacional de Estadistica, Republica de Columbia</a></td></tr>
                                <tr data-value="('ref53')"><td class="short">COM</td><td><a id="ref53" target="_blank" href="http://www.inseed.km/">Institut Nationale de la Statistique et des Etudes Economiques et Démographiques, Union des Comores</a></td></tr>
                                <tr data-value="('ref54')"><td class="short">CPV</td><td><a id="ref54" target="_blank" href="http://www.ine.cv/">Instituto Nacional de Estatística, Cabo Verde</a></td></tr>
                                <tr data-value="('ref55')"><td class="short">CRI</td><td><a id="ref55" target="_blank" href="http://www.inec.go.cr/">Instituto Nacional de Estadística y Censos, Costa Rica</a></td></tr>
                                <tr data-value="('ref56')"><td class="short">CUB</td><td><a id="ref56" target="_blank" href="http://www.onei.gob.cu/">Oficina Nacional de Estadisticas, República de Cuba</a></td></tr>
                                <tr data-value="('ref57')"><td class="short">CUW</td><td><a id="ref57" target="_blank" href="http://www.cbs.cw/">Central Bureau of Statistics, Curaçao</a></td></tr>
                                <tr data-value="('ref58')"><td class="short">CVI</td><td><a id="ref58" target="_blank" href="http://www.ins.ci/">Institut National de la Statistique, Republique de Côte d'Ivoire</a></td></tr>
                                <tr data-value="('ref59')"><td class="short">CYM</td><td><a id="ref59" target="_blank" href="http://www.eso.ky/">Government of the Cayman Islands, Economics and Statistics Office</a></td></tr>
                                <tr data-value="('ref60')"><td class="short">CYP</td><td><a id="ref60" target="_blank" href="http://www.mof.gov.cy/mof/cystat/statistics.nsf/index_en/index_en?OpenDocument">Statistical Service, Republic of Cyprus</a></td></tr>
                                <tr data-value="('ref61')"><td class="short">CZE</td><td><a id="ref61" target="_blank" href="http://www.czso.cz/">Czech Statistical Office</a></td></tr>
                                <tr data-value="('ref62')"><td class="short">DEU</td><td><a id="ref62" target="_blank" href="http://www.destatis.de/">Statistisches Bundesamt Deutschland</a></td></tr>
                                <tr data-value="('ref63')"><td class="short">DEU</td><td><a id="ref63" target="_blank" href="https://www.destatis.de/DE/Service/StatistischesAdressbuch/_inhalt.html">Statistical Offices of the German States</a></td></tr>
                                <tr data-value="('ref64')"><td class="short">DJI</td><td><a id="ref64" target="_blank" href="http://www.instad.dj/">Institut National de la Statistique de Djibouti</a></td></tr>
                                <tr data-value="('ref65')"><td class="short">DJI</td><td><a id="ref65" target="_blank" href="http://www.ministere-finances.dj">Ministère des Finances Djibouti</a></td></tr>
                                <tr data-value="('ref66')"><td class="short">DMA</td><td><a id="ref66" target="_blank" href="http://www.dominica.gov.dm">Government of the Commonwealth of Dominica</a></td></tr>
                                <tr data-value="('ref67')"><td class="short">DNK</td><td><a id="ref67" target="_blank" href="http://www.statbank.dk/statbank5a/default.asp?w=1600">Denmark Statistik (StatBank)</a></td></tr>
                                <tr data-value="('ref68')"><td class="short">DOM</td><td><a id="ref68" target="_blank" href="https://www.one.gob.do/">La Oficina Nacional de Estadística, República Dominicana</a></td></tr>
                                <tr data-value="('ref69')"><td class="short">DZA</td><td><a id="ref69" target="_blank" href="http://www.ons.dz/">Office National des Statistiques de l'Algérie</a></td></tr>
                                <tr data-value="('ref70')"><td class="short">ECU</td><td><a id="ref70" target="_blank" href="http://www.ecuadorencifras.gob.ec/">El Instituto Nacional de Estadística y Censos del Ecuador</a></td></tr>
                                <tr data-value="('ref71')"><td class="short">EGY</td><td><a id="ref71" target="_blank" href="http://www.capmas.gov.eg">Central Agency for Public Mobilization and Statistics, Egypt</a></td></tr>
                                <tr data-value="('ref72')"><td class="short">ESP</td><td><a id="ref72" target="_blank" href="http://www.ine.es/">Instituto Nacional de Estadística, España</a></td></tr>
                                <tr data-value="('ref73')"><td class="short">EST</td><td><a id="ref73" target="_blank" href="http://www.stat.ee/">Statistical Office of Estonia</a></td></tr>
                                <tr data-value="('ref74')"><td class="short">ETH</td><td><a id="ref74" target="_blank" href="https://www.statsethiopia.gov.et/">Central Statistics Agency of Ethiopia</a></td></tr>
                                <tr data-value="('ref75')"><td class="short">FIN</td><td><a id="ref75" target="_blank" href="http://www.stat.fi/index_en.html">Statistics Finland</a></td></tr>
                                <tr data-value="('ref76')"><td class="short">FJI</td><td><a id="ref76" target="_blank" href="http://www.statsfiji.gov.fj/">Fiji Islands Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref77')"><td class="short">FLK</td><td><a id="ref77" target="_blank" href="http://www.falklands.gov.fk/">Falkland Islands Government</a></td></tr>
                                <tr data-value="('ref78')"><td class="short">FRA</td><td><a id="ref78" target="_blank" href="http://www.insee.fr/">Institut National de la Statistique et des Études Économiques, Paris, France</a></td></tr>
                                <tr data-value="('ref79')"><td class="short">FRO</td><td><a id="ref79" target="_blank" href="http://www.hagstova.fo/">Hagstova Føroya, Statistics Faroe Islands</a></td></tr>
                                <tr data-value="('ref80')"><td class="short">FSM</td><td><a id="ref80" target="_blank" href="https://www.fsmstatistics.fm/">FSM Statistics Office</a></td></tr>
                                <tr data-value="('ref81')"><td class="short">GAB</td><td><a id="ref81" target="_blank" href="https://www.statgabon.ga/">Direction Générale des Statistiques du Gabon</a></td></tr>
                                <tr data-value="('ref82')"><td class="short">GBR</td><td><a id="ref82" target="_blank" href="http://www.nisra.gov.uk/">The Northern Ireland Statistics and Research Agency</a></td></tr>
                                <tr data-value="('ref83')"><td class="short">GBR</td><td><a id="ref83" target="_blank" href="http://www.nrscotland.gov.uk/">National Records of Scotland</a></td></tr>
                                <tr data-value="('ref84')"><td class="short">GBR</td><td><a id="ref84" target="_blank" href="https://www.ons.gov.uk/">UK Office for National Statistics</a></td></tr>
                                <tr data-value="('ref85')"><td class="short">GEO</td><td><a id="ref85" target="_blank" href="http://www.geostat.ge/">State Department for Statistics of Georgia</a></td></tr>
                                <tr data-value="('ref86')"><td class="short">GGY</td><td><a id="ref86" target="_blank" href="http://www.alderney.gov.gg/">States of Alderney (government site)</a></td></tr>
                                <tr data-value="('ref87')"><td class="short">GGY</td><td><a id="ref87" target="_blank" href="http://www.gov.gg/">States of Guernsey (government site)</a></td></tr>
                                <tr data-value="('ref88')"><td class="short">GHA</td><td><a id="ref88" target="_blank" href="http://www.statsghana.gov.gh">Ghana Statistical Service</a></td></tr>
                                <tr data-value="('ref89')"><td class="short">GIB</td><td><a id="ref89" target="_blank" href="http://www.gibraltar.gov.gi/statistics">Statistics Office Gibraltar</a></td></tr>
                                <tr data-value="('ref90')"><td class="short">GIN</td><td><a id="ref90" target="_blank" href="http://www.stat-guinee.org/">Direction Nationale de la Statistique de Guinée</a></td></tr>
                                <tr data-value="('ref91')"><td class="short">GMB</td><td><a id="ref91" target="_blank" href="https://www.gbosdata.org/">Gambia Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref92')"><td class="short">GNB</td><td><a id="ref92" target="_blank" href="http://www.stat-guinebissau.com/">Instituto Nacional de Estatística Guiné-Bissau</a></td></tr>
                                <tr data-value="('ref93')"><td class="short">GNQ</td><td><a id="ref93" target="_blank" href="http://www.inege.gq/">Instituto Nacional de Estadisticas de Guinea Ecuatorial</a></td></tr>
                                <tr data-value="('ref94')"><td class="short">GRC</td><td><a id="ref94" target="_blank" href="http://www.statistics.gr">General Secretariat of National Statistical Service of Greece</a></td></tr>
                                <tr data-value="('ref95')"><td class="short">GRD</td><td><a id="ref95" target="_blank" href="https://stats.gov.gd/">Grenada Central Statistics Office</a></td></tr>
                                <tr data-value="('ref96')"><td class="short">GRL</td><td><a id="ref96" target="_blank" href="http://www.stat.gl">Kalaallit Nunaanni Naatsorsueqqissaartarfik</a></td></tr>
                                <tr data-value="('ref97')"><td class="short">GTM</td><td><a id="ref97" target="_blank" href="http://www.ine.gob.gt/">Instituto Nacional de Estadistica Guatemala</a></td></tr>
                                <tr data-value="('ref98')"><td class="short">GUM</td><td><a id="ref98" target="_blank" href="http://www.spc.int/prism/country/gu/stats">Guam Bureau of Statistics and Plans</a></td></tr>
                                <tr data-value="('ref99')"><td class="short">GUY</td><td><a id="ref99" target="_blank" href="http://www.statisticsguyana.gov.gy/">Bureau of Statistics Guyana</a></td></tr>
                                <tr data-value="('ref100')"><td class="short">HKG</td><td><a id="ref100" target="_blank" href="http://www.censtatd.gov.hk/home.html">Census and Statistics Department, SAR Hong Kong</a></td></tr>
                                <tr data-value="('ref101')"><td class="short">HND</td><td><a id="ref101" target="_blank" href="http://www.ine.gob.hn/">Instituto Nacional de Estadística Honduras</a></td></tr>
                                <tr data-value="('ref102')"><td class="short">HRV</td><td><a id="ref102" target="_blank" href="http://www.dzs.hr/default_e.htm">Croatian Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref103')"><td class="short">HTI</td><td><a id="ref103" target="_blank" href="https://ihsi.ayiti.digital/">Institut Haïtien de Statistique et d'Informatique</a></td></tr>
                                <tr data-value="('ref104')"><td class="short">HUN</td><td><a id="ref104" target="_blank" href="http://portal.ksh.hu/">Hungarian Central Statistical Office</a></td></tr>
                                <tr data-value="('ref105')"><td class="short">IDN</td><td><a id="ref105" target="_blank" href="http://www.bps.go.id/">Badan Pusat Statistik, Republik Indonesia</a></td></tr>
                                <tr data-value="('ref106')"><td class="short">IMN</td><td><a id="ref106" target="_blank" href="https://www.gov.im/Census">Isle of Man Government</a></td></tr>
                                <tr data-value="('ref107')"><td class="short">IND</td><td><a id="ref107" target="_blank" href="http://www.censusindia.gov.in/">Office of the Registrar General and Census Commissioner, India</a></td></tr>
                                <tr data-value="('ref108')"><td class="short">IRL</td><td><a id="ref108" target="_blank" href="http://www.cso.ie/">Central Statistics Office, Ireland</a></td></tr>
                                <tr data-value="('ref109')"><td class="short">IRN</td><td><a id="ref109" target="_blank" href="http://www.amar.org.ir/">Statistical Centre of Iran</a></td></tr>
                                <tr data-value="('ref110')"><td class="short">IRQ</td><td><a id="ref110" target="_blank" href="http://cosit.gov.iq">Central Organization for Statistics and Information Technology Iraq</a></td></tr>
                                <tr data-value="('ref111')"><td class="short">ISL</td><td><a id="ref111" target="_blank" href="http://www.statice.is/">Statistics Iceland</a></td></tr>
                                <tr data-value="('ref112')"><td class="short">ISR</td><td><a id="ref112" target="_blank" href="http://www.cbs.gov.il/">Central Bureau of Statistics, The State of Israel</a></td></tr>
                                <tr data-value="('ref113')"><td class="short">ITA</td><td><a id="ref113" target="_blank" href="http://www.istat.it/">Istituto Nazionale di Statistica, Italia</a></td></tr>
                                <tr data-value="('ref114')"><td class="short">ITA</td><td><a id="ref114" target="_blank" href="http://demo.istat.it/">Geodemo Statistiche Demografiche dell'ISTAT</a></td></tr>
                                <tr data-value="('ref115')"><td class="short">JAM</td><td><a id="ref115" target="_blank" href="http://statinja.gov.jm/">Statistical Institute of Jamaica</a></td></tr>
                                <tr data-value="('ref116')"><td class="short">JAP</td><td><a id="ref116" target="_blank" href="http://www.stat.go.jp/english/index.htm">Statistics Bureau Japan</a></td></tr>
                                <tr data-value="('ref117')"><td class="short">JEY</td><td><a id="ref117" target="_blank" href="http://www.gov.je/GOVERNMENT/Pages/default.aspx">States of Jersey: Government and Administration</a></td></tr>
                                <tr data-value="('ref118')"><td class="short">JOR</td><td><a id="ref118" target="_blank" href="http://www.dos.gov.jo/">Department of Statistics, Jordan</a></td></tr>
                                <tr data-value="('ref119')"><td class="short">KAZ</td><td><a id="ref119" target="_blank" href="http://www.stat.gov.kz/">The Agency of Statistics of the Republic of Kazakhstan</a></td></tr>
                                <tr data-value="('ref120')"><td class="short">KEN</td><td><a id="ref120" target="_blank" href="http://www.knbs.or.ke/">Kenya National Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref121')"><td class="short">KGZ</td><td><a id="ref121" target="_blank" href="http://www.stat.kg">National Statistical Committee of Kyrgyz Republic</a></td></tr>
                                <tr data-value="('ref122')"><td class="short">KHM</td><td><a id="ref122" target="_blank" href="http://www.nis.gov.kh/">National Institute of Statistics of Cambodia</a></td></tr>
                                <tr data-value="('ref123')"><td class="short">KIR</td><td><a id="ref123" target="_blank" href="http://www.mfed.gov.ki/statistics/">Kiribati National Statistics Office</a></td></tr>
                                <tr data-value="('ref124')"><td class="short">KOR</td><td><a id="ref124" target="_blank" href="http://kostat.go.kr/">Statistics Korea</a></td></tr>
                                <tr data-value="('ref125')"><td class="short">KOS</td><td><a id="ref125" target="_blank" href="http://ask.rks-gov.net/">Kosovo Agency of Statistics</a></td></tr>
                                <tr data-value="('ref126')"><td class="short">KWT</td><td><a id="ref126" target="_blank" href="http://www.csb.gov.kw/">Central Statistical Office, State of Kuwait</a></td></tr>
                                <tr data-value="('ref127')"><td class="short">KWT</td><td><a id="ref127" target="_blank" href="http://www.paci.gov.kw">Kuwait Public Authority for Civil Information</a></td></tr>
                                <tr data-value="('ref128')"><td class="short">LAO</td><td><a id="ref128" target="_blank" href="http://www.lsb.gov.la/">Lao Statistics Bureau</a></td></tr>
                                <tr data-value="('ref129')"><td class="short">LAT</td><td><a id="ref129" target="_blank" href="http://www.csb.gov.lv">Central Statistical Bureau of Latvia</a></td></tr>
                                <tr data-value="('ref130')"><td class="short">LBN</td><td><a id="ref130" target="_blank" href="http://www.cas.gov.lb/">Central Administration for Statistics, Lebanese Republic</a></td></tr>
                                <tr data-value="('ref131')"><td class="short">LBR</td><td><a id="ref131" target="_blank" href="http://www.lisgis.net/">Liberia Institute of Statistics &amp; Geo-Information Services</a></td></tr>
                                <tr data-value="('ref132')"><td class="short">LBY</td><td><a id="ref132" target="_blank" href="http://www.bsc.ly/">Bureau of Statistics and Census Libya</a></td></tr>
                                <tr data-value="('ref133')"><td class="short">LCA</td><td><a id="ref133" target="_blank" href="http://www.stats.gov.lc/">St. Lucia Government Statistics Department</a></td></tr>
                                <tr data-value="('ref134')"><td class="short">LIE</td><td><a id="ref134" target="_blank" href="http://www.as.llv.li/">Amt für Statistik, Landesverwaltung Liechtenstein</a></td></tr>
                                <tr data-value="('ref135')"><td class="short">LKA</td><td><a id="ref135" target="_blank" href="http://www.statistics.gov.lk/">Departement of Census and Statistics Sri Lanka</a></td></tr>
                                <tr data-value="('ref136')"><td class="short">LSO</td><td><a id="ref136" target="_blank" href="http://www.bos.gov.ls/">Lesotho Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref137')"><td class="short">LTU</td><td><a id="ref137" target="_blank" href="http://www.stat.gov.lt">Department of Statistics, Republic of Lithuania</a></td></tr>
                                <tr data-value="('ref138')"><td class="short">LUX</td><td><a id="ref138" target="_blank" href="http://www.statistiques.public.lu/">Le Portail des Statistiques du Luxembourg</a></td></tr>
                                <tr data-value="('ref139')"><td class="short">MAC</td><td><a id="ref139" target="_blank" href="http://www.dsec.gov.mo/">Direcção dos Serviços de Estatística e Censos, Macau</a></td></tr>
                                <tr data-value="('ref140')"><td class="short">MAR</td><td><a id="ref140" target="_blank" href="http://www.hcp.ma/">Haut Commissariat au Plan, Royaume du Maroc</a></td></tr>
                                <tr data-value="('ref141')"><td class="short">MCO</td><td><a id="ref141" target="_blank" href="http://www.imsee.mc/">Monaco Statistics</a></td></tr>
                                <tr data-value="('ref142')"><td class="short">MDA</td><td><a id="ref142" target="_blank" href="http://www.statistica.md/?lang=en">Departamentul Statistica si Sociologie al Republicii Moldova</a></td></tr>
                                <tr data-value="('ref143')"><td class="short">MDG</td><td><a id="ref143" target="_blank" href="http://www.instat.mg/">Institut National de la Statistique Madagascar</a></td></tr>
                                <tr data-value="('ref144')"><td class="short">MDV</td><td><a id="ref144" target="_blank" href="http://statisticsmaldives.gov.mv/">National Bureau of Statistics, Republic of Maldives</a></td></tr>
                                <tr data-value="('ref145')"><td class="short">MEX</td><td><a id="ref145" target="_blank" href="http://www.inegi.org.mx/">Instituto Nacional de Estadística y Geografía, México</a></td></tr>
                                <tr data-value="('ref146')"><td class="short">MHL</td><td><a id="ref146" target="_blank" href="http://rmi.prism.spc.int/">Economic Policy, Planning and Statistics Office, Republic of the Marshall Islands</a></td></tr>
                                <tr data-value="('ref147')"><td class="short">MKD</td><td><a id="ref147" target="_blank" href="http://www.stat.gov.mk/">State Statistical Office, Republic of North Macedonia</a></td></tr>
                                <tr data-value="('ref148')"><td class="short">MLI</td><td><a id="ref148" target="_blank" href="http://www.instat-mali.org/">Institut National de la Statistiques, République du Mali</a></td></tr>
                                <tr data-value="('ref149')"><td class="short">MLT</td><td><a id="ref149" target="_blank" href="http://www.nso.gov.mt/">National Statistics Office Malta</a></td></tr>
                                <tr data-value="('ref150')"><td class="short">MMR</td><td><a id="ref150" target="_blank" href="http://www.mmsis.gov.mm/">Myanmar Statistical Information Service</a></td></tr>
                                <tr data-value="('ref151')"><td class="short">MMR</td><td><a id="ref151" target="_blank" href="https://www.csostat.gov.mm/">Myanmar Central Statistical Organization</a></td></tr>
                                <tr data-value="('ref152')"><td class="short">MNE</td><td><a id="ref152" target="_blank" href="http://www.monstat.org/">Statistical Office of Montenegro</a></td></tr>
                                <tr data-value="('ref153')"><td class="short">MNG</td><td><a id="ref153" target="_blank" href="http://www.nso.mn/">Mongolian National Statistical Office</a></td></tr>
                                <tr data-value="('ref154')"><td class="short">MNP</td><td><a id="ref154" target="_blank" href="http://www.commerce.gov.mp/">CNMI Department of Commerce</a></td></tr>
                                <tr data-value="('ref155')"><td class="short">MOZ</td><td><a id="ref155" target="_blank" href="http://www.ine.gov.mz/">Instituto Nacional de Estatistica Moçambique</a></td></tr>
                                <tr data-value="('ref156')"><td class="short">MRT</td><td><a id="ref156" target="_blank" href="http://www.ons.mr/">Office National de la Statistique, Mauretanie</a></td></tr>
                                <tr data-value="('ref157')"><td class="short">MUS</td><td><a id="ref157" target="_blank" href="http://statsmauritius.govmu.org/">Central Statistical Office of Mauritius</a></td></tr>
                                <tr data-value="('ref158')"><td class="short">MWI</td><td><a id="ref158" target="_blank" href="http://www.nsomalawi.mw/">National Statistical Office of Malawi</a></td></tr>
                                <tr data-value="('ref159')"><td class="short">MYS</td><td><a id="ref159" target="_blank" href="https://dosm.gov.my/">Department of Statistics Malaysia</a></td></tr>
                                <tr data-value="('ref160')"><td class="short">NAM</td><td><a id="ref160" target="_blank" href="http://www.nsa.org.na/">Namibia Statistics Agency</a></td></tr>
                                <tr data-value="('ref161')"><td class="short">NCL</td><td><a id="ref161" target="_blank" href="http://www.isee.nc/">Institut de la Statistique et des Études Économiques Nouvelle-Calédonie</a></td></tr>
                                <tr data-value="('ref162')"><td class="short">NER</td><td><a id="ref162" target="_blank" href="http://www.stat-niger.org/">Institut National de la Statistique du Niger</a></td></tr>
                                <tr data-value="('ref163')"><td class="short">NFK</td><td><a id="ref163" target="_blank" href="http://www.info.gov.nf/">Norfolk Island Government</a></td></tr>
                                <tr data-value="('ref164')"><td class="short">NGA</td><td><a id="ref164" target="_blank" href="http://www.nigerianstat.gov.ng/">National Bureau of Statistics Nigeria</a></td></tr>
                                <tr data-value="('ref165')"><td class="short">NIC</td><td><a id="ref165" target="_blank" href="http://www.inide.gob.ni/">Instituto Nacional de Información de Desarrollo, Nicaragua</a></td></tr>
                                <tr data-value="('ref166')"><td class="short">NIU</td><td><a id="ref166" target="_blank" href="http://www.spc.int/prism/niue/">Statistics Niue</a></td></tr>
                                <tr data-value="('ref167')"><td class="short">NLD</td><td><a id="ref167" target="_blank" href="http://www.cbs.nl/">Statistics Netherlands</a></td></tr>
                                <tr data-value="('ref168')"><td class="short">NOR</td><td><a id="ref168" target="_blank" href="http://www.ssb.no/english/">Statistics Norway</a></td></tr>
                                <tr data-value="('ref169')"><td class="short">NPL</td><td><a id="ref169" target="_blank" href="http://www.cbs.gov.np/">Central Bureau of Statistics, Nepal</a></td></tr>
                                <tr data-value="('ref170')"><td class="short">NRU</td><td><a id="ref170" target="_blank" href="http://www.spc.int/prism/nauru/">Nauru Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref171')"><td class="short">NZL</td><td><a id="ref171" target="_blank" href="http://www.stats.govt.nz/">Statistics New Zealand</a></td></tr>
                                <tr data-value="('ref172')"><td class="short">OIA</td><td><a id="ref172" target="_blank" href="http://www.pacificweb.org/">OIA Statistics Online</a></td></tr>
                                <tr data-value="('ref173')"><td class="short">OMN</td><td><a id="ref173" target="_blank" href="https://www.ncsi.gov.om/Pages/NCSI.aspx">Sultanate of Oman, National Centre for Statistics and Information</a></td></tr>
                                <tr data-value="('ref174')"><td class="short">PAK</td><td><a id="ref174" target="_blank" href="http://www.pbs.gov.pk/">Pakistan Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref175')"><td class="short">PAN</td><td><a id="ref175" target="_blank" href="http://www.contraloria.gob.pa/inec/">Instituto Nacional de Estadística y Censo, Panamá</a></td></tr>
                                <tr data-value="('ref176')"><td class="short">PER</td><td><a id="ref176" target="_blank" href="http://www.inei.gob.pe/">Instituto Nacional de Estadística e Informática, Peru</a></td></tr>
                                <tr data-value="('ref177')"><td class="short">PHL</td><td><a id="ref177" target="_blank" href="https://psa.gov.ph/">National Statistics Office of the Philippines</a></td></tr>
                                <tr data-value="('ref178')"><td class="short">PLW</td><td><a id="ref178" target="_blank" href="http://palaugov.org/executive-branch/ministries/finance/budgetandplanning/">Bureau of Budget &amp; Planning, Republic of Palau</a></td></tr>
                                <tr data-value="('ref179')"><td class="short">PNG</td><td><a id="ref179" target="_blank" href="http://www.nso.gov.pg/">National Statistical Office of Papua New Guinea</a></td></tr>
                                <tr data-value="('ref180')"><td class="short">POL</td><td><a id="ref180" target="_blank" href="http://www.stat.gov.pl/">Central Statistical Office Poland</a></td></tr>
                                <tr data-value="('ref181')"><td class="short">PRT</td><td><a id="ref181" target="_blank" href="http://www.ine.pt/">Instituto Nacional de Estatística Portugal</a></td></tr>
                                <tr data-value="('ref182')"><td class="short">PRY</td><td><a id="ref182" target="_blank" href="http://www.dgeec.gov.py/">Paraguay Dirección General de Estadísticas, Encuestas y Censos</a></td></tr>
                                <tr data-value="('ref183')"><td class="short">PSE</td><td><a id="ref183" target="_blank" href="http://www.pcbs.gov.ps/">Palestinian Central Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref184')"><td class="short">PYF</td><td><a id="ref184" target="_blank" href="http://www.ispf.pf/">Institute Statistique de Polynésie Française</a></td></tr>
                                <tr data-value="('ref185')"><td class="short">QAT</td><td><a id="ref185" target="_blank" href="https://www.psa.gov.qa">Qatar Planning and Statistics Authority</a></td></tr>
                                <tr data-value="('ref186')"><td class="short">ROU</td><td><a id="ref186" target="_blank" href="http://www.insse.ro/">Romania National Institute of Statistics</a></td></tr>
                                <tr data-value="('ref187')"><td class="short">RUS</td><td><a id="ref187" target="_blank" href="http://www.gks.ru/">Federal State Statistics Service Russia</a></td></tr>
                                <tr data-value="('ref188')"><td class="short">RUS</td><td><a id="ref188" target="_blank" href="http://www.perepis-2010.ru">Russia Census 2010</a></td></tr>
                                <tr data-value="('ref189')"><td class="short">RWA</td><td><a id="ref189" target="_blank" href="http://www.statistics.gov.rw/">National Institute of Statistics Rwanda</a></td></tr>
                                <tr data-value="('ref190')"><td class="short">SAU</td><td><a id="ref190" target="_blank" href="https://www.stats.gov.sa/">General Statistics Authority, Kingdom of Saudi Arabia</a></td></tr>
                                <tr data-value="('ref191')"><td class="short">SEN</td><td><a id="ref191" target="_blank" href="http://www.ansd.sn/">Agence Nationale de la Statistique et de la Démographie, Sénégal</a></td></tr>
                                <tr data-value="('ref192')"><td class="short">SGP</td><td><a id="ref192" target="_blank" href="http://www.singstat.gov.sg/">Singapore Department of Statistics</a></td></tr>
                                <tr data-value="('ref193')"><td class="short">SHN</td><td><a id="ref193" target="_blank" href="http://www.sainthelena.gov.sh/">Saint Helena Government</a></td></tr>
                                <tr data-value="('ref194')"><td class="short">SLB</td><td><a id="ref194" target="_blank" href="https://www.statistics.gov.sb/">Solomon Islands National Statistics Office</a></td></tr>
                                <tr data-value="('ref195')"><td class="short">SLE</td><td><a id="ref195" target="_blank" href="http://www.statistics.sl/">Statistics Sierra Leone</a></td></tr>
                                <tr data-value="('ref196')"><td class="short">SLV</td><td><a id="ref196" target="_blank" href="http://www.digestyc.gob.sv/">Dirección General de Estadistica y Censos, El Salvador</a></td></tr>
                                <tr data-value="('ref197')"><td class="short">SMR</td><td><a id="ref197" target="_blank" href="http://www.statistica.sm/">Ufficio Informatica, Tecnologia, Dati e Statistica, San Marino</a></td></tr>
                                <tr data-value="('ref198')"><td class="short">SOM</td><td><a id="ref198" target="_blank" href="https://www.nbs.gov.so/">Somalia National Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref199')"><td class="short">SRB</td><td><a id="ref199" target="_blank" href="http://stat.gov.rs/">Statistical Office of the Republic of Serbia</a></td></tr>
                                <tr data-value="('ref200')"><td class="short">SSD</td><td><a id="ref200" target="_blank" href="http://ssnbs.org/">South Sudan National Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref201')"><td class="short">STP</td><td><a id="ref201" target="_blank" href="http://www.ine.st/">Instituto Nacional de Estatística, República Democrática de São Tomé e Príncipe</a></td></tr>
                                <tr data-value="('ref202')"><td class="short">SUD</td><td><a id="ref202" target="_blank" href="http://cbs.gov.sd/">Central Bureau of Statistics Sudan</a></td></tr>
                                <tr data-value="('ref203')"><td class="short">SUR</td><td><a id="ref203" target="_blank" href="http://www.statistics-suriname.org/">Algemeen Bureau voor de Statistiek in Suriname</a></td></tr>
                                <tr data-value="('ref204')"><td class="short">SVK</td><td><a id="ref204" target="_blank" href="http://www.statistics.sk/">Statistical Office of the Slovak Republic</a></td></tr>
                                <tr data-value="('ref205')"><td class="short">SVN</td><td><a id="ref205" target="_blank" href="http://www.stat.si/eng/">Statistical Office of the Republic of Slovenia</a></td></tr>
                                <tr data-value="('ref206')"><td class="short">SWE</td><td><a id="ref206" target="_blank" href="http://www.scb.se/">Statistiska Centralbyrån (SCB), Sverige</a></td></tr>
                                <tr data-value="('ref207')"><td class="short">SWZ</td><td><a id="ref207" target="_blank" href="http://www.swazistats.org.sz/">Central Statistical Office Swaziland</a></td></tr>
                                <tr data-value="('ref208')"><td class="short">SXM</td><td><a id="ref208" target="_blank" href="http://stat.gov.sx/">Department of Statistics Sint Maarten</a></td></tr>
                                <tr data-value="('ref209')"><td class="short">SYC</td><td><a id="ref209" target="_blank" href="http://www.nbs.gov.sc/">National Statistics Bureau, Seychelles</a></td></tr>
                                <tr data-value="('ref210')"><td class="short">SYR</td><td><a id="ref210" target="_blank" href="http://www.cbssyr.org/">Central Bureau of Statistics, Syrian Arab Republic</a></td></tr>
                                <tr data-value="('ref211')"><td class="short">TCA</td><td><a id="ref211" target="_blank" href="https://www.gov.tc/stats/">Turks and Caicos Statistics Department</a></td></tr>
                                <tr data-value="('ref212')"><td class="short">TCD</td><td><a id="ref212" target="_blank" href="https://www.inseed.td/"> Institut National de la Statistique, des Études Économiques et Démographiques du Tchad</a></td></tr>
                                <tr data-value="('ref213')"><td class="short">TGO</td><td><a id="ref213" target="_blank" href="https://inseed.tg/">Direction Générale de la Statistique et de la Comptabilité Nationale, République Togolaise</a></td></tr>
                                <tr data-value="('ref214')"><td class="short">THA</td><td><a id="ref214" target="_blank" href="http://www.nso.go.th/">National Statistical Office, Thailand</a></td></tr>
                                <tr data-value="('ref215')"><td class="short">TJK</td><td><a id="ref215" target="_blank" href="http://www.stat.tj/">State Statistical Committee of the Republic of Tajikistan</a></td></tr>
                                <tr data-value="('ref216')"><td class="short">TKL</td><td><a id="ref216" target="_blank" href="http://www.tokelaunso.tk/">Tokelau National Statistical Unit</a></td></tr>
                                <tr data-value="('ref217')"><td class="short">TKM</td><td><a id="ref217" target="_blank" href="http://www.stat.gov.tm/">State Committee for Statistics, Turkmenistan</a></td></tr>
                                <tr data-value="('ref218')"><td class="short">TLS</td><td><a id="ref218" target="_blank" href="http://www.statistics.gov.tl/">National Directorate of Statistics Timor-Leste</a></td></tr>
                                <tr data-value="('ref219')"><td class="short">TON</td><td><a id="ref219" target="_blank" href="http://tonga.prism.spc.int/">Tonga Department of Statistics</a></td></tr>
                                <tr data-value="('ref220')"><td class="short">TRNC</td><td><a id="ref220" target="_blank" href="http://www.devplan.org/">TRNC State Planning Organization</a></td></tr>
                                <tr data-value="('ref221')"><td class="short">TTO</td><td><a id="ref221" target="_blank" href="http://www.cso.gov.tt/">Central Statistical Office, Trinidad and Tobago</a></td></tr>
                                <tr data-value="('ref222')"><td class="short">TUN</td><td><a id="ref222" target="_blank" href="http://www.ins.tn/">Institut National de la Statistique Tunisie</a></td></tr>
                                <tr data-value="('ref223')"><td class="short">TUR</td><td><a id="ref223" target="_blank" href="http://www.turkstat.gov.tr/Start.do">Republic of Turkey, State Institute of Statistics</a></td></tr>
                                <tr data-value="('ref224')"><td class="short">TUV</td><td><a id="ref224" target="_blank" href="https://tuvalu.prism.spc.int/">Tuvalu Central Statistics Division</a></td></tr>
                                <tr data-value="('ref225')"><td class="short">TWN</td><td><a id="ref225" target="_blank" href="http://www.moi.gov.tw">Ministry of Interior, Republic of China</a></td></tr>
                                <tr data-value="('ref226')"><td class="short">TWN</td><td><a id="ref226" target="_blank" href="http://eng.stat.gov.tw">National Statistics, Republic of China</a></td></tr>
                                <tr data-value="('ref227')"><td class="short">TZA</td><td><a id="ref227" target="_blank" href="http://www.nbs.go.tz/">National Bureau of Statistics Tanzania</a></td></tr>
                                <tr data-value="('ref228')"><td class="short">UGA</td><td><a id="ref228" target="_blank" href="http://www.ubos.org/">Uganda Bureau of Statistics</a></td></tr>
                                <tr data-value="('ref229')"><td class="short">UKR</td><td><a id="ref229" target="_blank" href="http://www.ukrstat.gov.ua/">State Statistics Committee of Ukraine</a></td></tr>
                                <tr data-value="('ref230')"><td class="short">UN</td><td><a id="ref230" target="_blank" href="http://www.undp.org/popin/wdtrends/urb/furb.htm">UN Department of Economic and Social Affairs, Population Division</a></td></tr>
                                <tr data-value="('ref231')"><td class="short">URY</td><td><a id="ref231" target="_blank" href="http://www.ine.gub.uy/">Instituto Nacional de Estadística, Uruguay</a></td></tr>
                                <tr data-value="('ref232')"><td class="short">USA</td><td><a id="ref232" target="_blank" href="http://www.census.gov/">U.S. Census Bureau</a></td></tr>
                                <tr data-value="('ref233')"><td class="short">UZB</td><td><a id="ref233" target="_blank" href="http://stat.uz/">State Committee of Uzbekistan on Statistics</a></td></tr>
                                <tr data-value="('ref234')"><td class="short">VCT</td><td><a id="ref234" target="_blank" href="http://www.stats.gov.vc/">Government of St. Vincent &amp; the Grenadines - Statistical Office</a></td></tr>
                                <tr data-value="('ref235')"><td class="short">VEN</td><td><a id="ref235" target="_blank" href="http://www.ine.gov.ve/">Instituto Nacional de Estadística, Venezuela</a></td></tr>
                                <tr data-value="('ref236')"><td class="short">VGB</td><td><a id="ref236" target="_blank" href="http://www.bvi.gov.vg/statistics">Government of the Virgin Islands (UK), Central Statistics Office</a></td></tr>
                                <tr data-value="('ref237')"><td class="short">VNM</td><td><a id="ref237" target="_blank" href="http://www.gso.gov.vn/">General Statistics Office of Vietnam</a></td></tr>
                                <tr data-value="('ref238')"><td class="short">VUT</td><td><a id="ref238" target="_blank" href="https://vnso.gov.vu/">Vanuatu National Statistics Office</a></td></tr>
                                <tr data-value="('ref239')"><td class="short">WLF</td><td><a id="ref239" target="_blank" href="http://www.statistique.wf/">Service Territorial de la Statistique et des Études Économiques, Wallis et Futuna</a></td></tr>
                                <tr data-value="('ref240')"><td class="short">WSM</td><td><a id="ref240" target="_blank" href="http://www.sbs.gov.ws/">Samoa Statistical Services Division</a></td></tr>
                                <tr data-value="('ref241')"><td class="short">YEM</td><td><a id="ref241" target="_blank" href="http://www.cso-yemen.org/">Central Statistical Organisation Yemen</a></td></tr>
                                <tr data-value="('ref242')"><td class="short">ZAF</td><td><a id="ref242" target="_blank" href="http://www.statssa.gov.za/">Statistics South Africa</a></td></tr>
                                <tr data-value="('ref243')"><td class="short">ZMB</td><td><a id="ref243" target="_blank" href="http://www.zamstats.gov.zm">Central Statistical Office Zambia</a></td></tr>
                                <tr data-value="('ref244')"><td class="short">ZWE</td><td><a id="ref244" target="_blank" href="http://www.zimstat.co.zw/">Central Statistical Office Zimbabwe</a></td></tr>
                                </tbody></table>
                        </div>
                        <div style="padding-left:1em;">
                            <u>International Web Resources</u>
                            <table class="overview">
                                <tr data-value="('ref245')"><td class="short"><a id="ref245" target="_blank" href="https://www.cia.gov/library/publications/the-world-factbook/">CIA World Fact Book</a></td><td>Country profiles and small static maps.</td></tr>
                                <tr data-value="('ref246')"><td class="short"><a id="ref246" target="_blank" href="https://data.humdata.org/">Humanitarian Data Exchange</a></td><td>For almost all countries of the world statistical and geospatial data.</td></tr>
                                <tr data-value="('ref247')"><td class="short"><a id="ref247" target="_blank" href="http://www.openstreetmap.org/">OpenStreetMap</a></td><td>Project that creates and provides free geographic data especially about streets and other points of interest; contains partly also administrative boundaries.</td></tr>
                                <tr data-value="('ref248')"><td class="short"><a id="ref248" target="_blank" href="http://pop-stat.mashke.org/">Population Statistics of Eastern Europe (pop-stat.mashke.org)</a></td><td>Detailed population statistics for Eastern and Middle Europe.</td></tr>
                                <tr data-value="('ref249')"><td class="short"><a id="ref249" target="_blank" href="https://sdd.spc.int/">SPC Statistics for Development Division (PRISM)</a></td><td>Statistics of the pacific island countries and territories by the Secretariat of the Pacific Community.</td></tr>
                                <tr data-value="('ref250')"><td class="short"><a id="ref250" target="_blank" href="http://www.statoids.com/">Statoids</a></td><td>Information about the administrative divisions of countries (update stopped in 2018).</td></tr>
                                <tr data-value="('ref251')"><td class="short"><a id="ref251" target="_blank" href="https://unstats.un.org/unsd/demographic/products/dyb/dyb2.htm">UN Statistics Division: Demographic Yearbook</a></td><td>Population of countries, capital cities and cities of 100,000 and more inhabitants</td></tr>
                                <tr data-value="('ref252')"><td class="short"><a id="ref252" target="_blank" href="https://unstats.un.org/unsd/demographic/sources/census/censusdates.htm">UN Statistics Division: Census Dates</a></td><td>Overview over census dates and some links.</td></tr>
                                <tr data-value="('ref253')"><td class="short"><a id="ref253" target="_blank" href="https://www.wikidata.org">Wikidata</a></td><td>Wikidata is a free and open knowledge base representing a subset of Wikipedia data in a structured way.</td></tr>
                                <tr data-value="('ref254')"><td class="short"><a id="ref254" target="_blank" href="https://en.wikipedia.org">Wikipedia, the free encyclodia</a></td><td>The free encyclopedia provides many facts about countries and cities, however, of varying quality.</td></tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- END section -->

        <?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
        <?php
    }

}
Prayer_Global_Porch_Data_Source::instance();
