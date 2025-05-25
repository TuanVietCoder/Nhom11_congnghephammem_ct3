<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/9574f0deef.js" crossorigin="anonymous"></script>

    <title>Mầm non 11</title>

    
</head>
<body>
    <?php include "header.php"; ?>


   <section class="blog">
        <div class="banner">
            <img class="img-event" src="../images/back2.jpg" alt="">
        </div>
        <div class="title-event">
            <h1 class="title-blog">Tin Tức và sự kiện</h1>
        </div>
   </section>

    <main>
        <div class="event-list" id="eventList">
            <!-- Event cards will be injected here by JavaScript -->
        </div>
    </main>
    <?php include "footer.php"; ?>


    <script>
        // Sample event data
        const events = [
            {
                title: "BÉ HỌC KỸ NĂNG ĐỘI MŨ BẢO HIỂM ĐÚNG CÁCH! (MNMMNM NGUYỄN CHÍ THANH)",
                date: "2025-02-15",
                description: "Tại Mầm Non 11, an toàn luôn được ưu tiên hàng đầu! Hôm nay, các bé đã học được cách đội mũ bảo hiểm đúng cách qua từng bước hướng dẫn chi tiết của cô giáo từ việc quan sát đến thực hành, các bé không chỉ hiểu được tầm quan trọng của mũ bảo hiểm mà còn tự tin đội mũ một cách thành thạo.",
                image: "../images/logo.png",
                url:"be-hoc-ky-nang-doi-mu-bao-hiem-dung-cach-mnm-nguyen-chi-thanh.php"
            },
            {
                title: "CHĂM SÓC BÉ YÊU THẬT KHOẺ MẠNH ĐỂ BÉ VUI CHƠI VÀ PHÁT TRIỂN! ",
                date: "2025-02-20",
                description: "Những năm tháng đầu đời của con là hành trình khám phá và học hỏi không ngừng, vì vậy việc kiểm tra sức khỏe định kỳ rất quan trọng để đảm bảo con luôn khỏe mạnh và sẵn sàng cho mọi hành trình phát triển sau này! ",
                image: "../images/logo.png",
                url:"cham-soc-be-yeu-that-khoe-manh-de-be-vui-choi-va-phat-trien.php"
            },
            {
                title: "PhÁT TRIỂN TRÍ TUỆ CẢM XÚC",
                date: "2025-03-10",
                description: "Trí tuệ cảm xúc (EQ) là khả năng nhận biết, hiểu và quản lý cảm xúc của bản thân và người khác. Đối với trẻ em, phát triển EQ là một yếu tố quan trọng giúp xây dựng nền tảng tâm lý vững chắc.",
                image: "../images/logo.png",
                url:"phat-trien-tri-tue-o-tre.php"
            },
            {
                title: "NUÔI DƯỠNG MẦM XANH YÊU THƯƠNG",
                date: "2025-02-15",
                description: "Giữa ánh nắng vàng rực rỡ, những bác nông dân nhỏ VBBS đang chăm sóc cho mầm cây xanh tươi trong vườn và chờ đợi cho mùa thu hoạch. Tại VBBS, mỗi góc vườn đều mang đến cho con một không gian",
                image: "../images/logo.png",
                url:"nuoi-duong-mam-11-yeu-thuong.php"
            },
            {
                title: "NUÔI DƯỠNG TÌNH YÊU ĐỌC SÁCH TRONG CON",
                date: "2025-02-20",
                description: "Ba mẹ có muốn biến giờ đọc sách cùng con trở nên thú vị khiến bé yêu luôn háo hức chờ đón? VBBS bật mí bí quyết giúp bé thích đọc sách hơn.",
                image: "../images/logo.png",
                url:""
            },
           
            {
                title: "CON SÁNG TẠO QUA NHỮNG GIỜ HỌC TRUE CREATIVE ART",
                date: "2025-03-10",
                description: "Thế giới trong mắt trẻ thơ tràn ngập sắc màu và những điều kỳ diệu, nơi trí tưởng tượng bay bổng không ngừng. Với những nét vẽ ngây ngô và đáng yêu, các bé đã tạo nên những ngôi nhà nấm thật độc đáo và đầy màu sắc. Mỗi bức tranh đều là tác phẩm của riêng con, thể hiện sự sáng tạo và tình yêu nghệ thuật trong con.",
                image: "../images/logo.png",
                url:"nuoi-duong-tinh-yeu-doc-sach-trong-con.php"
            }
        ];

        const eventList = document.getElementById('eventList');

        // Function to display events
        function displayEvents() {
            eventList.innerHTML = events.map(event => `
            <a href="${event.url}">
                <div class="event-card">
                    <div class="event-image">
                        <img src="${event.image}" alt="${event.title}">
                    </div>
                    <div class="event-content">
                        <div class="event-title">${event.title}</div>
                        <div class="event-date">${event.date}</div>
                        <p>${event.description}</p>
                    </div>
                    <div class="event-detail">
                        <a class="event-detail" href="${event.url}">Xem thêm...</a>
                    </div>
                    
                </div>
            </a>
            `).join('');
        }

        // Initialize events
        displayEvents();
    </script>
</body>
</html>
