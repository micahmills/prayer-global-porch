
####################################################
# How many locations +admin2 plus
# Full Flat Grid
SELECT
    lg1.grid_id, lg1.population, lg1.country_code
FROM wp_3_dt_location_grid lg1
WHERE lg1.level = 0
  AND lg1.grid_id NOT IN ( SELECT lg11.admin0_grid_id FROM wp_3_dt_location_grid lg11 WHERE lg11.level = 1 AND lg11.admin0_grid_id = lg1.grid_id )
#'China', 'India', 'France', 'Spain', 'Pakistan', 'Bangladesh'
  AND lg1.admin0_grid_id NOT IN (100050711,100219347, 100089589,100074576,100259978,100018514)
#'Romania', 'Estonia', 'Bhutan', 'Croatia', 'Solomon Islands', 'Guyana', 'Iceland', 'Vanuatu', 'Cape Verde', 'Samoa', 'Faroe Islands', 'Norway', 'Uruguay', 'Mongolia', 'United Arab Emirates', 'Slovenia', 'Bulgaria', 'Honduras', 'Columbia', 'Namibia', 'Switzerland', 'Western Sahara'
  AND lg1.admin0_grid_id NOT IN (100314737,100083318,100041128,100133112,100341242,100132648,100222839,100379914,100055707,100379993,100130389,100255271,100363975,100248845,100001527,100342458,100024289,100132795,100054605,100253456,100342975,100074571)
# above admin 0 (22)

UNION ALL
--
# admin 1 locations that have no level 2 (768)
--
SELECT
    lg2.grid_id, lg2.population, lg2.country_code
FROM wp_3_dt_location_grid lg2
WHERE lg2.level = 1
  AND lg2.grid_id NOT IN ( SELECT lg22.admin1_grid_id FROM wp_3_dt_location_grid lg22 WHERE lg22.level = 2 AND lg22.admin1_grid_id = lg2.grid_id )
#'China', 'India', 'France', 'Spain', 'Pakistan', 'Bangladesh'
  AND lg2.admin0_grid_id NOT IN (100050711,100219347, 100089589,100074576,100259978,100018514)
#'Romania', 'Estonia', 'Bhutan', 'Croatia', 'Solomon Islands', 'Guyana', 'Iceland', 'Vanuatu', 'Cape Verde', 'Samoa', 'Faroe Islands', 'Norway', 'Uruguay', 'Mongolia', 'United Arab Emirates', 'Slovenia', 'Bulgaria', 'Honduras', 'Columbia', 'Namibia', 'Switzerland', 'Western Sahara'
  AND lg2.admin0_grid_id NOT IN (100314737,100083318,100041128,100133112,100341242,100132648,100222839,100379914,100055707,100379993,100130389,100255271,100363975,100248845,100001527,100342458,100024289,100132795,100054605,100253456,100342975,100074571)

UNION ALL
--
# admin 2 all countries (37100)
--
SELECT
    lg3.grid_id, lg3.population,  lg3.country_code
FROM wp_3_dt_location_grid lg3
WHERE lg3.level = 2
#'China', 'India', 'France', 'Spain', 'Pakistan', 'Bangladesh'
  AND lg3.admin0_grid_id NOT IN (100050711,100219347, 100089589,100074576,100259978,100018514)
#'Romania', 'Estonia', 'Bhutan', 'Croatia', 'Solomon Islands', 'Guyana', 'Iceland', 'Vanuatu', 'Cape Verde', 'Samoa', 'Faroe Islands', 'Norway', 'Uruguay', 'Mongolia', 'United Arab Emirates', 'Slovenia', 'Bulgaria', 'Honduras', 'Columbia', 'Namibia', 'Switzerland', 'Western Sahara'
  AND lg3.admin0_grid_id NOT IN (100314737,100083318,100041128,100133112,100341242,100132648,100222839,100379914,100055707,100379993,100130389,100255271,100363975,100248845,100001527,100342458,100024289,100132795,100054605,100253456,100342975,100074571)

