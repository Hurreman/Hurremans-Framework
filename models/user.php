<?php
/**
 * Class: mUser
 * Author: Fredrik Karlsson (fredrik@fkinnovation.se)
 * Created: 2013-03-17
 * Desc: Minimalistic User model example
 */
class mUser extends Model
{
    protected $id;
    protected $username;
    protected $password;
    protected $lastLogin;

    protected $tableName = 'users';

    protected $createCols = array('username' => 'text', 'password' => 'text');
    
    public function login($username, $password) {

    	$loginQuery = $this->db->createQuery('SELECT id FROM ? WHERE username = ? AND password = ? LIMIT 1');


        $loginQuery->setParameter(1, $this->tableName, true);
        $loginQuery->setparameter(2, $username);
        $loginQuery->setparameter(3, md5($password));
        $result = $loginQuery->execute();

        if($result->getRowCount()) {
            while($result->next()) {
                return $result->getField('id');
            }
        } 
        else {
            return false;
        }

    }
}
?>