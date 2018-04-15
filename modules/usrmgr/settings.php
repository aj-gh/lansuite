<?php

$gd = new \LanSuite\GD();

// Create user in user_settings, if not pressent
// (in some installations this was not created automatically)
#$row = $db->qry_first("SELECT 1 AS found FROM %prefix%user WHERE userid = %int%", $auth['userid']);
#if (!$row['found']) $db->qry("INSERT INTO %prefix%user SET userid = %int%", $auth['userid']);

function CheckAndResizeUploadPic($AvatarName)
{
    global $gd;

    if ($AvatarName == '') {
        return false;
    }
    $FileEnding = strtolower(substr($AvatarName, strrpos($AvatarName, '.'), 5));
    if ($FileEnding != '.png' and $FileEnding != '.gif' and $FileEnding != '.jpg' and $FileEnding != '.jpeg') {
        return t('Bitte eine Grafikdatei auswählen');
    }

    $gd->CreateThumb($AvatarName, $AvatarName, 100, 100);
    return false;
}

$mf = new \LanSuite\MasterForm();

// Designs
if ($cfg['user_design_change']) {
    $selections = array();
    $selections[''] = t('System-Vorgabe');

    $xml = new \LanSuite\XML();

    $ResDesign = opendir('design/');
    while ($dir = readdir($ResDesign)) {
        if (is_dir("design/$dir") and file_exists("design/$dir/design.xml") and ($dir != 'beamer')) {
            $file = "design/$dir/design.xml";
            $ResFile = fopen($file, "rb");
            $XMLFile = fread($ResFile, filesize($file));
            fclose($ResFile);
            $DesignName = $xml->get_tag_content('name', $XMLFile);
            $selections[$dir] = $DesignName;
        }
    }
    closedir($ResDesign);

    $mf->AddField(t('Design'), 'design', \LanSuite\MasterForm::IS_SELECTION, $selections, \LanSuite\MasterForm::FIELD_OPTIONAL);
}

$mf->AddField(t('Mich auf der Karte zeigen') .'|'. t('Meine Adresse in der Besucherkarte anzeigen?'), 'show_me_in_map', '', '', \LanSuite\MasterForm::FIELD_OPTIONAL);
$mf->AddField(t('LS-Mail Alert') .'|'. t('Mir eine E-Mail senden, wenn eine neue LS-Mail eingegangen ist'), 'lsmail_alert', '', '', \LanSuite\MasterForm::FIELD_OPTIONAL);

if ($cfg['user_avatarupload']) {
    $mf->AddField(t('Avatar'), 'avatar_path', \LanSuite\MasterForm::IS_FILE_UPLOAD, 'ext_inc/avatare/'. $auth['userid'] .'_', \LanSuite\MasterForm::FIELD_OPTIONAL, 'CheckAndResizeUploadPic');
}
$mf->AddField(t('Signatur'), 'signature', '', \LanSuite\MasterForm::LSCODE_ALLOWED, \LanSuite\MasterForm::FIELD_OPTIONAL);

$mf->SendForm('', 'user', 'userid', $auth['userid']);
