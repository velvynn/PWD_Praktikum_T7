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

// Handle delete
if(isset($_GET['delete_id'])) {
    $film->id = $_GET['delete_id'];
    if($film->delete()) {
        echo "<script>alert('Film berhasil dihapus!'); window.location.href='movies.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus film!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details - LianFlix</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .movie-management {
            margin-top: 100px;
            padding: 50px;
            min-height: 80vh;
        }

        .movie-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .movie-title {
            font-size: 2.5rem;
            color: var(--text-light);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .add-movie-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 168, 255, 0.3);
        }

        .add-movie-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 168, 255, 0.5);
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .movie-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 168, 255, 0.3);
            border-color: var(--secondary-color);
        }

        .movie-poster {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .movie-info {
            padding: 20px;
        }

        .movie-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-light);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .movie-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .movie-card-year {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .movie-card-genre {
            background: var(--primary-color);
            color: white;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .movie-card-category {
            background: var(--secondary-color);
            color: white;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .movie-card-description {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .movie-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            flex: 1;
            padding: 8px 15px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .watch-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .watch-btn:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
        }

        .edit-btn {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .edit-btn:hover {
            background: rgba(255, 193, 7, 0.3);
        }

        .delete-btn {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .delete-btn:hover {
            background: rgba(220, 53, 69, 0.3);
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px;
            color: var(--text-gray);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .movie-management {
                padding: 30px 20px;
                margin-top: 80px;
            }
            
            .movie-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .movie-grid {
                grid-template-columns: 1fr;
            }
            
            .movie-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="header" id="header">
        <a href="index.php" class="logo">LIANFLIX</a>
        <nav class="nav">
            <a href="index.php">Beranda</a>
            <a href="movies.php" class="active">Movie</a>
            <a href="#">Serial TV</a>
            <a href="#">Baru & Terpopuler</a>
            <a href="movies.php">Daftar Saya</a>
            <a href="add_movie.php" class="add-movie-btn"><i class="fas fa-plus"></i> Tambah Film</a>
        </nav>
    </header>

    <section class="movie-management">
        <div class="movie-header">
            <h1 class="movie-title">Movie Details</h1>
            <a href="add_movie.php" class="add-movie-btn">
                <i class="fas fa-plus-circle"></i> Tambah Movie Baru
            </a>
        </div>

        <div class="movie-grid">
            <?php if(count($films) > 0): ?>
                <?php foreach($films as $film): ?>
                <div class="movie-card">
                    <img src="<?php echo htmlspecialchars($film['poster_url']); ?>" 
                         alt="<?php echo htmlspecialchars($film['title']); ?>" 
                         class="movie-poster"
                         onerror="this.src='https://via.placeholder.com/300x200/1a3a6d/ffffff?text=<?php echo urlencode($film['title']); ?>'">
                    
                    <div class="movie-info">
                        <h3 class="movie-card-title">
                            <?php echo htmlspecialchars($film['title']); ?>
                            <span class="movie-card-rating"><?php echo htmlspecialchars($film['rating']); ?>/10</span>
                        </h3>
                        
                        <div class="movie-card-meta">
                            <span class="movie-card-year"><?php echo htmlspecialchars($film['release_year']); ?></span>
                            <span class="movie-card-genre"><?php echo htmlspecialchars($film['genre']); ?></span>
                            <span class="movie-card-category"><?php echo htmlspecialchars($film['category']); ?></span>
                            <span class="movie-card-duration"><?php echo htmlspecialchars($film['duration']); ?></span>
                        </div>
                        
                        <p class="movie-card-description">
                            <?php echo htmlspecialchars($film['description']); ?>
                        </p>
                        
                        <div class="movie-actions">
                            <a href="film_detail.php?id=<?php echo $film['id']; ?>" class="action-btn watch-btn">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="edit_movie.php?id=<?php echo $film['id']; ?>" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="movies.php?delete_id=<?php echo $film['id']; ?>" 
                               class="action-btn delete-btn"
                               onclick="return confirm('Yakin ingin menghapus film ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-film"></i>
                    <h3>Belum ada film</h3>
                    <p>Mulai dengan menambahkan film pertama Anda</p>
                    <a href="add_movie.php" class="add-movie-btn" style="display: inline-flex; margin-top: 20px;">
                        <i class="fas fa-plus"></i> Tambah Film Pertama
                    </a>
                </div>
            <?php endif; ?>
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
    </script>
</body>
</html>