<?php

function _check_access($userLevel, $categoryLevels) {
    if (in_array($userLevel, json_decode($categoryLevels))) {
        return TRUE;
    }
    return FALSE;
}

?>
