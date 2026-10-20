<?php
require_once 'db.php';

$message = '';
$error = '';

// Kurekodi mkopo mpya kwenye Database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_borrowing'])) {
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $due_date = $_POST['due_date'];

    if (!empty($book_id) && !empty($member_id) && !empty($due_date)) {
        try {
            $pdo->beginTransaction();

            // 1. Ingiza rekodi ya mkopo
            $stmt = $pdo->prepare("INSERT INTO borrowings (book_id, member_id, borrow_date, due_date, status) VALUES (?, ?, CURDATE(), ?, 'Borrowed')");
            $stmt->execute([$book_id, $member_id, $due_date]);

            // 2. Punguza idadi ya vitabu kwenye quantity
            $updateBook = $pdo->prepare("UPDATE books SET quantity = quantity - 1 WHERE book_id = ? AND quantity > 0");
            $updateBook->execute([$book_id]);

            if ($updateBook->rowCount() === 0) {
                throw new Exception("Kitabu hiki kimekwisha (Out of Stock), hakiwezi kukopwa!");
            }

            $pdo->commit();
            $message = "Mkopo umehifadhiwa kwa mafanikio!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Hitilafu: " . $e->getMessage();
        }
    } else {
        $error = "Tafadhali jaza taarifa zote zinazohitajika.";
    }
}

// Kuchukua orodha ya vitabu vyote
$books = $pdo->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);

// Kuchukua orodha ya members wote
$members = $pdo->query("SELECT * FROM members")->fetchAll(PDO::FETCH_ASSOC);

// Kuchukua orodha yote ya Borrowings ikitumia 'book_title'
$borrowings = $pdo->query("
    SELECT br.*, b.book_title, m.first_name, m.last_name 
    FROM borrowings br 
    JOIN books b ON br.book_id = b.book_id 
    JOIN members m ON br.member_id = m.member_id 
    ORDER BY br.borrowing_id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library Management System | Borrowings</title>
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
                        <a href="borrowings.php" class="nav-link active">
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
                        <h1 class="m-0">Book Borrowings & Loans</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                
                <!-- Alerts -->
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

                <div class="row">
                    <!-- Column 1: Issue Book Form -->
                    <div class="col-md-4">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Issue Book (New Loan)</h3>
                            </div>
                            <form action="borrowings.php" method="POST">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="book_id">Select Book</label>
                                        <select class="form-control" id="book_id" name="book_id" required>
                                            <option value="">-- Choose Book --</option>
                                            <?php foreach ($books as $b): ?>
                                                <option value="<?php echo $b['book_id']; ?>"><?php echo htmlspecialchars($b['book_title']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="member_id">Select Member</label>
                                        <select class="form-control" id="member_id" name="member_id" required>
                                            <option value="">-- Choose Member --</option>
                                            <?php foreach ($members as $m): ?>
                                                <option value="<?php echo $m['member_id']; ?>"><?php echo htmlspecialchars($m['first_name'] . ' ' . $m['last_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="add_borrowing" class="btn btn-warning btn-block">Issue Book</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Column 2: Borrowings List Table -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Active Borrowings List</h3>
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
                                        <?php if (count($borrowings) > 0): ?>
                                            <?php foreach ($borrowings as $row): ?>
                                            <tr>
                                                <td><?php echo $row['borrowing_id']; ?></td>
                                                <td><strong><?php echo htmlspecialchars($row['book_title']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                <td><?php echo $row['borrow_date']; ?></td>
                                                <td><?php echo $row['due_date']; ?></td>
                                                <td>
                                                    <span class="badge bg-warning"><?php echo $row['status']; ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">Hakuna kumbukumbu za mikopo kwa sasa.</td>
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
