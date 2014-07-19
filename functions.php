<?php
/* Will Beange
 * April 1st, 2012
 * CSC 370 Term Project
 */

function add_airline() {
  global $ydb;

  $al_name = ( isset( $_REQUEST['al_name'] ) ? $_REQUEST['al_name'] : '' );
  $al_code = ( isset( $_REQUEST['al_code'] ) ? $_REQUEST['al_code'] : '' );
  $al_url  = ( isset( $_REQUEST['al_url'] )  ? $_REQUEST['al_url'] : '' );

  //TODO: human errors.
  if( $al_name == '' || $al_code == '' || $al_url == '')
  {
    $return['status'] = 'Error: all fields must be filled in!';
  }
  else
  {
    $sql = "INSERT INTO airlines (name, code, url) VALUES ('".$al_name."', '".$al_code."', '".$al_url."')";

    if( $ydb->query($sql) )
    {
      $return['status'] = $al_name . ' Airline Added.';
    }
    else
    {
      $return['status'] = $ydb->error;
    }
  }

  return $return;
}

function add_plane_model() {
  global $ydb;

  $pm_code     = ( isset( $_REQUEST['pm_code'] )     ? $_REQUEST['pm_code'] : '' );
  $pm_capacity = ( isset( $_REQUEST['pm_capacity'] ) ? $_REQUEST['pm_capacity'] : '' );

  //TODO: human errors.
  if( $pm_code == '' || $pm_capacity == '' )
  {
    $return['status'] = 'Error: all fields must be filled in!';
  }
  else
  {
    $sql = "INSERT INTO plane_models (code, capacity) VALUES ('".$pm_code."', '".$pm_capacity."')";

    if( $ydb->query($sql) )
    {
      $return['status'] = $pm_code . ' Plane Model Added.';
    } 
    else 
    {
      $return['status'] = $ydb->error;
    }
  }

  return $return;
}

function add_passenger() {
  global $ydb;

  $fname = ( isset( $_REQUEST['p_fname'] ) ? $_REQUEST['p_fname'] : '' );
  $lname = ( isset( $_REQUEST['p_lname'] ) ? $_REQUEST['p_lname'] : '' );
  $citiz = ( isset( $_REQUEST['p_c'] )     ? $_REQUEST['p_c'] : '' );
  $dob   = ( isset( $_REQUEST['p_dob'] )   ? $_REQUEST['p_dob'] : '' );
  $pob   = ( isset( $_REQUEST['p_pob'] )   ? $_REQUEST['p_pob'] : '' );

  //TODO: human errors.
  if( $fname == '' || $lname == '' || $citiz == '' || $dob == '' || $pob == '' )
  {
    $return['status'] = 'Error: all fields must be filled in!';
  }
  else
  {
    if( $ydb->query("INSERT INTO passengers (last_name, first_name, date_of_birth, place_of_birth, citizenship) VALUES ('".$fname."', '".$lname."', '".$dob."', '".$pob."', '".$citiz."')") ) 
    {
      $return['status'] = 'Passenger Added. ID: ' . $ydb->insert_id;
    }
    else
    {
      $return['status'] = $ydb->error;
    }
  }

  return $return;
}

