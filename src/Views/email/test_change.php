<?php require __DIR__ . '/common/header.php' ?>
<?php /* @var $inventory \Util\UserTestNotifications\UserInventory */ ?>

<h3>New test failures</h3>

<?php if (!count($inventory->getFailed())) { ?>
    <p class="lead"><em>There are no new failed tests</em></p>
<?php } else { ?>
    <ul>
        <?php foreach ($inventory->getFailed() as $item) { ?>
            <li><?php echo $item->getTestName() ?> test on <?php echo $item->getDomain()->getUri() ?></li>
        <?php } ?>
    </ul>
<?php } ?>

<br>
<br>

<h3>The following tests are again successful:</h3>

<?php if (!count($inventory->getSucceeded())) { ?>
    <p class="lead"><em>There are no new succeeded tests</em></p>
<?php } else { ?>
    <ul>
        <?php foreach ($inventory->getSucceeded() as $item) { ?>
            <li><?php echo $item->getTestName() ?> test on <?php echo $item->getDomain()->getUri() ?></li>
        <?php } ?>
    </ul>
<?php } ?>


<?php require __DIR__ . '/common/footer.php' ?>
