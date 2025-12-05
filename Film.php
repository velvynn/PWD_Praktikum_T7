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
    public $genre;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
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
            $this->id = $row['id'] ?? null;
            $this->title = $row['title'] ?? '';
            $this->poster_url = $row['poster_url'] ?? '';
            $this->video_url = $row['video_url'] ?? '';
            $this->description = $row['description'] ?? '';
            $this->release_year = $row['release_year'] ?? '';
            $this->category = $row['category'] ?? '';
            $this->genre = $row['genre'] ?? '';
            $this->duration = $row['duration'] ?? 'Tidak diketahui';
            $this->rating = $row['rating'] ?? '8.5';
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET title=:title, poster_url=:poster_url, video_url=:video_url, 
                  description=:description, release_year=:release_year, 
                  category=:category, genre=:genre, duration=:duration, rating=:rating";
        
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->poster_url = htmlspecialchars(strip_tags($this->poster_url));
        $this->video_url = htmlspecialchars(strip_tags($this->video_url));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->release_year = htmlspecialchars(strip_tags($this->release_year));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->genre = htmlspecialchars(strip_tags($this->genre));
        $this->duration = htmlspecialchars(strip_tags($this->duration));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        
        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":poster_url", $this->poster_url);
        $stmt->bindParam(":video_url", $this->video_url);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":release_year", $this->release_year);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":genre", $this->genre);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":rating", $this->rating);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title=:title, poster_url=:poster_url, video_url=:video_url, 
                  description=:description, release_year=:release_year, 
                  category=:category, genre=:genre, duration=:duration, rating=:rating
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->poster_url = htmlspecialchars(strip_tags($this->poster_url));
        $this->video_url = htmlspecialchars(strip_tags($this->video_url));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->release_year = htmlspecialchars(strip_tags($this->release_year));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->genre = htmlspecialchars(strip_tags($this->genre));
        $this->duration = htmlspecialchars(strip_tags($this->duration));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":poster_url", $this->poster_url);
        $stmt->bindParam(":video_url", $this->video_url);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":release_year", $this->release_year);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":genre", $this->genre);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>