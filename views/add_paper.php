<?php
ob_start();
$title = "Add Paper";
?>

<form id="addPaperForm" method="POST" action="?action=add-paper">
    <h3 class="mb-3">Add new paper</h3>

    <div class="mb-2">
        <label for="title" class="form-label">Paper Title</label>
        <input type="text" class="form-control" id="title" name="title" required>

        
    </div>

    <div class="mb-2">
        <label for="topic" class="form-label">Topic</label>
        <select class="form-select" id="topic" name="topic_id" required>
            <option value="">-- Choose topic --</option>
            <?php foreach ($topics as $topic): ?>
                <option value="<?= $topic['topic_id'] ?>"><?= htmlspecialchars($topic['topic_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="abstract" class="form-label">Abstract</label>
        <textarea class="form-control" id="abstract" name="abstract" rows="2" required></textarea>
    </div>

    <div class="mb-3">
        <label for="conference" class="form-label">Conference</label>
        <select class="form-select" id="conference" name="conference" required>
            <option value="">-- Choose conference --</option>
            <?php foreach ($conferences as $conference): ?>
                <option value="<?= $conference['conference_id'] . "/" . htmlspecialchars($conference['name']) ?>"><?= htmlspecialchars($conference['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Add authors</label>
        <div id="authorsList">
            <div class="row mb-2 author-row">
                <div class="col-md-5">
                    <select name="authors[]" class="form-select" required>
                        <option value="">-- Choose author --</option>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?= $author['AuthorId'] . "/" . htmlspecialchars($author['AuthorFName']) ?>"><?= $author['AuthorFName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <select name="roles[]" class="form-select" required>
                        <!-- <option value="first_author">First author</option> -->
                        <option value="member">Member</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger removeAuthorBtn">X</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mt-2" id="addAuthorBtn">+ Add author</button>
                            
    </div>

    <button type="submit" class="btn btn-primary">Save paper</button>
</form>
<script>
document.getElementById("addAuthorBtn").addEventListener("click", function () {
    const newAuthorRow = document.querySelector(".author-row").cloneNode(true);
    newAuthorRow.querySelector("select").value = "";
    document.getElementById("authorsList").appendChild(newAuthorRow);
});

document.getElementById("authorsList").addEventListener("click", function (e) {
    if (e.target.classList.contains("removeAuthorBtn")) {
        const allRows = document.querySelectorAll(".author-row");
        if (allRows.length > 1) {
            e.target.closest(".author-row").remove();
        } else {
            alert("Cần ít nhất 1 tác giả.");
        }
    }
});

document.getElementById("addPaperForm").addEventListener("submit", function (e) {
    const title = document.getElementById("title").value.trim();
    const abstract = document.getElementById("abstract").value.trim();
    const topic = document.getElementById("topic").value;
    if (!title || !abstract || !topic) {
        alert("Please fill all infomation fields");
        e.preventDefault();
        return;
    }

    const authors = document.querySelectorAll("select[name='authors[]']");
    const roles = document.querySelectorAll("select[name='roles[]']");

    for (let i = 0; i < authors.length; i++) {
        if (!authors[i].value || !roles[i].value.trim()) {
            alert("Please specify missing author");
            e.preventDefault();
            return;
        }
    }
});
</script>

<?php
$cont = ob_get_clean();

require_once 'views/layout.php';