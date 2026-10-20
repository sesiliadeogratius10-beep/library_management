
<?php
require_once 'db.php';

$message = '';
$error = '';
$libraries = [];

try {
    // Kuongeza Library Branch mpya
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_library'])) {

        $library_name = trim($_POST['library_name'] ?? '');
        $location = trim($_POST['location'] ?? '');

        if ($library_name === '') {
            $error = "Please enter library branch name.";
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO libraries (library_name, location)
                 VALUES (?, ?)"
            );

            $stmt->execute([
                $library_name,
                $location !== '' ? $location : null
            ]);

            // Kuzuia form kutumwa tena wakati wa refresh
            header("Location: library_branches.php?success=1");
            exit;
        }
    }

    if (isset($_GET['success'])) {
        $message = "Library branch added successfully!";
    }

    // Kuchukua branches zote
    $stmt = $pdo->query(
        "SELECT * FROM libraries ORDER BY library_id DESC"
    );

    $libraries = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Library Management System | Branches</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>

            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link">Home</a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="index.php" class="brand-link">
            <span class="brand-text font-weight-light">Library System</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview" role="menu">

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
                        <a href="borrowings.php" class="nav-link active">
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
                        <a href="reports.php" class="nav-link">
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

        <!-- Page Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Library Branches</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">

                <?php if ($message !== ''): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close"
                                data-dismiss="alert" aria-hidden="true">
                            &times;
                        </button>

                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close"
                                data-dismiss="alert" aria-hidden="true">
                            &times;
                        </button>

                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="row">

                    <!-- Add Branch Form -->
                    <div class="col-md-4">
                        <div class="card card-secondary">

                            <div class="card-header">
                                <h3 class="card-title">Add Branch</h3>
                            </div>

                            <form action="library_branches.php" method="POST">

                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="library_name">
                                            Branch Name
                                        </label>

                                        <input type="text"
                                               class="form-control"
                                               id="library_name"
                                               name="library_name"
                                               placeholder="e.g. Main Library"
                                               maxlength="150"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label for="location">
                                            Location / Building
                                        </label>

                                        <input type="text"
                                               class="form-control"
                                               id="location"
                                               name="location"
                                               placeholder="e.g. Science Block, Ground Floor">
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="submit"
                                            name="add_library"
                                            class="btn btn-secondary btn-block">
                                        <i class="fas fa-save"></i>
                                        Save Branch
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Branches Table -->
                    <div class="col-md-8">
                        <div class="card">

                            <div class="card-header">
                                <h3 class="card-title">Branches List</h3>
                            </div>

                            <div class="card-body table-responsive p-0">

                                <table class="table table-hover text-nowrap">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Branch Name</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php if (count($libraries) > 0): ?>

                                            <?php foreach ($libraries as $lib): ?>
                                                <tr>
                                                    <td>
                                                        <?= htmlspecialchars(
                                                            (string)$lib['library_id']
                                                        ) ?>
                                                    </td>

                                                    <td>
                                                        <strong>
                                                            <?= htmlspecialchars(
                                                                (string)$lib['library_name']
                                                            ) ?>
                                                        </strong>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars(
                                                            $lib['location'] ?? 'N/A'
                                                        ) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php else: ?>

                                            <tr>
                                                <td colspan="3"
                                                    class="text-center">
                                                    No library branches found
                                                    in database.
                                                </td>
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

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Library Management System</strong>
        <div class="float-right d-none d-sm-inline-block">
            AdminLTE 3
        </div>
    </footer>

</div>

<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
```