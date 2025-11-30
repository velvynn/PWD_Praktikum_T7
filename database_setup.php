<?php
error_reporting(0); // Menyembunyikan warning untuk production
header('Content-Type: text/html; charset=utf-8');

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "lianflix";

try {
    // Create connection
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);
    echo "<p style='color: #00a8ff;'>✓ Database created successfully</p>";
    
    // Use database
    $conn->exec("USE $dbname");
    
    // Create films table
    $sql = "CREATE TABLE IF NOT EXISTS films (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        poster_url VARCHAR(500) NOT NULL,
        video_url VARCHAR(500) NOT NULL,
        description TEXT,
        release_year YEAR,
        category VARCHAR(100),
        duration VARCHAR(50) DEFAULT 'Tidak diketahui',
        rating DECIMAL(3,1) DEFAULT 8.5,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "<p style='color: #00a8ff;'>✓ Table films created successfully</p>";
    
// Insert sample data
$films = [
    [
        'title' => 'Moana',
        'poster_url' => 'https://via.placeholder.com/300x400/1e3a8a/ffffff?text=MOANA',
        'video_url' => 'https://www.youtube.com/watch?v=LKFuXETZUsI',
        'description' => 'Seorang putri berpetualang melintasi lautan untuk menyelamatkan rakyatnya dengan bantuan dewa setengah dewa yang perkasa.',
        'release_year' => 2016,
        'category' => 'Animation',
        'duration' => '107 menit',
        'rating' => 7.6
    ],
    [
        'title' => 'Inside Out 2',
        'poster_url' => 'https://image.tmdb.org/t/p/w500/vpnVM9B6NMmQpWeZvzLvDESb2QY.jpg',
        'video_url' => 'https://www.youtube.com/watch?v=LEjhY2IQB6s',
        'description' => 'Riley yang kini remaja menyambut Emosi baru yang tak terduga: Kecemasan, Kebanggaan, Rasa Malu, dan Kebosanan.',
        'release_year' => 2024,
        'category' => 'Animation',
        'duration' => '96 menit',
        'rating' => 8.2
    ],
    [
        'title' => 'Luca',
        'poster_url' => 'https://image.tmdb.org/t/p/w500/jTswp6KyDYKtvC52GbHagrZbGvD.jpg',
        'video_url' => 'https://www.youtube.com/watch?v=mYfJxlgR2jw',
        'description' => 'Petualangan musim panas seorang bocah laut di tepi pantai Italia yang indah, berteman dengan manusia.',
        'release_year' => 2021,
        'category' => 'Animation',
        'duration' => '95 menit',
        'rating' => 7.4
    ],
    [
        'title' => 'Finding Nemo',
        'poster_url' => 'https://image.tmdb.org/t/p/w500/eHuGQ10FUzK1mdOY69wF5pGgEf5.jpg',
        'video_url' => 'https://www.youtube.com/watch?v=9oQ628Seb9w',
        'description' => 'Seekor ikan badut yang terlalu protektif melakukan perjalanan berbahaya melintasi lautan untuk mencari putranya yang hilang.',
        'release_year' => 2003,
        'category' => 'Animation',
        'duration' => '100 menit',
        'rating' => 8.1
    ],
    [
        'title' => 'The Good Dinosaur',
        'poster_url' => 'https://image.tmdb.org/t/p/w500/2ZckiMTfSkCep2JTt1uOpPZTPpo.jpg',
        'video_url' => 'https://www.youtube.com/watch?v=O-RgquKVTPE',
        'description' => 'Bagaimana jika asteroid yang memusnahkan dinosaurus tidak pernah menghantam Bumi? Seorang dinosaurus remaja berteman dengan manusia.',
        'release_year' => 2015,
        'category' => 'Animation',
        'duration' => '93 menit',
        'rating' => 6.7
    ],
    [
        'title' => 'Small Foot',
        'poster_url' => 'https://image.tmdb.org/t/p/w500/4nKoB6wMVXfsYgRZK5lHZ5VMQ6J.jpg',
        'video_url' => 'https://www.youtube.com/watch?v=gO5UR85pJMA',
        'description' => 'Seekor Yeti muda menemukan sesuatu yang dia kira tidak ada: manusia. Petualangan menakjubkan dimulai!',
        'release_year' => 2018,
        'category' => 'Animation',
        'duration' => '96 menit',
        'rating' => 6.5
    ],
    [
        'title' => 'Wreck-it Ralph',
        'poster_url' => 'https://image.tmdb.org/t/p/w500/93YI7FQH1vFmORMecV4mnY1JKhW.jpg',
        'video_url' => 'https://www.youtube.com/watch?v=87E6N7ToCxs',
        'description' => 'Penjahat video game ingin menjadi pahlawan dan melakukan perjalanan melalui berbagai arcade untuk membuktikan dirinya.',
        'release_year' => 2012,
        'category' => 'Animation',
        'duration' => '101 menit',
        'rating' => 7.7
    ],
];
    
    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO films (title, poster_url, video_url, description, release_year, category, duration, rating) 
                           VALUES (:title, :poster_url, :video_url, :description, :release_year, :category, :duration, :rating)");
    
    $inserted = 0;
    foreach($films as $film) {
        try {
            $stmt->execute($film);
            $inserted++;
        } catch(PDOException $e) {
            // Skip if duplicate entry
            if($e->getCode() == 23000) {
                continue;
            }
            throw $e;
        }
    }
    
    echo "<p style='color: #00a8ff;'>✓ Inserted $inserted films successfully</p>";
    echo "<h3 style='color: #fff; margin-top: 20px;'>Setup completed! <a href='index.php' style='color: #e50914; text-decoration: none; font-weight: bold;'>Go to LianFlix</a></h3>";
    
} catch(PDOException $e) {
    echo "<p style='color: #e50914;'>Error: " . $e->getMessage() . "</p>";
}

$conn = null;
?>