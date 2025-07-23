<?php if (count($papers) === 0): ?>
    <div class="alert alert-warning w-50 mx-auto mt-5" style="margin-top: 10px; text-align: center;">
        No papers found
    </div>
<?php else: ?>
    <div class="paper-list">
        <?php foreach ($papers as $paper): ?>
            <div class="paper-card">
                <h4><a class="text-decoration-none" href="?action=paper-details&id=<?= $paper['paper_id'] ?>"><?= htmlspecialchars($paper['title']) ?></a></h4>
                <p><strong>Authors:</strong> <?= htmlspecialchars($paper['author_string_list']) ?></p>
                <p><strong>Topic:</strong> <?= htmlspecialchars($paper['topic_name']) ?> | 
                   <strong>Conference:</strong> <?= htmlspecialchars($paper['name']) ?> (<?= htmlspecialchars($paper['abbreviation']) ?>)</p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
