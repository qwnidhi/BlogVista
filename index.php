<?php
include 'partials/header.php';
require 'config/database.php'; // Ensure this file initializes $conn

// Fetch featured post from the database
$featured_query = "SELECT * FROM posts WHERE is_featured = 1";
$featured_result = mysqli_query($conn, $featured_query);
if (!$featured_result) {
    die("Error fetching featured post: " . mysqli_error($conn));
}
$featured = mysqli_fetch_assoc($featured_result);

// Fetch 9 posts from the posts table
$query = "SELECT * FROM posts ORDER BY date_time DESC LIMIT 9";
$posts = mysqli_query($conn, $query);
if (!$posts) {
    die("Error fetching posts: " . mysqli_error($conn));
}

?>

<!----- Show featured post if there's any ----->
<?php if ($featured): ?>
<section class="featured">
    <div class="container featured__container">
        <div class="post__thumbnail">
            <img src="./images/<?= htmlspecialchars($featured['thumbnail']) ?>" alt="Featured Image">
        </div>
        <div class="post__info">
            <?php 
            // Fetch category from categories table using category_id of posts
            $category_id = $featured['category_id'];
            $category_query = "SELECT * FROM categories WHERE id = $category_id";
            $category_result = mysqli_query($conn, $category_query);
            if (!$category_result) {
                die("Error fetching category: " . mysqli_error($conn));
            }
            $category = mysqli_fetch_assoc($category_result);
            ?>
            <a href="<?= ROOT__URL ?>category-posts.php?id=<?= htmlspecialchars($featured['category_id']) ?>" class="category__button"><?= htmlspecialchars($category['title']) ?></a>
            <h2 class="post__title"><a href="<?= ROOT__URL ?>post.php?id=<?= htmlspecialchars($featured['id']) ?>"><?= htmlspecialchars($featured['title']) ?></a></h2>
            <p class="post__body">
                <?= htmlspecialchars(substr($featured['body'], 0, 300)) ?>......
            </p>
            <div class="post__author">
                <?php
                // Fetch author from users table using author_id
                $author_id = $featured['author_id'];
                $author_query = "SELECT * FROM users WHERE id = $author_id";
                $author_result = mysqli_query($conn, $author_query);
                if (!$author_result) {
                    die("Error fetching author: " . mysqli_error($conn));
                }
                $author = mysqli_fetch_assoc($author_result);
                ?>
                <div class="post__author-avatar">
                    <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" alt="Author Avatar">
                </div>
                <div class="post__author-info">
                    <h5>By: <?= htmlspecialchars("{$author['firstname']} {$author['lastname']}") ?></h5>
                    <small><?= date("M d, Y - H:i", strtotime($featured['date_time'])) ?></small>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif ?>
<!------------------ End of featured ------------------>

<section class="posts <?= $featured ? '' : 'section__extra-margin' ?>">
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
                    $category_query = "SELECT * FROM categories WHERE id = $category_id";
                    $category_result = mysqli_query($conn, $category_query);
                    if (!$category_result) {
                        die("Error fetching category: " . mysqli_error($conn));
                    }
                    $category = mysqli_fetch_assoc($category_result);
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
                        $author_query = "SELECT * FROM users WHERE id = $author_id";
                        $author_result = mysqli_query($conn, $author_query);
                        if (!$author_result) {
                            die("Error fetching author: " . mysqli_error($conn));
                        }
                        $author = mysqli_fetch_assoc($author_result);
                        ?>
                        <div class="post__author-avatar">
                            <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" alt="Author Avatar">
                        </div>
                        <div class="post__author-info">
                            <h5>By: <?= htmlspecialchars("{$author['firstname']} {$author['lastname']}") ?></h5>
                            <small><?= date("M d, Y - H:i", strtotime($post['date_time'])) ?></small>
                        </div>
                    </div>
                </div>    
            </article>
        <?php endwhile; ?>
    </div>
</section>

<!------------------ End of posts section ------------------>

<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php 
        $all_categories_query = "SELECT * FROM categories";
        $all_categories = mysqli_query($conn, $all_categories_query);
        if (!$all_categories) {
            die("Error fetching categories: " . mysqli_error($conn));
        }
        ?>
        <?php while ($category = mysqli_fetch_assoc($all_categories)): ?>
            <a href="<?= ROOT__URL ?>category-posts.php?id=<?= htmlspecialchars($category['id']) ?>" class="category__button"><?= htmlspecialchars($category['title']) ?></a>
        <?php endwhile ?>
    </div>
</section>

<!------------------ End of category buttons ////------------------>

<?php
include 'partials/footer.php';
?>
