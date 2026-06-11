<?php
// admin.php — CRUD admin panel
require_once __DIR__ . '/config.php';
$pdo = getDBConnection();

$catStmt    = $pdo->query('SELECT * FROM categories ORDER BY sort_order, id');
$categories = $catStmt->fetchAll();

$slideStmt = $pdo->query('SELECT s.*, c.name AS category_name FROM slides s JOIN categories c ON s.category_id=c.id ORDER BY c.sort_order, s.sort_order, s.id');
$slides    = $slideStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WPoets Admin — CRUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">

<header class="site-header">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo">W<span>POETS</span></a>
            <a href="index.php" class="btn btn-outline-light btn-sm">← Portfolio</a>
        </div>
    </div>
</header>

<div class="container-fluid px-4 py-5">
    <h1 class="admin-title mb-1">Content Manager</h1>
    <p class="text-muted mb-5">Manage portfolio categories and slides.</p>

    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index:9999"></div>

    <!-- ── CATEGORIES ─────────────────────────────────────────── -->
    <section class="admin-section mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="admin-section-title mb-0">Categories</h2>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#catModal" onclick="openCatModal()">+ Add Category</button>
        </div>

        <div class="table-responsive admin-table-wrap">
            <table class="table admin-table" id="catTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Sort Order</th>
                        <th>Slides</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr data-id="<?= $cat['id'] ?>">
                        <td><?= $cat['id'] ?></td>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= $cat['sort_order'] ?></td>
                        <td>
                            <?php
                            $count = $pdo->prepare('SELECT COUNT(*) FROM slides WHERE category_id=?');
                            $count->execute([$cat['id']]);
                            echo $count->fetchColumn();
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary me-1"
                                onclick="openCatModal(<?= htmlspecialchars(json_encode($cat)) ?>)">Edit</button>
                            <button class="btn btn-sm btn-outline-danger"
                                onclick="deleteCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name']) ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ── SLIDES ──────────────────────────────────────────────── -->
    <section class="admin-section">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="admin-section-title mb-0">Slides</h2>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#slideModal" onclick="openSlideModal()">+ Add Slide</button>
        </div>

        <div class="table-responsive admin-table-wrap">
            <table class="table admin-table" id="slideTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slides as $slide): ?>
                    <tr data-id="<?= $slide['id'] ?>">
                        <td><?= $slide['id'] ?></td>
                        <td><?= htmlspecialchars($slide['category_name']) ?></td>
                        <td><?= htmlspecialchars($slide['title']) ?></td>
                        <td class="desc-cell"><?= htmlspecialchars($slide['description']) ?></td>
                        <td><img src="<?= htmlspecialchars($slide['image_url']) ?>" class="thumb-img" alt=""></td>
                        <td><?= $slide['sort_order'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary me-1"
                                onclick="openSlideModal(<?= htmlspecialchars(json_encode($slide)) ?>)">Edit</button>
                            <button class="btn btn-sm btn-outline-danger"
                                onclick="deleteSlide(<?= $slide['id'] ?>, '<?= htmlspecialchars($slide['title']) ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- ── CATEGORY MODAL ─────────────────────────────────────────── -->
<div class="modal fade" id="catModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content admin-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="catModalTitle">Add Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="catId">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control admin-input" id="catName" placeholder="e.g. Architecture">
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" class="form-control admin-input" id="catOrder" value="0" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()">Save Category</button>
            </div>
        </div>
    </div>
</div>

<!-- ── SLIDE MODAL ────────────────────────────────────────────── -->
<div class="modal fade" id="slideModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content admin-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="slideModalTitle">Add Slide</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="slideId">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select admin-input" id="slideCatId">
                            <option value="">Select category…</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Sort Order</label>
                        <input type="number" class="form-control admin-input" id="slideOrder" value="0" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control admin-input" id="slideTitle" placeholder="Slide title">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control admin-input" id="slideDesc" rows="3" placeholder="Short description…"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Image URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control admin-input" id="slideImg" placeholder="https://…">
                        <div class="mt-2">
                            <img id="imgPreview" src="" alt="" class="img-preview d-none">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSlide()">Save Slide</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="js/admin.js"></script>
</body>
</html>
