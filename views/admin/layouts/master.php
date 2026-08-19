<?php

/*
|--------------------------------------------------------------------------
| MASTER LAYOUT
|--------------------------------------------------------------------------
*/

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($pageTitle ?? "Mini Shop Admin") ?>
    </title>


    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

</head>


<body>


    <!-- ==================================================
         HEADER
    ================================================== -->

    <?php include __DIR__ . "/header.php"; ?>


    <!-- ==================================================
         MAIN
    ================================================== -->

    <div class="container-fluid">

        <div class="row">


            <!-- ==================================================
                 SIDEBAR
            ================================================== -->

            <?php include __DIR__ . "/sidebar.php"; ?>


            <!-- ==================================================
                 CONTENT
            ================================================== -->

            <main class="col-md-10 p-4">

                <?= $content ?? "" ?>

            </main>


        </div>

    </div>


    <!-- ==================================================
         FOOTER
    ================================================== -->

    <?php include __DIR__ . "/footer.php"; ?>


    <!-- ==================================================
         BOOTSTRAP JS
    ================================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


</body>

</html>