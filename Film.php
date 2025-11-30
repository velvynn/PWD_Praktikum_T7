<?php
class Film {
    private $conn;
    private $table_name = "films";

    public $id;
    public $title;
    public $poster_url;
    public $video_url;
    public $description;
    public $release_year;
    public $category;
    public $duration;
    public $rating;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY release_year DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            // Gunakan null coalescing operator untuk menghindari warning
            $this->id = $row['id'] ?? null;
            $this->title = $row['title'] ?? '';
            $this->poster_url = $row['poster_url'] ?? '';
            $this->video_url = $row['video_url'] ?? '';
            $this->description = $row['description'] ?? '';
            $this->release_year = $row['release_year'] ?? '';
            $this->category = $row['category'] ?? '';
            $this->duration = $row['duration'] ?? 'Tidak diketahui';
            $this->rating = $row['rating'] ?? '8.5';
            return true;
        }
        return false;
    }

    public function getFeatured() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY RAND() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = ? ORDER BY release_year DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->execute();
        return $stmt;
    }
}
?>