<?php

use Slim\Slim;
use Dotenv\Dotenv;
use \Firebase\JWT\JWT;
use Stacey\Emoji\Model\User;
use Stacey\Emoji\Model\Emoji;
use Stacey\Emoji\Model\EmojiKeywords;
use Stacey\Emoji\FunctionsDir\Reusable;

require __DIR__ . "/../vendor/autoload.php";

/** Load the secret key from .env */

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();

$key = getenv('SECRET_KEY');

/**
 * Creat a new slim instance.
 */

$app = new Slim(array(
    'debug' => true
));

/**
 *  Route to homepage.
 */

$app->get("/", function () {
    echo "<h1>Welcome to Naija Emoji :) </h1>";
});

/**
 *  Route to register a new user.
 */

$app->post('/register', function () use ($app) {
    try {
        $app->response()->headers("Content-Type", "application/json");
        $user = json_decode($app->request->getBody());

        User::create([
            'name'      => $user->name,
            'username'  => $user->username,
            'password'  => $user->password
            ]);

        echo json_encode(array(
                "status" => "success",
                "message" => "You have been registered!"
            ));
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 *  Route to login an existing user.
 */

$app->post('/auth/login', function () use ($app) {
    try {
        $app->response()->headers("Content-Type", "application/json");
        $user = json_decode($app->request->getBody());

        $userDB = User::getUser($user->username);

        if ($userDB !== null) {
            if (($user->password === $userDB->password) && ($user->username === $userDB->username)) {
                $tokenExpiration = time() + 3600;
                $payload = array(
                    "user" => $userDB->username,
                    "id"   => $userDB->id,
                    "exp"  => $tokenExpiration
                );

                $token = JWT::encode($payload, $GLOBALS['key']);

                $userDB->token = true;
                $userDB->save();

                echo json_encode([
                    "username" => $userDB->username,
                    "token" => $token
                ]);
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => "Wrong log in credentials!"
                ));
            }
        } else {
            echo json_encode(array(
                'status' => 'false',
                'message' => "No User with that username"
            ));
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 *  Route to log out a user.
 */

$app->post('/auth/logout', function () use ($app) {
    try {
        $authHeader = $app->request->headers->get('Authorization');
        $decodedToken = Reusable::tokenVerify($authHeader);

        $user = User::getUser($decodedToken['user']);

        if ($user) {
            $user->token = false;
            $user->save();
        }
        echo json_encode(array(
            'status' => 'true',
            'message' => "Successfully logged out"
            ));
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 *  Route to get all emojis.
 */

$app->get('/emojis', function () use ($app) {
    try {
        $emojis = Emoji::with('keywords')->get();

        if ($emojis->isEmpty()) {
            echo json_encode(array(
                'status' => 'Error',
                'message' => "Emojis not yet created"
            ));
        } else {
            $emojisNew = [];
            foreach ($emojis as $emoji) {
                $keywords = [];
                foreach ($emoji->keywords as $value) {
                    $keywords[] = $value->keyword;
                }
                $emoji->keywords = $keywords;
                $emojisNew[] = $emoji->getAttributes();
            }
            $app->response()->headers("Content-Type", "application/json");
            echo json_encode($emojisNew);
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 *  Route to get a single emoji.
 */

$app->get('/emojis/:id', function ($id) use ($app) {
    try {
        $emoji = Emoji::with('keywords')->where('id', $id)->first();

        if (is_null($emoji)) {
            echo json_encode(array(
                'status' => 'Error',
                'message' => "Emoji does not exist"
            ));
        } else {
            $keywords = [];
            foreach ($emoji->keywords as $value) {
                $keywords[] = $value->keyword;
            }
            $emoji->keywords = $keywords;
            $emojiNew = $emoji->getAttributes();

            $app->response()->headers("Content-Type", "application/json");
            echo json_encode($emojiNew);
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 *  Route to create an emoji.
 */

$app->post('/emojis', function () use ($app) {
    try {
        $app->response()->headers("Content-Type", "application/json");

        $authHeader = $app->request->headers->get('Authorization');
        $decodedToken = Reusable::tokenVerify($authHeader);

        if ($decodedToken) {
            $userID = $decodedToken['id'];
            $emoji = json_decode($app->request->getBody());

            $user = User::find($userID);
            if ($user->token !== 1) {
                echo json_encode(array(
                    "status" => "Bad request",
                    "message" => "Log in to create the emoji!"
                ));
            } else {
                $emojiCreated = $user->emojis()->create([
                    'name' => $emoji->name,
                    'symbol' => $emoji->symbol,
                    'category' => $emoji->category
                ]);

                echo json_encode(array(
                    "status" => "success",
                    "message" => "Emoji created!"
                ));
            }
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 *  Route to add keywords to an emoji.
 */

$app->post('/emojis/:id', function ($id) use ($app) {
    try {
        $app->response()->headers("Content-Type", "application/json");

        $authHeader = $app->request->headers->get('Authorization');
        $decodedToken = Reusable::tokenVerify($authHeader);

        if ($decodedToken) {
            $words = json_decode($app->request->getBody());

            $emoji = Emoji::with('user')->where('id', $id)->first();
            if ($emoji->user->token !== 1) {
                echo json_encode(array(
                    "status" => "Bad request",
                    "message" => "Log in to add the keyword!"
                ));
            } else {
                $word = $emoji->keywords()->create([
                    'keyword' => $words->keyword,
                ]);

                echo json_encode(array(
                    "status" => "success",
                    "message" => "Keyword added to emoji!"
                ));
            }
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 *  Route to update an emoji.
 */

$app->put('/emojis/:id', function ($id) use ($app) {
    try {
        $app->response()->headers("Content-Type", "application/json");

        $authHeader = $app->request->headers->get('Authorization');
        $decodedToken = Reusable::tokenVerify($authHeader);

        if ($decodedToken) {
            $emoji = json_decode($app->request->getBody(), true);

            $emojiUpdate = Emoji::with('user')->where('id', $id)->first();

            if ($emojiUpdate->user->token !== 1) {
                echo json_encode(array(
                    "status" => "Bad request",
                    "message" => "Log in to update the emoji!"
                ));
            } else {
                $emojiUpdate->update($emoji);

                echo json_encode(array(
                    "status" => "success",
                    "message" => "Emoji updated!"
                ));
            }
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 * Route to partially update an emoji.
 */

$app->patch('/emojis/:id', function ($id) use ($app) {
    try {
        $app->response()->headers("Content-Type", "application/json");

        $authHeader = $app->request->headers->get('Authorization');
        $decodedToken = Reusable::tokenVerify($authHeader);

        if ($decodedToken) {
            $emoji = json_decode($app->request->getBody(), true);

            $emojiUpdate = Emoji::with('user')->where('id', $id)->first();
            if ($emojiUpdate->user->token !== 1) {
                echo json_encode(array(
                    "status" => "Bad request",
                    "message" => "Log in to partially update the emoji!"
                ));
            } else {
                $emojiUpdate->update($emoji);

                echo json_encode(array(
                    "status" => "success",
                    "message" => "Emoji partially updated!"
                ));
            }
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 * Route to delete an emoji.
 */

$app->delete('/emojis/:id', function ($id) use ($app) {
    try {
        $app->response()->headers("Content-Type", "application/json");

        $authHeader = $app->request->headers->get('Authorization');
        $decodedToken = Reusable::tokenVerify($authHeader);

        if ($decodedToken) {
            $emoji = Emoji::with('user')->where('id', $id)->first();
            if ($emoji->user->token !== 1) {
                echo json_encode(array(
                    "status" => "Bad request",
                    "message" => "Log in to delete the emoji!"
                ));
            } else {
                Emoji::destroy($id);

                echo json_encode(array(
                    "status" => "success",
                    "message" => "Emoji deleted!"
                ));
            }
        }
    } catch (\PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

/**
 * Run the Slim app.
 */

$app->run();
