<?php

namespace Vundi\EmojiApi\Controllers;

use Vundi\EmojiApi\AuthToken;

class AuthController
{

    /**
     *  Login the user with given username.
     *  @return string $json
     */
    public static function login($username)
    {
        // make a random token
        $token = md5(uniqid(mt_rand(), true));

        $auth_token = AuthToken::find(['username' => $username]);

        if (empty($auth_token)) {
            $auth_token = new AuthToken($username, $token);
            $auth_token->save();
        } else {
            $auth_token->token = $token;
            $temp = new AuthToken(
                $auth_token->username,
                $auth_token->token
            );
            $auth_token->expiry = $temp->expiry;
            $auth_token->save();
            $auth_token = $temp;
        }

        $fields = $auth_token->getFields();

        return json_encode($fields);
    }

    /**
     *  Logout the user with the token.
     *  @return boolean $prompt
     */
    public static function logout($token)
    {
        return AuthToken::delete($token);
    }

    /**
     *  Authenticates the passed token.
     *  Returns true if it is valid, false if not.
     *  @return boolean
     */
    public static function authenticateToken($token)
    {
        $authToken = AuthToken::find(['token' => $token]);
        if (empty($authToken)) {
            return false;
        }

        // check whether the expiry time date has passed.
        $expiry = $authToken->expiry;
        if (time() > $expiry) {
            return false;
        }

        return true;
    }
}
