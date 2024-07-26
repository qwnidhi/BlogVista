<?php
include 'partials/header.php';

// Fetch posts if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Debugging: Check if id is valid
    if (!is_numeric($id) || $id <= 0) {
        header('Location: ' . ROOT_URL . 'blog.php');
        die();
    }

    // Prepare and execute query to fetch posts
    $query = $connection->prepare("SELECT * FROM posts WHERE category_id = ? ORDER BY date_time DESC");
    $query->bind_param("i", $id);
    $query->execute();
    $posts = $query->get_result();

    // Debugging: Check if posts are fetched
    if ($posts->num_rows === 0) {
        echo 'No posts found for this category.';
        die();
    }
} else {
    header('Location: ' . ROOT_URL . 'blog.php');
    die();
}
?>

<header class="category__title">
    <h2>
        <?php 
        // Fetch category from categories table using category_id
        $category_query = $connection->prepare("SELECT * FROM categories WHERE id = ?");
        $category_query->bind_param("i", $id);
        $category_query->execute();
        $category_result = $category_query->get_result();
        $category = $category_result->fetch_assoc();
        
        // Debugging: Check if category is fetched correctly
        if (!$category) {
            echo 'Category not found.';
        } else {
            echo htmlspecialchars($category['title']);
        }
        ?>
    </h2>
</header>

<!------------------end of category title---------------->

<section class="posts">
    <div class="container post__container">
        <?php while ($post = $posts->fetch_assoc()): ?>
            <article class="post">
                <div class="post__thumbnail">
                    <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" alt="Post Thumbnail">
                </div>
                <div class="post__info">
                    <h3 class="post__title">
                        <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                    </h3>
                    <p class="post__body">
                        <?= htmlspecialchars(substr($post['body'], 0, 150)) ?>......
                    </p>
                    <div class="post__author">
                        <?php
                        // Fetch author from users table using author_id
                        $author_id = $post['author_id'];
                        $author_query = "SELECT * FROM users WHERE id = $author_id";
                        $author_result = mysqli_query($connection, $author_query);
                        $author = mysqli_fetch_assoc($author_result);
                        
                        // Debugging: Check if author is fetched correctly
                        if (!$author) {
                            echo 'Author not found.';
                        }
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

<!------------------end of posts section---------------->

<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php 
        // Fetch all categories
        $all_categories_query = "SELECT * FROM categories";
        $all_categories = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while ($category = mysqli_fetch_assoc($all_categories)) : ?>
            <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" 
                class="category__button"><?= htmlspecialchars($category['title']) ?>
            </a>
        <?php endwhile ?>
    </div>
</section>

<!------------------end of delete category buttons---------------->

<?php
include 'partials/footer.php';
?>
