<?php

/**
* Debug
*
* Dump-die wordpress asset globals.
*
* @return void
*/
function debugClientAssets()
{
    global $wp_styles;
    global $wp_scripts;

    dd([$wp_styles, $wp_scripts]);
}
