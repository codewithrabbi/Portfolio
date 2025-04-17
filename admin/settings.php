<?php
include 'includes/auth.php';
include 'includes/header.php';

// Handle personal info update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $full_name = $_POST['full_name'];
    $job_title = $_POST['job_title'];
    $about_text = $_POST['about_text'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Handle file upload
    $profile_image = $_POST['existing_image'] ?? '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            // Generate unique filename
            $new_filename = 'profile.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $new_filename;
                
                // Delete old image if exists and is different
                if (!empty($_POST['existing_image']) && $_POST['existing_image'] !== $new_filename) {
                    @unlink($target_dir . $_POST['existing_image']);
                }
            }
        }
    }
    
    $sql = "UPDATE personal_info SET 
            full_name = ?, 
            job_title = ?, 
            about_text = ?, 
            email = ?, 
            phone = ?, 
            address = ?, 
            profile_image = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $full_name, $job_title, $about_text, $email, $phone, $address, $profile_image);
    $stmt->execute();
    $stmt->close();
    
    header('Location: settings.php?saved=1');
    exit();
}

// Handle social link addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_social'])) {
    $platform = $_POST['platform'];
    $url = $_POST['url'];
    $icon_class = $_POST['icon_class'];
    
    $sql = "INSERT INTO social_links (platform, url, icon_class) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $platform, $url, $icon_class);
    $stmt->execute();
    $stmt->close();
    
    header('Location: settings.php?saved=1');
    exit();
}

// Handle social link deletion
if (isset($_GET['delete_social'])) {
    $id = $_GET['delete_social'];
    $sql = "DELETE FROM social_links WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: settings.php?deleted=1');
    exit();
}

// Get current personal info
$sql = "SELECT * FROM personal_info LIMIT 1";
$result = $conn->query($sql);
$personal_info = $result->fetch_assoc();

// Get social links
$social_links = $conn->query("SELECT * FROM social_links");
?>

<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Settings</h1>
        
        <?php if (isset($_GET['saved'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Settings saved successfully!
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                Social link deleted successfully!
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="update_info" value="1">
                    <input type="hidden" name="existing_image" value="<?php echo $personal_info['profile_image'] ?? ''; ?>">
                    
                    <div class="mb-4">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required value="<?php echo $personal_info['full_name'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                        <input type="text" id="job_title" name="job_title" required value="<?php echo $personal_info['job_title'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="about_text" class="block text-sm font-medium text-gray-700 mb-2">About Text</label>
                        <textarea id="about_text" name="about_text" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo $personal_info['about_text'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required value="<?php echo $personal_info['email'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo $personal_info['phone'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="address" name="address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo $personal_info['address'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                        <?php if (!empty($personal_info['profile_image'])): ?>
                            <div class="mb-2">
                                <img src="../assets/images/<?php echo $personal_info['profile_image']; ?>" alt="Current Profile Image" class="h-24 w-24 rounded-full object-cover border">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="profile_image" name="profile_image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-300">Save Personal Information</button>
                </form>
            </div>
            
            <!-- Social Links -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Social Links</h2>
                
                <!-- Add New Social Link Form -->
                <form method="POST" class="mb-8 bg-gray-50 p-4 rounded-lg">
                    <input type="hidden" name="add_social" value="1">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="platform" class="block text-sm font-medium text-gray-700 mb-2">Platform</label>
                            <input type="text" id="platform" name="platform" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                            <input type="url" id="url" name="url" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="icon_class" class="block text-sm font-medium text-gray-700 mb-2">Icon Class (Font Awesome)</label>
                            <input type="text" id="icon_class" name="icon_class" required placeholder="fab fa-twitter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <button type="submit" class="mt-4 w-full bg-green-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-green-700 transition duration-300">Add Social Link</button>
                </form>
                
                <!-- Existing Social Links -->
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Social Links</h3>
                <div class="space-y-4">
                    <?php if ($social_links->num_rows > 0): ?>
                        <?php while($row = $social_links->fetch_assoc()): ?>
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <i class="<?php echo $row['icon_class']; ?> text-xl mr-3 text-gray-700"></i>
                                    <div>
                                        <p class="font-medium"><?php echo $row['platform']; ?></p>
                                        <p class="text-sm text-gray-600 truncate max-w-xs"><?php echo $row['url']; ?></p>
                                    </div>
                                </div>
                                <a href="?delete_social=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this social link?')" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No social links added yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>