<?php
require_once 'db.php';

// Fetch total counts for Dashboard Small Boxes
$totalBooks =$pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$totalMembers =$pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
$totalBorrowings =$pdo->query("SELECT COUNT(*) FROM borrowings")->fetchColumn();

// Fetch book list for the List Screen
$stmt =$pdo->query("SELECT b.*, c.category_name FROM books b LEFT JOIN categories c ON b.category_id = c.category_id");
$books =$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library Management System | Dashboard</title>
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
                        <a href="index.php" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="add_book.php" class="nav-link">
                            <i class="nav-icon fas fa-plus-circle"></i>
                            <p>Add book</p>
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
                        <h1 class="m-0">Library Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                
                <!-- Small Boxes (Dashboard Summary) -->
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $totalBooks; ?></h3>
                                <p>Total Books</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $totalMembers; ?></h3>
                                <p>Total Members</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $totalBorrowings; ?></h3>
                                <p>Active Borrowings</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book List Screen (Table) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Book Inventory List</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Category</th>
                                            <th>Available Copies</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($books) > 0): ?>
                                            <?php foreach ($books as $book): ?>
                                            <tr>
                                                <td><?php echo $book['book_id']; ?></td>
                                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                                <td><?php echo htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?></td>
                                                <td><?php echo $book['available_copies']; ?></td>
                                                <td>
                                                    <?php if ($book['available_copies'] > 0): ?>
                                                        <span class="badge bg-success">In Stock</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No books found in the database.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
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

