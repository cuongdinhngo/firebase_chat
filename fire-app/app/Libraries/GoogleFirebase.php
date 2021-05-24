<?php

namespace App\Libraries;

use Exception;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;

class GoogleFirebase
{
    public $database;

    public $cloudMessage;

    public $auth;

    public $factory;

    public function __construct()
    {
        $this->factory = (new Factory)
            ->withServiceAccount('../firebase_credentials.json')
            ->withDatabaseUri(config('services.firebase.database_url'));

        $this->database = $this->factory->createDatabase();

        $this->auth = $this->factory->createAuth();
    }
}
