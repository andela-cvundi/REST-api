<?php

namespace Vundi\EmojiApi;

use Vundi\EmojiApi\Emoji;

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
