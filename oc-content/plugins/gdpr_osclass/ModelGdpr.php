<?php 

class ModelGdpr extends DAO {

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

    public function findItemsByUserID($userId)
    {
        $this->dao->select('l.*, i.*');
        $this->dao->from(DB_TABLE_PREFIX.'t_item i, '.DB_TABLE_PREFIX.'t_item_location l');
        $this->dao->where('l.fk_i_item_id = i.pk_i_id');
        $array_where = array(
            'i.fk_i_user_id' => $userId
        );
        $this->dao->where($array_where);
        $this->dao->orderBy('i.pk_i_id', 'DESC');

        $result = $this->dao->get();
        if($result == false) {
            return array();
        }
        $items  = $result->result();

        return Item::newInstance()->extendData($items);
    }

    function findAlertsByUser($userId)
    {
        $this->dao->select();
        $this->dao->from(DB_TABLE_PREFIX.'t_alerts' );
        $this->dao->where('fk_i_user_id', $userId);
        $result = $this->dao->get();

        if($result == false) {
            return array();
        }
        return $result->result();
    }
}