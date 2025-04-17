</div> <!-- Close main content div -->
    
    <script>
        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.id === 'projectModal') {
                event.target.classList.add('hidden');
            }
        });
    </script>
</body>
</html>