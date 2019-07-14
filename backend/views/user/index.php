<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div style="margin-top: 30px">
    <?php foreach ($users as $user) { ?>
        <li><?= $user->email ?></li>
    <?php } ?>
</div>

