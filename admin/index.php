<?php
include 'includes/auth.php';
include 'includes/header.php';

// Get counts for dashboard
$projects_count = $conn->query("SELECT COUNT(*) FROM projects")->fetch_row()[0];
$messages_count = $conn->query("SELECT COUNT(*) FROM messages WHERE is_read = FALSE")->fetch_row()[0];
$total_views = $conn->query("SELECT SUM(views) FROM projects")->fetch_row()[0];
?>

<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Projects Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Projects</h3>
                        <p class="text-3xl font-bold text-indigo-600"><?php echo $projects_count; ?></p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-project-diagram text-indigo-600 text-xl"></i>
                    </div>
                </div>
                <a href="projects.php" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium">View Projects →</a>
            </div>
            
            <!-- Messages Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">New Messages</h3>
                        <p class="text-3xl font-bold text-pink-600"><?php echo $messages_count; ?></p>
                    </div>
                    <div class="bg-pink-100 p-3 rounded-full">
                        <i class="fas fa-envelope text-pink-600 text-xl"></i>
                    </div>
                </div>
                <a href="messages.php" class="mt-4 inline-block text-pink-600 hover:text-pink-800 font-medium">View Messages →</a>
            </div>
            
            <!-- Views Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Project Views</h3>
                        <p class="text-3xl font-bold text-green-600"><?php echo $total_views; ?></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-eye text-green-600 text-xl"></i>
                    </div>
                </div>
                <a href="projects.php" class="mt-4 inline-block text-green-600 hover:text-green-800 font-medium">View Analytics →</a>
            </div>
        </div>
        
        <!-- Recent Messages -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Recent Messages</h2>
                <a href="messages.php" class="text-blue-600 hover:text-blue-800 font-medium">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $sql = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">'.$row['name'].'</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">'.$row['email'].'</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">'.substr($row['subject'], 0, 30).(strlen($row['subject']) > 30 ? '...' : '').'</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">'.date('M d, Y', strtotime($row['created_at'])).'</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo $row['is_read'] ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Read</span>' : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Unread</span>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No messages found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Projects -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Recent Projects</h2>
                <a href="projects.php" class="text-blue-600 hover:text-blue-800 font-medium">View All</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $sql = "SELECT * FROM projects ORDER BY created_at DESC LIMIT 3";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-300">';
                        echo '<div class="h-48 overflow-hidden">';
                        echo '<img src="../assets/images/'.$row['image_url'].'" alt="'.$row['title'].'" class="w-full h-full object-cover">';
                        echo '</div>';
                        echo '<div class="p-4">';
                        echo '<h3 class="font-bold text-lg mb-2">'.$row['title'].'</h3>';
                        echo '<p class="text-gray-600 text-sm mb-3">'.substr($row['description'], 0, 80).(strlen($row['description']) > 80 ? '...' : '').'</p>';
                        echo '<div class="flex justify-between items-center">';
                        echo '<span class="text-xs bg-gray-100 px-2 py-1 rounded-full">'.$row['category'].'</span>';
                        echo '<span class="text-xs text-gray-500"><i class="fas fa-eye mr-1"></i>'.$row['views'].'</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="col-span-3 text-center text-gray-500">No projects found</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>