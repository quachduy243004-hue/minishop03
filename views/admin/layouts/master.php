<?php include __DIR__ . "/header.php"; ?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . "/sidebar.php"; ?>

        <main class="col-10 p-4">

            <?php echo isset($content) ? $content : ""; ?>

        </main>

    </div>
</div>

<?php include __DIR__ . "/footer.php"; ?>