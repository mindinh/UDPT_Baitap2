<?php
ob_start();
$title = "Search Papers";
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<form id="searchForm" class="container my-3">
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label for="title" class="form-label">Paper Title</label>
      <input type="text" id="title" name="title" class="form-control" placeholder="Enter paper title">
    </div>

    <div class="col-md-2">
      <label for="topic" class="form-label">Topic</label>
      <select id="topic" name="topic" class="form-select">
        <option value="">-- Select Topic --</option>
        <?php foreach ($topics as $topic): ?>
          <option value="<?= htmlspecialchars($topic['topic_name']) ?>">
            <?= htmlspecialchars($topic['topic_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-2">
      <label for="author" class="form-label">Author</label>
      <select id="author" name="author" class="form-select">
        <option value="">-- Select Author --</option>
        <?php foreach ($authors as $author): ?>
          <option value="<?= htmlspecialchars($author['AuthorFName']) ?>">
            <?= htmlspecialchars($author['AuthorFName']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <label for="conference" class="form-label">Conference</label>
      <select id="conference" name="conference" class="form-select">
        <option value="">-- Select Conference --</option>
        <?php foreach ($conferences as $conference): ?>
          <option value="<?= htmlspecialchars($conference['conference_id']) ?>">
            <?= htmlspecialchars($conference['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <label for="conference-abbrev" class="form-label">Conference Abbreviation</label>
      <select id="conference-abbrev" name="conference-abbrev" class="form-select">
        <option value="">-- Conference Abbrev --</option>
        <?php foreach ($conferences as $conference): ?>
          <option value="<?= htmlspecialchars($conference['abbreviation']) ?>">
            <?= htmlspecialchars($conference['abbreviation']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
  </div>
</form>

<div id='searchResults'>

</div>


<?php
$cont = ob_get_clean();
require_once 'layout.php';