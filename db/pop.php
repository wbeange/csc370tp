<?php
/*CSC 370 - Term Project
 * March 14th, 2012
 */

  $mysqli = new mysqli("localhost", "root", "school");

  if ( $mysqli->connect_errno ) {
    printf( "Connect failed: %s\n ", $mysqli->connect_error );
    exit();
  }

  //select db.
  $mysqli->select_db( "csc370tp" );

  $mysqli->query("INSERT INTO airlines (name, code, url) VALUES ('Air Canada', '111', 'http://www.aircanada.com')");
  $mysqli->query("INSERT INTO airlines (name, code, url) VALUES ('West Jet', '222', 'http://www.westjet.com')");
  $mysqli->query("INSERT INTO airlines (name, code, url) VALUES ('United', '333', 'http://www.united.com')");

  $mysqli->query("INSERT INTO plane_models (code, capacity) VALUES ('Boolean 747', '300')");
  $mysqli->query("INSERT INTO plane_models (code, capacity) VALUES ('Boolean 787', '450')");
  $mysqli->query("INSERT INTO plane_models (code, capacity) VALUES ('Fighter Plane', '2')");
  $mysqli->query("INSERT INTO plane_models (code, capacity) VALUES ('Beaver 3', '18')");

  $mysqli->query("INSERT INTO flights (source, destination, airline_name, plane_model_code) VALUES ('Vancouver', 'Toronto', 'Air Canada', 'Boolean 747')");
  $mysqli->query("INSERT INTO incomings (number, planned_arrival_time) VALUES ('1', '12:30')");

  $mysqli->query("INSERT INTO flights (source, destination, airline_name, plane_model_code) VALUES ('Toronto', 'Vancouver', 'West Jet', 'Beaver 3')");
  $mysqli->query("INSERT INTO incomings (number, planned_arrival_time) VALUES ('2', '4:55')");

  $mysqli->query("INSERT INTO flights (source, destination, airline_name, plane_model_code) VALUES ('Comox', 'Victoria', 'Air Canada', 'Fighter Plane')");
  $mysqli->query("INSERT INTO outgoings (number, planned_departure_date) VALUES ('3', '5:55')");

  $mysqli->query("INSERT INTO flights (source, destination, airline_name, plane_model_code) VALUES ('Victoria', 'Vancouver', 'Air Canada', 'Beaver 3')");
  $mysqli->query("INSERT INTO outgoings (number, planned_departure_date) VALUES ('4', '9:15')");
  
  //focus on more arriavls / departure to test 4C.
  $mysqli->query("INSERT INTO arrivals (number, gate, arrival_date, arrival_status) VALUES ('1', '7', '2012:12:08', 'default')");
  $mysqli->query("INSERT INTO arrivals (number, gate, arrival_date, arrival_status) VALUES ('2', '7', '2012:04:01', 'default')");
  $mysqli->query("INSERT INTO arrivals (number, gate, arrival_date, arrival_status) VALUES ('1', '2', '1111:11:11', 'default')");

  $mysqli->query("INSERT INTO departures (number, gate, departure_date, arrival_status) VALUES ('3', '1', '2222:22:22', 'default')");
  $mysqli->query("INSERT INTO departures (number, gate, departure_date, arrival_status) VALUES ('4', '1', '3333:33:33', 'default')");
  $mysqli->query("INSERT INTO departures (number, gate, departure_date, arrival_status) VALUES ('4', '4', '4444:44:44', 'default')");

  $mysqli->query("INSERT INTO passengers (last_name, first_name, date_of_birth, place_of_birth, citizenship) VALUES ('Beange', 'Will', '1988:12:08', 'Vancouver', 'Canadian')");
  $mysqli->query("INSERT INTO passengers (last_name, first_name, date_of_birth, place_of_birth, citizenship) VALUES ('Bill', 'Whittall', '1988:08:12', 'Byron Bay', 'Australia')");

  //TODO: make this a transaction.

  $mysqli->close();

?>
