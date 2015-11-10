<?php
/**
 * Fonction d'ajout des versions dans la file d'attente
 *
 * @param int $id_document l'id du document original
 * @param string $objet
 * @param int $id_objet
 */
function zencoder_notification($id_document){
  $cwd = getcwd();
  chdir(realpath(_DIR_ZENCODER_LIB));
  require_once "Services/Zencoder.php";
  chdir($cwd);
  include_spip('inc/config');
  
  $api_key=lire_config('zencoder/api_key');
  

  // Initialize the Services_Zencoder class
  $zencoder = new Services_Zencoder($api_key);
  
  // Catch notification
  $notification = $zencoder->notifications->parseIncoming();
  
  // Check output/job state
  if($notification->job->outputs[0]->state == "finished") {
    spip_log('zencode',$notification);
  
    // If you're encoding to multiple outputs and only care when all of the outputs are finished
    // you can check if the entire job is finished.
    if($notification->job->state == "finished") {
     spip_log('zencode',$notification);
    }
  } elseif ($notification->job->outputs[0]->state == "cancelled") {
    spip_log('zencode',$notification);
  } else {
    spip_log('zencode',$notification);
  }

  return;
}
