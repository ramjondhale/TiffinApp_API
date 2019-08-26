<?php

    class Api extends Rest {
        public $dbConn;
        public function __construct()
        {
            parent::__construct();                        
        }
// *****************************************************************************************************************************************
// *****************************API FOR USERS DATA***********************************************************************
        public function generateToken() {
           $email=$this->validateParameter('email',$this->param['email'],STRING);
           $password=$this->validateParameter('password',$this->param['password'],STRING);
           try{
            $stmt = $this->dbConn->prepare("SELECT * FROM customers WHERE  email = :email AND password = :password");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!is_array($user))
            {
                $this->returnResponse(INVALID_USER_PASS,"Email or Password is incorrect.");
            }
            if($user['active']==0){
                $this->returnResponse(USER_NOT_ACTIVE,"User is not activated.Plese contact to admin.");
            }
 
            $payload=[
                'iat'=> time(),
                'iss'=> 'localhost',
                'exp'=> time() + (10000*60),
                'userId'=>$user['id']
            ];
            $token=JWT::encode($payload,SECRETE_KEY);
            $data=['token'=>$token];
            $this->returnResponse(SUCCESS_RESPONSE,$data);

           } catch(Exception $e)
           {
               $this->throwError(JWT_PROCESSING_ERROR,$e->getMessage());
           }

           
        }

        public function createAccount() {
            $fname=$this->validateParameter('fname',$this->param['fname'],STRING,false);
            $lname=$this->validateParameter('lname',$this->param['lname'],STRING,false);
            $mobile=$this->validateParameter('mobile',$this->param['mobile'],INTEGER,false);
            $email=$this->validateParameter('email',$this->param['email'],STRING,false);
            $password=$this->validateParameter('password',$this->param['password'],STRING,false);
            $paddress=$this->validateParameter('paddress',$this->param['paddress'],STRING,false);

            
                $cust = new Customer;
                $cust->setFname($fname);
                $cust->setLname($lname);
                $cust->setMobile($mobile);
                $cust->setEmail($email);
                $cust->setPassword($password);
                $cust->setPaddress($paddress);   
                $cust->setActive(1);   
                $cust->setCreatedOn(date('d-m-Y'));
                if(!$cust->insert()) {
                    $this->throwError(ERROR, 'Failed to insert.');
                }else{
                    $this->returnResponse(SUCCESS_RESPONSE, 'Inserted successfuly.');                    
                }               
    
        }
    
        public function getCustomerById() {
            $customerId=$this->validateParameter('customerId',$this->param['customerId'],INTEGER);

            $cust =new Customer;
            $cust->setId($customerId);
            $customer=$cust->getCustomerDetailsById();
            if(!is_array($customer)) {
                $this->returnResponse(SUCCESS_RESPONSE,['message'=>'Customer Details not found.']);
            }             
            $this->returnResponse(SUCCESS_RESPONSE,$customer);    
        }

        public function getUserDetails() {
            $cust =new Customer;
            $cust->setId($this->userId);
            $customer=$cust->getCustomerDetailsById();
            if(!is_array($customer)) {
                $this->returnResponse(SUCCESS_RESPONSE,['message'=>'Customer Details not found.']);
            }             
            $this->returnResponse(SUCCESS_RESPONSE,$customer);    
        }
        


        // public function updateCustomer() {
        //     $customerId=$this->validateParameter('customerId',$this->param['customerId'],INTEGER);
        //     $name=$this->validateParameter('name',$this->param['name'],STRING,false);            
        //     $addr=$this->validateParameter('addr',$this->param['addr'],STRING,false);
        //     $mobile=$this->validateParameter('mobile',$this->param['mobile'],INTEGER,false);

            
        //         $cust = new Customer;
        //         $cust->setId($customerId);
        //         $cust->setName($name);                
        //         $cust-> setAddress($addr);
        //         $cust-> setMobile($mobile);
        //         $cust-> setUpdatedBy($this->userId);
        //         $cust-> setUpdatedOn(date('Y-m-d'));
        //         if(!$cust->update()) {
        //             $message='Failed to update.';
        //         }else{
        //             $message='Updated successfuly.';                    
        //         }

        //         $this->returnResponse(SUCCESS_RESPONSE,$message);
    
        // }

        // public function deleteCustomer(){
        //     $customerId=$this->validateParameter('customerId',$this->param['customerId'],INTEGER);

        //     $cust =new Customer;
        //     $cust->setId($customerId);
           
        //     if(!$cust->delete()) {
        //         $message ='Failed to delete';
        //     } else {
        //         $message ='Deleted  Successfully';
        //     }
        //     $this->returnResponse(SUCCESS_RESPONSE,$message);
        // }
