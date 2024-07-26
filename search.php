<?php
require 'partials/header.php';
require 'config/database.php'; // Ensure this file initializes $connection

if (isset($_GET['search']) && isset($_GET['submit'])) {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $query = "SELECT * FROM posts WHERE title LIKE ? ORDER BY date_time DESC";
    $stmt = $connection->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $posts = $stmt->get_result();
} else {
    header('Location: ' . ROOT__URL . 'blog.php');
    die();
}
?>

<?php if (mysqli_num_rows($posts) > 0) : ?>
<section class="posts section__extra-margin">
    <div class="container post__container">
        <?php while ($post = mysqli_fetch_assoc($posts)): ?>
            <article class="post">
                <div class="post__thumbnail">
                    <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" alt="Post Thumbnail">
                </div>
                <div class="post__info">
                    <?php 
                    // Fetch category from categories table using category_id of posts
                    $category_id = $post['category_id'];
                    $category_query = "SELECT * FROM categories WHERE id = ?";
                    $category_stmt = $connection->prepare($category_query);
                    $category_stmt->bind_param('i', $category_id);
                    $category_stmt->execute();
                    $category_result = $category_stmt->get_result();
                    $category = $category_result->fetch_assoc();
                    ?>
                    <a href="<?= ROOT__URL ?>category-posts.php?id=<?= htmlspecialchars($post['category_id']) ?>" class="category__button"><?= htmlspecialchars($category['title']) ?></a>
                    <h3 class="post__title">
                        <a href="<?= ROOT__URL ?>post.php?id=<?= htmlspecialchars($post['id']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                    </h3>
                    <p class="post__body">
                        <?= htmlspecialchars(substr($post['body'], 0, 150)) ?>......
                    </p>
                    <div class="post__author">
                        <?php
                        // Fetch author from users table using author_id
                        $author_id = $post['author_id'];
                        $author_query = "SELECT * FROM users WHERE id = ?";
                        $author_stmt = $connection->prepare($author_query);
                        $author_stmt->bind_param('i', $author_id);
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
        <?php endwhile; ?>
    </div>
</section>
<?php else : ?>
    <div class="alert__message error lg section__extra-margin">
        <p> No posts found for this search </p>
    </div>
<?php endif ?>

<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php 
        $all_categories_query = "SELECT * FROM categories";
        $all_categories = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while ($category = mysqli_fetch_assoc($all_categories)) : ?>
            <a href="<?= ROOT__URL ?>category-posts.php?id=<?= htmlspecialchars($category['id']) ?>" class="category__button"><?= htmlspecialchars($category['title']) ?></a>
        <?php endwhile ?>
    </div>
</section>

<?php include 'partials/footer.php' ?>
