<?php

/**
 * TagTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TagTable extends Doctrine_Table
{
    static public function getAllTagNames()
    {
        $q = Doctrine_Query::create()
			->select('t.name')
			->from('Tag t');
                
        return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);        
    }
}