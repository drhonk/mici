<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('User', 'default');

/**
 * BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property enum $role
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $display_name
 * @property boolean $activated
 * @property boolean $banned
 * @property string $ban_reason
 * @property string $new_password_key
 * @property timestamp $new_password_requested
 * @property string $new_email
 * @property string $new_email_key
 * @property Doctrine_Collection $Apikey
 * @property Doctrine_Collection $Autologin
 * @property Doctrine_Collection $UserProfile
 * @property Doctrine_Collection $Access
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 10, array(
             'primary' => true,
             'unsigned' => true,
             'type' => 'integer',
             'autoincrement' => true,
             'length' => '10',
             ));
        $this->hasColumn('role', 'enum', 10, array(
             'default' => 'client',
             'type' => 'enum',
             'notnull' => true,
             'values' => 
             array(
              0 => 'super_admin',
              1 => 'admin',
              2 => 'employee',
              3 => 'contractor',
              4 => 'client',
             ),
             'length' => '10',
             ));
        $this->hasColumn('username', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('password', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('email', 'string', 100, array(
             'type' => 'string',
             'email' => true,
             'length' => '100',
             ));
        $this->hasColumn('display_name', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('activated', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('banned', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('ban_reason', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('new_password_key', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('new_password_requested', 'timestamp', 25, array(
             'type' => 'timestamp',
             'length' => '25',
             ));
        $this->hasColumn('new_email', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
        $this->hasColumn('new_email_key', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));


        $this->index('username_index', array(
             'fields' => 
             array(
              0 => 'username',
             ),
             'type' => 'unique',
             ));
        $this->index('email_index', array(
             'fields' => 
             array(
              0 => 'email',
             ),
             'type' => 'unique',
             ));
        $this->index('activated_index', array(
             'fields' => 
             array(
              0 => 'activated',
             ),
             ));
        $this->index('banned_index', array(
             'fields' => 
             array(
              0 => 'banned',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Apikey', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Autologin', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('UserProfile', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Access', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $versionable0 = new Doctrine_Template_Versionable();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($versionable0);
        $this->actAs($timestampable0);
    }
}