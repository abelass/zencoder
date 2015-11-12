<?php
/**
 * Fonction d'ajout des versions dans la file d'attente
 *
 * @param int $id_document l'id du document original
 * @param string $objet
 * @param int $id_objet
 */
function zencoder_new_job($id_document){
  spip_log('start zencoder_new_job','zencoder');
  $cwd = getcwd();
  chdir(realpath(_DIR_ZENCODER_LIB));
  require_once "Services/Zencoder.php";
  chdir($cwd);
  include_spip('inc/config');
  include_spip('inc/utils');
  $api_key=lire_config('zencoder/api_key');
  $document =  generer_url_entite_absolue($id_document,'document');
  $url_notification =  generer_url_action( 'zencoder_notification','id_document=' . $id_document, true, false );
  $clip_length = '10';
  try {
  // Initialize the Services_Zencoder class
  $zencoder = new Services_Zencoder($api_key);

  // New Encoding Job
  $encoding_job = $zencoder->jobs->create(
    array(
      "input" => $document,
      "outputs" => array(
        array(
          "label" => "webm",
          "size" =>"640x480",
          "format" =>"webm",
          "notifications" => array(
          "format" =>"json", 
          "url" =>$url_notification,
          "clip_length" =>$clip_length,
          )
        ),
         array(
          "label" => "ogg",
          "format" =>"ogg",
          "size" =>"640x480",
          "notifications" => array(
          "format" =>"json", 
          "url" =>$url_notification,
          "clip_length" =>$clip_length,
          )
        ),
         array(
          "label" => "mp4 low",
          "size" =>"640x480",
          "notifications" => array(
          "format" =>"json", 
          "url" =>$url_notification,
          "clip_length" =>$clip_length,
          )
        ),        
      )
    )
  );

  // Success if we got here
	spip_log('success- Job ID:' .$encoding_job->id. ' Output ID:' .$encoding_job->outputs['web']->id,'zencoder');

} catch (Services_Zencoder_Exception $e) {
  // If were here, an error occured

  spip_log('error:' . print_r($e),'zencoder');
}

echo "\nAll Job Attributes:\n";

  //spip_log('zencoder','error:' . var_dump($encoding_job));		   
  
  /*$encoding_job = new Services_Zencoder('
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
}*/
  return;
}
