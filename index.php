<?php

require 'vendor/autoload.php';
use Slim\Slim;
use Vundi\Potato\Model;

class myAPI extends Model
{
    protected static $entity_table = 'Person';
    public $app;

    public function __construct()
    {
        parent::__construct();
        $this->app = new \Slim\Slim();

    }

    public function enable()
    {

        $this->app->get('/', function () {
            // query database for all articles
            $people = self::findAll();

            // send response header for JSON content type
            $this->app->response()->header('Content-Type', 'application/json');

            // return JSON-encoded response body with query results
            echo json_encode($people);
        });

        $this->app->get('/:id', function ($id) {
            $id = (int)$id;
            $person = self::find($id);
            $this->app->response()->header('Content-Type', 'application/json');

            // return JSON-encoded response body with query results
            echo json_encode($person);
        });

        $this->app->post('/person', function () {
            $request = $this->app->request();
            $body = $request->post();

            $person = new self();
            //$person->id = $body['id'];
            $person->FName = $body['FName'];
            $person->LName = $body['LName'];
            $person->Age = $body['Age'];
            $person->Gender = $body['Gender'];
            $person->save();
            $this->app->response()->header('Content-Type', 'application/json');
            echo json_encode($person->db_fields);

        });

        $this->app->put('/person/:id', function ($id) {
            $id = (int)$id;
            $request = $this->app->request();
            $body = $request->put();

            $person = self::find($id);
            $person->FName = $body['FName'];
            $person->LName = $body['LName'];
            $person->Age = $body['Age'];
            $person->Gender = $body['Gender'];
            $person->update();
            $this->app->response()->header('Content-Type', 'application/json');
            echo json_encode($person->db_fields);

        });

        $this->app->delete('/person/:id', function ($id) {
            $this->app->response()->header("Content-Type", "application/json");
            $id = (int)$id;
            self::remove($id);
            echo json_encode(array(
                "status" => true,
                "message" => "Person deleted successfully"
            ));

        });
        // start Slim
        $this->app->run();
    }
}

$emoji = new myAPI();
$emoji->enable();
