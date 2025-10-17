<?php

namespace Perei\PortfolioObj\Model;

class Contact
{
    private ?int $id;
    private string $title;
    private string $firstname;
    private string $phone;
    private string $email;
    private string $message;

    public function __construct(
        string $title,
        string $firstname,
        string $phone,
        string $email,
        string $message,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->firstname = $firstname;
        $this->phone = $phone;
        $this->email = $email;
        $this->message = $message;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getFirstname(): string
    {
        return $this->firstname;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getMessage(): string
    {
        return $this->message;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function insertContact(Contact $contact): void
    {
        try {
            $stmt = $this->connexion->prepare(
                'INSERT INTO contact (title, firstname, phone, email, message)
             VALUES (:title, :firstname, :phone, :email, :message)'
            );

            $stmt->bindValue(':title', $contact->getTitle());
            $stmt->bindValue(':firstname', $contact->getFirstname());
            $stmt->bindValue(':phone', $contact->getPhone());
            $stmt->bindValue(':email', $contact->getEmail());
            $stmt->bindValue(':message', $contact->getMessage());

            $stmt->execute();
            $contact->setId((int) $this->connexion->lastInsertId());
        } catch (\PDOException $e) {
            error_log('Erreur lors de l’insertion du message de contact : ' . $e->getMessage());
        }
    }
    public function getAllContacts(): array
    {
        try {
            $stmt = $this->connexion->query('SELECT * FROM contact ORDER BY id DESC');
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();

            $contacts = [];
            foreach ($rows as $row) {
                $contacts[] = new Contact(
                    $row['title'],
                    $row['firstname'],
                    $row['phone'],
                    $row['email'],
                    $row['message'],
                    (int) $row['id']
                );
            }

            return $contacts;
        } catch (\PDOException $e) {
            error_log('Erreur lors de la récupération des contacts : ' . $e->getMessage());
            return [];
        }
    }
    public function deleteContact(int $id): void
    {
        try {
            $stmt = $this->connexion->prepare('DELETE FROM contact WHERE id = :id');
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Erreur lors de la suppression du contact : ' . $e->getMessage());
        }
    }



}