function add_flight_pattern() {
  global $ydb;

  $action = ( isset( $_REQUEST['action'] )   ? $_REQUEST['action'] : '' );
  $source = ( isset( $_REQUEST['f_source'] ) ? $_REQUEST['f_source'] : '' );
  $dest   = ( isset( $_REQUEST['f_dest'] )   ? $_REQUEST['f_dest'] : '' );
  $time   = ( isset( $_REQUEST['f_time'] )   ? $_REQUEST['f_time'] : '' );
  $aname  = ( isset( $_REQUEST['f_aname'] )  ? $_REQUEST['f_aname'] : '' );
  $pmcode = ( isset( $_REQUEST['f_pmcode'] ) ? $_REQUEST['f_pmcode'] : '' );

  //TODO: format time.
  
  //TODO: human errors.
  if( $source == '' || $dest == '' || $time == '' || $aname == '' || $pmcode == '' ) 
  {
    $return['status'] = 'Error: all fields must be filled in!';
  } 
  else 
  {
    //make sure flight doesn't exist. Flight patterns are based on an auto incremented id. Need this to make sure all the other fields as one combo does not exist.
    if( $action == 'add_incoming_flight' ) 
    {
      $flight_exists = $ydb->query("SELECT * FROM flights F JOIN incomings I WHERE F.source = '".$source.
                                   "' AND F.destination = '".$dest.
                                   "' AND F.airline_name = '".$aname.
                                   "' AND F.plane_model_code = '".$pmcode.
                                   "' AND I.planned_arrival_time = '".$time.
                                   "'");
    } 
    else 
    {
      $flight_exists = $ydb->query("SELECT * FROM flights F JOIN outgoings I WHERE F.source = '".$source.
                                   "' AND F.destination = '".$dest.
                                   "' AND F.airline_name = '".$aname.
                                   "' AND F.plane_model_code = '".$pmcode.
                                   "' AND I.planned_departure_date = '".$time.
                                   "'");
    }

    if( $flight_exists->num_rows > 0 ) 
    {
      $return['status'] = 'Error: Flight pattern already exists.';
    } 
    else 
    {
      //create parent.
      if( $ins_parent = $ydb->query("INSERT INTO flights (source, destination, airline_name, plane_model_code) VALUES ('".$source."', '".$dest."', '".$aname."', '".$pmcode."')") ) 
      {
        $flight_num = $ydb->insert_id;

        //create child statement with returned number id.
        if( $action == 'add_incoming_flight' ) 
        {
          $sql = "INSERT INTO incomings (number, planned_arrival_time) VALUES ('".$flight_num."', '".$time."')";
        } 
        else 
        {
          $sql = "INSERT INTO outgoings (number, planned_departure_date) VALUES ('".$flight_num."', '".$time."')";
        }

        if( $ydb->query($sql) ) 
        {
          $return['status'] = 'Flight pattern added. ID: ' . $flight_num;
        } 
        else 
        {
          $return['status'] = $ydb->error;
        }
      } 
      else 
      {
        $return['status'] = 'Flight insert failed: ' . $ydb->error;
      }
    }
  }

  return $return;
}

function add_flight_instance() {
  global $ydb;

  $flightnum = ( isset( $_REQUEST['if_flightnum'] ) ? $_REQUEST['if_flightnum'] : '' );
  $gate      = ( isset( $_REQUEST['if_gate'] )      ? $_REQUEST['if_gate'] : '' );
  $date      = ( isset( $_REQUEST['if_date'] )      ? $_REQUEST['if_date'] : '' );
  $action    = ( isset( $_REQUEST['action'] )       ? $_REQUEST['action'] : '' );

  //TODO: format / validate time.

  //TODO: human errors.
  if( $flightnum == '' || $gate == '' || $date == '' )
  {
    $return['status'] = 'Error: all fields must be filled in!';
  }
  else
  {
    if( $action == 'add_arriving' )
    {
      $status = 'Arriving at ' . $date;

      $flight_exists = $ydb->query("SELECT * FROM flights F JOIN incomings I USING(number) WHERE F.number = '".$flightnum."'");

      if($flight_exists->num_rows > 0)
      {
        if( $ydb->query( "INSERT INTO arrivals (number, gate, arrival_date, arrival_status) VALUES ('".$flightnum."', '".$gate."', '".$date."', '".$status."')" ) )
        {
          $return['status'] = 'Arriving flight instance added.';
        }
        else
        {
          $return['status'] = 'Error: ' . $ydb->error;
        }
      }
      else
      {
        $return['status'] = 'Error: not a valid flight number.';
      }
    }
    else
    {
      $status = 'Departing at ' . $date;
      $flight_exists = $ydb->query("SELECT * FROM flights F JOIN outgoings I USING(number) WHERE F.number = '".$flightnum."'");

      if($flight_exists->num_rows > 0)
      {
        if( $ydb->query( "INSERT INTO departures (number, gate, departure_date, arrival_status) VALUES ('".$flightnum."', '".$gate."', '".$date."', '".$status."')" ) ) 
        {
          $return['status'] = 'Departing flight instance added.';
        } 
        else 
        {
          $return['status'] = 'Error: ' . $ydb->error;
        }
      }
      else
      {
        $return['status'] = 'Error: not a valid flight number.';
      }
    }
  }
  
  return $return;
}

