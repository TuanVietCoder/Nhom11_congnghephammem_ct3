<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    
    <link rel="stylesheet" href="../css/style-admin.css">
    <script src="https://kit.fontawesome.com/9574f0deef.js" crossorigin="anonymous"></script>

</head>
<body>
    <section>
        <div class="page-top">
            <div class="page-menu">
                <ul>
                    <a href=''><img class="logo" src="../images/logo.png" alt=""></a>
                    <li class="menu-item"><a href="">
                    <i class="fa-solid fa-house icon-menu"></i>
                    <span class="menu-title">Trang chủ</span>
                    </a></li>
                    <li class="menu-item">
                        <a href="">
                        <i class="fa-solid fa-graduation-cap icon-menu"></i>
                        <span class="menu-title" >Học sinh</span>
                        </a>
                    </li>
                    <li class="menu-item">
                    <a href="">
                        <i class="fa-solid fa-school icon-menu"></i>
                        <span class="menu-title">Lớp học</span>
                        </a>
                    </li>
                    <li class="menu-item" ><a href="">
                    <i class="fa-regular fa-note-sticky icon-menu"></i>
                        <span class="menu-title" >Thời khóa biểu</span>
                        </a>
                    </li>
                    <li class="menu-item">
                    <a href="">
                        <i class="fa-regular fa-user icon-menu"></i>
                        <span class="menu-title" >Tài khoản</span>
                        </a>
                    </li>
                    <li class="menu-item">
                    <a href="">
                        <i class="fa-solid fa-bullhorn icon-menu"></i>
                        <span class="menu-title" >Thông báo</span>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="page-conten">
                <?php include("admin/teacher.php"); ?>
            </div>
        </div>
    </section>
</body>
</html>