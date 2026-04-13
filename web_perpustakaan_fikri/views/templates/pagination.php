<?php
/**
 * VIEW: pagination.php
 * Potongan Kode (Komponen) Tombol Angka Halaman (Next/Prev) dinamis.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
// Template Pagination Component
// Membutuhkan variabel: $page, $total_pages, $search
if (!isset($search)) $search = '';
?>
<?php if ($total_pages > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo; Prev</span>
      </a>
    </li>
    
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
            <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
        </li>
    <?php endfor; ?>
    
    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
      <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" aria-label="Next">
        <span aria-hidden="true">Next &raquo;</span>
      </a>
    </li>
  </ul>
</nav>
<?php endif; ?>
