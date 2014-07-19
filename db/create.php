<?php
/*CSC 370 - Term Project
 * March 14th, 2012
 */

  $mysqli = new mysqli("localhost", "root", "school");

  if ( $mysqli->connect_errno ) {
    printf( "Connect failed: %s\n ", $mysqli->connect_error );
    exit();
  }

  //create db.
  if( $mysqli->query("CREATE DATABASE csc370tp") ) {
    printf( "Database created.\n" );
  } else {
    printf( "Error creating database: %s\n", $mysqli->error );
    exit();
  }

  //select db.
  $mysqli->select_db( "csc370tp" );

  //create user.
  $sql = "GRANT ALL ON csc370.* TO 'user' IDENTIFIED BY 'school'";
  if ( $mysqli->query($sql) ) {
    printf( "User created.\n" );
  } else {
    printf( "Error creating user.\n");
  }

  //creating tables...
  $create_tables = array();

  //airlines
  $sql = "CREATE TABLE IF NOT EXISTS `airlines` (
    `name` VARCHAR(128) PRIMARY KEY,
    `code` INT NOT NULL,
    `url` text BINARY NOT NULL
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //plane_models
  $sql = "CREATE TABLE IF NOT EXISTS `plane_models` (
    `code` VARCHAR(56) PRIMARY KEY,
    `capacity` INT NOT NULL,
    CHECK (capacity > 0)
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //flights
  $sql = "CREATE TABLE IF NOT EXISTS `flights` (
    `number` INT PRIMARY KEY AUTO_INCREMENT,
    `source` VARCHAR(128) NOT NULL,
    `destination` VARCHAR(128) NOT NULL,
    `airline_name` VARCHAR(128),
    `plane_model_code` VARCHAR(56),
    FOREIGN KEY (airline_name) REFERENCES airlines(name)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    FOREIGN KEY (plane_model_code) REFERENCES plane_models(code)
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //incomings
  $sql = "CREATE TABLE IF NOT EXISTS `incomings` (
  `number` INT NOT NULL,
  `planned_arrival_time` TIME NOT NULL,
  FOREIGN KEY (number) REFERENCES flights(number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //outgoings
  $sql = "CREATE TABLE IF NOT EXISTS `outgoings` (
  `number` INT NOT NULL,
  `planned_departure_date` TIME NOT NULL,
  FOREIGN KEY (number) REFERENCES flights(number)
    ON DELETE CASCADE
    ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //arrivals
  //
  //NOTE: CHECK constraints in MYSQL are parsed but ignored by all storage engines.
  //The status here is compared against 'Arrived at ##:##:##', 'Cancelled', etc. 
  //For MYSQL, an alternative would be to use a trigger that compares the status against the regualar expressions.
  //For the sake of this assignment, I believe this is enough, as confirmed with Dr. Thomo.
  $sql = 'CREATE TABLE IF NOT EXISTS `arrivals` (
  `number` INT NOT NULL,
  `gate` INT NOT NULL,
  `arrival_date` DATE NOT NULL,
  `arrival_status` VARCHAR(128) NOT NULL,
  PRIMARY KEY (number, arrival_date, gate),
  FOREIGN KEY (number) REFERENCES flights(number)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT chk_status CHECK ( 
                               (arrival_status REGEXP "^[[:<:]]Arrived at[[:>:]][:space:][0-9]{2}[:][0-9]{2}[:][0-9]{2}$" ) OR
                               (arrival_status REGEXP "^[[:<:]]Delayed to[[:>:]][:space:][0-9]{2}[:][0-9]{2}[:][0-9]{2}$" ) OR
                               (arrival_status REGEXP "^[[:<:]]Arriving at[[:>:]][:space:][0-9]{2}[:][0-9]{2}[:][0-9]{2}$" ) OR
                               (arrival_status REGEXP "^[[:<:]]Cancelled[[:>:]]$" ) 
                              )
  ) ENGINE=InnoDB;';
  array_push($create_tables, $sql);

  //departures
  $sql = 'CREATE TABLE IF NOT EXISTS `departures` (
  `number` INT NOT NULL,
  `gate` INT NOT NULL,
  `departure_date` DATE NOT NULL,
  `arrival_status` VARCHAR(128) NOT NULL,
  PRIMARY KEY (number, departure_date, gate),
  FOREIGN KEY (number) REFERENCES flights(number)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT chk_status CHECK ( 
                               (departure_status REGEXP "^[[:<:]]Departed at[[:>:]][:space:][0-9]{2}[:][0-9]{2}[:][0-9]{2}$" ) OR
                               (departure_status REGEXP "^[[:<:]]Delayed to[[:>:]][:space:][0-9]{2}[:][0-9]{2}[:][0-9]{2}$" ) OR
                               (departure_status REGEXP "^[[:<:]]Departying at[[:>:]][:space:][0-9]{2}[:][0-9]{2}[:][0-9]{2}$" ) OR
                               (departure_status REGEXP "^[[:<:]]Cancelled[[:>:]]$" ) 
                              )
  ) ENGINE=InnoDB;';
  array_push($create_tables, $sql);

  /**********/

  //classes, keeping class name in here. it's redundant but easier for searches.
  $sql = "CREATE TABLE IF NOT EXISTS `classes` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `class` VARCHAR(28)
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //first class
  $sql = "CREATE TABLE IF NOT EXISTS `first_class` (
    `id` INT,
    `cocktail` VARCHAR(128),
    FOREIGN KEY (id) REFERENCES classes(id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //regular class
  $sql = "CREATE TABLE IF NOT EXISTS `regular_class` (
    `id` INT NOT NULL,
    `coupons` INT,
    FOREIGN KEY (id) REFERENCES classes(id)
      ON DELETE CASCADE 
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //special class
  $sql = "CREATE TABLE IF NOT EXISTS `special_class` (
    `id` INT NOT NULL,
    `info` TEXT,
    FOREIGN KEY (id) REFERENCES classes(id)
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //infants class 
  $sql = "CREATE TABLE IF NOT EXISTS `infants_class` (
    `id` INT NOT NULL,
    `baby_chair` INT DEFAULT 0,
    FOREIGN KEY (id) REFERENCES classes(id)
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //passengers
  //I added to the requirements here. A passenger will be associated with a class on a per flight basis.
  $sql = "CREATE TABLE IF NOT EXISTS `passengers` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `last_name` VARCHAR(128) NOT NULL,
    `first_name` VARCHAR(128) NOT NULL,
    `date_of_birth` DATE NOT NULL,
    `place_of_birth` VARCHAR(128) NOT NULL,
    `citizenship` VARCHAR(128) NOT NULL
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //bags
  $sql = "CREATE TABLE IF NOT EXISTS `bags` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `owner` INT NOT NULL,
    FOREIGN KEY (owner) REFERENCES passengers(id)
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  /**********/

  //arrival bags
  $sql = "CREATE TABLE IF NOT EXISTS `arrivals_bags` (
    `bid` INT NOT NULL,
    `number` INT NOT NULL,
    `date` DATE NOT NULL,
    `gate` INT NOT NULL,
    FOREIGN KEY (bid) REFERENCES bags(id)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    FOREIGN KEY (number, date, gate) REFERENCES arrivals(number, arrival_date, gate)
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //departure bags
  $sql = "CREATE TABLE IF NOT EXISTS `departures_bags` (
    `bid` INT NOT NULL,
    `number` INT NOT NULL,
    `date` DATE NOT NULL,
    `gate` INT NOT NULL,
    FOREIGN KEY (bid) REFERENCES bags(id)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    FOREIGN KEY (number, date, gate) REFERENCES departures(number, departure_date, gate)
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //arrival tickets
  $sql = "CREATE TABLE IF NOT EXISTS `arrival_tickets` (
    `number` INT NOT NULL,
    `date` DATE NOT NULL,
    `gate` INT NOT NULL,
    `class_id` INT NOT NULL,
    `pid` INT NOT NULL,
    PRIMARY KEY (number, date, gate, pid),
    FOREIGN KEY (number, date, gate) REFERENCES arrivals(number, arrival_date, gate)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (pid) REFERENCES passengers(id)
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //departure tickets
  $sql = "CREATE TABLE IF NOT EXISTS `departure_tickets` (
    `number` INT NOT NULL,
    `date` DATE NOT NULL,
    `gate` INT NOT NULL,
    `class_id` INT NOT NULL,
    `pid` INT NOT NULL,
    PRIMARY KEY (number, date, gate, pid),
    FOREIGN KEY (number, date, gate) REFERENCES departures(number, departure_date, gate)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (pid) REFERENCES passengers(id)
  ) ENGINE=InnoDB;";
  array_push($create_tables, $sql);

  //make this a single transaction, in case something fails.
  $mysqli->autocommit(FALSE);

  foreach( $create_tables as $table ) {
    if( $result = $mysqli->query($table) ) {
      printf( "Table created.\n" );
    } else {
      printf( "Table creation failed: %s\n", $mysqli->error );
      $mysqli->query("DROP DATABASE csc370tp");
      exit();
    }
  }

  $mysqli->commit();
  $mysqli->close();
?>
