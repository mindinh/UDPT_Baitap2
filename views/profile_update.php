<?php
ob_start();
$title = "Profile Update";
?>

<form action="?action=update-profile" method="POST" enctype="multipart/form-data">

    <div class="mb-3">
    <label for="website" class="form-label">Website</label>
    <textarea name="website" class="form-control"><?= htmlspecialchars($user["website"]) ?></textarea>
  </div>

  <div class="mb-3">
    <label for="bio" class="form-label">Bio</label>
    <textarea name="bio" class="form-control"><?= htmlspecialchars($user["bio"]) ?></textarea>
  </div>

  <div class="mb-3">
    <label for="interests" class="form-label">Interests (Type ', ' for a new entry)</label>
    <input type="text" name="interests" class="form-control" value="<?= htmlspecialchars(implode(", ", $user["interests"])) ?>">
  </div>

  <div class="mb-3">
    <label for="education" class="form-label">Education (Enter new line for a new entry)</label>
    <textarea name="education" class="form-control"><?= htmlspecialchars(implode("\n", $user["education"])) ?></textarea>
  </div>

  <div class="mb-3">
    <label for="work_exp" class="form-label">Work Experience (Enter new line for a new entry)</label>
    <textarea name="work_exp" class="form-control"><?= htmlspecialchars(implode("\n", $user["work_experience"])) ?></textarea>
  </div>

  <div class="mb-3">
    <label for="avatar">Avatar</label>
    <input type="file" name="avatar" class="form-control" accept="image/png" />
  </div>

  <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>

<?php
$cont = ob_get_clean();
require_once 'views/layout.php';