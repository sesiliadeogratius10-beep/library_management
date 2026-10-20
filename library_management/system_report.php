<?php
require_once 'db.php';

// Kuchukua takwimu na ripoti mbalimbali kutoka kwenye database
$totalBooks = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$totalMembers = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
$totalBorrowings = $pdo->query("SELECT COUNT(*) FROM borrowings")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalLibrarians = $pdo->query("SELECT COUNT(*) FROM librarians")->fetchColumn();
$totalLibraries = $pdo->query("SELECT COUNT(*) FROM libraries")->fetchColumn();

// Kuchukua ripoti ya hivi karibuni ya mikopo
$recentBorrowings = $pdo->query("
    SELECT br.*, b.book_title, m.first_name, m.last_name 
    FROM borrowings br 
    JOIN books b ON br.book_id = b.book_id 
    JOIN members m ON br.member_id = m.member_id 
    ORDER BY br.borrowing_id DESC LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library Management System | Reports</title>
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
                        <a href="add_book.php" class="nav-link">
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
                        <a href="reports.php" class="nav-link active">
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
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Library Summary & Reports</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button onclick="window.print();" class="btn btn-primary"><i class="fas fa-print"></i> Print Report</button>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                
                <!-- Info Boxes -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $totalBooks; ?></h3>
                                <p>Total Books</p>
                            </div>
                            <div class="icon"><i class="fas fa-book"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $totalMembers; ?></h3>
                                <p>Total Members</p>
                            </div>
                            <div class="icon"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $totalBorrowings; ?></h3>
                                <p>Total Borrowings</p>
                            </div>
                            <div class="icon"><i class="fas fa-exchange-alt"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $totalCategories; ?></h3>
                                <p>Categories</p>
                            </div>
                            <div class="icon"><i class="fas fa-list"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Info Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Administrative Overview</h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Registered Librarians</b> <span class="float-right badge bg-primary"><?php echo $totalLibrarians; ?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Library Branches</b> <span class="float-right badge bg-success"><?php echo $totalLibraries; ?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Total Inventory Records</b> <span class="float-right badge bg-warning"><?php echo $totalBooks; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title">Quick Actions</h3>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted">Unaweza kuchapa au kuhifadhi ripoti hii kwa urahisi kwa kubonyeza kitufe hapo juu.</p>
                                <a href="index.php" class="btn btn-app bg-secondary"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                <a href="borrowings.php" class="btn btn-app bg-warning"><i class="fas fa-book-reader"></i> Borrowings</a>
                                <a href="members.php" class="btn btn-app bg-success"><i class="fas fa-user-plus"></i> Members</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Borrowings Table Report -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Borrowing Transactions Report</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Book Title</th>
                                            <th>Member Name</th>
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($recentBorrowings) > 0): ?>
                                            <?php foreach ($recentBorrowings as $row): ?>
                                            <tr>
                                                <td><?php echo $row['borrowing_id']; ?></td>
                                                <td><strong><?php echo htmlspecialchars($row['book_title']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                <td><?php echo $row['borrow_date']; ?></td>
                                                <td><?php echo $row['due_date']; ?></td>
                                                <td><span class="badge bg-warning"><?php echo $row['status']; ?></span></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No transactions recorded yet.</td>
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
