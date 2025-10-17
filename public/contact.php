<?php

include_once __DIR__ . "/../public/index.php";

/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact = new Contact(
        $_POST['title'],
        $_POST['firstname'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['message']
    );

    $db->insertContact($contact);
    echo "<p>Merci pour votre message, je vous répondrai rapidement !</p>";
}
