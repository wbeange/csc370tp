<?php
/* From what I could find online, this is the most standard way of connecting to a database.
 * Using the global variable results in not having to write this code in every function.
 * I recently did work on an open source project called yourls that did it in a similar fashion.
 * The DB connection is never closed, as this will be done by php clean up.
 */
function dbconnect() {

  global $ydb;

  $ydb = new mysqli("localhost", "root", "school");

  if ( $ydb->connect_errno ) {
    printf( "Connect failed: %s\n ", $ydb->connect_error );
    exit();
  }

  $ydb->select_db( "csc370tp" );

  return $ydb;
}

?>
