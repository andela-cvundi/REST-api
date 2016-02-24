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
        'message' => 'Emoji successfully created',
    ];

    $json = json_encode($message);

    $emoji = EmojiController::find($id)->db_fields;

    $json = json_encode($emoji);
    echo $json;
});

// Partially update an emoji with ID.
$app->patch('/:id', function ($id) use ($app) {
    $data = $app->request->patch();
    $id = (int)$id;
    $emoji = EmojiController::updateEmoji($id, $data);
    if (empty($emoji)) {
        $app->response->setStatus(304);
        $app->response->body(
            '{"error" : "Not Modified."}'
        );
        return $app->response();
    }
    $app->response->body($emoji);
    return $app->response();
});


    $app->run();
