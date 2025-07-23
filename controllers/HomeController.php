<?php

require_once 'models/TopicModel.php';

class HomeController {
    public function index() {
        $topicModel = new TopicModel();
        $paperModel = new PaperModel();
        $topics = $topicModel->getAllTopics();

        $content = '<h3 class="mt-4 mb-3 fw-bold">Home</h3>';

        foreach ($topics as $topic) {
            $content .= "<div class='mb-4'>";
            $content .= "<h5 class='text-primary mb-2'>" . htmlspecialchars($topic['topic_name']) . "</h5>";

            $papersByTopic = $paperModel->getPaperByTopicId($topic['topic_id']);

            if (!empty($papersByTopic)) {
                $content .= "<ul class='list-group'>";
                foreach ($papersByTopic as $paper) {
                    $paperTitle = htmlspecialchars($paper['PaperTitle']);
                    $paperId = htmlspecialchars($paper['PaperId']);
                    $content .= "
                        <li class='list-group-item paper-item'>
                            <a href='?action=paper-details&id={$paperId}' class='text-decoration-none text-dark'>
                                {$paperTitle}
                            </a>
                        </li>
                    ";
                }
                $content .= "</ul>";
            } else {
                $content .= "<p class='text-muted'>No papers in this topic.</p>";
            }

        $content .= "</div><hr>";
        }

        require_once 'views/home.php';
    }
}
