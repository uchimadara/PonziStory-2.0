<?
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function asset($asset)
{
    return getAssetPath() . $asset;
}

function wwwAsset($asset) {
    return BASE_URL.'assets'.ASSET_VERSION.'/'.$asset;
}

function getAssetPath()
{
    return CDN_URL . 'assets' . ASSET_VERSION . '/';
}


function assetFilePath()
{
    return FCPATH . 'assets' . ASSET_VERSION . '/';
}

function methodIcon($method)
{
    return asset('images/currencies/' . strtolower($method) . '.gif');
}

function avatar($file) {
    if (empty($file)) $file = 'default/default.jpg';
    return CDN_URL . 'avatars/' . $file;
}

function screenshot($file) {
    return BASE_URL.'uploads/'.$file;
}

function putObject($key, $source, $contentType) {

    copy($source, FCPATH.$key);
    return TRUE;
}
