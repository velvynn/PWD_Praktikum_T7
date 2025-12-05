<?php
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once 'config.php';
include_once 'Film.php';

$database = new Database();
$db = $database->getConnection();

$film = new Film($db);

$film_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$film->readOne($film_id)){
    echo "<script>alert('Film tidak ditemukan!'); window.location.href='movies.php';</script>";
    exit();
}

$message = '';
$message_type = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $film->title = $_POST['title'];
    $film->poster_url = $_POST['poster_url'];
    $film->video_url = $_POST['video_url'];
    $film->description = $_POST['description'];
    $film->release_year = $_POST['release_year'];
    $film->category = $_POST['category'];
    $film->genre = $_POST['genre'];
    $film->duration = $_POST['duration'];
    $film->rating = $_POST['rating'];
    $film->id = $film_id;
    
    if($film->update()){
        $message = "Film berhasil diperbarui!";
        $message_type = "success";
    } else{
        $message = "Gagal memperbarui film.";
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Film - LianFlix</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .edit-movie-container {
            margin-top: 100px;
            padding: 50px;
            min-height: 80vh;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-title {
            font-size: 2.5rem;
            color: var(--text-light);
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .movie-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-light);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.5);
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(0, 168, 255, 0.2);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-actions {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .submit-btn {
            flex: 1;
            padding: 15px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 168, 255, 0.5);
        }

        .cancel-btn {
            flex: 1;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .cancel-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .delete-btn {
            flex: 1;
            padding: 15px;
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .delete-btn:hover {
            background: rgba(220, 53, 69, 0.3);
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .message.success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .message.error {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .preview-section {
            margin-top: 20px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .preview-title {
            color: var(--text-light);
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .poster-preview {
            max-width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .edit-movie-container {
                padding: 30px 20px;
                margin-top: 80px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .movie-form {
                padding: 20px;
            }
            
            .form-actions {
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
            <a href="movies.php">Movie</a>
            <a href="#">Serial TV</a>
            <a href="#">Baru & Terpopuler</a>
            <a href="movies.php">Daftar Saya</a>
            <a href="add_movie.php" class="add-movie-btn"><i class="fas fa-plus"></i> Tambah Film</a>
        </nav>
    </header>

    <section class="edit-movie-container">
        <h1 class="form-title">Edit Film: <?php echo htmlspecialchars($film->title); ?></h1>
        
        <?php if($message): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <form class="movie-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $film_id); ?>">
            <div class="form-group">
                <label class="form-label">Judul Film *</label>
                <input type="text" class="form-input" name="title" required 
                       value="<?php echo htmlspecialchars($film->title); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">URL Poster *</label>
                <input type="url" class="form-input" name="poster_url" required 
                       value="<?php echo htmlspecialchars($film->poster_url); ?>">
                <div class="preview-section">
                    <div class="preview-title">Preview Poster:</div>
                    <img id="posterPreview" src="<?php echo htmlspecialchars($film->poster_url); ?>" 
                         alt="Preview Poster" class="poster-preview">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">URL Video *</label>
                    <input type="url" class="form-input" name="video_url" required 
                           value="<?php echo htmlspecialchars($film->video_url); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tahun Rilis *</label>
                    <input type="number" class="form-input" name="release_year" required 
                           min="1900" max="2030" value="<?php echo htmlspecialchars($film->release_year); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select class="form-select" name="category" required>
                        <option value="Animation" <?php echo ($film->category == 'Animation') ? 'selected' : ''; ?>>Animation</option>
                        <option value="Action" <?php echo ($film->category == 'Action') ? 'selected' : ''; ?>>Action</option>
                        <option value="Comedy" <?php echo ($film->category == 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
                        <option value="Drama" <?php echo ($film->category == 'Drama') ? 'selected' : ''; ?>>Drama</option>
                        <option value="Fantasy" <?php echo ($film->category == 'Fantasy') ? 'selected' : ''; ?>>Fantasy</option>
                        <option value="Horror" <?php echo ($film->category == 'Horror') ? 'selected' : ''; ?>>Horror</option>
                        <option value="Romance" <?php echo ($film->category == 'Romance') ? 'selected' : ''; ?>>Romance</option>
                        <option value="Sci-Fi" <?php echo ($film->category == 'Sci-Fi') ? 'selected' : ''; ?>>Sci-Fi</option>
                        <option value="Thriller" <?php echo ($film->category == 'Thriller') ? 'selected' : ''; ?>>Thriller</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Genre *</label>
                    <input type="text" class="form-input" name="genre" required 
                           value="<?php echo htmlspecialchars($film->genre); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Durasi *</label>
                    <input type="text" class="form-input" name="duration" required 
                           value="<?php echo htmlspecialchars($film->duration); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Rating *</label>
                    <input type="number" class="form-input" name="rating" required 
                           min="0" max="10" step="0.1" value="<?php echo htmlspecialchars($film->rating); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi *</label>
                <textarea class="form-textarea" name="description" required rows="5"><?php echo htmlspecialchars($film->description); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Update Film
                </button>
                <a href="movies.php" class="cancel-btn">
                    <i class="fas fa-times"></i> Batal
                </a>
                <a href="movies.php?delete_id=<?php echo $film_id; ?>" 
                   class="delete-btn"
                   onclick="return confirm('Yakin ingin menghapus film ini?')">
                    <i class="fas fa-trash"></i> Hapus Film
                </a>
            </div>
        </form>
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
        // Update poster preview
        document.querySelector('input[name="poster_url"]').addEventListener('input', function(e) {
            const preview = document.getElementById('posterPreview');
            preview.src = this.value;
        });

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