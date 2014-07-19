<?php
/* Will Beange
 * April 2nd, 2012
 * CSC370 DB Term Project.
 */

//Please refer the the functions.php for all insert queries.

// PART 4
//Section 4 queries have been loosely extracted from the code in functions.php.

//4a.
$incomings = $ydb->query("SELECT * FROM flights JOIN incomings USING (number) WHERE airline_name = '".$aname."'");
$outgoings = $ydb->query("SELECT * FROM flights JOIN outgoings USING (number) WHERE airline_name = '".$aname."'");

//4b.
$incomings = $ydb->query("SELECT * FROM flights JOIN incomings USING (number) WHERE source = '".$place."' OR destination = '".$place."'");
$outgoings = $ydb->query("SELECT * FROM flights JOIN outgoings USING (number) WHERE source = '".$place."' OR destination = '".$place."'");

//4c.
//Assumes a one hour interval.
$arrivals = $ydb->query("SELECT * FROM arrivals A JOIN (SELECT number, planned_arrival_time FROM flights JOIN incomings USING (number)) B WHERE A.number = B.number AND CAST(B.planned_arrival_time AS time) BETWEEN SUBTIME('".$time."', '".$interval."') AND ADDTIME('".$time."', '".$interval."')");
$departures = $ydb->query("SELECT * FROM departures D JOIN (SELECT number, planned_departure_time FROM flights JOIN outgoings USING (number)) B WHERE D.number = B.number AND CAST(B.planned_departure_time AS time) BETWEEN SUBTIME('".$time."', '".$interval."') AND ADDTIME('".$time."', '".$interval."')");

//4d.
//The type of flight could be determined with the user information, however here it is provided.
//Considering the info is giving, I woudl argure that a simpler query yeilds less work on the DB.
$return['title'] = '<h3>Passengers</h3>';
if( $type == 'arrival' )
{
  $passengers = $ydb->query("SELECT * FROM passengers WHERE id IN (SELECT pid FROM arrival_tickets WHERE number='".$flight."' AND date='".$date."' AND gate='".$gate."')");
}
else
{
  $passengers = $ydb->query("SELECT * FROM passengers WHERE id IN (SELECT pid FROM departure_tickets WHERE number='".$flight."' AND date='".$date."' AND gate='".$gate."')");
}

//4e.
if( $type == 'arrival' )
{
  $bags = $ydb->query("SELECT * FROM bags B JOIN arrivals_bags A ON B.id=A.bid WHERE B.owner='".$pid."' AND A.number='".$flight."' AND A.date='".$date."' AND A.gate='".$gate."'");
}
else
{
  $bags = $ydb->query("SELECT * FROM bags B JOIN departures_bags A ON B.id=A.bid WHERE B.owner='".$pid."' AND A.number='".$flight."' AND A.date='".$date."' AND A.gate='".$gate."'");
}

/**********************************/
// PART 5

//5A.
//Here I am assuming a grace period of 30 minutes to connect between flights, however the minimum case here would be a very stressful flight!
$sql = "SELECT INCOMING.number, OUTGOING.number
FROM ( SELECT number, source, destination, planned_arrival_time FROM flights JOIN incomings USING(number) ) INCOMING,
( SELECT number, source, destination, planned_departure_date FROM flights JOIN outgoings USING(number) ) OUTGOING
WHERE INCOMING.number <> OUTGOING.number
AND INCOMING.destination = OUTGOING.source
AND INCOMING.planned_arrival_time BETWEEN SUBTIME(OUTGOING.planned_departure_date, '03:00:00') AND SUBTIME(OUTGOING.planned_departure_date, '00:30:00');";

//5B.
//In order to answer this question, we need to make an assumption of how long flights are, in order to define a flight as 'en route'.
//This wouldn't be necessary if each incoming / outgoing flight had a duration variable.
//I am going to assume each flight takes 6 hours.
//This way we can take the current datetime, and search for all arrivals/departures within a 6 hour window.

//This could be even trickier if we considered a flight's status, as delayed or cancelled flights would be void from my supplied query.
//I am going to assume we are not considering status.
$sql = 
"
SELECT A.pid
FROM arrival_tickets A,
( SELECT ROUTE.number AS number, ROUTE.gate AS gate, ROUTE.arrival_date AS date 
  FROM ( SELECT number, gate, arrival_date FROM arrivals WHERE number IN ( SELECT number 
                                                                           FROM incomings 
                                                                           WHERE CAST(planned_arrival_time AS time)
                                                                           BETWEEN CURTIME() AND ADDTIME(planned_arrival_time, '06:00:00') 
                                                                         ) 
       ) ROUTE,
  arrival_tickets INSTANCE
  WHERE ROUTE.number = INSTANCE.number
  AND ROUTE.gate = INSTANCE.gate
  AND ROUTE.arrival_date = INSTANCE.date
) X
WHERE A.number = X.number
AND A.gate = X.gate
AND A.date = X.date
AND CAST(A.date AS DATE) = CURDATE()
";

//5C.
//A passenger will either have exclusively arrivals, exclusively departures, or both.
//Take all three cases, merge, and sort.
$sql = 
"
( SELECT X.pid AS pid, (X.total1 + Y.total2) AS totalTickets
  FROM ( SELECT pid, COUNT(pid) AS total1 FROM arrival_tickets GROUP BY pid ) X,
  ( SELECT pid, COUNT(pid) AS total2 FROM departure_tickets GROUP BY pid ) Y
  WHERE X.pid = Y.pid )
UNION
( SELECT pid, COUNT(pid) AS totalTickets FROM arrival_tickets WHERE pid NOT IN (SELECT pid FROM departure_tickets) GROUP BY pid )
UNION
( SELECT pid, COUNT(pid) AS totalTickets FROM departure_tickets WHERE pid NOT IN (SELECT pid FROM arrival_tickets) GROUP BY pid  )
ORDER BY totalTickets DESC
LIMIT 3;
";

//5D.
$sql = 
"
";
?>
