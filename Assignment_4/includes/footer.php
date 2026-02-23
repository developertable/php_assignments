<!-- Footer -->
    <footer class="mt-5 py-4 bg-light border-top">
        <div class="container text-center">
            <p class="text-muted mb-1">
                <small>&copy; <?php echo date('Y'); ?> SportsPro Technical Support</small>
            </p>
            <p class="text-muted mb-0">
                <small>Built with PHP, MySQL & Bootstrap</small>
            </p>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (isset($extra_js)): ?>
        <script><?php echo $extra_js; ?></script>
    <?php endif; ?>
</body>
</html>