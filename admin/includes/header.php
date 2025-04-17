<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Navigation -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-6 py-3 ">
            <div class="flex justify-between items-center ">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-xl font-bold">Admin Panel</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                    <a href="?logout=1" class="text-sm hover:text-gray-300 transition duration-300">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Sidebar Button -->
    <div class="lg:hidden fixed bottom-4 right-4 z-40">
        <button id="sidebarToggle" class="bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-700 transition duration-300">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Sidebar -->
    <div id="sidebar" class="bg-gray-700 text-white w-64 fixed h-full lg:block z-30 transform -translate-x-full lg:translate-x-0 transition duration-300 ease-in-out">
        <div class="p-4">
            <div class="mt-8">
                <ul class="space-y-2">
                    <li>
                        <a href="index.php" class="flex items-center p-2 rounded-lg hover:bg-gray-600 transition duration-300 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-600' : ''; ?>">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="projects.php" class="flex items-center p-2 rounded-lg hover:bg-gray-600 transition duration-300 <?php echo basename($_SERVER['PHP_SELF']) == 'projects.php' ? 'bg-gray-600' : ''; ?>">
                            <i class="fas fa-project-diagram mr-3"></i>
                            <span>Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="messages.php" class="flex items-center p-2 rounded-lg hover:bg-gray-600 transition duration-300 <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'bg-gray-600' : ''; ?>">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>Messages</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" class="flex items-center p-2 rounded-lg hover:bg-gray-600 transition duration-300 <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-gray-600' : ''; ?>">
                            <i class="fas fa-cog mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="lg:ml-64">
        <script>
            // Toggle mobile sidebar
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('-translate-x-full');
            });
        </script>