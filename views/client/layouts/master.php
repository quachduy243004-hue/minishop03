<?php
$title = $title ?? "Mini Shop";
$content = $content ?? "";
?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title><?= htmlspecialchars($title) ?></title>


    <!-- =========================================================
         BOOTSTRAP CSS
    ========================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =========================================================
         BOOTSTRAP ICONS
    ========================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >


    <!-- =========================================================
         CLIENT CSS
    ========================================================== -->

    <link
        href="/Minishop_hoten/assets/client/style.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


    <!-- =========================================================
         HEADER
    ========================================================== -->

    <?php include __DIR__ . "/header.php"; ?>


    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->

    <main>

        <div class="container-fluid p-4">

            <?= $content ?>

        </div>

    </main>


    <!-- =========================================================
         FOOTER
    ========================================================== -->

    <?php include __DIR__ . "/footer.php"; ?>


    <!-- =========================================================
         BOOTSTRAP JS
    ========================================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>


    <!-- =========================================================
         CLIENT JS
    ========================================================== -->

    <script
        src="/Minishop_hoten/assets/client/script.js">
    </script>

</body>

</html>
