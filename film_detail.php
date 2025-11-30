<?php
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once 'config.php';
include_once 'Film.php';

$database = new Database();
$db = $database->getConnection();

$film = new Film($db);

// Validasi ID film - PERBAIKAN DI SINI
$film_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($film_id > 0 && $film->readOne($film_id)) {
    $film_data = [
        'id' => $film->id,
        'title' => $film->title,
        'poster_url' => $film->poster_url,
        'video_url' => $film->video_url,
        'description' => $film->description,
        'release_year' => $film->release_year,
        'category' => $film->category,
        'duration' => $film->duration,
        'rating' => $film->rating
    ];
} else {
    echo "<script>alert('Film tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film_data['title']); ?> - LianFlix</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
        .film-detail-hero {
            margin-top: 100px;
            padding: 0;
            position: relative;
            height: 90vh; 
            overflow: hidden;
        }

        .film-detail-backdrop {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center; 
            filter: brightness(0.4);
        }

        .film-detail-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 50px;
            display: flex;
            gap: 40px;
            align-items: flex-end;
        }

        .film-poster-large {
            width: 300px;
            height: 450px; /* ← TAMBAH INI */
            object-fit: contain; /* ← PASTIKAN CONTAIN */
            object-position: center center;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            flex-shrink: 0;
        }

        .film-info-detail {
            flex: 1;
        }

        .film-title-detail {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.8);
        }

        .film-meta-detail {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            align-items: center;
            flex-wrap: wrap;
        }

        .film-year-detail {
            font-size: 1.2rem;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .film-rating-detail {
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .film-category {
            background: var(--secondary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: 600;
        }

        .film-duration {
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        .film-description-detail {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 30px;
            color: var(--text-light);
            max-width: 800px;
        }

        .action-buttons {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
        }

        .watch-btn-detail {
            padding: 15px 40px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 168, 255, 0.4);
        }

        .watch-btn-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 168, 255, 0.6);
        }

        .back-btn {
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .film-detail-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 30px 20px;
            }
            
            .film-poster-large {
                width: 250px;
            }
            
            .film-title-detail {
                font-size: 2.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .film-title-detail {
                font-size: 2rem;
            }
            
            .film-poster-large {
                width: 200px;
            }
            
            .film-meta-detail {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header" id="header">
        <a href="index.php" class="logo">LIANFLIX</a>
        <nav class="nav">
            <a href="index.php">Beranda</a>
            <a href="#">Film</a>
            <a href="#">Serial TV</a>
            <a href="#">Daftar Saya</a>
        </nav>
    </header>

    <section class="film-detail-hero">
        <img src="<?php echo htmlspecialchars($film_data['poster_url']); ?>" 
             alt="<?php echo htmlspecialchars($film_data['title']); ?>" 
             class="film-detail-backdrop"
             onerror="this.src='https://via.placeholder.com/1200x600/1a3a6d/ffffff?text=Poster+Tidak+Tersedia'">
        
        <div class="film-detail-content">
            <img src="<?php echo htmlspecialchars($film_data['poster_url']); ?>" 
                 alt="<?php echo htmlspecialchars($film_data['title']); ?>" 
                 class="film-poster-large"
                 onerror="this.src='https://via.placeholder.com/300x450/1a3a6d/ffffff?text=Poster+Tidak+Tersedia'">
            
            <div class="film-info-detail">
                <h1 class="film-title-detail"><?php echo htmlspecialchars($film_data['title']); ?></h1>
                
                <div class="film-meta-detail">
                    <span class="film-year-detail"><?php echo htmlspecialchars($film_data['release_year']); ?></span>
                    <span class="film-rating-detail"><?php echo htmlspecialchars($film_data['rating']); ?>/10</span>
                    <span class="film-category"><?php echo htmlspecialchars($film_data['category']); ?></span>
                    <span class="film-duration"><?php echo htmlspecialchars($film_data['duration']); ?></span>
                </div>
                
                <p class="film-description-detail"><?php echo htmlspecialchars($film_data['description']); ?></p>
                
                <div class="action-buttons">
                    <a href="<?php echo htmlspecialchars($film_data['video_url']); ?>" target="_blank" class="watch-btn-detail">
                        Tonton Sekarang
                    </a>
                    <a href="index.php" class="back-btn">Kembali ke Beranda</a>
                </div>
            </div>
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
    </script>
</body>
</html>