<?php
const DIRECTORY_CATALOG = "directory_catalog";
const CLASSIFICATION_TAXONOMY = "classification";
const TERM_PERSON = "Person";
const TERM_COUNTRY = "Country";
const TERM_MULTIMEDIA = "Multimedia";
const TERM_JOURNAL = "Journal";
const TERM_LANGUAGE = "Language";
const MC_METABOX_ABSTRACT = "mc_metabox_abstract";
const MC_METABOX_IMAGE = "mc_metabox_image";
const MC_USER_POST_ID = 'user_post_id';
const MC_USER_REF = '"entity_ref"';
function mc_main_terms(): array
{
    return array(
        mc_node_term(TERM_PERSON, [], false),
        mc_node_term(TERM_COUNTRY, array(
            mc_node_term("Argentina"),
            mc_node_term("Brazil"),
            mc_node_term("Chile"),
            mc_node_term("Colombia"),
            mc_node_term("Mexico"),
            mc_node_term("Spain"),
            mc_node_term("United States"),
            mc_node_term("Uruguay"),
        )),
        mc_node_term(TERM_MULTIMEDIA, array(
            mc_node_term("Video"),
            mc_node_term("Audio")
        )),
        mc_node_term(TERM_JOURNAL, array(
            mc_node_term("News"),
            mc_node_term("Blog"),
            mc_node_term("Book"),
            mc_node_term("Article"),
            mc_node_term("Thesis"),
            mc_node_term("Website")
        )),
        mc_node_term(TERM_LANGUAGE, array(
            mc_node_term("spanish"),
            mc_node_term("english"),
            mc_node_term("portuguese")
        ))
    );
}


function mc_node_term(string $id, array $children = array(), bool $showUnsigned = true): array
{
    return array(
        "id" => $id,
        "showUnsigned"=>$showUnsigned,
        "children" => $children
    );
}