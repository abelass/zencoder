<?php
/**
 * Fonction d'ajout des versions dans la file d'attente
 *
 * @param int $id_document l'id du document original
 * @param string $objet
 * @param int $id_objet
 */
function action_zencoder_batch(){
  include_spip('action/zencoder_new_job');
  $documents = sql_select('id_document','spip_documents','mode="document" AND media="video"');
      
    $id_document = $flux['data']['ids'][0];

    //Convert the video with zencoder

  while($data = sql_fetch($documents)){
    $id_document = $data['id_document'];
    if(!sql_getfetsel('spip_documents.id_document','spip_documents,spip_documents_liens','mode="conversion" AND media="video" AND objet="document" AND id_objet=' . $id_document)) {
          print '<div>' . $id_document . '</div>';
          zencoder_new_job($id_document);
    }
    
  }

  return;
}
