<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$discord = new \LanSuite\Module\Discord();
        $discordServerData = $discord->fetchServerData();
        $discordGuildData = $discord->fetchGuildData();
$boxcontent = $discord->genBox($discordServerData, $discordGuildData);
$box->Row($boxcontent);