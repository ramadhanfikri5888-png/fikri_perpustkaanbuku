<?php 
/**
 * VIEW: riwayat.php
 * Tabel rekam jejak pribadi (Sirkulasi pinjam) khusus di satu akun Siswa.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Riwayat Peminjaman'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<h3 class="mb-4">📜 Riwayat Peminjaman Saya</h3>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>

<!-- Tab Navigations -->
<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif" type="button" role="tab">Sedang Dipinjam</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu" type="button" role="tab">
        Menunggu Persetujuan
        <?php if (count($menunggu) > 0): ?>
            <span class="badge bg-warning text-dark ms-1"><?= count($menunggu) ?></span>
        <?php endif; ?>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">Semua Riwayat</button>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
  <!-- Sedang Dipinjam -->
  <div class="tab-pane fade show active" id="aktif" role="tabpanel">
      <div class="row">
          <?php if (empty($aktif)): ?>
            <div class="col-12"><p class="text-muted text-center py-5">Tidak ada buku yang sedang dipinjam.</p></div>
          <?php else: ?>
            <?php foreach ($aktif as $p): ?>
                <div class="col-md-4 mb-4">
                    <div class="card border-warning shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <?php if ($p['cover']): ?>
                                        <img src="<?= base_url('public/uploads/' . $p['cover']) ?>" style="width:60px;height:85px;object-fit:cover;border-radius:4px;">
                                    <?php else: ?>
                                        <div style="width:60px;height:85px;background:#eee;border-radius:4px;" class="d-flex align-items-center justify-content-center">📕</div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?= e($p['judul']) ?></h6>
                                    <span class="badge bg-warning text-dark mb-2">Sedang Dipinjam</span>
                                    <p class="mb-0 text-muted" style="font-size:12px;">Pinjam: <?= $p['tgl_pinjam'] ?></p>
                                </div>
                            </div>
                            <div class="alert <?= strtotime($p['tgl_harus_kembali']) < time() ? 'alert-danger' : 'alert-info' ?> py-2 mb-0 text-center" style="font-size:13px;">
                                Jatuh Tempo:<br>
                                <strong><?= $p['tgl_harus_kembali'] ?></strong>
                                <?= strtotime($p['tgl_harus_kembali']) < time() ? '<br>⚠️ TERLAMBAT' : '' ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
          <?php endif; ?>
      </div>
  </div>

  <!-- Menunggu Persetujuan -->
  <div class="tab-pane fade" id="menunggu" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table mb-0 table-hover">
                    <thead class="table-light"><tr><th>Kode Transaksi</th><th>Buku</th><th>Tgl Pengajuan</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php if (empty($menunggu)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada pengajuan yang menunggu.</td></tr>
                        <?php else: ?>
                            <?php foreach ($menunggu as $p): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= e($p['kode_transaksi']) ?></span></td>
                                    <td><?= e($p['judul']) ?></td>
                                    <td><?= $p['tgl_pengajuan'] ?></td>
                                    <td><span class="badge bg-warning text-dark">Menunggu Persetujuan Admin</span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
  </div>

  <!-- Semua Riwayat -->
  <div class="tab-pane fade" id="riwayat" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table mb-0 table-striped">
                    <thead class="table-light">
                        <tr><th>Trx</th><th>Buku</th><th>Pinjam</th><th>Tempo</th><th>Kembali</th><th>Status</th><th>Denda</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($riwayat)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat.</td></tr>
                        <?php else: ?>
                            <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td class="text-muted"><small><?= e($r['kode_transaksi'] ?: '-') ?></small></td>
                                    <td><?= e($r['judul']) ?></td>
                                    <td><?= $r['tgl_pinjam'] ?: '-' ?></td>
                                    <td><?= $r['tgl_harus_kembali'] ?: '-' ?></td>
                                    <td><?= $r['tgl_kembali'] ?: '-' ?></td>
                                    <td>
                                        <?php if ($r['status'] === 'dipinjam'): ?>
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        <?php elseif ($r['status'] === 'dikembalikan'): ?>
                                            <span class="badge bg-success">Dikembalikan</span>
                                        <?php elseif ($r['status'] === 'ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $r['denda'] > 0 ? 'Rp ' . number_format($r['denda'], 0, ',', '.') : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
  </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