// *****************************************************************************************************************************************
// *****************************API FOR Features DATA***********************************************************************
        public function getAllFeatures() {
            $feat= new Features;
            $features= $feat->getFeatures();
            if(!is_array($features)) {
                $this->returnResponse(SUCCESS_RESPONSE,['message'=>'Features not found']);
            }           
            $this->returnResponse(SUCCESS_RESPONSE,$features); 


        }

        public function addFeature() {
            $feature=$this->validateParameter('feature',$this->param['feature'],STRING);

            $feat = new Features;
            $feat->setFeature($feature);

            if(!$feat->insert()) {
                $this->throwError(ERROR, 'Failed to insert feature.');
            }else{
                $this->returnResponse(SUCCESS_RESPONSE, 'feature inserted successfuly.');                    
            }
        }

        public function deleteFeature() {
            $featureId=$this->validateParameter('featureId',$this->param['featureId'],INTEGER);

            $feat =new Features;
            $feat->setId($featureId);
            
            if(!$feat->delete()) {
                $message ='Failed to delete feature';
            } else {
                $message ='Feature deleted Successfully';
            }
            $this->returnResponse(SUCCESS_RESPONSE,$message);
        }


 // *****************************API FOR Features DATA***********************************************************************
        public function getMenu() {
            $men= new Menu;
            $menu= $men->getMenu();
            if(!is_array($menu)) {
                $this->returnResponse(SUCCESS_RESPONSE,['message'=>'Menu not found']);
            }           
            $this->returnResponse(SUCCESS_RESPONSE,$menu); 


        }

        public function addMenu() {
            $day=$this->validateParameter('day',$this->param['day'],STRING);
            $bhaji=$this->validateParameter('bhaji',$this->param['bhaji'],STRING);
            $sbhaji=$this->validateParameter('sbhaji',$this->param['sbhaji'],STRING);
            $dal=$this->validateParameter('dal',$this->param['dal'],STRING);
            $rice=$this->validateParameter('rice',$this->param['rice'],STRING);
            $chapati=$this->validateParameter('chapati',$this->param['chapati'],STRING);
            $extras=$this->validateParameter('extras',$this->param['extras'],STRING);
           
            $men = new Menu;
            $men->setDay($day);
            $men->setBhaji($bhaji);
            $men->setSbhaji($sbhaji);
            $men->setDal($dal);
            $men->setRice($rice);
            $men->setChapati($chapati);
            $men->setExtras($extras);

            if(!$men->insert()) {
                $this->throwError(ERROR, 'Failed to insert menu.');
            }else{
                $this->returnResponse(SUCCESS_RESPONSE, 'Menu inserted successfuly.');                    
            }
        }

        public function deleteMenu() {
            $menuId=$this->validateParameter('menuId',$this->param['menuId'],INTEGER);

            $men =new Menu;
            $men->setId($menuId);
            
            if(!$men->delete()) {
                $message ='Failed to delete menu';
            } else {
                $message ='Menu deleted  Successfully';
            }
            $this->returnResponse(SUCCESS_RESPONSE,$message);
        }
 // *****************************API For Subscriptions Data***********************************************************************
        public function getSubscriptions() {
            $sub= new Subscriptions;
            $subscriptions= $sub->getSubscriptions();
            if(!is_array($subscriptions)) {
                $this->returnResponse(SUCCESS_RESPONSE,['message'=>'Subscriptions plan not found']);
            }           
            $this->returnResponse(SUCCESS_RESPONSE,$subscriptions); 

        }

        public function addSubscription() {
            $type=$this->validateParameter('type',$this->param['type'],STRING);
            $title=$this->validateParameter('title',$this->param['title'],STRING);
            $description=$this->validateParameter('description',$this->param['description'],STRING);
            $thumbnail=$this->validateParameter('thumbnail',$this->param['thumbnail'],STRING);
            $total_tiffin=$this->validateParameter('total_tiffin',$this->param['total_tiffin'],INTEGER);
            $price=$this->validateParameter('price',$this->param['price'],INTEGER);           
        
            $sub = new Subscriptions;
            $sub->setType($type);
            $sub->setTitle($title);
            $sub->setDescription($description);
            $sub->setThumbnail($thumbnail);
            $sub->setTotal_tiffin($total_tiffin);
            $sub->setPrice($price);                     

            if(!$sub->insert()) {
                $this->throwError(ERROR, 'Failed to insert Subscription Plan.');
            }else{
                $this->returnResponse(SUCCESS_RESPONSE, 'Subscription inserted successfuly.');                    
            }
        }

        public function deleteSubscription() {
            $subId=$this->validateParameter('subId',$this->param['subId'],INTEGER);

            $sub =new Subscriptions;
            $sub->setId($subId);
            
            if(!$sub->delete()) {
                $message ='Failed to delete Subscription';
            } else {
                $message ='Subscription deleted  Successfully';
            }
            $this->returnResponse(SUCCESS_RESPONSE,$message);
        }
 // *****************************API For Subscriptions Data***********************************************************************

        public function getSubscribedPlans() {
            $sub= new SubscribedPlans;
            $sub->setCust_id($this->userId);
            $subscribed_plans= $sub->getSubscribedPlans();
            if(!is_array($subscribed_plans)) {
                $this->returnResponse(SUCCESS_RESPONSE,['message'=>'No plans Subscribed!']);
            }           
            $this->returnResponse(SUCCESS_RESPONSE,$subscribed_plans); 

        }

        public function addSubscribedPlan() {
            $cust_id=$this->validateParameter('cust_id',$this->param['cust_id'],INTEGER);
            $subscription_id=$this->validateParameter('subscription_id',$this->param['subscription_id'],INTEGER);
            $start_date=$this->validateParameter('start_date',$this->param['start_date'],STRING);
            $remaining_tiffin=$this->validateParameter('remaining_tiffin',$this->param['remaining_tiffin'],INTEGER);
            $payment_id=$this->validateParameter('payment_id',$this->param['payment_id'],INTEGER);
            
            $sub = new SubscribedPlans;
            $sub->setCust_id($cust_id);
            $sub->setSubscription_id($subscription_id);
            $sub->setStart_date($start_date);
            $sub->setRemaining_tiffin($remaining_tiffin);
            $sub->setPayment_id($payment_id);                     

            if(!$sub->insert()) {
                $this->throwError(ERROR, 'Failed to insert Subscribed Plan.');
            }else{
                $this->returnResponse(SUCCESS_RESPONSE, 'Subscribed Successfully.');                    
            }
        }

        public function deleteSubscribedPlan() {
            $subId=$this->validateParameter('subId',$this->param['subId'],INTEGER);

            $sub =new SubscribedPlans;
            $sub->setId($subId);
            
            if(!$sub->delete()) {
                $message ='Failed to delete Subscribed Plan';
            } else {
                $message ='Subscription deleted  Successfully';
            }
            $this->returnResponse(SUCCESS_RESPONSE,$message);
        }
    }

?>