<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('OrganizationStaff', 'default');

/**
 * BaseOrganizationStaff
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $Organization_id
 * @property integer $User_id
 * @property Organization $Organization
 * @property User $User
 * @property Doctrine_Collection $TeamMember
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseOrganizationStaff extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('organization_staff');
        $this->hasColumn('id', 'integer', 10, array(
             'primary' => true,
             'type' => 'integer',
             'autoincrement' => true,
             'length' => '10',
             ));
        $this->hasColumn('Organization_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => '10',
             ));
        $this->hasColumn('User_id', 'integer', 10, array(
             'type' => 'integer',
             'length' => '10',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Organization', array(
             'local' => 'Organization_id',
             'foreign' => 'id'));

        $this->hasOne('User', array(
             'local' => 'User_id',
             'foreign' => 'id'));

        $this->hasMany('TeamMember', array(
             'local' => 'id',
             'foreign' => 'OrganizationStaff_id'));

        $versionable0 = new Doctrine_Template_Versionable();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $taggable0 = new Taggable();
        $trackauthor0 = new TrackAuthor();
        $this->actAs($versionable0);
        $this->actAs($timestampable0);
        $this->actAs($taggable0);
        $this->actAs($trackauthor0);
    }
}