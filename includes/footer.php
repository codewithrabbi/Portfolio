
<!-- Footer -->
<footer>
        <div class="container">
            <div class="footer-content">
                <a href="#" class="footer-logo">Portfo<span>lio.</span></a>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="skills.php">Skills</a></li>
                    <li><a href="projects.php">Projects</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <div class="footer-social">
                <?php
                    $sql = "SELECT * FROM social_links";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<a href="'.$row['url'].'" target="_blank" >';
                            echo '<i class="'.$row['icon_class'].'"></i>';
                            echo '</a>';
                        }
                    }
                    ?>
                </div>
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> Naimur R. <span style="color: #e84118;">♥</span></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <div class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>


    
    <script src="assets/js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>