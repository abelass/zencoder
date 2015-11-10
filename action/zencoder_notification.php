<?php
/**
 * Fonction d'ajout des versions dans la file d'attente
 *
 * @param int $id_document l'id du document original
 * @param string $objet
 * @param int $id_objet
 */
function zencoder_notification($id_document){
  include_spip('lib/zencoder_api_2.2.3/Services/Zencoder');
  $api_key=lire_config('zencoder/api_key');
  
  // Initialize the Services_Zencoder class
  $zencoder = new Services_Zencoder($api_key);
  
  // Catch notification
  $notification = $zencoder->notifications->parseIncoming();
  
  // Check output/job state
  if($notification->job->outputs[0]->state == "finished") {
    spip_logo('zencode',$notification);
  
    // If you're encoding to multiple outputs and only care when all of the outputs are finished
    // you can check if the entire job is finished.
    if($notification->job->state == "finished") {
      
    }
  } elseif ($notification->job->outputs[0]->state == "cancelled") {
    spip_logo('zencode',$notification);
  } else {
    spip_logo('zencode',$notification);
  }
  return;
}
