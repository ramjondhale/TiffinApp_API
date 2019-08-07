<?php 
    class Features{
        private $id;
        private $feature;
        private $tableName = 'features';
        private $dbConn;

        function setId($id) { $this->id = $id; }
        function getId() { return $this->id; }
        function setFeature($feature) { $this->feature = $feature; }
        function getFeature() { return $this->feature; }

        public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
        }
        
        public function getFeatures() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$features = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $features;
        }
        
        public function insert() {			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, feature) VALUES(null, :feature)';   
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':feature', $this->feature);			   
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