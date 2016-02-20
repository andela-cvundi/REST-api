<?php

require 'vendor/autoload.php';
use Slim\Slim;
use Vundi\Potato\Model;
use Exceptions\ResourceNotFound;

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
            echo "Welcome to the homepage of my API service";
        });

        $this->app->get('/people', function () {
            // query database for all articles
            $people = self::findAll();

            // send response header for JSON content type
            $this->app->response()->header('Content-Type', 'application/json');

            // return JSON-encoded response body with query results
            echo json_encode($people);
        });

        $this->app->get('/person/:id', function ($id) {
            try {
                $id = (int)$id;
                $person = self::find($id);
                if (is_object($person)) {
                    $this->app->response()->header('Content-Type', 'application/json');
                    // return JSON-encoded response body with query results
                    echo json_encode($person);
                } else {
                    throw new ResourceNotFound();
                }
            } catch (ResourceNotFound $e) {
                echo $this->app->response()->status(404);
            } catch (Exception $e) {
                echo $this->app->response()->status(400);
                echo $this->app->response()->header('X-Status-Reason', $e->getMessage());
            }
        });



        $this->app->post('/person', function () {
            try {
                $request = $this->app->request();
                $body = $request->post();

                $person = new self();
                $person->FName = $body['FName'];
                $person->LName = $body['LName'];
                $person->Age = $body['Age'];
                $person->Gender = $body['Gender'];
                $person->save();
                $this->app->response()->header('Content-Type', 'application/json');
                echo json_encode($person->db_fields);
            } catch (Exception $e) {
                echo $this->app->response()->status(400);
                echo $this->app->response()->header('X-Status-Reason', $e->getMessage());
            }


        });

        $this->app->put('/person/:id', function ($id) {
            try {
                $id = (int)$id;
                $person = self::find($id);
                if (is_object($person)) {
                    $request = $this->app->request();
                    $body = $request->put();
                    $person->FName = $body['FName'];
                    $person->LName = $body['LName'];
                    $person->Age = $body['Age'];
                    $person->Gender = $body['Gender'];
                    $person->update();
                    $this->app->response()->header('Content-Type', 'application/json');
                    echo json_encode($person->db_fields);
                } else {
                    throw new ResourceNotFound();
                }

            } catch (ResourceNotFound $e) {
                echo $this->app->response()->status(404);
            } catch (Exception $e) {
                echo $this->app->response()->status(404);
                echo $this->app->response()->header('X-Status-Reason', $e->getMessage());
            }
        });

        $this->app->delete('/person/:id', function ($id) {
            $this->app->response()->header("Content-Type", "application/json");
            try {
                $id = (int)$id;
                if (is_Object(self::find($id))) {
                    self::remove($id);
                    echo json_encode(array(
                        "status" => true,
                        "message" => "Person deleted successfully"
                    ));
                } else {
                    throw new ResourceNotFound();
                }
            } catch (ResourceNotFound $e) {
                echo $this->app->response()->status(404);
            } catch (Exception $e) {
                echo $this->app->response()->status(404);
                echo $this->app->response()->header('X-Status-Reason', $e->getMessage());
            }



        });
        // start Slim
        $this->app->run();
    }
}

$emoji = new myAPI();
$emoji->enable();
