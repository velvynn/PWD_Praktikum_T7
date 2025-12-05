<?php
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');

$host = "localhost";
$username = "root";
$password = "";
$dbname = "lianflix";

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);
    echo "<p style='color: #00a8ff;'>✓ Database created successfully</p>";
    
    $conn->exec("USE $dbname");
    
    // Update table structure to include genre
    $sql = "CREATE TABLE IF NOT EXISTS films (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        poster_url VARCHAR(500) NOT NULL,
        video_url VARCHAR(500) NOT NULL,
        description TEXT,
        release_year YEAR,
        category VARCHAR(100),
        genre VARCHAR(100) DEFAULT 'Animation',
        duration VARCHAR(50) DEFAULT 'Tidak diketahui',
        rating DECIMAL(3,1) DEFAULT 8.5,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "<p style='color: #00a8ff;'>✓ Table films created successfully</p>";
    
    // Insert sample data dengan genre
    $films = [
        [
            'title' => 'Moana',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/4JeejGugONWpJkbnvL12hVoesN0.jpg',
            'video_url' => 'https://www.youtube.com/watch?v=LKFuXETZUsI',
            'description' => 'Seorang putri berpetualang melintasi lautan untuk menyelamatkan rakyatnya dengan bantuan dewa setengah dewa yang perkasa.',
            'release_year' => 2016,
            'category' => 'Animation',
            'genre' => 'Adventure',
            'duration' => '107 menit',
            'rating' => 7.6
        ],
        [
            'title' => 'The Witcher: Nightmare of the Wolf',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/7vj5BdP6eK5paJLiF8KBQcN2wXc.jpg',
            'video_url' => 'https://www.youtube.com/watch?v=j7qD8f2sNtE',
            'description' => 'The Witcher: Nightmare of the Wolf is a 2021 animated film based on the Witcher series.',
            'release_year' => 2021,
            'category' => 'Animation',
            'genre' => 'Dark Fantasy',
            'duration' => '83 menit',
            'rating' => 8.1
        ],
        [
            'title' => 'Jumbo',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/aQPeznU7l2df7kS6inKTXQlgW2r.jpg',
            'video_url' => 'https://www.youtube.com/watch?v=example',
            'description' => 'Don, a chubby boy bullied as "Jumbo", encounters Mezi, a spirit seeking help to reunite with her troubled family\'s spirits. Their journey unfolds.',
            'release_year' => 2023,
            'category' => 'Animation',
            'genre' => 'Family',
            'duration' => '95 menit',
            'rating' => 7.2
        ],
        [
            'title' => 'The Witcher: Shows of the Deep',
            'poster_url' => 'https://image.tmdb.org/t/p/w500/8Vt6mWEReuy4Of61Lnj5Xj704m8.jpg',
            'video_url' => 'https://www.youtube.com/watch?v=example2',
            'description' => 'Hired to probe seaside village attacks, mutant monster hunter Geralt unravels an age-old conflict between humans and sea people that threatens war between kingdoms.',
            'release_year' => 2023,
            'category' => 'Animation',
            'genre' => 'Dark Fantasy',
            'duration' => '78 menit',
            'rating' => 8.3
        ]
    ];
    
    $stmt = $conn->prepare("INSERT INTO films (title, poster_url, video_url, description, release_year, category, genre, duration, rating) 
                           VALUES (:title, :poster_url, :video_url, :description, :release_year, :category, :genre, :duration, :rating)");
    
    $inserted = 0;
    foreach($films as $film) {
        try {
            $stmt->execute($film);
            $inserted++;
        } catch(PDOException $e) {
            if($e->getCode() == 23000) continue;
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