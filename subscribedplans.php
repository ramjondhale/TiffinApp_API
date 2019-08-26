<?php 
    class SubscribedPlans{
        private $id;
        private $cust_id;
        private $subscription_id;
        private $start_date;        
        private $remaining_tiffin;       
        private $payment_id;
        
        private $tableName = 'subscribed_plans';
        private $dbConn;

        function setId($id) { $this->id = $id; }
        function getId() { return $this->id; }
        function setCust_id($cust_id) { $this->cust_id = $cust_id; }
        function getCust_id() { return $this->cust_id; }
        function setSubscription_id($subscription_id) { $this->subscription_id = $subscription_id; }
        function getSubscription_id() { return $this->subscription_id; }
        function setStart_date($start_date) { $this->start_date = $start_date; }
        function getStart_date() { return $this->start_date; }
        function setRemaining_tiffin($remaining_tiffin) { $this->remaining_tiffin = $remaining_tiffin; }
        function getRemaining_tiffin() { return $this->remaining_tiffin; }
        function setPayment_id($payment_id) { $this->payment_id = $payment_id; }
        function getPayment_id() { return $this->payment_id; }
        

        public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
        }
        
        public function getSubscribedPlans() {
			$stmt = $this->dbConn->prepare("SELECT s.*,sd.remaining_tiffin FROM subscriptions s,subscribed_plans sd 
            WHERE s.id=sd.subscription_id AND sd.cust_id=:cust_id");
            $stmt->bindParam(':cust_id', $this->cust_id);
			$stmt->execute();
			$subscribed_plans= $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $subscribed_plans;
        }
        
        public function insert() {			
            $sql = 'INSERT INTO ' . $this->tableName . '(id, cust_id, subscription_id, start_date, remaining_tiffin, payment_id) 
            VALUES(null, :cust_id, :subscription_id, :start_date, :remaining_tiffin, :payment_id)';   
			$stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':cust_id', $this->cust_id);
            $stmt->bindParam(':subscription_id', $this->subscription_id);
            $stmt->bindParam(':start_date', $this->start_date);
            $stmt->bindParam(':remaining_tiffin', $this->remaining_tiffin);            
            $stmt->bindParam(':payment_id', $this->payment_id);
           		   
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