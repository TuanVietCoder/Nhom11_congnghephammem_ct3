
<?php
require_once 'system/connect.php'; 
// Lấy dữ liệu từ bảng curriculum
$sql = "SELECT * FROM curriculum";
$result = $conn->query($sql);

// Kiểm tra kết quả trả về
$curriculum_data = [];
if ($result->num_rows > 0) {
    // Duyệt qua từng dòng dữ liệu
    while($row = $result->fetch_assoc()) {
        // Lưu dữ liệu vào mảng
        $curriculum_data[] = $row;
    }
} else {
    echo "No records found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mầm non 11</title>
    <script src="https://kit.fontawesome.com/9574f0deef.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <?php include "header.php" ?>

    <main id="main" class="main-curricilum">
<div id="content" class="content-area page-wrapper" role="main">
	<div class="row row-main">
		<div class="large-12 col">
			<div class="col-inner">
                <?php 
                    $index = 0; // Biến đếm
                    foreach ($curriculum_data as $row): 
                ?>


                    <div class="row align-middle" id="row-837597203">
                    <?php if ($index % 2 == 0): ?>
                        <div id="col-1971333971" class="col medium-4 small-12 large-4">
                            <div class="col-inner">
                    
                    
                                <div class="img has-hover x md-x lg-x y md-y lg-y" id="image_1563094910">
                                    <div class="img-inner dark">
                                        <img fetchpriority="high" decoding="async" width="905" height="627" src="../images/uploads/<?php echo htmlspecialchars($row['image']); ?>" class="attachment-large size-large" alt=""  sizes="(max-width: 905px) 100vw, 905px">						
                                    </div>
                                        
                                </div>
            
                            </div>
                        </div>


                        <div id="col-1578210235" class="col medium-8 small-12 large-8">
                            <div class="col-inner">
                    
                    
                                <div id="text-1712272776" class="text">
                
                                    <h2 style="text-align: center;"><span style="color: #f99b1c;"><strong>&nbsp;<?php echo htmlspecialchars($row['title']); ?></strong></span></h2>
                                    <p class="text"><?php echo htmlspecialchars($row['content']); ?></p>
                                            
                                </div>
            
                            </div>
                        </div>
                        <?php else: ?>
                            <div id="col-1578210235" class="col medium-8 small-12 large-8">
                            <div class="col-inner">
                    
                    
                                <div id="text-1712272776" class="text">
                
                                    <h2 style="text-align: center;"><span style="color: #f99b1c;text-transform: uppercase;"><strong>&nbsp;<?php echo htmlspecialchars($row['title']); ?></strong></span></h2>
                                    <p class="text"><?php echo htmlspecialchars($row['content']); ?></p>
                                            
                                </div>
            
                            </div>
                        </div>
                            <div id="col-1971333971" class="col medium-4 small-12 large-4">
                            <div class="col-inner">
                    
                    
                                <div class="img has-hover x md-x lg-x y md-y lg-y" id="image_1563094910">
                                    <div class="img-inner dark">
                                        <img fetchpriority="high" decoding="async" width="905" height="627" src="../images/uploads/<?php echo htmlspecialchars($row['image']); ?>" class="attachment-large size-large" alt=""  sizes="(max-width: 905px) 100vw, 905px">						
                                    </div>
                                        
                                </div>
            
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php $index++; // Tăng biến đếm ?>
                <?php endforeach; ?>


						
			</div>
		</div>
	</div>
</div>


</main>

    <?php include "footer.php" ?>

</body>
</html>

