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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LianFlix - Platform Streaming Terbaik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header" id="header">
        <a href="#" class="logo"></a>
        <nav class="nav">
            <a href="#">Beranda</a>
            <a href="#">Film</a>
            <a href="#">Serial TV</a>
            <a href="#">Baru & Terpopuler</a>
            <a href="#">Daftar Saya</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Rekomendasi Film Terbaik</h1>
            <p>Temukan petualangan menakjubkan dan cerita mengharukan dalam koleksi film animasi pilihan kami</p>
            <a href="#films" class="cta-button">Jelajahi Sekarang</a>
        </div>
    </section>

    <section class="films-section" id="films">
        <h2 class="section-title">Film Pilihan Untuk Anda</h2>
        <div class="films-grid">
            <?php foreach($films as $film): ?>
            <div class="film-card">
                <img src="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                     alt="<?php echo htmlspecialchars($film['title']); ?>" 
                     class="film-poster"
                      style="object-fit: contain; height: auto; width: 100%;"
                     onerror="this.src='https://via.placeholder.com/300x400/1a3a6d/ffffff?text=<?php echo urlencode($film['title']); ?>'"> <!-- PERBAIKAN DI SINI -->
                <div class="film-overlay">
                    <div class="film-info">
                        <h3 class="film-title"><?php echo htmlspecialchars($film['title']); ?></h3>
                        <div class="film-meta">
                            <span class="film-year"><?php echo htmlspecialchars($film['release_year']); ?></span>
                            <span class="film-rating"><?php echo htmlspecialchars($film['rating'] ?? '8.5'); ?>/10</span>
                        </div>
                        <p class="film-description"><?php echo htmlspecialchars($film['description']); ?></p>
                        <a href="film_detail.php?id=<?php echo $film['id']; ?>" class="watch-btn">Tonton Sekarang</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
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