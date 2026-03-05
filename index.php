<?php 
include 'db.php'; 
session_start(); // Required to check if user is logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLX Clone | Buy and Sell Locally</title>
    <style>
        /* Modern CSS Reset & Variables */
        :root {
            --primary: #002f34;
            --secondary: #00a49f;
            --accent: #ffce32;
            --bg: #f2f4f5;
            --text: #406367;
        }

        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Roboto', Arial, sans-serif; }
        body { background: var(--bg); }

        /* Header Styling */
        header { 
            background: #fff; 
            padding: 10px 10%; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.08); 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
        }
        .logo { font-size: 32px; font-weight: 900; color: var(--primary); text-decoration: none; }
        
        .search-container { flex: 1; margin: 0 30px; display: flex; max-width: 600px; }
        .search-container input { 
            width: 100%; padding: 12px; border: 2px solid var(--primary); 
            border-radius: 4px 0 0 4px; outline: none; font-size: 16px;
        }
        .search-btn { 
            background: var(--primary); color: white; border: none; 
            padding: 0 25px; cursor: pointer; border-radius: 0 4px 4px 0; 
        }

        .nav-links { display: flex; align-items: center; }
        .nav-links a { 
            text-decoration: none; color: var(--primary); 
            font-weight: bold; margin-left: 20px; font-size: 16px;
        }
        .user-name { color: var(--primary); font-weight: bold; margin-left: 15px; border-bottom: 2px solid var(--accent); }
        
        /* The Iconic OLX Sell Button */
        .sell-btn { 
            background: #fff; 
            padding: 8px 18px; 
            border-radius: 25px; 
            border: 5px solid;
            border-image: linear-gradient(to right, #23e5db, #ffce32, #3a77ff) 1;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: 0.2s;
        }
        .sell-btn:hover { background: #f8f9fa; transform: translateY(-2px); }

        /* Category Bar */
        .cat-bar { background: #fff; border-top: 1px solid #eee; padding: 10px 10%; display: flex; gap: 20px; font-size: 14px; font-weight: 500; }
        .cat-bar span { cursor: pointer; color: var(--primary); }
        .cat-bar span:hover { color: var(--secondary); }

        /* Listing Grid */
        .container { width: 80%; margin: 30px auto; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        
        .ad-card { 
            background: white; border: 1px solid #ced4da; border-radius: 4px; 
            overflow: hidden; transition: 0.3s; cursor: pointer; 
        }
        .ad-card:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .ad-img-box { width: 100%; height: 200px; background: #eee; position: relative; }
        .ad-img { width: 100%; height: 100%; object-fit: cover; }
        
        .ad-info { padding: 15px; position: relative; }
        .featured-tag { 
            position: absolute; top: -185px; left: 10px; background: var(--accent); 
            padding: 2px 8px; font-size: 10px; font-weight: bold; border-radius: 2px;
        }
        .price { font-size: 20px; font-weight: 800; color: var(--primary); margin-bottom: 5px; }
        .title { color: var(--text); font-size: 16px; height: 20px; overflow: hidden; }
        .location-date { 
            color: #7f9799; font-size: 12px; display: flex; 
            justify-content: space-between; margin-top: 15px; text-transform: uppercase;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="logo">OLX</a>
    
    <div class="search-container">
        <input type="text" placeholder="Find Cars, Mobile Phones and more...">
        <button class="search-btn">
            <svg width="24" height="24" viewBox="0 0 1024 1024" fill="white"><path d="M448 768c-176.7 0-320-143.3-320-320s143.3-320 320-320 320 143.3 320 320-143.3 320-320 320zm448 44.8l-192-192c45.6-59.2 72-132.8 72-212.8 0-194.4-157.6-352-352-352s-352 157.6-352 352 157.6 352 352 352c80 0 153.6-26.4 212.8-72l192 192c12.5 12.5 32.8 12.5 45.3 0s12.4-32.8-0.1-45.2z"></path></svg>
        </button>
    </div>

    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-name">Welcome, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?></span>
            <a href="logout.php" style="color: #ff4d4d;">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
        
        <a href="post-ad.php" class="sell-btn">+ SELL</a>
    </div>
</header>

<div class="cat-bar">
    <span style="font-weight: bold;">ALL CATEGORIES</span>
    <span>Mobile Phones</span>
    <span>Cars</span>
    <span>Motorcycles</span>
    <span>Houses</span>
    <span>Tablets</span>
</div>

<div class="container">
    <h2 style="color: var(--primary); margin-bottom: 20px;">Fresh recommendations</h2>
    <div class="grid">
        <?php
        $stmt = $pdo->query("SELECT * FROM ads WHERE status='active' ORDER BY created_at DESC");
        if($stmt->rowCount() > 0) {
            while($row = $stmt->fetch()) {
                ?>
                <div class='ad-card' onclick="window.location.href='ad-details.php?id=<?php echo $row['id']; ?>'">
                    <div class="ad-img-box">
                        <img src='<?php echo ($row['image_url'] ? $row['image_url'] : "https://via.placeholder.com/300x200?text=".$row['title']); ?>' class='ad-img'>
                        <span class="featured-tag">FEATURED</span>
                    </div>
                    <div class='ad-info'>
                        <p class='price'>Rs <?php echo number_format($row['price']); ?></p>
                        <p class='title'><?php echo $row['title']; ?></p>
                        <div class='location-date'>
                            <span><?php echo $row['location']; ?></span>
                            <span><?php echo date('M d', strtotime($row['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p style='grid-column: 1/-1; text-align: center; padding: 50px;'>No ads found. Be the first to post something!</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