//TODO: code cleanup...
function add_ticket() {
  global $ydb;

  $bags      = ( isset( $_REQUEST['t_bags'] ) ? $_REQUEST['t_bags'] : '' );
  $gate      = ( isset( $_REQUEST['t_gate'] ) ? $_REQUEST['t_gate'] : '' );
  $pid       = ( isset( $_REQUEST['t_pid'] ) ? $_REQUEST['t_pid'] : '' );
  $flightnum = ( isset( $_REQUEST['t_fnum'] ) ? $_REQUEST['t_fnum'] : '' );
  $date      = ( isset( $_REQUEST['t_date'] ) ? $_REQUEST['t_date'] : '' );

  $class     = ( isset( $_REQUEST['t_class'] ) ? $_REQUEST['t_class'] : '' );
  $cocktail  = ( isset( $_REQUEST['t_cocktail'] ) ? $_REQUEST['t_cocktail'] : '' );
  $coupons   = ( isset( $_REQUEST['t_coupons'] ) ? $_REQUEST['t_coupons'] : '' );
  $cinfo     = ( isset( $_REQUEST['t_cinfo'] ) ? $_REQUEST['t_cinfo'] : '' );
  $babychair = ( isset( $_REQUEST['t_babychair'] ) ? $_REQUEST['t_babychair'] : '' );

  if( $bags == '' || $gate == '' || $pid == '' || $flightnum == '' || $date == '' )
  {
    $return['status'] = 'Input fields can not be blank!';
  } 
  else if ( $bags > 10 ) 
  {
    $return['status'] = 'No more than 9 bags!';
  } 
  else 
  {

    //TODO: make this better... find type of flight...
    $arr_path_exists = $ydb->query("SELECT * FROM arrivals WHERE number = '".$flightnum."' AND arrival_date = '".$date."' AND gate = '".$gate."'");
    $dep_path_exists = $ydb->query("SELECT * FROM departures WHERE number = '".$flightnum."' AND departure_date = '".$date."' AND gate = '".$gate."'");

    if( $arr_path_exists->num_rows > 0 ) 
    {
      $tick_type = 'arrivals';
    } 
    else if( $dep_path_exists->num_rows > 0 ) 
    {
      $tick_type = 'departures';
    } 
    else 
    {
      //TODO this sucks?
      $return['status'] = 'Error: the flight, gate, date combo does not exist. ' . $ydb->error;
      return $return;
    }

    //TODO: check if passenger is already on this flight.

    //create class...
    if( $ydb->query("INSERT INTO classes (class) VALUES ('".$class."')") ) 
    {
      $class_id = $ydb->insert_id;

      //statements per class type.
      if( $class == 'first' ) 
      {
        $sql = "INSERT INTO first_class (id, cocktail) VALUES ('".$class_id."', '".$cocktail."')";
      } 
      else if( $class == 'regular' ) 
      {
        $sql = "INSERT INTO regular_class (id, coupons) VALUES ('".$class_id."', '".$coupons."')";
      } 
      else if( $class == 'special' ) 
      {
        $sql = "INSERT INTO special_class (id, info) VALUES ('".$class_id."', '".$info."')";
      }
      else 
      {
        $sql = "INSERT INTO infants_class (id, baby_chair) VALUES ('".$class_id."', '".$babychair."')";
      }

      //run insert class statement
      if( $ydb->query( $sql ) ) 
      {
        //create ticket...
        //TODO: at this point I got lazy and stopped checking for db query errors.
        if( $tick_type == 'arrivals' ) 
        {
          //TODO: check for db error.
          $ydb->query("INSERT INTO arrival_tickets (number, date, gate, class_id, pid) VALUES ('".$flightnum."', '".$date."', '".$gate."', '".$class_id."', '".$pid."')");
          $bagtype = $tick_type;
        } 
        else 
        {
          //TODO: check for db error.
          $ydb->query("INSERT INTO departure_tickets (number, date, gate, class_id, pid) VALUES ('".$flightnum."', '".$date."', '".$gate."', '".$class_id."', '".$pid."')");
          $bagtype = $tick_type;
        }

        //create bags...
        $i=0;
        for($i; $i<$bags; $i++) 
        {
          $result = $ydb->query("INSERT INTO bags (owner) VALUES ('".$pid."')");
          $bag_id = $ydb->insert_id;
          //add bags to flight.
          $ydb->query("INSERT INTO ".$bagtype."_bags (bid, number, gate, date) VALUES ('".$bag_id."', '".$flightnum."', '".$gate."', '".$date."')");
        }

        $return['status'] = 'Ticket Created!';
      } 
      else 
      {
        $return['status'] = 'Error: insert child class failed.' . $ydb->error;
      }
    } 
    else 
    {
      $return['status'] = 'Error: insert class type failed: ' . $ydb->error;
    }
  }

  return $return;
}

