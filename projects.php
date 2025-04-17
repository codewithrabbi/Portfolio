<?php include 'includes/header.php'; ?>

<!-- Projects Section -->
<section class="section" id="projects">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>My <span>Projects</span></h2>
            </div>
            <div class="projects-container">

            

            
                

            <?php
            // Fetch projects from database
            $sql = "SELECT * FROM projects ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Increment view count
                    $update_sql = "UPDATE projects SET views = views + 1 WHERE id = ".$row['id'];
                    $conn->query($update_sql);
                    
                    echo '<div class="project-card" data-aos="fade-up" data-aos-delay="100">';
                    echo '<div class="project-image">';
                    echo '<img src="assets/images/'.$row['image_url'].'" alt="'.$row['title'].'" class="">';
                    echo '</div>';
                    echo '<div class="project-info">';
                    echo '<h3 class="">'.$row['title'].'</h3>';
                    echo '<p class="">'.$row['description'].'</p>';
                    echo '<div class="project-tech">';
                    echo '<span class="tech-tag">'.$row['category'].'</span>';
                    echo '<div class="project-links">';
                   
                    echo '<a href="'.$row['project_url'].'" target="_blank" ><i class="fas fa-external-link-alt"></i> View Project</a>';
                    

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="col-span-3 text-center">No projects found.</p>';
            }
            ?>
        </div>
                
                
            </div>
        </div>
    </section>


<?php include 'includes/footer.php'; ?>