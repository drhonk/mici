FRAMEWORK OVERVIEW
================== 

The default framework comes with the following directories. 

_docs
----- 

This is the frameworks documentation. PDF files are handy for searching 
without requiring internet connection. The HTML folder contains web 
links to the online documentation. You can delete this folder without 
any issues. 

_install
-------- 

This contains the files needed to setup the framework using the 
instructions provided below. You can delete this folder without any 
issues. 

application
----------- 

This is the core of the framework where all code should be written. This 
folder cannot be deleted. 

assets
------ 

This folder is used for any content that will be uploaded through the 
framework by external users. This folder cannot be deleted. 

media
----- 

This is where all CSS, Images and Javascript files should be contained. 
There should be a folder for each device you want to support. This 
folder cannot be deleted. 

system
------ 

This is the main CodeIgniter application. No changes should be made to 
this directory unless it is a direct upgrade to CodeIgniter itself. This 
folder cannot be deleted. 

web
--- 

This should be the only web accessible folder of the bunch. This folder 
cannot be deleted.

INSTALLING THE FRAMEWORK FOR LOCAL DEVELOPMENT 
==============================================

Before you get started, please note that, in an attempt to make things
easier, all template files have TMP_* variables that you can easily do
a find and replace with so you may want to have open all the files in the
_install directory to replace the same values in all open documents.

Here are the variables that were created, and what they are for:

	TMP_DOCROOT:    Absolute path to document root of server ( not MICI )
	TMP_FOLDER:     Absolute path to temp directory where MICI files are stored
	TMP_IPADDRESS:  Your development machines IP Address ( for remote access )
	TMP_PROJECT:    Folder name used for MICI installation ( no spaces )
	TMP_WEBSITE:    Domain name used for local Development ( no http://www. )

Step 1 - Folder Setup
---------------------

Create a directory for this project in the root of your web server. For 
the sake of this tutorial we will assume you are developing locally, and 
you have created a folder named "mici" and copied the MICI framework 
files into this folder. So you would be accessing this framework via:
 
	http://localhost/mici/web/
	
But not just yet!  You still have some setup to complete ;)
	
To make things easier on yourself, you may wish to use this name as the
project name you will use later for all custom folders you will need
to setup.

Now you will need to setup the directories where some files will get
stored.  Pick a folder that is not going to be accessible via the web
server.  You can use an existing folder that is already apart of apache,
just not one that is in the document root.

There are three folders that you will need to create manually.
These folders are already setup in the _install/template/ folder._

Once you have these folders created you will need to create a new folder
in each with the same name you used in your web root when you added
MICI.  That would be "mici" for this writeup.

The purpose of this is so you can have more than one installation of
MICI without creating any issues with others installations.

Step 2 - Apache Configuration
-----------------------------

Now you will need to add the apache configuration code. To do this just 
drop the website-framework.conf file found in the _install folder into 
your apache conf extra folder. This file contains all the code you will 
need to both access the server as a virtual host as well as remotely 
from mobile devices. Update your httpd.conf file to include the new 
file. 

You can do this by adding a single line as shown below ( updated with 
the correct path and name you used for this file ): 

	Include "conf/extra/website-framework.conf" 

There are two Environmental Variables that you will need to take notice 
of. 

FRAMEWORK_APPLICATION_ENV 

This should be either: development, staging or production. If you are 
running this locally, more than likely it should be set to development. 
If this is not defined, it will default to production. 

FRAMEWORK_CONFIG_INI 

This is the backbone of the entire framework. Make sure you set this 
correctly to the absolute path to where this file can be located. You 
will find a sample framework.ini file in the _install folder. Copy this 
ini file somewhere that is not publicly accessible on your server. You 
can rename it to whatever you want. Just make sure that your also use 
that same name in the apache path.

You will also need to replace all the TMP_* variables as discussed
earlier in this document._

Once you get everything in place, restart apache. 

Step 3 - INI Configuration
--------------------------

Now that apache is setup, it's time to configure that framework itself. 
To do this all you need to do is open the framework.ini file you copied 
in the previous step. There are only a few variables that you need to 
update to get everything up and running. If you wish to learn more about 
what these variables do, just look in the /application/config/ folder. 
The name of the PHP file is the exact same as the section in the 
framework.ini file. i.e. [config] = config.php 

