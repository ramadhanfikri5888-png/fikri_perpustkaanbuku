<?php
/**
 * VIEW: footer.php
 * Komponen Global: Bagian Kaki (Bawah) dari kerangka struktur kerangka halaman web.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
// Template Footer
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        </div> <!-- End admin-content -->
    </div> <!-- End admin-layout -->
<?php else: ?>
    </div> <!-- End container -->
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
