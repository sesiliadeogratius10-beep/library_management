<?php
require_once 'db.php';

$message = '';
$error = '';

// Handling form submission when user clicks 'Save Book'
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category_id = $_POST['category_id'];
    $total_copies = $_POST['total_copies'];

    if (!empty($title) && !empty($author) && !empty($category_id) && !empty($total_copies)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO books (title, author, category_id, total_copies, available_copies) VALUES (?, ?, ?, ?, ?)");
            // Initially, available copies equals total copies when adding a new book
            $stmt->execute([$title, $author, $category_id, $total_copies, $total_copies]);
            $message = "Book added successfully!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

// Fetch categories for the dropdown selection
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library Management System | Add Book</title>
    <!-- AdminLTE 3 CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link">Home</a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index.php" class="brand-link">
            <span class="brand-text font-weight-light">Library System</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="add_book.php" class="nav-link active">
                            <i class="nav-icon fas fa-plus-circle"></i>
                            <p>Add Book</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="categories.php" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Members</p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="borrowings.php" class="nav-link">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Borrowings</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="libraries.php" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Libraries</p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="librarians.php" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Librarians</p>
                        </a>
                    </li>
                     
                    <li class="nav-item">
                        <a href="system_report.php" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>System Reports</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Add New Book</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        
                        <!-- Alert Messages -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form Card -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Book Entry Form</h3>
                            </div>
                            <!-- Form Start -->
                            <form action="add_book.php" method="POST">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="title">Book Title</label>
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter book title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="author">Author</label>
                                        <input type="text" class="form-control" id="author" name="author" placeholder="Enter author name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_copies">Total Copies</label>
                                        <input type="number" class="form-control" id="total_copies" name="total_copies" placeholder="Enter number of copies" min="1" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save Book</button>
                                    <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

</div>

<!-- AdminLTE & jQuery JS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
