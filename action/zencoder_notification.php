<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Fonction d'ajout des versions dans la file d'attente
 *
 * @param int $id_document l'id du document original
 * @param string $objet
 * @param int $id_objet
 */
function action_zencoder_notification(){
  include_spip('inc/config');
  include_spip('inc/renseigner_document');


  $id_document = _request('id_document');
  $api_key=lire_config('zencoder/api_key');
  spip_log('notification start','zencoder');


  // Catch notification
  $notification = json_decode(trim(file_get_contents('php://input')), true);

  spip_log($notification,'zencoder');
  // Check output/job state
  if ($notification['output']['state'] == 'finished'){
        spip_log('notification job finished','zencoder');
    $file = $notification['output']['url'];
    if ($file){
      $ajouter_documents = charger_fonction('ajouter_documents', 'action');
      $copie_local = charger_fonction('copier_local', 'action');
      spip_log('notification job file' . $file,'zencoder');
      set_request('joindre_distant',true);
      set_request('url',$file);
      include_spip('inc/joindre_document');
      $files = joindre_trouver_fichier_envoye();

      $nouveaux_doc = $ajouter_documents('new',$files,'document',$id_document,'conversion');
      $copie_local($nouveaux_doc[0]);

      /**
       * Invalidation du cache
       */
      include_spip('inc/invalideur');
      suivre_invalideur("0",true);
    }

  }
    // If you're encoding to multiple outputs and only care when all of the outputs are finished
    // you can check if the entire job is finished.
   elseif ($notification['output']['state'] == "cancelled") {
    spip_log('canceled' . print_r($notification),'zencoder');
  } else {
    spip_log('error' . print_r($notification),'zencoder');
  }

  return;
}