function get_4ab() {
  global $ydb;

  $aname = ( isset( $_REQUEST['aname'] ) ? $_REQUEST['aname'] : '' );
  $place = ( isset( $_REQUEST['place'] ) ? $_REQUEST['place'] : '' );
  $time  = ( isset( $_REQUEST['time'] ) ? $_REQUEST['time'] : '' );

  $return['title1'] = '';
  $return['title2'] = '';

  if( $aname == '' && $place == '' && $time == '')
  {
    $return['status'] = 'Error: no input specified.';
  }
  else
  {
    $return['status'] = 'OK.';

    if( $place == '' && $time == '' )
    {
      //4a query
      $incomings = $ydb->query("SELECT * FROM flights JOIN incomings USING (number) WHERE airline_name = '".$aname."'");
      $outgoings = $ydb->query("SELECT * FROM flights JOIN outgoings USING (number) WHERE airline_name = '".$aname."'");

      $return['title1'] = '<h3>Incomings</h3>';
      $return['title2'] = '<h3>Outgoings</h3>';
    }
    else if( $aname == '' && $time == '' )
    {
      //4b query
      $incomings = $ydb->query("SELECT * FROM flights JOIN incomings USING (number) WHERE source = '".$place."' OR destination = '".$place."'");
      $outgoings = $ydb->query("SELECT * FROM flights JOIN outgoings USING (number) WHERE source = '".$place."' OR destination = '".$place."'");

      $return['title1'] = '<h3>Incomings</h3>';
      $return['title2'] = '<h3>Outgoings</h3>';
    }
    else if( $aname == '' && $place == '' )
    {
      //4c.
      //between an hour interval.
      $interval = '00:30:00';

      $arrivals = $ydb->query("SELECT * FROM arrivals A JOIN (SELECT number, planned_arrival_time FROM flights JOIN incomings USING (number)) B WHERE A.number = B.number AND CAST(B.planned_arrival_time AS time) BETWEEN SUBTIME('".$time."', '".$interval."') AND ADDTIME('".$time."', '".$interval."')");

      $departures = $ydb->query("SELECT * FROM departures D JOIN (SELECT number, planned_departure_time FROM flights JOIN outgoings USING (number)) B WHERE D.number = B.number AND CAST(B.planned_departure_time AS time) BETWEEN SUBTIME('".$time."', '".$interval."') AND ADDTIME('".$time."', '".$interval."')");

      $return['title1'] = '<h3>Arrivals</h3>';
      $return['title2'] = '<h3>Departures</h3>';
    }
    else
    {
      $return['status'] = 'Error: too many input specified. Pick only one.';
    }

    $inc_list = '';
    $out_list = '';

    //deals with 4a and 4b.
    if( $time == '' )
    {
      if( $incomings->num_rows == 0 )
      {
        $inc_list = 'No such flights.';
      }
      else
      {
        while( $flight = $incomings->fetch_row() )
        {
          $inc_list .= '<li>Flight #'.$flight[0].' '.$flight[1].' to '.$flight[2].' on a '.$flight[4].' leaving at '.$flight[5].'.</li>'; 
        }
      }

      if( $outgoings->num_rows == 0 )
      {
        $out_list = 'No such flights.';
      }
      else
      {      
        while( $flight = $outgoings->fetch_row() )
        {
          $out_list .= '<li>Flight #'.$flight[0].' '.$flight[1].' to '.$flight[2].' on a '.$flight[4].' leaving at '.$flight[5].'.</li>';
        }
      }

      $return['incomings'] = $inc_list;
      $return['outgoings'] = $out_list;
    }
    // 4c.
    else
    {
      if( $arrivals->num_rows == 0 )
      {
        $inc_list = '<li>No such flights.</li>';
      }
      else
      {
        // number | gate | arrival_date | arrival_status | number | planned_arrival_time
        while( $flight = $arrivals->fetch_row() )
        {
          $inc_list .= '<li>Flight #'.$flight[1].', Date: '.$flight[0].', Planned Arrival Time: '.$flight[5].', Gate #'.$flight[1].', Status: '.$flight[3].'</li>';
        }
      }

      if( $departures->num_rows == 0 )
      {
        $out_list = '<li>No such flights.</li>';
      }
      else
      {
        // number | gate | arrival_date | arrival_status | number | planned_arrival_time
        while( $flight = $departures->fetch_row() )
        {
          $out_list .= '<li>Flight #'.$flight[1].', Date: '.$flight[0].', Planned Departure Time: '.$flight[5].', Gate #'.$flight[1].', Status: '.$flight[3].'</li>';
        }
      }

      $return['incomings'] = $inc_list;
      $return['outgoings'] = $out_list;
    }

  }
  return $return;
}

