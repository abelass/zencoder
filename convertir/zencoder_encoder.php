<?php
/**
 * Fonction d'encodage de Zencoder
 *
 * @plugin     Zencoder
 * @copyright  2015
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Zencoder\Zencoder
 */

if (!defined("_ECRIRE_INC_VERSION")) return;

function zencoder_encoder($id_document,$options = array()){
  
  include_spip('lib/zencoder_api_2.2.3/Services/Zencoder');
  include_spip('inc/config');
  $api_key=lire_config('zencoder/api_key');
  
  $encoding_job = new ZencoderJob('
   {
     "api_key": "$api_key",
     "input": "s3://bucket-name/file-name.avi",
     "outputs": [
     {
        "label": "mp4 high",
        // Change this to your server: "url": "s3://output-bucket/output-file-name.mp4",
        "h264_profile": "high"
      },
      {
        // Change this to your server: "url": "s3://output-bucket/output-file-name.webm",
        "label": "webm",
        "format": "webm"
      },
      {
        // Change this to your server: "url": "s3://output-bucket/output-file-name.ogg",
        "label": "ogg",
        "format": "ogg"
      },
      {
        // Change this to your server: "url": "s3://output-bucket/output-file-name-mobile.mp4",
        "label": "mp4 low",
        "size": "640x480"
      }
    ]
   }
  ');
  
  return $ret;
}
?>