<?php
/* Will Beange
 * April 1st, 2012
 * CSC 370 Term Project
 */
require_once( "dbconnect.php" );
require_once( "functions.php" );

global $ydb;
dbconnect();

$action = ( isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null );

switch( $action ) {

  case 'add_airline':
    $return = add_airline();
    break;

  case 'add_plane_model':
    $return = add_plane_model();
    break;

  case 'add_incoming_flight':
  case 'add_departing_flight':
    $return = add_flight_pattern();
    break;

  case 'add_passenger':
    $return = add_passenger();
    break;

  case 'add_arriving':
  case 'add_departing':
    $return = add_flight_instance();
    break;

  case 'add_ticket':
    $return = add_ticket();
    break;

  case 'del_form':
    $return = del_example();
    break;

  case 'get4ab':
    $return = get_4ab();
    break;

  case 'get4d':
    $return = get_4d();
    break;
  
  default:
    $return = array(
      'status' => 400,
      'message' => 'Unknown or missing "action" parameter',
      'simple' => 'Unknown or missing "action" parameter',
    );
}

header('Content-type: application/json');  
echo json_encode( $return );


