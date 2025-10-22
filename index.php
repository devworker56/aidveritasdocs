<?php
// index.php - Documentation portal homepage
require_once 'includes/config.php';
require_once 'includes/database.php';

$page_title = $current_language === 'fr' ? 'Accueil Documentation' : 'Documentation Home';
$current_page = 'home';

// Get all active sections with their chapters
$db = new Database();
$conn = $db->getConnection();

$sections = [];
if ($conn) {
    $stmt = $conn->prepare("
        SELECT s.*, 
               COUNT(DISTINCT c.id) as chapter_count,
               COUNT(DISTINCT p.id) as page_count
        FROM sections s
        LEFT JOIN chapters c ON s.id = c.section_id AND c.is_active = TRUE
        LEFT JOIN pages p ON c.id = p.chapter_id AND p.is_active = TRUE
        WHERE s.is_active = TRUE
        GROUP BY s.id
        ORDER BY s.sort_order
    ");
    $stmt->execute();
    $sections = $stmt->fetchAll();
}
?>

<?php include 'includes/header.php'; ?>

<!-- Main Banner -->
<section class="main-banner">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold text-white mb-4">
                    <?php echo $current_language === 'fr' ? 'Documentation AidVeritas' : 'AidVeritas Documentation'; ?>
                </h1>
                <p class="lead text-white mb-4">
                    <?php echo $current_language === 'fr' 
                        ? 'Guide complet pour utiliser l\'écosystème AidVeritas. Trouvez tout ce dont vous avez besoin pour les dons vérifiables et attribués.'
                        : 'Complete guide to using the AidVeritas ecosystem. Find everything you need for verifiable and attributed donations.'; ?>
                </p>
                <div class="search-box">
                    <form action="search.php" method="GET">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" name="q" 
                                   placeholder="<?php echo $lang['search_placeholder']; ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sections Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($sections as $section): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card section-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="text-primary mb-3">
                            <i class="<?php echo $section['icon']; ?> fa-3x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">
                            <?php echo $current_language === 'fr' ? $section['title_fr'] : $section['title_en']; ?>
                        </h4>
                        <p class="text-muted mb-4">
                            <?php echo $current_language === 'fr' ? $section['description_fr'] : $section['description_en']; ?>
                        </p>
                        <div class="stats mb-4">
                            <span class="badge bg-primary me-2">
                                <?php echo $section['chapter_count']; ?> 
                                <?php echo $current_language === 'fr' ? 'chapitres' : 'chapters'; ?>
                            </span>
                            <span class="badge bg-secondary">
                                <?php echo $section['page_count']; ?> 
                                <?php echo $current_language === 'fr' ? 'pages' : 'pages'; ?>
                            </span>
                        </div>
                        <a href="section.php?id=<?php echo $section['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-book-open me-2"></i>
                            <?php echo $lang['view_all']; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quick Links -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold text-primary mb-5">
                    <?php echo $current_language === 'fr' ? 'Guides Rapides' : 'Quick Guides'; ?>
                </h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <i class="fas fa-download fa-2x text-primary mb-3"></i>
                                <h5><?php echo $current_language === 'fr' ? 'Premiers Pas' : 'Getting Started'; ?></h5>
                                <p class="text-muted small">
                                    <?php echo $current_language === 'fr' 
                                        ? 'Installez l\'application et créez votre compte'
                                        : 'Install the app and create your account'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <i class="fas fa-qrcode fa-2x text-primary mb-3"></i>
                                <h5><?php echo $current_language === 'fr' ? 'Scanner un QR' : 'Scan QR Code'; ?></h5>
                                <p class="text-muted small">
                                    <?php echo $current_language === 'fr' 
                                        ? 'Comment utiliser les codes QR pour faire un don'
                                        : 'How to use QR codes to make a donation'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <i class="fas fa-receipt fa-2x text-primary mb-3"></i>
                                <h5><?php echo $current_language === 'fr' ? 'Reçus Fiscaux' : 'Tax Receipts'; ?></h5>
                                <p class="text-muted small">
                                    <?php echo $current_language === 'fr' 
                                        ? 'Comprendre et utiliser vos reçus fiscaux'
                                        : 'Understand and use your tax receipts'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>