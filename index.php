<?php

require 'vendor/autoload.php';
use Slim\Slim;
use Vundi\Potato\Model;

class myAPI extends Model
{
    protected static $entity_table = 'Person';

    public function __construct()
    {
        parent::__construct();
        $this->app = new \Slim\Slim();
    }

    public function enable()
    {
        //setup the routes
        $this->app->get('/', array($this, 'getAll'));
        $this->app->get('/:id', array($this, 'getItem'));
        $this->app->post('/', array($this, 'postItem'));
        $this->app->put('/:id', array($this, 'putItem'));
        $this->app->delete('/:id', array($this, 'deleteItem'));

        // start Slim
        $this->app->run();
    }

    public function getAll()
    {
        $items = self::findAll();
        echo json_encode($items);
    }
}

$emoji = new myAPI();
$emoji->getAll();

// $user->FName = "Slim";
// $user->LName = "Framework";
// $user->Gender = "Male";
// $user->Age = 21;
// $user->save();
