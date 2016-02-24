<?php

namespace Vundi\EmojiApi;

class AuthToken extends Model
{

    public $username;
    public $token;
    public $expiry;

    //collection for the class
    public static $collection = 'tokens';

    public function __construct($username, $token)
    {
        $this->username = $username;
        $this->token = $token;

        // Tokens expire 1 hour after their creation.
        $this->expiry = time() + 3600;
    }

    /**
     *  Implementation of the abstract getFields().
     *  @return array $fields
     */
    public function getFields()
    {
        return [
            'token' => $this->token,
            'username' => $this->username,
            'expiry' => $this->expiry,
        ];
    }

    /**
     *  @param string $token
     *  Delete a model with the given token.
     *  @return boolean
     */
    public static function delete($token)
    {
        $authInstance = self::find($token);

        if (empty($authInstance)) {
            return false;
        }

        $authInstance->delete();
        return true;
    }

    /**
     *  @param array $match
     *  Find whether token is there on db and return it.
     *  @return AuthToken $authInstance
     */
    public static function find($match)
    {
        $client = static::getClient();
        $collection = $client->getCollection(self::$collection);

        $authInstance = $collection->find()
            ->where(key($match), reset($match))
            ->findOne();

        return $authInstance;
    }
}
