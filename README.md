# LitePHP - A *Lite* weight PHP Framework.

### What is LitePHP
LitePHP is a PHP Framework that is designed to be fast, light and small. This project is not specifically designed to help you build a website as quick as possible, but more about giving you the structure and utilities to build a stable and well structured application.

### Features
- MVC
 - **Model** - A class that represents data
 - **View** - An output handler, passed a set of data and a template to generate output
 - **Controller** - Business Logic, this is decision maker, what template to use, what data to use etc.

- Database Drivers
 - The current implementation uses PDO for Database Abstraction, the following database types are supported.
     - **Cubrid**
     - **DBLib**    FreeTDS / Microsoft SQL Server / Sybase
     - **Firebird** Firebird/Interbase 6
     - **IBM**	    IBM DB2
     - **informix**	IBM Informix Dynamic Server
     - **MySQL**	  MySQL 3.x/4.x/5.x
     - **OCI*	      Oracle Call Interface
     - **ODBC**	    ODBC v3 (IBM DB2, unixODBC and win32 ODBC)
     - **PgSQL**	  PostgreSQL
     - **SQLite**	  SQLite 3 and SQLite 2
     - **SQLSrv**	  Microsoft SQL Server / SQL Azure
     - **4D**	      4D

- Libraries
 - LitePHP tries to abstract away everything into libraries, so that you only use what you need when you need it
 - Libraries are automatically loaded when you actually make the call, such as `$this->libraries->session->get('userid')`. note that the session library is loaded just before the `get()` call.

- Models
 - As with Libraries, Models are also loaded at the time you actually need them, such as `$user = $this->models->users->get($userid)`
 - Models can extend the `Model` class, if this is true, the model class will have access to the database, note that the database is only ever connection if you actually make a call to the database.

- Configurations
 - Configurations are also loaded when you actually need them, so doing `$this->config->system` will autolaod the `configs/system.php` and instantiate the config class.

- Views
 - Views are like templates, you pass the view a set of data, and then tell it to render that set of data inside a particular view file. An example of this is shown below.
##### controllers/index.php
```php
class Index_Controller extends Controller
{
  public function index()
  {
    $this->view->hello = "World";
    
    /**
     * Render the output.
     */
    $this->view->render('index');
  }
}
```

##### views/index.php
```php
Hello <?php echo $this->hello ?>
```

### Why are configurations, models and libraries loaded on the fly.
Well, lets say you build a website that has around 10,000 daily visits, without LitePHP you would load all your code and then
carry out your work.

If a user goes to `/user/16456/` on your website, you are more than likely going to need database and templates to show that page, but what
if another user at the same times goes to `/help/` it is less likely that we will need the database, but under many conventional frameworks we would connect to the database for no reason, slowing page load.

With LitePHP, you only load stuff when you actually need it, so it free's up resources for other request to be handled faster.

### Console
We are actively developing to allow you to build *MC* Console Applications as well, this will allow you to build an application in the exact same way
as building a web application, but using a different output method.

A example of a console app is:

```php
class Utils_Controller extends Controller
{
    public function gc()
    {
        //Clean old sessions
        $this->libraries->session->gc();
        
        //Output a message
        $this->output->send("Garbage Collection completed.");
    }
}
```

And then running like so

`php -e cli.php utils gc`

### Resources
  - [Restful Routing](https://gist.github.com/robertpitt/421ca3efabe5817b1d11)

### Development
LitePHP is currently sstill under heavy development, you are more than free to take it and do as you wish but currently its lacking
a lot of strucutre.

We plan to intergrate many features before `1.0` so maybe you would like to start the project and stay up to date with whats going on
or maybe even contribute if you wish.

if you wish to clone the project, just follow these instructures:

- To clone with minimal libraries
 - `git clone https://github.com/lite-php/framework.git`
- To clone with libraries
 - `git clone --recursive https://github.com/lite-php/framework.git`

The second clones all submodules as well as teh core framework, submodules are seperate repositories within the framework
repository.

### Requirements
- Coffee (1-2 cups per day)
- PHP5 (we do not have an exact version as development is still very active)

### Installation

### Licence

###Documentation
