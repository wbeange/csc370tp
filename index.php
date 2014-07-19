<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CSC 370 - Term Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="bootstrap/docs/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="bootstrap/docs/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="bootstrap/docs/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="bootstrap/docs/assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">CSC 370 / Term Project</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#insert">Insert</a></li>
              <li><a href="#delete">Delete</a></li>
              <li><a href="#query">Query</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

    <section id="insert">
      <h1>Create Stuff.</h1>

      <!-- Create Airlines -->
      <form id="add_al_form" class="well form-inline" action="" >
        <label>Name</label>
        <input type="text" id="al_name"/>
        <label>Code</label>
        <input type="text" id="al_code"/>
        <label>Website</label>
        <input type="text" id="al_url" placeholder="http://www.domain.com"/>

        <input type="button" class="add_al_button" value="Create Airline" />
      </form>

      <!-- Create Plane Models -->
      <form id="add_pm_form" class="well form-inline">
        <label>Code</label>
        <input id="pm_code" type="text"/>
        <label>Capacity</label>
        <input id="pm_capacity" type="text"/>

        <input type="button" class="add_pm_button" value="Create Plane Model"/>
      </form>

      <!-- Add Flights Form -->
      <form id="add_f_form" class="well form-inline">
        <label>Incoming Flight</label>
        <input type="radio" name="f_type" id="f_typei" value="incoming" checked>
        <label>Outgoing Flight</label>
        <input type="radio" name="f_type" id="f_typeo" value="outgoing">
        <br /><br />

        <label>Source</label>
        <input id="f_source" type="text"/>
        <label>Destination</label>
        <input id="f_dest" type="text"/>

        <label id="f_time_label1">Planned Arrival Time</label>
        <label id="f_time_label2">Planned Departure Time</label>
        <input id="f_time" type="text" placeholder="HH:MM:SS"/>
        <br /><br />

        <label>Airline Name</label>
        <input id="f_aname" type="text"/>
        <label>Plane Model Code</label>
        <input id="f_pmcode" type="text"/>

        <input type="button" class="add_f_button" value="Create Flight Route"/>
      </form>
     
      <!-- Add Flight Instances Form --> 
      <form id="add_if" class="well form-inline">
        <label>Arrival</label>
        <input type="radio" name="if_type" id="if_typea" value="arriving" checked>
        <label>Departure</label>
        <input type="radio" name="if_type" id="if_typed" value="departing">
        <label>Flight #</label>
        <input id="if_flightnum" type="text"/>
        <label>Gate #</label>
        <input id="if_gate" type="text"/>

        <label id="if_date_label1">Arrival Date</label>
        <label id="if_date_label2">Departure Date</label>
        <input id="if_date" type="text" placeholder="YYYY:MM:DD"/>

        <input type="button" class="add_if_button" value="Create Flight Instance"/>
      </form>     

      <!-- Add Passenger Form --> 
      <form id="add_passenger_form" class="well form-inline">
        <label>First Name</label>
        <input id="p_fname" type="text"/>
        <label>Last Name</label>
        <input id="p_lname" type="text"/>
        <label>Citizenship</label>
        <input id="p_c" type="text"/>
        <br /><br />

        <label>Date of Birth</label>
        <input id="p_dob" type="text" placeholder="YYYY:MM:DD"/>
        <label>Place of Birth</label>
        <input id="p_pob" type="text"/>

        <input type="button" class="add_passenger_button" value="Create Passenger"/>
      </form>

      <!-- Create Ticket -->
      <form id="add_t_form" class="well form-inline">
      <div class="row">
        <div class="span1.5">
          <label>First Class</label>
          <input type="radio" name="t_class" id="t_classf" value="first"><br />
          <label>Regular Class</label>
          <input type="radio" name="t_class" id="t_classr" value="regular" checked><br />
          <label>Special Class</label>
          <input type="radio" name="t_class" id="t_classs" value="special"><br />
          <label>Infant Class</label>
          <input type="radio" name="t_class" id="t_classi" value="infant"><br />
        </div>

        <div class="span15">
          <div class="t_class_f">
            <label>Preferred cocktail on boarding: </label> 
            <input id="t_cocktail" type="text" placeholder="Martini?"/>
          </div>
          <div class="t_class_r"><br />
            <label>Number of on-flight meal coupons: </label> 
            <input id="t_coupons" type="text" placeholder="Input a number..."/>
          </div>
          <div class="t_class_s">
            <label>Infomation: </label>
            <textarea id="t_cinfo" type="text" class="input-xlarge" rows="3" placeholder="Please provide more information..."/></textarea>
          </div>
          <div class="t_class_i"><br /><br /><br /><br />
            <label>Would you like a baby chair?  Yes</label> 
            <input id="t_class_i_babychairyes" type="radio" name="t_class_i_babychair" value="true"/>
            <label>No </label>
            <input id="t_class_i_babychairno" type="radio" name="t_class_i_babychair" value="false" checked/>
          </div>
        </div>

        <div class="span15">
          <br />
          <label>Number of bags</label>
          <input id="t_bags" type="text"/>
          <label>Gate # </label>
          <input id="t_gate" type="text"/>
          <br /><br />

          <label>Passenger ID</label>
          <input id="t_pid" type="text"/>
          <label>Flight #</label>
          <input id="t_fnum" type="text"/>
          <label>Arrival / Departure Date</label>
          <input id="t_date" type="text"/>
          
          <input type="button" class="add_t_button" value="Create Ticket"/>
        </div>
      </div>
      </form>     
    </section>

    <section id="delete">
      <h1>Delete Stuff.</h1>

      <!-- Delete Form -->
      <form id="del_form" class="well">
        <label>3. Delete an Airline</label>
        <input id="del_aname" type="text" placeholder="Airline Name..."/>

        <input type="button" class="del_button" value="Submit"/>
      </form>
    </section>

    <section id="query">
      <h1>Query Stuff.</h1>
      <p>Because of the way this has been implemented, pleace refresh the page between queries. This will be fixed in phase 2...</p>

      <!-- 4ABC. -->
      <form id="get_4a_form" class="well">
        <label>4A. Given an airline find all flights it operates.</label>
        <input id="4a_aname" type="text" placeholder="Airline Name..."/>

        <label>4B. Given a place (eg. Toronto) find all the flights from and to that place.</label>
        <input id="4b_place" type="text" placeholder="Place..."/>

        <label>4C. Given a time of the day find all the arrivals and departures around that time and print their status.</label>
        <input id="4c_time" type="text" placeholder="HH:MM:SS">
      
        <br />
        <input type="button" class="get_4a_button" value="Submit"/>

        <div class="4a_result" style="display: none;">
          <h2>Results</h2>

          <!-- Result divs to be pupulated on the fly. -->
          <div class="4a_title1"></div>
          <ul class="unstyled">
            <div class="4a_incomings"></div>
          </ul>
          <div class="4a_title2"></div>
          <ul class="unstyled">
            <div class="4a_outgoings"></div>
          </ul>
        </div>
      </form>

      <!-- 4D -->
      <form id="get_4d_form" class="well form-inline">
        <label>4D. Given a departure or arrival find all the passengers recored for it. Print all the information about these passengers.</label><br />

        <label>Arrival</label>
        <input type="radio" name="4d_type" id="4d_typea" value="arrival" checked>
        <label>Departure</label>
        <input type="radio" name="4d_type" id="4d_typed" value="departure">
        <label>Flight #</label>
        <input id="4d_flightnum" type="text"/>
        <label>Gate #</label>
        <input id="4d_gate" type="text"/>
        <label>Date:</label>
        <input id="4d_time" type="text" placeholder="YYYY:MM:DD">

        <br /><br />
        <label>4E. For a given passenger in a flight find his/her baggage (fill in above as well).</label>
        <input id="4e_pid" type="text" placeholder="Passenger ID...">

        <br /><br />
        <input type="button" class="get_4d_button" value="Submit"/>

        <div class="4d_result" style="display: none;">
          <div class="4d_title"></div>
          <ul class="unstyled">
            <div class="4d_passengers"></div>
          </ul>
        </div>
      </form>

      <!--
      <form id="get__form" class="well">
        <label></label>
        <input id="" type="text"/>

        <input type="button" class="add__button" value=""/>
      </form>
      -->
    </section>

    <hr>

    <footer>
      <p>UVIC CSC 370 Term Project. Will Beange 2012.</p>
    </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/docs/assets/js/jquery.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-transition.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-alert.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-modal.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-dropdown.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-scrollspy.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-tab.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-tooltip.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-popover.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-button.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-collapse.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-carousel.js"></script>
    <script src="bootstrap/docs/assets/js/bootstrap-typeahead.js"></script>

    <script type="text/javascript" src="js/ajax.js"></script>

  </body>
</html>
