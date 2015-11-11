<?php
/**
 * Fonction d'ajout des versions dans la file d'attente
 *
 * @param int $id_document l'id du document original
 * @param string $objet
 * @param int $id_objet
 */
function action_zencoder_notification(){
  $cwd = getcwd();
  chdir(realpath(_DIR_ZENCODER_LIB));
  require_once "Services/Zencoder.php";
  chdir($cwd);
  include_spip('inc/config');
  include_spip('inc/renseigner_document');
  
  $id_document = _request('id_document');
  $api_key=lire_config('zencoder/api_key');
  spip_log('notification start','zencoder');

  // Initialize the Services_Zencoder class
  $zencoder = new Services_Zencoder($api_key);
  
  // Catch notification
  $notification = $zencoder->notifications->parseIncoming();
  
  spip_log($notification,'zencoder');
  // Check output/job state

    spip_log('notification job finished','zencoder');
    $ajouter_documents = charger_fonction('ajouter_documents', 'action');
    $file = $notification->job->outputs->outputs[0]->url;
     spip_log('notification job file' . $file,'zencoder');   
    $infos = renseigner_source_distante($file);
    if (!is_array($infos)){
      spip_log('erreur recuperation fichier distant' . print_r($infos),'zencoder');
			return $infos; // message d'erreur
			}
		else
			$files = array(
				array(
					'name' => basename($path),
					'tmp_name' => $path,
					'distant' => true,
				)
			);
			
		spip_log('notification job files' . print_r($files),'zencoder');   
    $mode = 'document';
    $nouveaux_doc = $ajouter_documents('new',$files,'document',$id_document,$mode);
    
    $return = 'fichier converti: ' . print_r($nouveaux_doc);
    
    spip_log($return,'zencoder');
    // If you're encoding to multiple outputs and only care when all of the outputs are finished
    // you can check if the entire job is finished.

 /* } elseif ($notification->job->outputs[0]->state == "cancelled") {
    spip_log('canceled' . print_r($notification),'zencoder');
  } else {
    spip_log('error' . print_r($notification),'zencoder');
  }*/

  return $return;
}
