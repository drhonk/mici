<?php
/**
 * TrackAuthor
 *
 * Easily track the author that created 
 *
 * @author      John Kramlich <me@johnkramlich.com>
 */
class TrackAuthor extends Doctrine_Template
{
    /**
     * Array of TrackAuthor options
     *
     * @var string
     */
    protected $_options = array('created' =>  array('name'          =>  'created_by',
                                                    'alias'         =>  null,
                                                    'type'          =>  'integer',
                                                    'length'        =>  10,
                                                    'disabled'      =>  false,
                                                    'options'       =>  array('notnull' => false)),
                                'updated' =>  array('name'          =>  'updated_by',
                                                    'alias'         =>  null,
                                                    'type'          =>  'integer',
                                                    'length'        =>  10,
                                                    'disabled'      =>  false,
                                                    'expression'    =>  false,
                                                    'onInsert'      =>  true,
                                                    'options'       =>  array('notnull' => false)));

    /**
     * Set table definition for TrackAuthor behavior
     *
     * @return void
     */
    public function setTableDefinition()
    {
        if ( ! $this->_options['created']['disabled']) {
            $name = $this->_options['created']['name'];
            if ($this->_options['created']['alias']) {
                $name .= ' as ' . $this->_options['created']['alias'];
            }
            $this->hasColumn($name, $this->_options['created']['type'], null, $this->_options['created']['options']);
        }

        if ( ! $this->_options['updated']['disabled']) {
            $name = $this->_options['updated']['name'];
            if ($this->_options['updated']['alias']) {
                $name .= ' as ' . $this->_options['updated']['alias'];
            }
            $this->hasColumn($name, $this->_options['updated']['type'], null, $this->_options['updated']['options']);
        }

        $this->addListener(new TrackAuthorListener($this->_options));
    }
}