<?php
include 'includes/auth.php';
include 'includes/header.php';

// Handle project deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: projects.php?deleted=1');
    exit();
}

// Handle project form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $project_url = $_POST['project_url'];
    
    // Handle file upload
    $image_url = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Generate unique filename
            $new_filename = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $new_filename;
                
                // Delete old image if editing
                if (!empty($_POST['existing_image']) && $_POST['existing_image'] !== $new_filename) {
                    @unlink($target_dir . $_POST['existing_image']);
                }
            }
        }
    }
    
    if ($id > 0) {
        // Update existing project
        $sql = "UPDATE projects SET title = ?, description = ?, image_url = ?, project_url = ?, category = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $title, $description, $image_url, $project_url, $category, $id);
    } else {
        // Insert new project
        $sql = "INSERT INTO projects (title, description, image_url, project_url, category) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $title, $description, $image_url, $project_url, $category);
    }
    
    $stmt->execute();
    $stmt->close();
    
    header('Location: projects.php?saved=1');
    exit();
}

// Get project for editing
$edit_project = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_project = $result->fetch_assoc();
    $stmt->close();
}
?>

<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Projects</h1>
            <button onclick="document.getElementById('projectModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                <i class="fas fa-plus mr-2"></i>Add Project
            </button>
        </div>
        
        <?php if (isset($_GET['saved'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Project saved successfully!
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                Project deleted successfully!
            </div>
        <?php endif; ?>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $sql = "SELECT * FROM projects ORDER BY created_at DESC";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td class="px-6 py-4 whitespace-nowrap">';
                            echo '<img src="../assets/images/'.$row['image_url'].'" alt="'.$row['title'].'" class="h-12 w-12 rounded-full object-cover">';
                            echo '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap">';
                            echo '<div class="font-medium text-gray-900">'.$row['title'].'</div>';
                            echo '<div class="text-gray-500 text-sm">'.substr($row['description'], 0, 50).(strlen($row['description']) > 50 ? '...' : '').'</div>';
                            echo '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap">';
                            echo '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">'.$row['category'].'</span>';
                            echo '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-gray-500">'.$row['views'].'</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">';
                            echo '<a href="?edit='.$row['id'].'" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>';
                            echo '<a href="?delete='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete this project?\')" class="text-red-600 hover:text-red-900">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No projects found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Project Modal -->
<div id="projectModal" class="<?php echo $edit_project ? '' : 'hidden' ?> fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center border-b p-4">
            <h2 class="text-xl font-bold text-gray-800"><?php echo $edit_project ? 'Edit Project' : 'Add New Project'; ?></h2>
            <button onclick="document.getElementById('projectModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="id" value="<?php echo $edit_project ? $edit_project['id'] : ''; ?>">
            <input type="hidden" name="existing_image" value="<?php echo $edit_project ? $edit_project['image_url'] : ''; ?>">
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Project Title</label>
                <input type="text" id="title" name="title" required value="<?php echo $edit_project ? $edit_project['title'] : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo $edit_project ? $edit_project['description'] : ''; ?></textarea>
            </div>
            
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" id="category" name="category" required value="<?php echo $edit_project ? $edit_project['category'] : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="project_url" class="block text-sm font-medium text-gray-700 mb-2">Project URL (optional)</label>
                <input type="url" id="project_url" name="project_url" value="<?php echo $edit_project ? $edit_project['project_url'] : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Project Image</label>
                <?php if ($edit_project && $edit_project['image_url']): ?>
                    <div class="mb-2">
                        <img src="../assets/images/<?php echo $edit_project['image_url']; ?>" alt="Current Image" class="h-32 object-contain border">
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" <?php echo $edit_project ? '' : 'required'; ?> class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Upload a high-quality image for your project (JPEG, PNG, etc.)</p>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="document.getElementById('projectModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Save Project</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>