<?php
// index.php — Main page
require_once __DIR__ . '/config.php';

$pdo = getDBConnection();

// Fetch all categories
$catStmt    = $pdo->query('SELECT * FROM categories ORDER BY sort_order, id');
$categories = $catStmt->fetchAll();

// Fetch all slides keyed by category_id
$slideStmt = $pdo->query('SELECT * FROM slides ORDER BY sort_order, id');
$allSlides = $slideStmt->fetchAll();
$slidesByCategory = [];
foreach ($allSlides as $slide) {
    $slidesByCategory[$slide['category_id']][] = $slide;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WPoets — Portfolio Showcase</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ── HEADER ────────────────────────────────────────────────────── -->
<header class="site-header">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo">W<span>POETS</span></a>
            <nav class="header-nav d-none d-md-flex gap-4">
                <a href="index.php">Portfolio</a>
                <a href="admin.php">Admin</a>
            </nav>
            <a href="admin.php" class="btn btn-outline-light btn-sm d-md-none">Admin</a>
        </div>
    </div>
</header>

<!-- ── HERO LABEL ─────────────────────────────────────────────────── -->
<section class="section-label">
    <div class="container-fluid px-4">
        <p class="eyebrow">Selected Works</p>
        <h1 class="section-title">Our Portfolio</h1>
    </div>
</section>

<!-- ── MAIN 3-COLUMN SECTION ──────────────────────────────────────── -->
<section class="showcase-section">
    <div class="container-fluid px-0">
        <div class="row g-0 showcase-grid">

            <!-- ── COLUMN 1: Tabs / Accordion ──────────────────── -->
            <div class="col-12 col-lg-2 col-xl-2 col-tabs">
                <!-- Desktop Tabs -->
                <nav class="tab-nav d-none d-lg-flex flex-column" id="categoryTabs">
                    <?php foreach ($categories as $i => $cat): ?>
                    <button
                        class="tab-btn <?= $i === 0 ? 'active' : '' ?>"
                        data-category="<?= $cat['id'] ?>"
                        data-index="<?= $i ?>"
                    >
                        <span class="tab-number"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="tab-label"><?= htmlspecialchars($cat['name']) ?></span>
                        <span class="tab-arrow">→</span>
                    </button>
                    <?php endforeach; ?>
                </nav>

                <!-- Mobile Accordion -->
                <div class="accordion d-lg-none" id="mobileAccordion">
                    <?php foreach ($categories as $i => $cat):
                        $slides = $slidesByCategory[$cat['id']] ?? [];
                    ?>
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button <?= $i !== 0 ? 'collapsed' : '' ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#acc<?= $cat['id'] ?>"
                            >
                                <span class="acc-number"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                <?= htmlspecialchars($cat['name']) ?>
                            </button>
                        </h2>
                        <div id="acc<?= $cat['id'] ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#mobileAccordion">
                            <div class="accordion-body p-0">
                                <!-- Mobile Slider: full-bleed image bg + text -->
                                <div class="swiper mobile-swiper" id="mobileSwiper<?= $cat['id'] ?>">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($slides as $slide): ?>
                                        <div class="swiper-slide mobile-slide" style="background-image: url('<?= htmlspecialchars($slide['image_url']) ?>')">
                                            <div class="mobile-slide-inner">
                                                <h3><?= htmlspecialchars($slide['title']) ?></h3>
                                                <p><?= htmlspecialchars($slide['description']) ?></p>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ── COLUMN 2: Desktop Slider (text + controls) ── -->
            <div class="col-lg-5 col-xl-6 d-none d-lg-block col-slider">
                <?php foreach ($categories as $i => $cat):
                    $slides = $slidesByCategory[$cat['id']] ?? [];
                ?>
                <div class="desktop-slider-wrap <?= $i === 0 ? 'active' : '' ?>" data-category="<?= $cat['id'] ?>">
                    <div class="swiper desktop-swiper" id="desktopSwiper<?= $cat['id'] ?>">
                        <div class="swiper-wrapper">
                            <?php foreach ($slides as $slide): ?>
                            <div class="swiper-slide desktop-slide" data-image="<?= htmlspecialchars($slide['image_url']) ?>">
                                <div class="slide-content">
                                    <p class="slide-category-label"><?= htmlspecialchars($cat['name']) ?></p>
                                    <h2 class="slide-title"><?= htmlspecialchars($slide['title']) ?></h2>
                                    <p class="slide-desc"><?= htmlspecialchars($slide['description']) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Controls inside col 2 -->
                        <div class="swiper-controls d-flex align-items-center gap-3">
                            <button class="swiper-btn swiper-btn-prev" id="prev<?= $cat['id'] ?>">←</button>
                            <div class="swiper-pagination inline-pagination" id="pag<?= $cat['id'] ?>"></div>
                            <button class="swiper-btn swiper-btn-next" id="next<?= $cat['id'] ?>">→</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── COLUMN 3: Feature Image (1:1) ─────────────── -->
            <div class="col-lg-5 col-xl-4 d-none d-lg-block col-image">
                <div class="feature-image-wrap">
                    <div class="feature-image" id="featureImage">
                        <?php
                        // Default: first image of first category
                        $firstSlide = !empty($slidesByCategory[$categories[0]['id']]) ? $slidesByCategory[$categories[0]['id']][0] : null;
                        if ($firstSlide):
                        ?>
                        <img src="<?= htmlspecialchars($firstSlide['image_url']) ?>" alt="<?= htmlspecialchars($firstSlide['title']) ?>" id="featureImg">
                        <?php endif; ?>
                        <div class="image-overlay">
                            <span id="featureCounter">01 / <?= count($slidesByCategory[$categories[0]['id']] ?? []) ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<!-- Pass PHP data to JS -->
<script>
    const WP_DATA = <?= json_encode([
        'categories'       => $categories,
        'slidesByCategory' => $slidesByCategory,
    ]) ?>;
</script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
