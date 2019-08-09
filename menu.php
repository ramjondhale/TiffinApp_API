<?php 
    class Menu{
        private $id;
        private $day;
        private $bhaji;
        private $sbhaji;
        private $dal;
        private $rice;
        private $chapati;
        private $extras;
        private $tableName = 'menu';
        private $dbConn;

        function setId($id) { $this->id = $id; }
        function getId() { return $this->id; }
        function setDay($day) { $this->day = $day; }
        function getDay() { return $this->day; }
        function setBhaji($bhaji) { $this->bhaji = $bhaji; }
        function getBhaji() { return $this->bhaji; }
        function setSbhaji($sbhaji) { $this->sbhaji = $sbhaji; }
        function getSbhaji() { return $this->sbhaji; }
        function setDal($dal) { $this->dal = $dal; }
        function getDal() { return $this->dal; }
        function setRice($rice) { $this->rice = $rice; }
        function getRice() { return $this->rice; }
        function setChapati($chapati) { $this->chapati = $chapati; }
        function getChapati() { return $this->chapati; }
        function setExtras($extras) { $this->extras = $extras; }
        function getExtras() { return $this->extras; }

        public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
        }
        
        public function getMenu() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $menu;
        }
        
        public function insert() {			
            $sql = 'INSERT INTO ' . $this->tableName . '(id, day, bhaji, sbhaji, dal, rice, chapati, extras) 
            VALUES(null, :day, :bhaji, :sbhaji, :dal, :rice, :chapati, :extras)';   
			$stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':day', $this->day);
            $stmt->bindParam(':bhaji', $this->bhaji);
            $stmt->bindParam(':sbhaji', $this->sbhaji);
            $stmt->bindParam(':dal', $this->dal);
            $stmt->bindParam(':rice', $this->rice);
            $stmt->bindParam(':chapati', $this->chapati);
            $stmt->bindParam(':extras', $this->extras);			   
			if($stmt->execute()) {
				return true;
		    } else {
				return false;
			}         
        }
        
        public function delete() {
            $stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :id');
            $stmt->bindParam(':id', $this->id);                
            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

    }
?>