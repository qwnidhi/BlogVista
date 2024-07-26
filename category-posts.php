<?php
include 'partials/header.php';
require 'config/constants.php';
require 'config/database.php';

// Check if ID is set and valid
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare and execute query to fetch posts
    $query = "SELECT * FROM posts WHERE category_id = ? ORDER BY date_time DESC";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $posts = $stmt->get_result();

    if ($posts === false) {
        die("Error executing query: " . $connection->error);
    }

    // Fetch category for the title
    $category_query = "SELECT * FROM categories WHERE id = ?";
    $category_stmt = $connection->prepare($category_query);
    $category_stmt->bind_param("i", $id);
    $category_stmt->execute();
    $category_result = $category_stmt->get_result();
    $category = $category_result->fetch_assoc();

    if (!$category) {
        echo 'Category not found.';
        die();
    }
} else {
    header('Location: ' . ROOT__URL . 'blog.php');
    die();
}
?>

<header class="category__title">
    <h2><?= htmlspecialchars($category['title']) ?></h2>
</header>

<section class="posts">
    <div class="container post__container">
        <?php if ($posts->num_rows > 0): ?>
            <?php while ($post = $posts->fetch_assoc()): ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" alt="Post Thumbnail">
                    </div>
                    <div class="post__info">
                        <h3 class="post__title">
                            <a href="<?= ROOT__URL ?>post.php?id=<?= htmlspecialchars($post['id']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                        </h3>
                        <p class="post__body">
                            <?= htmlspecialchars(substr($post['body'], 0, 150)) ?>......
                        </p>
                        <div class="post__author">
                            <?php
                            $author_id = $post['author_id'];
                            $author_query = "SELECT * FROM users WHERE id = ?";
                            $author_stmt = $connection->prepare($author_query);
                            $author_stmt->bind_param("i", $author_id);
                            $author_stmt->execute();
                            $author_result = $author_stmt->get_result();
                            $author = $author_result->fetch_assoc();
                            ?>
                            <div class="post__author-avatar">
                                <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" alt="Author Avatar">
                            </div>
                            <div class="post__author-info">
                                <h5>By: <?= htmlspecialchars("{$author['firstname']} {$author['lastname']}") ?></h5>
                                <small>
                                    <?= date("M d, Y - H:i", strtotime($post['date_time'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile ?>
        <?php else: ?>
            <p>No posts found for this category.</p>
        <?php endif ?>
    </div>
</section>

<?php
include 'partials/footer.php';
?>