The variables you need to pay special attention to are: 

	[config] 
	base_url 			( full URL to your installation ending with a / ) 
	log_threshold 		( 0 = none, 4 = verbose ) 
	log_path 			( absolute path to log directory ) 
	cache_path 			( absolute path to cache directory ) 
	sess_cookie_name	( unique if you have more than one MICI app ) 
	csrf_token_name 	( unique if you have more than one MICI app ) 
	csrf_cookie_name 	( unique if you have more than one MICI app ) 

	[database] 
	hostname 
	username 
	password 
	database 
	memcache_port 
	unix_socket				( leave this blank unless you need to set it )

	[auth] 
	website_name 			( name of the web site ) 
	webmaster_email 		( YOUR email address ) 
	autologin_cookie_name 	( unique if you have more than one MICI app ) 
	ipinfodb_apikey 		( http://www.ipinfodb.com/register.php ) 

	[floodblocker] 
	logs_path 				( absolute path to log directory ) 

	[paths] 
	media_url 				( full URL to the media directory ) 
	media_abs_path 			( absolute path to media directory ) 
	assets_url 				( full URL to the assets directory ) 
	assets_abs_path 		( absolute path to assets directory ) 

You will also need to replace all the TMP_* variables as discussed
earlier in this document._

The remote_framework.ini file is, for the most part, identical to the 
framework.ini with the exception that it is used for accessing your 
development server remotely. So there are a few URL's you will need to 
alter to point to the specific local IP address that is hosting your 
application. This will allow you to connect to your development machine
from external devices ( like and iPad, iPhone or Android Device ).
This remote INI file contains unique path and cookie info so you can
be assured your links will not break and try to redirect to something
like "localhost" or "mici.loc" which your remote device will not understand.

Please note that if you are using http://localhost/ this will use the
remote_framework.ini file and all links in Codeigniter will use your 
IP Address instead of localhost._

Step 4 
------ 

Once you have finished setting everything up, you can easily install the 
core database, tables and default fixtures by pointing your browser to: 

	http://localhost/mici/web/system/check 

You will need to adjust the above URL, or course, to where you actually 
placed your installation of this framework. This System Check will also 
tell you if there were any problem preventing a successful install and 
suggest actions to take to correct your installation. 

Step 5
------ 

There are a few optional tools enabled for you to develop easily that 
are not already built into either Doctrine of CodeIgniter (CI). 

To use the custom code profiler to profile both CI and Doctrine, use the 
following code in your CI Controller: 

	$this->output->enable_profiler(TRUE); 

FirePHP is also installed and enabled by default if your 
FRAMEWORK_APPLICATION_ENV is set to development. To use it in your 
controllers, use any of the following: 

NOTE: You can pass things like integers, strings or arrays. 

	$this->firephp->log($var); $this->firephp->warn($var); 
	$this->firephp->error($var); $this->firephp->debug($var); 

There is also a built in API with full REST support. Your API Controller 
can be located here: 

	/application/controllers/ApiController.php 

To test the GET method of this API you can use the default enabled API 
Key and point your browser to: 

	http://localhost/mici/web/api/index/apikey/00038A21-814F-1A94-A593-FECBB8BAF7F2 

If everything is working you should correctly get the notice: 

	Invalid GET Request. Refer to API Documentation. 

This is because the API controller triggered the index method in 
ApiController.php and there was nothing to do, so it showed an error. 
Read the ApiController.php for more details on how to write your own 
controller to integrate with your existing models. 

The API Keys are stored in the `apikey` table in your database. By 
default, the first key is enabled and assigned to our company. You can 
write your own code so assign API keys to other users however you wish. 

SETTING UP GEO LOCATION SERVICE
=============================== 

This framework uses http://www.ipinfodb.com to pull accurate Geo 
Location information. You will need to register with their API service 
in order to gain access to their services. You can do that here: 

http://www.ipinfodb.com/register.php Once you get an API Key from them, 
update your framework.ini files ipinfodb_apikey variable. You do not 
need to do this, but your you will lose capturing this information in 
the database if it is not setup. 

WORKING WITH SYSTEM SECTION
=========================== 

After you have completed the installation, you can access the system 
section by going to: 

	http://localhost/mici/web/system/

To login use the default administrator login info: 

	username: admin
	password: password 

The system section is more for the systems admin to review and manage 
the inner workings of the application. There are six main sections in 
the system admin area: 

dashboard
--------- 

The dashboard is available at:

	http://localhost/mici/web/system/
	
and provides quick access to the other sections of the site. This page is 
also handy as if there are any PHP errors, there will be a red badge on 
the Error Logs icon indicating the number of errors. 

user management
--------------- 

The user management section is available at:

	http://localhost/mici/web/system/users/ 

and is, as it sounds, where you will manage your users for your 
application. 

database management
------------------- 

There are three subsections inside the database management section. The 
first is a place to view the database schema. This is an image that can 
be panned and zoomed that is an exported PNG from ORM Designer. 

The next sections is where the meat of your work will come into play. 
Local Migration Manager is broken down into five main tabs that are in 
the order that they will be most likely used. You will want to view the 
following page for more information about how to use these sections: 

	http://localhost/mici/web/system/database/migration#help 

The last subsection allows you to update the database. This is done by 
using the migrations that are created from the migration tab in the 
local migration manager subsection. 

php error logs
-------------- 

From time to time you may generate some errors while working on some 
code. This section will capture all PHP errors and log them for easy 
viewing. You can configured the level of what gets logged by altering 
the log_threshold variable in the framework.ini file. 

	0 = Disables logging, Error logging TURNED OFF 
	1 = Error Messages (including PHP errors) 
	2 = Debug Messages 
	3 = Informational Messages 
	4 = All Messages 

For a live site you'll usually only enable Errors (1) to be logged 
otherwise your log files will fill up very fast. 

settings
-------- 

Here you can manage the Cache for the site. Check the boxes of the 
active Cache Types you want to Flush then select "Flush Cache" from the 
drop down list. Once you're ready, click the "Apply to selected" to 
flush the selected Cache Types. 

system check
------------ 

This System Check will tell you if there are any problems preventing a 
successful install and suggest actions to take to correct your 
installation. If everything is configured correctly the system check 
will also see if it can go ahead and perform a clean install of the 
system if it detects it is not already installed. This section will more 
than likely be the first area of the site you access once you have 
finished setting up your system as outlined in INSTALLING THE FRAMEWORK 
FOR LOCAL DEVELOPMENT. 

WORKING WITH ADMIN SECTION
========================== 

After you have completed the installation, you can access the admin 
section by going to: 

http://localhost/mici/web/admin/

To login use the default administrator login info: 

	username: admin
	password: password 

WORKING WITH ORM DESIGNER
========================= 

In order to work with the:

	/application/doctrine/orm_designer/Doctrine.ormdesigner

ORM Designer file you will need to install ORM Designer located at: 

	http://www.orm-designer.com/download-orm-designer 

Once you have this program installed, you can manage the ORM file with 
the manual available on the ORM Designer Website located here: 

	http://www.orm-designer.com/tag/doctrine

When creating new modules, you will want to check how the other modules 
were setup first and duplicate the paths to the YML and XML files so the 
system will work properly for you. 

HOW TO USE THE TAGGABLE BEHAVIOR
================================

First, if you are going to be using ORM Designer you will want to read the README file in:

	/application/doctrine/orm_designer/config/

You can use our Doctrine Taggable behavior on any entities you create in your object creation.  

In your Doctrine YML code the model would look like this:

	Model:
	  actAs:
		Timestampable: 
		Taggable: 
	  tableName: model
	  connection: default
	  columns:
		...
		
In your Doctrine PHP code the model would look like this:

	abstract class BaseModel extends Doctrine_Record
	{
		public function setTableDefinition()
		{
			// typical model code goes here
		}

		public function setUp()
		{
			// typical model code goes here, but add
			$taggable0 = new Taggable();
			$this->actAs($taggable0);
		}
	}

Once that is setup you can start using the new behavior. Then you can do stuff like this in your CodeIgniter Models:

    $model = new Model();
    $model->foo = 'bar';
    $model->setTags(array('tags','are','cool'));
    $model->save();

To fetch tags is a bit more complicated.  In your CodeIgniter Models __construct() method you will need a variable set like this:

    $this->_tags = NULL;

Then you will need a functions like this in that model:

    public function my_tags($format=NULL)
    {
        return ($format == 'json') ? json_encode($this->_tags) : $this->tags;
    }
	
Then anytime you want to get a specific ID from a CodeIgniter Model, you can fetch its tags pretty easily in your model's code that fetches the ID with something like this:

	public function get($id=NULL)
	{
		// get single id from model
		if ($id)
		{
			try
			{
				$model = Doctrine_Core::getTable('Model')->findOneById($id);
				$model->_tags = $model->getTagNames();

				return $model;
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
		// fetch all from model
		else
		{
			try
			{
				return Doctrine_Query::create()
					->from('Model t')
					->orderBy('t.name ASC')
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
	
Now in your CodeIgniter Controllers you can use your models pretty easily.

	$this->load->model('codeigniter/my_model');
	$this->load->model('codeigniter/tag_model');
	
	// first get the record
	$data['model'] = $this->my_model->get($id);
	$data['all_tags'] = $this->tag_model->get_all_tags('json');
	$data['existing_tags'] = $this->my_model->my_tags('json');