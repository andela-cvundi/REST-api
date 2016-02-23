<?php

require 'vendor/autoload.php';

use Slim\Slim;
use Vundi\EmojiApi\EmojiController;
use Vundi\EmojiApi\Emoji;
use Vundi\Potato\Exceptions\NonExistentID;

$app = new Slim();

$app->get('/', function () {
    echo "Welcome to the homepage";
});

$app->get('/people', function () use ($app) {
    $app->response->headers
        ->set('Content-Type', 'application/json');
    // return JSON-encoded response body with query results
    echo json_encode(EmojiController::All());
});

$app->get('/person/:id', function ($id) use ($app) {
    $app->response->headers
        ->set('Content-Type', 'application/json');
    $emoji = EmojiController::find($id);
    if (empty($emoji)) {
        throw new NonExistentID;
    }
        echo json_encode($emoji);
});

// Create an Emoji.
$app->post('/person', function () use ($app) {
    try {
        $request = $app->request();
        $body = $request->post();

        $person = new Emoji();
        $person->FName = $body['FName'];
        $person->LName = $body['LName'];
        $person->Age = $body['Age'];
        $person->Gender = $body['Gender'];
        $person->save();
        $app->response()->headers('Content-Type', 'application/json');
        echo json_encode($person->db_fields);
    } catch (Exception $e) {
        echo $app->response()->status(400);
        echo $app->response()->header('X-Status-Reason', $e->getMessage());
    }
});


$app->put('/person/:id', function ($id) use ($app) {
    $id = (int)$id;
    $person = Emoji::find($id);
    $request = $app->request();
    $body = $request->put();
    $person->FName = $body['FName'];
    $person->LName = $body['LName'];
    $person->Age = $body['Age'];
    $person->Gender = $body['Gender'];
    $person->update();
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($person->db_fields);
});


$app->run();