function del_example()
{
  global $ydb;

  $aname = ( isset( $_REQUEST['aname'] ) ? $_REQUEST['aname'] : '' );

  if( $aname == '' )
  {
    $return['status'] = 'Error: blank airline name.';
  }
  else
  {
    $result = $ydb->query("SELECT * FROM airlines WHERE name = '".$aname."'");

    if( $result->num_rows > 0 )
    {
      //delete airline... db constraints should do the child deletions.
      if( $ydb->query("DELETE FROM airlines WHERE name = '".$aname."'") )
      {
        $return['status'] = $aname . ' deleted.';
      }
      else
      {
        $return['status'] = 'Error: ' . $ydb->error;
      }
    }
    else
    {
      $return['status'] = 'Error: that airline does not exist.';
    }
  }

  return $return;
}

function get_4d()
{
  global $ydb;

  $flight = ( isset( $_REQUEST['fnum'] ) ? $_REQUEST['fnum'] : '' );
  $gate   = ( isset( $_REQUEST['gnum'] ) ? $_REQUEST['gnum'] : '' );
  $date   = ( isset( $_REQUEST['date'] ) ? $_REQUEST['date'] : '' );
  $type   = ( isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '' );
  $pid    = ( isset( $_REQUEST['pid'] ) ? $_REQUEST['pid'] : '' );

  $return['title'] = ''; 

  if( $flight == '' || $gate == '' || $date == '' )
  {
    $return['status'] = 'Error: not all input specified.';
  }
  else
  {
    $return['status'] = 'OK.';
    $list = '';

    if( $pid == '' )
    {
      //4D.
      $return['title'] = '<h3>Passengers</h3>'; 
      if( $type == 'arrival' )
      {
        $passengers = $ydb->query("SELECT * FROM passengers WHERE id IN (SELECT pid FROM arrival_tickets WHERE number='".$flight."' AND date='".$date."' AND gate='".$gate."')");
      }
      else
      {
        $passengers = $ydb->query("SELECT * FROM passengers WHERE id IN (SELECT pid FROM departure_tickets WHERE number='".$flight."' AND date='".$date."' AND gate='".$gate."')");
      }

      //TODO: grab class info for that flight, maybe flight info as well..?
      if( $passengers->num_rows == 0 )
      {
        $return['status'] = 'No such passengers.';
      }
      else
      {
        //id | last_name | first_name | date_of_birth | place_of_birth | citizenship
        while( $p = $passengers->fetch_row() )
        {
          $list .= '<li>PID#'. $p[0] . ' ' . $p[2] . ' ' . $p[1] . ' born on: '.$p[3].' from: '.$p[4].' ['.$p[5].']</li>';
        }
        $return['p'] = $list;
      }
    }
    else
    {
      //4E.
      $return['title'] = '<h3>Bags</h3>';
      $list = '';

      if( $type == 'arrival' )
      {
        $bags = $ydb->query("SELECT * FROM bags B JOIN arrivals_bags A ON B.id=A.bid WHERE B.owner='".$pid."' AND A.number='".$flight."' AND A.date='".$date."' AND A.gate='".$gate."'");
      }
      else
      {
        $bags = $ydb->query("SELECT * FROM bags B JOIN departures_bags A ON B.id=A.bid WHERE B.owner='".$pid."' AND A.number='".$flight."' AND A.date='".$date."' AND A.gate='".$gate."'");
      }

      if( $bags->num_rows == 0 )
      {
        $return['status'] = 'No bags!';
      }
      else
      {
        //id | owner | bid | number | date | gate
        while( $b = $bags->fetch_row() )
        {
          $list .= '<li>Bag ID: ' . $b[0] . '</li>';
        }
        $return['p'] = $list;
      }
    }
  }
  return $return;
}
