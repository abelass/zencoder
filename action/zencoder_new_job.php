<?php
/**
 * Fonction d'ajout des versions dans la file d'attente
 *
 * @param int $id_document l'id du document original
 * @param string $objet
 * @param int $id_objet
 */
function zencoder_new_job($id_document){
  include_spip('lib/zencoder_api_2.2.3/Services/Zencoder');
  include_spip('inc/config');
  include_spip('inc/utils');
  
  $api_key=lire_config('zencoder/api_key');
  $document =  generer_url_entite_absolue($id_document,'document');
  $url_notification =  generer_url_action( 'zencoder_notification','id_document=' . $id_document, false, false );
  $encoding_job = new ZencoderJob('
   {
     "api_key": "' . $api_key . '",
     "input": ' . $document . ',
     "outputs": [
     {
        "label": "mp4 high",
        "h264_profile": "high",
        "notifications":[
      {"format": "json", "url": "' .$url_notification . '"}
     ]
   }
      },
      {
        "label": "webm",
        "format": "webm",
        "notifications":[
      {"format": "json", "url": "' .$url_notification . '"}
     ]
      },
      {
        "label": "ogg",
        "format": "ogg",
        "notifications":[
      {"format": "json", "url": "' .$url_notification . '"}
     ]
      },
      {
        "label": "mp4 low",
        "size": "640x480",
        "notifications":[
      {"format": "json", "url": "' .$url_notification . '"}
     ]
      }
    ]
   }
  ');
  
  if ($encoding_job->created) {
      spip_log('zencoder','encoding_job_success' . $encoding_job->outputs["web"]->label  . ' ID: '.$encoding_job->outputs["web"]->id);

 } else {
       $erreurs=array();
       foreach($encoding_job->errors as $error) {
        $erreurs[] =$error;
     }
   spip_log('zencoder','encoding_job_fail' . var_dump($erreurs));
}
  return;
}
