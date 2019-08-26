<?php 
    class Subscriptions{
        private $id;
        private $type;
        private $title;
        private $description;
        private $price;
        private $total_tiffin;
        private $thumbnail;
        
        private $tableName = 'subscriptions';
        private $dbConn;

        function setId($id) { $this->id = $id; }
        function getId() { return $this->id; }
        function setType($type) { $this->type = $type; }
        function getType() { return $this->type; }
        function setTitle($title) { $this->title = $title; }
        function getTitle() { return $this->title; }
        function setDescription($description) { $this->description = $description; }
        function getDescription() { return $this->description; }
        function setThumbnail($thumbnail) { $this->thumbnail = $thumbnail; }
        function getThumbnail() { return $this->thumbnail; }
        function setTotal_tiffin($total_tiffin) { $this->total_tiffin = $total_tiffin; }
        function getTotal_tiffin() { return $this->total_tiffin; }
        function setPrice($price) { $this->price = $price; }
        function getPrice() { return $this->price; }
       

        public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
        }
        
        public function getSubscriptions() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $subscriptions;
        }
        
        public function insert() {			
            $sql = 'INSERT INTO ' . $this->tableName . '(id, type, title, description, thumbnail, total_tiffin, price) 
            VALUES(null, :type, :title, :description, :thumbnail, :total_tiffin, :price)';   
			$stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':thumbnail', $this->thumbnail);
            $stmt->bindParam(':total_tiffin', $this->total_tiffin);
            $stmt->bindParam(':price', $this->price);
           		   
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