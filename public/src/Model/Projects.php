<?php

namespace Perei\PortfolioObj\Model;

class Projects extends Database
{
    private ?int $id;
    private string $title;
    private string $description;
    private string $image;
    private string $date;
    public function __construct(
        string $title,
        string $description,
        \DateTime|string $date,
        string $image,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date instanceof \DateTime ? $date : new \DateTime($date);
        $this->image = $image;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getImage(): string
    {
        return $this->image;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function setDate(\DateTime|string $date): void
    {
        $this->date = $date instanceof \DateTime ? $date : new \DateTime($date);
    }
    public function setImage(string $image): void
    {
        $this->image = $image;
    }
    public function insertProject(Projects $project): void
    {
        $title = $project->getTitle();
        $description = $project->getDescription();
        $date = $project->getDate()->format('Y-m-d');
        $image = $project->getImage();

        $stmt = $this->connexion->prepare(
            'INSERT INTO project (title, description, date, image)
         VALUES (:title, :description, :date, :image)'
        );

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':image', $image);
        $stmt->execute();

        $project->setId((int) $this->connexion->lastInsertId());
    }
    public function updateProject(Projects $project): void
    {
        $stmt = $this->connexion->prepare(
            'UPDATE project 
         SET title = :title, description = :description, date = :date, image = :image
         WHERE id = :id'
        );

        $title = $project->getTitle();
        $description = $project->getDescription();
        $date = $project->getDate()->format('Y-m-d');
        $image = $project->getImage();
        $id = $project->getId();

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        $stmt->execute();
    }
    public function searchProjects(string $keyword): array
    {
        $stmt = $this->connexion->prepare(
            'SELECT * FROM project WHERE title LIKE :keyword OR description LIKE :keyword'
        );
        $like = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $like);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $stmt->fetchAll();

        $projects = [];
        foreach ($results as $row) {
            $projects[] = new Projects(
                $row['title'],
                $row['description'],
                $row['date'],
                $row['image'],
                (int) $row['id']
            );
        }

        return $projects;
    }
    public function getAllProjects(): array
    {
        $listeProjects = [];
        $requeteSelection = $this->connexion->query('SELECT * FROM project');
        $requeteSelection->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $requeteSelection->fetchAll();

        foreach ($results as $row) {
            $project = new Projects(
                $row['title'],
                $row['description'],
                $row['date'],
                $row['image'],
                (int) $row['id']
            );
            $listeProjects[] = $project;
        }
        return $listeProjects;
    }
    public function deleteProject(int $id): void
    {
        $requeteSuppression = $this->connexion->prepare(
            'DELETE FROM project WHERE id = :id'
        );
        $requeteSuppression->bindParam('id', $id);
        $requeteSuppression->execute();
    }
    public function getProjectById(int $id): ?Projects
    {
        $requeteSelection = $this->connexion->prepare(
            'SELECT * FROM project WHERE id = :id'
        );
        $requeteSelection->bindParam('id', $id);
        $requeteSelection->execute();
        $requeteSelection->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $requeteSelection->fetch();

        if ($row) {
            return new Projects(
                $row['title'],
                $row['description'],
                $row['date'],
                $row['image'],
                (int) $row['id']
            );
        }
        return null;
    }
}
