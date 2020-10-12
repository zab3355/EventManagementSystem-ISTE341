<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: MySQLDatabase.php
    function: Database connection and functions
*/

    class MySQLDatabase{
        // connect to the database
        public function connect(){
            $server = "---";
            $username = "---";
            $password = "----!";
            $dbname = "---";
            return new mysqli($server, $username, $password, $dbname);
        }

        //Logging in a user via username and password 
        public function login($username, $password){
            $conn = $this->connect();
            $query = "SELECT * FROM attendee WHERE name = ? AND password = ?;";
            $stmt = $conn->prepare($query);
            $password = hash("sha256", $password);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $response = $stmt->get_result()->fetch_assoc();
            if($response == null){
                $stmt->close();
                $conn->close();
                return -1;
            }
            else{
                // Set user's role as Admin, Manager or Attendee: userRole
                $role = $response['role'];
                if($role == 1){
                    $_SESSION['userRole'] = "admin";
                }
                else if($role == 2){
                    $_SESSION['userRole'] = "manager";
                }
                else{
                    $_SESSION['userRole'] = "attendee";
                }
                // Set the session name to the user's ID.
                $_SESSION['username'] = $response['idattendee'];
            }
            //redirect to registrations page
            header("Location: http://serenity.ist.rit.edu/~zab5957/341/project1/registrations.php");
            $stmt->close();
            $conn->close();
        }

        
        //Get information on events based on manager
        public function getInfo(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT event FROM manager_event WHERE manager = '" . $_SESSION["username"] . "';";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $query2 = "SELECT * FROM event WHERE idevent = " . $row . ";";
                $result2 = $conn->query($query2);
                while($row2 = $result2->fetch_assoc()){
                    $array[] = $row2;
                }
            }
            $conn->close();
            return $array;
        }
        
        //Function to insert an event into the database with its specific field entries from form with result
        public function insertEvent($name, $dateStart, $dateEnd, $numberAllowed, $venue, $manager){
            $conn = $this->connect();
            $query = "INSERT INTO event SET name = ?, datestart = ?, dateend = ?, numberallowed = ?, venue = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssii", $name, $dateStart, $dateEnd, $numberAllowed, $venue);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $eventId = $conn->insert_id;
                $query = "INSERT INTO manager_event SET event = ?, manager = ?;";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $eventId, $manager);
                if(!$stmt->execute()){
                    $response = -1;
                }
                else{
                    $response = 1;
                }
            }
            $stmt->close();
            $conn->close();
            return $response;
        }

        
        //Function to set user to attend an event
        public function attendEvent($event, $attendee){
            $conn = $this->connect();
            $query = "INSERT INTO attendee_event SET event = ?, attendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $event, $attendee);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        //Function to set user to attend a session
        public function attendSession($session, $attendee){
            $conn = $this->connect();
            $query = "INSERT INTO attendee_session SET session = ?, attendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $session, $attendee);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        //Function to insert a session for an event and update the database
        public function insertSession($name, $id, $dateStart, $dateEnd, $numberAllowed){
            $conn = $this->connect();
            $query = "INSERT INTO session SET name = ?, numberallowed = ?, event = ?, startdate = ?, enddate = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("siiss", $name, $numberAllowed, $id, $dateStart, $dateEnd);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        //Function to insert a new user into the database
        public function insertUser($name, $password, $role){
            $conn = $this->connect();
            $query = "INSERT INTO attendee SET name = ?, password = ?, role = ?;";
            $password = hash("sha256", $password);
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $name, $password, $role);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        // Function to insert new venue into the database with the name of the venue and num of people allowed
        public function insertVenue($name, $capacity){
            $conn = $this->connect();
            $query = "INSERT INTO venue SET name = ?, capacity = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $name, $capacity);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        // Function to delete an item from a specific table by ID (attendee_event or attendee_session)
        public function delete($table, $id, $idName){
            $conn = $this->connect();
            $query = "DELETE FROM " . $table . " WHERE " . $idName . " = ? AND attendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $id, $_SESSION["username"]);
            if($stmt->execute()){
                $stmt->close();
                $conn->close();
                return 1;
            }
            else{
                $stmt->close();
                $conn->close();
                return -1;
            }
        }
        
        //Function to delete an item from a specific table (Admins only and no ID necessary)
        public function adminDelete($table, $id, $idName){
            $conn = $this->connect();
            $query = "DELETE FROM " . $table . " WHERE " . $idName . " = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->close();
                $conn->close();
                return 1;
            }
            else{
                $stmt->close();
                $conn->close();
                return -1;
            }
        }
        
        // Retrieve user information from all users and output as array of data for each user
        public function getAttendees(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM attendee;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }
        
        // Get specific user information in an array
        public function getAttendee($id){
            $conn = $this->connect();
            $query = "SELECT * FROM attendee WHERE idattendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
                
        // Get all venue informartion in an array
        public function getVenues(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM venue;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }
        
        // Get specific venue information within a array
        public function getVenue($id){
            $conn = $this->connect();
            $query = "SELECT * FROM venue WHERE idvenue = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        // Get all event information in an array
        public function getEvents(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM event;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }    
        
        // Get all events that were created (Admin or Manager user role only)
        public function getManagerEvents(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT event FROM manager_event WHERE manager = " . $_SESSION["username"] . ";";
            if($result = $conn->query($query)){
                while(($row = $result->fetch_assoc()) && !($result == false)){
                    $query2 = "SELECT * FROM event WHERE idevent = " . $row['event'] . ";";
                    $result2 = $conn->query($query2);
                    while($row2 = $result2->fetch_assoc()){
                        $array[] = $row2;
                    }
                }
            }
            $conn->close();
            return $array; 
        }
        
        // Get specific event information, with event ID and array of data about event
        public function getEvent($id){
            $conn = $this->connect();
            $query = "SELECT * FROM event WHERE idevent = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        
        // Get all session information and return as an array
        public function getSessions(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM session;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }
        
        // Get specific session information and return as an array
        public function getSession($id){
            $conn = $this->connect();
            $query = "SELECT * FROM session WHERE idsession = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        // Get specific session information by event as array
        public function getSessionByEvent($id){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM session WHERE event = " . $id . ";";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }

        // Update user table information with form parameters
        public function adminEditUser($name, $role, $id){
            $conn = $this->connect();
            $query = "UPDATE attendee SET name = ?, role = ? WHERE idattendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sii", $name, $role, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        
        // Update event table information with form parameters
        public function editEvent($name, $dateStart, $dateEnd, $numberAllowed, $venue, $id){
            $conn = $this->connect();
            $query = "UPDATE event SET name = ?, datestart = ?, dateend = ?, numberallowed = ?, venue = ? WHERE idevent = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssiii", $name, $dateStart, $dateEnd, $numberAllowed, $venue, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        
        // Update session table information with form parameters
        public function editSession($name, $dateStart, $dateEnd, $numberAllowed, $event, $id){
            $conn = $this->connect();
            $query = "UPDATE session SET name = ?, startdate = ?, enddate = ?, numberallowed = ?, event = ? WHERE idsession = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssiii", $name, $dateStart, $dateEnd, $numberAllowed, $event, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        
        // Update venue table information with form parameters
        public function editVenue($name, $capacity, $id){
            $conn = $this->connect();
            $query = "UPDATE venue SET name = ?, capacity = ? WHERE idvenue = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sii", $name, $capacity, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }

        // Get registered events from specific users, array to show session info
        public function getRegisteredEvents($user){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT event FROM attendee_event WHERE attendee = " . $user . ";";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $query2 = "SELECT e.*, v.name FROM event as e LEFT JOIN venue as v ON v.idvenue = e.venue WHERE e.idevent = " . $row["event"] . ";";
                $result2 = $conn->query($query2);
                while($row2 = $result2->fetch_assoc()){
                    $array[] = $row2;
                }
            }
            $conn->close();
            return $array;
        }

        // Get registered sessions from specifc users, array shows session info
        public function getRegisteredSessions($user){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT session FROM attendee_session WHERE attendee = " . $user . ";";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $query2 = "SELECT s.*, e.name FROM session as s LEFT JOIN event as e ON e.idevent = s.event WHERE s.idsession = " . $row["session"] . ";";
                $result2 = $conn->query($query2);
                while($row2 = $result2->fetch_assoc()){
                    $array[] = $row2;
                }
            }
            $conn->close();
            return $array;  
        }
    }
?>
