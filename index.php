<?php

require 'vendor/autoload.php';

use Slim\Slim;
use Vundi\EmojiApi\Controllers\EmojiController;
use Vundi\EmojiApi\Models\Emoji;
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
    $data = $app->request->post();

    EmojiController::newEmoji($data);
    $app->response->headers('Content-type', 'application/json');
    $app->response->status(201);
    $message = [
        'success' => true,
        'message' => 'Emoji successfully created',
    ];
    $json = json_encode($message);
    echo $json;

});


$app->put('/person/:id', function ($id) use ($app) {
    $data = $app->request->put();
    $id = (int)$id;
    EmojiController::updateEmoji($id, $data);

    $app->response->headers('Content-type', 'application/json');
    $app->response->status(201);
    $message = [
        'success' => true,
        'message' => 'Emoji successfully updated',
    ];

    $emoji = EmojiController::find($id)->db_fields;

    $json = json_encode($emoji);
    echo $json;
});

// Partially update an emoji with ID.
$app->patch('/person/:id', function ($id) use ($app) {
    $id = (int)$id;
    $emoji = Emoji::find($id);
    foreach ($app->request->patch() as $key => $value) {
        $emoji->{$key} = $value;
    }

    $patch = $emoji->update();
    if ($patch) {
        $message = [
            'success' => true,
            'message' => 'Emoji updated partially',
        ];
        $app->response->status(201);
    } else {
        $message = [
            'success' => false,
            'message' => 'Emoji not partially updated',
        ];
        $app->response->status(304);
    }
    $emoji = EmojiController::find($id)->db_fields;

    $json = json_encode($emoji);
    echo $json;
});

$app->delete('/person/:id', function ($id) use ($app) {
    $app->response()->header("Content-Type", "application/json");
    $id = (int)$id;
    Emoji::remove($id);
    echo json_encode(array(
        "status" => true,
        "message" => "Person deleted successfully"
    ));
});


$app->run();
