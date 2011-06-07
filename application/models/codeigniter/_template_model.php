<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category @MODEL@_model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * @todo 	To use this as a template replace:
 * 
 *			@MODEL@ = Model Name (title case)
 * 			@VAR@ = Variable Name (lower case of @MODEL@)
 * 			@ABBR@ = Doctrine Table Abbreviation (first lower case letter of @MODEL@)
 *			@SORT@ = Doctrine Table Sort Column (column name to sort with)
 *			@ORDER@ = Doctrine Table Sort Order (either ACS or DESC)
 *
 *			NOTE:  Don't use this template unless you are familiar with MICI
 */

/**
 * Begin Document
 */
class @MODEL@_model extends MI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
		try
		{
		    $@VAR@ = new @MODEL@();
		    $@VAR@->title = $data['title'];
		    $@VAR@->save();

		    return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
		    log_message('error', $e->getMessage());
		    return array(
				'error' => $e->getMessage(),
				'code' => $e->getCode()
		    );
		}
    }

    public function update($id, $data)
    {
		try
		{
		    $@VAR@ = Doctrine_Core::getTable('@MODEL@')->findOneById($id);
		    $@VAR@->title = $data['title'];
		    $@VAR@->save();

		    return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
		    log_message('error', $e->getMessage());
		    return array(
				'error' => $e->getMessage(),
				'code' => $e->getCode()
		    );
		}
    }

    public function delete($id)
    {
		try
		{
		    Doctrine_Query::create()
			    ->delete('@MODEL@')
			    ->where('id = ?', $id)
			    ->execute();

		    return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
		    log_message('error', $e->getMessage());
		    return array(
				'error' => $e->getMessage(),
				'code' => $e->getCode()
		    );
		}
    }

    public function get($id=NULL)
    {
		if ($id)
		{
		    try
		    {
				return Doctrine_Core::getTable('@MODEL@')->findOneById($id);
		    }
		    catch (Doctrine_Connection_Exception $e)
		    {
				log_message('error', $e->getMessage());
				return array(
				    'error' => $e->getMessage(),
				    'code' => $e->getCode()
				);
		    }
		}
		else
		{
		    try
		    {
				return Doctrine_Query::create()
					->from('@MODEL@ @ABBR@')
					->orderBy('@ABBR@.@SORT@ @ORDER@')
					->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		    }
		    catch (Doctrine_Connection_Exception $e)
		    {
				log_message('error', $e->getMessage());
				return array(
				    'error' => $e->getMessage(),
				    'code' => $e->getCode()
				);
		    }
		}
    }
}

/* End of file @VAR@_model.php */
/* Location: ./application/models/codeigniter/@VAR@_model.php */