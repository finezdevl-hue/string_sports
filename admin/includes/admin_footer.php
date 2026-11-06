<?php
// admin/includes/admin_footer.php
?>
        </main>
    </div>

    <!-- Admin Interactive Script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Confirm Delete handlers
        document.querySelectorAll('.btn-confirm-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const message = btn.dataset.confirmMessage || 'Are you sure you want to permanently delete this item? This action cannot be reversed.';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

        // Image Preview on File Input
        const fileInput = document.getElementById('adminProductImageInput');
        const previewImg = document.getElementById('adminImagePreview');
        if (fileInput && previewImg) {
            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
    </script>
</body>
</html>
