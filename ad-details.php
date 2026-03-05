<?php
include 'db.php';
session_start();

if(!isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$id = (int)$_GET['id'];

// --- BUY NOW LOGIC ---
if (isset($_GET['action']) && $_GET['action'] == 'buy') {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login to buy this item!'); window.location.href='login.php';</script>";
        exit;
    }
    
    // Update ad status to sold
    $update = $pdo->prepare("UPDATE ads SET status = 'sold' WHERE id = ?");
    if($update->execute([$id])) {
        echo "<script>alert('Congratulations! Item Bought Successfully.'); window.location.href='ad-details.php?id=$id';</script>";
        exit;
    }
}

// Fetch Ad Details
$stmt = $pdo->prepare("SELECT ads.*, users.full_name FROM ads JOIN users ON ads.user_id = users.id WHERE ads.id = ?");
$stmt->execute([$id]);
$ad = $stmt->fetch();

if (!$ad) { 
    echo "<script>window.location.href='index.php';</script>"; 
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $ad['title']; ?> | Marketplace</title>
    <style>
        :root { --primary: #002f34; --accent: #00a49f; --sold: #ff4d4d; }
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f2f4f5; color: var(--primary); }
        .container { width: 85%; max-width: 1100px; margin: 30px auto; display: flex; gap: 20px; }
        
        /* Left Content */
        .main-content { flex: 2; }
        .img-box { background: #000; height: 450px; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .img-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .desc-card { background: #fff; padding: 25px; margin-top: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }

        /* Right Sidebar */
        .sidebar { flex: 1; }
        .side-card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .price { font-size: 32px; font-weight: 800; margin-bottom: 10px; }
        .title { font-size: 20px; color: #406367; margin-bottom: 20px; }

        /* Buy Now Button */
        .btn-buy { 
            display: block; width: 100%; padding: 18px; background: var(--primary); 
            color: #fff; text-align: center; text-decoration: none; 
            font-weight: bold; font-size: 18px; border-radius: 4px; transition: 0.3s;
        }
        .btn-buy:hover { background: #004d56; transform: translateY(-2px); }

        /* Item Bought / Sold State */
        .status-sold { 
            background: var(--sold); color: white; padding: 15px; 
            text-align: center; font-weight: bold; border-radius: 4px; font-size: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-content">
        <div class="img-box">
            <img src="<?php echo $ad['image_url'] ? $ad['image_url'] : 'https://via.placeholder.com/600'; ?>">
        </div>
        <div class="desc-card">
            <h3 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Description</h3>
            <p><?php echo nl2br(htmlspecialchars($ad['description'])); ?></p>
        </div>
    </div>

    <div class="sidebar">
        <div class="side-card">
            <p class="price">Rs <?php echo number_format($ad['price']); ?></p>
            <h1 class="title"><?php echo htmlspecialchars($ad['title']); ?></h1>
            <p style="color: #7f9799; font-size: 14px; margin-bottom: 20px;"><?php echo $ad['location']; ?></p>

            <?php if ($ad['status'] == 'active'): ?>
                <a href="ad-details.php?id=<?php echo $id; ?>&action=buy" class="btn-buy">BUY NOW</a>
            <?php else: ?>
                <div class="status-sold">ITEM BOUGHT</div>
            <?php endif; ?>
        </div>

        <div class="side-card">
            <p style="font-size: 14px; color: #7f9799;">Seller</p>
            <p style="font-weight: bold; font-size: 18px;"><?php echo htmlspecialchars($ad['full_name']); ?></p>
        </div>
    </div>
</div>

</body>
</html>
