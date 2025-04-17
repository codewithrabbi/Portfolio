<?php
include 'includes/auth.php';
include 'includes/header.php';

// Handle message deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: messages.php?deleted=1');
    exit();
}

// Handle marking message as read
if (isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    $sql = "UPDATE messages SET is_read = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: messages.php');
    exit();
}

// Get message for viewing
$view_message = null;
if (isset($_GET['view'])) {
    $id = $_GET['view'];
    $sql = "SELECT * FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $view_message = $result->fetch_assoc();
    $stmt->close();
    
    // Mark as read when viewing
    if ($view_message && !$view_message['is_read']) {
        $sql = "UPDATE messages SET is_read = TRUE WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Messages</h1>
        </div>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                Message deleted successfully!
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Message List -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">All Messages</h2>
                </div>
                <div class="divide-y divide-gray-200 max-h-screen overflow-y-auto">
                    <?php
                    $sql = "SELECT * FROM messages ORDER BY created_at DESC";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $isActive = isset($_GET['view']) && $_GET['view'] == $row['id'];
                            echo '<a href="?view='.$row['id'].'" class="block p-4 hover:bg-gray-50 transition duration-200 '.($isActive ? 'bg-blue-50' : '').'">';
                            echo '<div class="flex justify-between items-start">';
                            echo '<div>';
                            echo '<h3 class="font-medium text-gray-900 '.($row['is_read'] ? '' : 'font-bold').'">'.$row['name'].'</h3>';
                            echo '<p class="text-sm text-gray-500 truncate">'.$row['subject'].'</p>';
                            echo '</div>';
                            echo '<span class="text-xs text-gray-500">'.date('M d', strtotime($row['created_at'])).'</span>';
                            echo '</div>';
                            echo '<p class="mt-1 text-sm text-gray-600 truncate">'.substr($row['message'], 0, 60).(strlen($row['message']) > 60 ? '...' : '').'</p>';
                            echo '</a>';
                        }
                    } else {
                        echo '<div class="p-4 text-center text-gray-500">No messages found</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Message Detail -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($view_message): ?>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800"><?php echo $view_message['subject']; ?></h2>
                                <p class="text-sm text-gray-500 mt-1">From: <?php echo $view_message['name']; ?> &lt;<?php echo $view_message['email']; ?>&gt;</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="?mark_read=<?php echo $view_message['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-check-circle"></i> Mark as <?php echo $view_message['is_read'] ? 'Unread' : 'Read'; ?>
                                </a>
                                <a href="?delete=<?php echo $view_message['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?')" class="text-red-600 hover:text-red-800 ml-3">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                        
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line"><?php echo $view_message['message']; ?></p>
                        </div>
                        
                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500">
                                Received: <?php echo date('F j, Y \a\t g:i a', strtotime($view_message['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-envelope-open-text text-4xl mb-4 opacity-30"></i>
                        <p>Select a message to view its contents</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>