UNION ALL
--
# admin 1 for little highly divided countries (352)
--
SELECT
    lg4.grid_id, lg4.population,  lg4.country_code
FROM wp_3_dt_location_grid lg4
WHERE lg4.level = 1
#'China', 'India', 'France', 'Spain', 'Pakistan', 'Bangladesh'
  AND lg4.admin0_grid_id NOT IN (100050711,100219347, 100089589,100074576,100259978,100018514)
#'Romania', 'Estonia', 'Bhutan', 'Croatia', 'Solomon Islands', 'Guyana', 'Iceland', 'Vanuatu', 'Cape Verde', 'Samoa', 'Faroe Islands', 'Norway', 'Uruguay', 'Mongolia', 'United Arab Emirates', 'Slovenia', 'Bulgaria', 'Honduras', 'Columbia', 'Namibia', 'Switzerland', 'Western Sahara'
  AND lg4.admin0_grid_id IN (100314737,100083318,100041128,100133112,100341242,100132648,100222839,100379914,100055707,100379993,100130389,100255271,100363975,100248845,100001527,100342458,100024289,100132795,100054605,100253456,100342975,100074571)

UNION ALL

--
# admin 3 for big countries (6153)
--
SELECT
    lg5.grid_id, lg5.population, lg5.country_code
FROM wp_3_dt_location_grid as lg5
WHERE
        lg5.level = 3
#'China', 'India', 'France', 'Spain', 'Pakistan', 'Bangladesh'
  AND lg5.admin0_grid_id IN (100050711,100219347, 100089589,100074576,100259978,100018514)
#'Romania', 'Estonia', 'Bhutan', 'Croatia', 'Solomon Islands', 'Guyana', 'Iceland', 'Vanuatu', 'Cape Verde', 'Samoa', 'Faroe Islands', 'Norway', 'Uruguay', 'Mongolia', 'United Arab Emirates', 'Slovenia', 'Bulgaria', 'Honduras', 'Columbia', 'Namibia', 'Switzerland', 'Western Sahara'
  AND lg5.admin0_grid_id NOT IN (100314737,100083318,100041128,100133112,100341242,100132648,100222839,100379914,100055707,100379993,100130389,100255271,100363975,100248845,100001527,100342458,100024289,100132795,100054605,100253456,100342975,100074571)
;
# Total Records (44395)
####################################################



####################################################
# All locations Admin2 and up, plus 'China', 'India', 'France', 'Spain', 'Pakistan', 'Bangladesh'
SELECT
    lg3.grid_id, lg3.population,  lg3.country_code
FROM wp_3_dt_location_grid lg3
WHERE lg3.level < 3
UNION ALL
SELECT
    lg5.grid_id, lg5.population, lg5.country_code
FROM wp_3_dt_location_grid as lg5
WHERE
        lg5.level = 3
#'China', 'India', 'France', 'Spain', 'Pakistan', 'Bangladesh'
  AND lg5.admin0_grid_id IN (100050711,100219347, 100089589,100074576,100259978,100018514);
# (55991)
####################################################



####################################################
# All State Level Polygons
SELECT
    lg1.grid_id, lg1.population, lg1.country_code, lg1.name
FROM location_grid lg1
WHERE lg1.level = 0
  AND lg1.grid_id NOT IN ( SELECT lg11.admin0_grid_id FROM location_grid lg11 WHERE lg11.level = 1 AND lg11.admin0_grid_id = lg1.grid_id )
  AND lg1.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
UNION ALL
SELECT
    lg2.grid_id, lg2.population, lg2.country_code, lg2.name
FROM location_grid lg2
WHERE lg2.level = 1
  AND lg2.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
UNION ALL
SELECT
    lg3.grid_id, lg3.population, lg3.country_code, lg3.name
FROM location_grid lg3
WHERE lg3.level = 2
  AND lg3.admin0_grid_id IN (100050711,100219347,100089589,100074576,100259978,100018514);
# (3629)
####################################################
