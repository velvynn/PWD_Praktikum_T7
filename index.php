<?php
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once 'config.php';
include_once 'Film.php';

$database = new Database();
$db = $database->getConnection();

$film = new Film($db);
$stmt = $film->read();
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil film featured untuk hero section
$featured_stmt = $film->read();
$featured_films = $featured_stmt->fetchAll(PDO::FETCH_ASSOC);
$featured = !empty($featured_films) ? $featured_films[array_rand($featured_films)] : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LianFlix - Platform Streaming Terbaik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header" id="header">
        <a href="index.php" class="logo">LIANFLIX</a>
        <nav class="nav">
            <a href="index.php">Beranda</a>
            <a href="movies.php">Movie</a>
            <a href="#">Serial TV</a>
            <a href="#">Baru & Terpopuler</a>
            <a href="movies.php">Daftar Saya</a>
            <a href="add_movie.php" class="add-movie-btn"><i class="fas fa-plus"></i> Tambah Film</a>
        </nav>
    </header>

    <section class="hero">
        <?php if($featured): ?>
        <div class="hero-background" style="background-image: url('<?php echo htmlspecialchars($featured['poster_url']); ?>');"></div>
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($featured['title']); ?></h1>
            <p><?php echo htmlspecialchars(substr($featured['description'], 0, 150)); ?>...</p>
            <div class="hero-meta">
                <span class="hero-year"><?php echo htmlspecialchars($featured['release_year']); ?></span>
                <span class="hero-rating"><?php echo htmlspecialchars($featured['rating']); ?>/10</span>
                <span class="hero-genre"><?php echo htmlspecialchars($featured['genre']); ?></span>
                <span class="hero-duration"><?php echo htmlspecialchars($featured['duration']); ?></span>
            </div>
            <div class="hero-buttons">
                <a href="film_detail.php?id=<?php echo $featured['id']; ?>" class="cta-button">
                    <i class="fas fa-play"></i> Tonton Sekarang
                </a>
                <a href="movies.php" class="secondary-button">
                    <i class="fas fa-list"></i> Lihat Semua Film
                </a>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <section class="films-section" id="films">
        <h2 class="section-title">Film Pilihan Untuk Anda</h2>
        <div class="films-grid">
            <?php foreach(array_slice($films, 0, 6) as $film): ?>
            <div class="film-card">
                <img src="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                     alt="<?php echo htmlspecialchars($film['title']); ?>" 
                     class="film-poster"
                     onerror="this.src='https://via.placeholder.com/300x400/1a3a6d/ffffff?text=<?php echo urlencode($film['title']); ?>'">
                <div class="film-overlay">
                    <div class="film-info">
                        <h3 class="film-title"><?php echo htmlspecialchars($film['title']); ?></h3>
                        <div class="film-meta">
                            <span class="film-year"><?php echo htmlspecialchars($film['release_year']); ?></span>
                            <span class="film-genre"><?php echo htmlspecialchars($film['genre']); ?></span>
                            <span class="film-rating"><?php echo htmlspecialchars($film['rating']); ?>/10</span>
                        </div>
                        <p class="film-description"><?php echo htmlspecialchars(substr($film['description'], 0, 100)); ?>...</p>
                        <div class="film-actions">
                            <a href="film_detail.php?id=<?php echo $film['id']; ?>" class="watch-btn">
                                <i class="fas fa-play"></i> Tonton
                            </a>
                            <a href="edit_movie.php?id=<?php echo $film['id']; ?>" class="edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="view-all-container">
            <a href="movies.php" class="view-all-btn">Lihat Semua Film <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2025 LianFlix. Semua hak dilindungi undang-undang.</p>
        <p>Platform streaming film terbaik untuk pengalaman menonton yang tak terlupakan</p>
        <div class="footer-links">
            <a href="#">Ketentuan Layanan</a>
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Pusat Bantuan</a>
            <a href="#">Hubungi Kami</a>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>