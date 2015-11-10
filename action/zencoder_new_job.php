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
  
  $api_key=lire_config('zencoder/api_key');
  $document = sql_getfetsel('fichier','spip_documents','id_document=' . $id_document);
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
