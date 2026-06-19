<div class="page-heading d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1><i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Media Library</h1>
        <p>Upload and manage images, videos, and PDF documents. Copy URLs to use across the website.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-upload me-1"></i>Upload File
    </button>
</div>

<!-- Filter bar -->
<div class="d-flex gap-2 mb-3 flex-wrap">
    <?php foreach ([''=>'All','image'=>'Images','application/pdf'=>'PDFs','video'=>'Videos'] as $t=>$l): ?>
        <a href="<?= e(url('/cms/media' . ($t ? '?type='.urlencode($t) : ''))) ?>"
           class="btn btn-sm <?= $filter === $t ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <?= e($l) ?>
        </a>
    <?php endforeach; ?>
    <span class="text-muted small ms-auto align-self-center"><?= count($files) ?> file(s)</span>
</div>

<!-- Search bar -->
<div class="mb-4">
    <div class="input-group" style="max-width:360px">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" id="mediaSearch" class="form-control" placeholder="Search files by name&hellip;">
        <button class="btn btn-outline-secondary" id="mediaSearchClear" title="Clear"><i class="bi bi-x"></i></button>
    </div>
</div>

<?php if (empty($files)): ?>
    <div class="empty-state">
        <i class="bi bi-images"></i>
        <h5>No files yet</h5>
        <p>Upload your first file to get started.</p>
    </div>
<?php else: ?>
<div class="row g-3">
<?php foreach ($files as $f):
    $isImage = str_starts_with($f['mime_type'], 'image');
    $isPdf   = $f['mime_type'] === 'application/pdf';
    $isVideo = str_starts_with($f['mime_type'], 'video');
    $fileUrl = url($f['file_path']);
?>
<div class="col-6 col-md-4 col-lg-3 media-item" data-name="<?= e(strtolower($f['file_name'])) ?>">
    <div class="media-card">
        <div class="media-preview">
            <?php if ($isImage): ?>
                <img src="<?= e($fileUrl) ?>" alt="<?= e($f['alt_text'] ?: $f['file_name']) ?>">
            <?php elseif ($isPdf): ?>
                <div class="media-icon-placeholder"><i class="bi bi-file-earmark-pdf text-danger"></i></div>
            <?php elseif ($isVideo): ?>
                <div class="media-icon-placeholder"><i class="bi bi-play-circle text-primary"></i></div>
            <?php else: ?>
                <div class="media-icon-placeholder"><i class="bi bi-file-earmark"></i></div>
            <?php endif; ?>
        </div>
        <div class="media-info">
            <p class="media-name" title="<?= e($f['file_name']) ?>"><?= e(mb_substr($f['file_name'],0,24)) ?></p>
            <p class="media-size"><?= round($f['file_size']/1024,1) ?> KB</p>
            <div class="d-flex gap-1 mt-2">
                <button class="btn btn-xs flex-fill"
                        onclick="navigator.clipboard.writeText('<?= e($fileUrl) ?>').then(()=>alert('URL copied!'))">
                    <i class="bi bi-clipboard me-1"></i>Copy URL
                </button>
                <form method="post" action="<?= e(url('/cms/media/delete')) ?>"
                      onsubmit="return confirm('Delete this file permanently?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $f['id'] ?>">
                    <button class="btn-icon-danger" type="submit" title="Delete"><i class="bi bi-trash3"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?= e(url('/cms/media')) ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-upload me-2 text-primary"></i>Upload File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">File <span class="text-danger">*</span></label>
          <input class="form-control" type="file" name="file"
                 accept="image/*,application/pdf,video/mp4" required>
          <small class="text-muted">Images, PDFs, MP4 videos. Max 20 MB.</small>
        </div>
        <div>
          <label class="form-label fw-semibold">Alt Text / Description</label>
          <input class="form-control" name="alt_text" placeholder="Describe this file (for images: alt text)">
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-upload me-1"></i>Upload</button>
      </div>
    </form>
  </div>
</div>

<script>
(function () {
    var input = document.getElementById('mediaSearch');
    var clear = document.getElementById('mediaSearchClear');
    if (!input) return;
    function filter() {
        var q = input.value.toLowerCase();
        document.querySelectorAll('.media-item').forEach(function (el) {
            el.style.display = (!q || el.dataset.name.includes(q)) ? '' : 'none';
        });
    }
    input.addEventListener('input', filter);
    clear.addEventListener('click', function () { input.value = ''; filter(); });
}());
</script>
