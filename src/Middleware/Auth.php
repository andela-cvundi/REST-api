<?php

namespace Vundi\EmojiApi\Middleware;

use Vundi\EmojiApi\Controllers\AuthController;

class Auth extends \Slim\Middleware
{
    /**
     * This method will check the HTTP request for authentication.
     * If the request is authenticated, the next middleware is called.
     * Otherwise, a 401 response is returned to the client.
     */
    public function call()
    {
        $req = $this->app->request();
        $app = $this->app;
        if ($req->isGet()) {
            $this->next->call();
            return;
        }

        if ($req->isPost() || $req->isPatch() ||
            $req->isPut() || $req->isDelete()) {
            if ($req->getResourceUri() == '/auth/login') {
                $this->next->call();
                return;
            }

            $token = $req->headers->get('token');
            if (AuthController::authenticateToken($token)) {
                $this->next->call();
            } else {
                $app->response->status(401);
                $app->response->headers->set(
                    'Content-Type',
                    'application/json'
                );
                $app->response->body(
                    '{"error" : "Not Authorized"}'
                );

                return $app->response();
            }
        }
    }
}
