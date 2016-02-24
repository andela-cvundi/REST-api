<?php

namespace Vundi\EmojiApi\Controllers;

use Vundi\EmojiApi\Models\Emoji;

class EmojiController
{
    public static function All()
    {
        $emojis = Emoji::findAll();

        return $emojis;
    }

    public static function find($id)
    {
        $id = (int)$id;
        $emoji = Emoji::find($id);

        return $emoji;
    }

    public static function newEmoji($data)
    {
        $emoji = new Emoji();
        $emoji->FName = $data['FName'];
        $emoji->LName = $data['LName'];
        $emoji->Gender = $data['Gender'];
        $emoji->Age = $data['Age'];
        $emoji->save();
    }


    public static function updateEmoji($id, $data)
    {
        $emoji = Emoji::find($id);
        $emoji->FName = $data['FName'];
        $emoji->LName = $data['LName'];
        $emoji->Gender = $data['Gender'];
        $emoji->Age = $data['Age'];
        $emoji->update();
    }

    public static function delete($id)
    {
        try {
            $id = (int)$id;
            if (is_Object(Emoji::find($id))) {
                Emoji::remove($id);
                echo json_encode(array(
                    "status" => true,
                    "message" => "Person deleted successfully"
                ));
            } else {
                throw new Exception("Person with that ID does not exist");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}