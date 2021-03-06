<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Autologin', 'default');

/**
 * BaseAutologin
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property string $key_id
 * @property string $user_agent
 * @property string $last_ip
 * @property timestamp $last_login
 * @property User $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAutologin extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('autologin');
        $this->hasColumn('id', 'integer', 10, array(
             'primary' => true,
             'unsigned' => true,
             'type' => 'integer',
             'autoincrement' => true,
             'length' => '10',
             ));
        $this->hasColumn('user_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => '10',
             ));
        $this->hasColumn('key_id', 'string', 32, array(
             'unique' => true,
             'unsigned' => true,
             'type' => 'string',
             'fixed' => 1,
             'length' => '32',
             ));
        $this->hasColumn('user_agent', 'string', 150, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '150',
             ));
        $this->hasColumn('last_ip', 'string', 40, array(
             'type' => 'string',
             'length' => '40',
             ));
        $this->hasColumn('last_login', 'timestamp', 25, array(
             'type' => 'timestamp',
             'length' => '25',
             ));


        $this->index('user_id_index', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->index('user_agent_index', array(
             'fields' => 
             array(
              0 => 'user_agent',
             ),
             ));
        $this->index('last_ip_index', array(
             'fields' => 
             array(
              0 => 'last_ip',
             ),
             ));
        $this->index('last_login_index', array(
             'fields' => 
             array(
              0 => 'last_login',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}