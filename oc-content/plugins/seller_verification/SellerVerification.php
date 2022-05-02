<?php
    class SellerVerification extends DAO
    {
      
        private static $instance ;

       
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        
        function __construct()
        {
            parent::__construct();
        }
        
        public function getTable_Plugin()
        {
            return DB_TABLE_PREFIX.'t_seller_verification' ;
        }
        
/**
* Do not touch this one
*/
 public function import($file)
        {
            $path = osc_plugin_resource($file) ;
            $sql = file_get_contents($path);

            if(! $this->dao->importSQL($sql) ){
                throw new Exception( "Error importSQL::SellerVerification<br>".$file ) ;
            }
        }
/**
* Do not touch this one
*/
    public function uninstall()
        {
          $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_Plugin()) ) ;
            
        }
 
/**
* Do not touch this one IF 
 * fk_i_user_id  IS THE SAME  name as IN struct.sql
*/
   public function deleteItem($userId)
        {
        }
     
/**
* Do not touch this one IF 
 * fk_i_user_id  IS THE SAME  name as IN struct.sql
*/
   public function getSellerVerificationAttr($userId)
        {
       if(!is_numeric($userId)){
           return false;
       }
            $this->dao->select();
            $this->dao->from($this->getTable_Plugin());
            $this->dao->where('fk_i_user_id', $userId);
            
            $result = $this->dao->get();
            if( !$result ) {
                return array() ;
            }
            return $result->row();
        }
        
  
/**
* Do not touch this one IF 
 * fk_i_user_id  IS THE SAME  name as IN struct.sql
*/
  public function insertSellerVerificationAttr( $arrayInsert, $userId )
        {
      if(!is_numeric($userId)){
           return false;
       }
            $aSet = $this->toArrayInsert($arrayInsert);
            $aSet['fk_i_user_id'] = $userId;
            
            return $this->dao->insert($this->getTable_Plugin(), $aSet);
        }

    public function insertValue($seller_verification, $seller_description, $userID)    {
        $args = array(
            'b_seller_verification' => $seller_verification,
            's_seller_description' => $seller_description,
            'fk_i_user_id' => $userID
        );
        return $this->dao->insert($this->getTable_Plugin(), $args);
    }  

    public function updateAttr($seller_verification, $seller_description, $userID)
        {
            $aSet = array(
                'b_seller_verification' => $seller_verification,
                's_seller_description' => $seller_description
                
            );
            
            $aWhere = array( 'fk_i_user_id' => $userID);
            
            return $this->_update($this->getTable_Plugin(), $aSet, $aWhere);
        } 
        
/**
* Do not touch this one IF 
 * fk_i_user_id  IS THE SAME  name as IN struct.sql
*/
        public function updateSellerVerificationAttr( $arrayUpdate, $userId )
        {
             if(!is_numeric($userId)){
           return false;
       }
            $aUpdate = $this->toArrayInsert($arrayUpdate) ;
            return $this->_update( $this->getTable_Plugin(), $aUpdate, array('fk_i_user_id' => $userId));
        }
        
/**
* Here u must play ... 
 * fields are sended by index.php 
 * 
 * _getSelerVerificationParameters... 
 * 
 * must match exactly  name and values
 * 
 * 
 * 
 * 
 * 
 * 
 * 
*/
        private function toArrayInsert( $arrayInsert )
        {
            $array = array(  
                'b_seller_verification'      =>  $arrayInsert['seller_verification'],
                's_seller_description'      =>  $arrayInsert['seller_description']
               
                
            );
        
         return $array;
        }
        
        // not to touch
        function _update($table, $values, $where)
        {
            $this->dao->from($table) ;
            $this->dao->set($values) ;
            $this->dao->where($where) ;
            return $this->dao->update() ;
        }
        
        

        
        
    }
?>