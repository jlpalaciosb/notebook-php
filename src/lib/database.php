<?php

# Return database connection
function db() {
    static $connection = null;

    if ($connection === null) {
        $db = CONFIG['db'];
        $connection = new PDO(
            "pgsql:host={$db['host']};dbname={$db['name']}",
            $db['user'],
            $db['pass']
        );
    }

    return $connection;
}

# Return true if exists a diary entry of the user $user with date $date
function existsEntry($user, $date) {
    $stmt = db()->prepare("SELECT * FROM entries WHERE owner=:o AND date=:d");
    $stmt->bindParam(':o', $user);
    $stmt->bindParam(':d', $date);
    $stmt->execute();
    return $stmt->rowCount() == 1;
}

# Return true if the $user is in the database
function registered($user) {
    $stmt = db()->prepare('SELECT * FROM users WHERE name=:u');
    $stmt->bindParam(':u', $user);
    $stmt->execute();
    return $stmt->rowCount() == 1;
}

# Register a user
function register($user, $password) {
    $stmt = db()->prepare('INSERT INTO users (name, password) VALUES (:n, :p)');
    $stmt->bindParam(':n', $user);
    $stmt->bindParam(':p', $password);
    $password = md5($password);
    $stmt->execute();
}

# Return true if the $user and $password are correct
function authenticate($user, $password) {
    $stmt = db()->prepare('SELECT * FROM users WHERE name=:n AND password=:p');
    $stmt->bindParam(':n', $user);
    $stmt->bindParam(':p', $password);
    $password = md5($password);
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($row['theme'])) {
            $_SESSION['theme'] = $row['theme'];
        }
        return true;
    }

    return false;
}
