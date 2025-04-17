<?php include 'includes/header.php'; ?>


    <!-- Contact Section -->
    <section class="section" id="contact">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Contact <span>Me</span></h2>
            </div>
            <?php
                $sql = "SELECT * FROM personal_info LIMIT 1";
                $result = $conn->query($sql);
                $info = $result->fetch_assoc();
            ?>
            <div class="contact-container">
                <div class="contact-info" data-aos="fade-right">
                    <h3>Get In Touch</h3>
                    <p>Feel free to reach out to me for collaboration, job opportunities, or just to say hello. I'll get back to you as soon as possible.</p>
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Email</h4>
                                <p><?php echo $info['email']; ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Phone</h4>
                                <p><?php echo $info['phone'] ?? 'Not provided'; ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Location</h4>
                                <p><?php echo $info['address'] ?? 'Not provided'; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="social-links">
                    <?php
                        $sql = "SELECT * FROM social_links";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<a href="'.$row['url'].'" target="_blank" class="text-2xl hover:text-yellow-300 transition duration-300">';
                                echo '<i class="'.$row['icon_class'].'"></i>';
                                echo '</a>';
                            }
                        }
                    ?>
                    </div>
                </div>
                <div class="contact-form" data-aos="fade-left">
                    <form id="contactForm" action="process-contact.php" method="POST">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                        <div id="formMessage" style="margin-top: 10px; font-weight: bold;"></div>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo "<p id='Message' style='color: #17ff00;'>Form submitted successfully!</p>";
}
?>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'error') {
    echo "<p id='Message' style='color: #ff1100;'>Form submitted successfully!</p>";
}
?>
                        
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
    setTimeout(function () {
        var msg = document.getElementById("Message");
        if (msg) {
            msg.style.display = "none";
        }
    }, 3000); // ৫০০০ মিলিসেকেন্ড = ৫ সেকেন্ড
</script>
    
<?php include 'includes/footer.php'; ?>