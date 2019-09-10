<?php
    class Customer {
		private $id;
		private $fname;
		private $lname;
		private $mobile;	
		private $email;			
		private $password;	
		private $active;
		private $createdOn;
		private $flat_no;
		private $landmark;
		private $building;
		private $area;
		private $tableName = 'customers';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setFname($fname) { $this->fname = $fname; }
		function getFname() { return $this->fname; }
		function setLname($lname) { $this->lname = $lname; }
		function getLname() { return $this->lname; }
		function setMobile($mobile) { $this->mobile = $mobile; }
		function getMobile() { return $this->mobile; }
		function setEmail($email) { $this->email = $email; }
		function getEmail() { return $this->email; }
		function setPassword($password) { $this->password = $password; }
		function getPassword() { return $this->password; }
		function setFlat_no($flat_no) { $this->flat_no = $flat_no; }
		function getFlat_no() { return $this->flat_no; }	
		function setLandmark($landmark) { $this->landmark = $landmark; }
		function getLandmark() { return $this->landmark; }	
		function setBuilding($building) { $this->building = $building; }
		function getBuilding() { return $this->building; }	
		function setArea($area) { $this->area = $area; }
		function getArea() { return $this->area; }		
		function setActive($active) { $this->active = $active; }
		function getActive() { return $this->active; }
		function setCreatedOn($createdOn) { $this->createdOn = $createdOn; }
		function getCreatedOn() { return $this->createdOn; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllCustomers() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $customers;
		}

		public function getCustomerDetailsById() {

			$sql = "SELECT * FROM customers WHERE id = :customerId";
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':customerId', $this->id);
			$stmt->execute();
			$customer = $stmt->fetch(PDO::FETCH_ASSOC);
			return $customer;
		}		
		

		public function insert() {
			$stmt1 = $this->dbConn->prepare("SELECT * FROM customers WHERE  email = :email OR mobile = :mobile");
			$stmt1->bindParam(":email", $this->email);   
			$stmt1->bindParam(":mobile", $this->mobile);          
            $stmt1->execute();
            $user = $stmt1->fetch(PDO::FETCH_ASSOC);
            if(!is_array($user))
            {
				$sql = 'INSERT INTO ' . $this->tableName . '(id, fname, lname, mobile, email, password, flat_no, landmark, building, area, active, created_on) VALUES(null, :fname,
				:lname, :mobile, :email, :password, :flat_no, :landmark, :building, :area, :active, :createdOn)';
   
			   $stmt = $this->dbConn->prepare($sql);
			   $stmt->bindParam(':fname', $this->fname);
			   $stmt->bindParam(':lname', $this->lname);
			   $stmt->bindParam(':mobile', $this->mobile);
			   $stmt->bindParam(':email', $this->email);
			   $stmt->bindParam(':password', $this->password);
			   $stmt->bindParam(':flat_no', $this->flat_no);
			   $stmt->bindParam(':landmark', $this->landmark);
			   $stmt->bindParam(':building', $this->flat_no);
			   $stmt->bindParam(':area', $this->area);		
			   $stmt->bindParam(':active', $this->active);
			   $stmt->bindParam(':createdOn', $this->createdOn);
			  
			   
			   if($stmt->execute()) {
				   return true;
			   } else {
				   return false;
			   }
            } else {
				$res=new Rest;
				$res->returnResponse(SUCCESS_RESPONSE,"Email or Mobile number already exist ");
			}
           
			
		}

		// public function update() {
			
		// 	$sql = "UPDATE $this->tableName SET";
		// 	if( null != $this->getName()) {
		// 		$sql .=	" name = '" . $this->getName() . "',";
		// 	}

		// 	if( null != $this->getAddress()) {
		// 		$sql .=	" address = '" . $this->getAddress() . "',";
		// 	}

		// 	if( null != $this->getMobile()) {
		// 		$sql .=	" mobile = " . $this->getMobile() . ",";
		// 	}

		// 	$sql .=	" updated_by = :updatedBy, 
		// 			  updated_on = :updatedOn
		// 			WHERE 
		// 				id = :userId";

		// 	$stmt = $this->dbConn->prepare($sql);
		// 	$stmt->bindParam(':userId', $this->id);
		// 	$stmt->bindParam(':updatedBy', $this->updatedBy);
		// 	$stmt->bindParam(':updatedOn', $this->updatedOn);
		// 	if($stmt->execute()) {
		// 		return true;
		// 	} else {
		// 		return false;
		// 	}
		// }

		// public function delete() {
		// 	$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :userId');
		// 	$stmt->bindParam(':userId', $this->id);
			
		// 	if($stmt->execute()) {
		// 		return true;
		// 	} else {
		// 		return false;
		// 	}
		// }
    }
    
?>