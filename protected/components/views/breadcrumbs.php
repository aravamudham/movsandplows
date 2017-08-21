<?php foreach ($links as $link) { ?>
    <?php if ($link == $links[0]) { ?>
        <li>
            <i class="fa fa-home"></i>
            <?php echo $link ?>
            <i class="fa fa-angle-right"></i>
        </li>
    <?php } else {

        if ($link != end($links)) { ?>
            <li>
                <?php echo $link ?>
                <i class="fa fa-angle-right"></i>
            </li>
        <?php
        } else {
            ?>
            <li>
                <?php echo $link ?>
            </li>
        <?php

        }
    }
}
?>