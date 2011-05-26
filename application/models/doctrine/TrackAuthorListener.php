<?php
/**
 * Listener for the TrackAuthor behavior which automatically sets the created by
 * and updatd by columns when a record is inserted and updated.
 *
 * @author      John Kramlich <me@johnkramlich.com>
 */
class TrackAuthorListener extends Doctrine_Record_Listener
{
    /**
     * Array of options
     *
     * @var string
     */
    protected $_options = array();

    /**
     * __construct
     *
     * @param string $options 
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Set the created by and updated by columns when a record is inserted
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preInsert(Doctrine_Event $event)
    {
        if ( ! $this->_options['created']['disabled']) {
            $createdName = $event->getInvoker()->getTable()->getFieldName($this->_options['created']['name']);
            $modified = $event->getInvoker()->getModified();
            if ( ! isset($modified[$createdName])) {
                $event->getInvoker()->$createdName = ($this->getUser() != null) ? $this->getUser() : new Doctrine_Null();
            }
        }

        if ( ! $this->_options['updated']['disabled'] && $this->_options['updated']['onInsert']) {
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
            $modified = $event->getInvoker()->getModified();
            if ( ! isset($modified[$updatedName])) {
                $event->getInvoker()->$updatedName = ($this->getUser() != null) ? $this->getUser() : new Doctrine_Null();
            }
        }
    }

    /**
     * Set updated by column when a record is updated
     *
     * @param Doctrine_Event $evet
     * @return void
     */
    public function preUpdate(Doctrine_Event $event)
    {
        if ( ! $this->_options['updated']['disabled']) {
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
            $modified = $event->getInvoker()->getModified();
            if ( ! isset($modified[$updatedName])) {
                $event->getInvoker()->$updatedName = ($this->getUser() != null) ? $this->getUser() : new Doctrine_Null();
            }
        }
    }

    /**
     * Set the updated field for dql update queries
     *
     * @param Doctrine_Event $evet
     * @return void
     */
    public function preDqlUpdate(Doctrine_Event $event)
    {
        if ( ! $this->_options['updated']['disabled']) {
            $params = $event->getParams();
            $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
            $field = $params['alias'] . '.' . $updatedName;
            $query = $event->getQuery();

            if ( ! $query->contains($field)) {
                
                if($this->getUser() != null){
                    $value = $this->getUser();
                } else {
                    $value = new Doctrine_Null();
                }
                
                $query->set($field, '?', $value);
            }
        }
    }

    /**
     * Gets the logged in user
     *
     * @return void
     */
    public function getUser()
    {     
        $ci = null; // The CodeIgniter global variable
        $user_id = null;
        
        $ci = & get_instance(); // Get the CodeIgniter instance
        
        return $ci->session->userdata('user_id');
        
    }
}