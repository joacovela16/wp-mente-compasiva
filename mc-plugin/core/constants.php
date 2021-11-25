<?php
const DIRECTORY_CATALOG       = "directory_catalog";
const CLASSIFICATION_TAXONOMY = "classification";
const TERM_PERSON             = "Person";
const TERM_COUNTRY            = "Country";
const TERM_MULTIMEDIA         = "Multimedia";
const TERM_JOURNAL            = "Journal";

function mc_main_terms(): array {
	return array(
		mc_node_term( TERM_PERSON ),
		mc_node_term( TERM_COUNTRY, array(
			mc_node_term( "Argentina" ),
			mc_node_term( "Armenia" ),
			mc_node_term( "Australia" ),
			mc_node_term( "Austria" ),
			mc_node_term( "Bangladesh" ),
			mc_node_term( "Belarus" ),
			mc_node_term( "Belgium" ),
			mc_node_term( "Bolivia" ),
			mc_node_term( "Brazil" ),
			mc_node_term( "Bulgaria" ),
			mc_node_term( "Canada" ),
			mc_node_term( "Chile" ),
			mc_node_term( "China" ),
			mc_node_term( "Colombia" ),
			mc_node_term( "Costa Rica" ),
			mc_node_term( "Croatia" ),
			mc_node_term( "Cuba" ),
			mc_node_term( "Cyprus" ),
			mc_node_term( "Czech Republic" ),
			mc_node_term( "Denmark" ),
			mc_node_term( "Ecuador" ),
			mc_node_term( "Egypt" ),
			mc_node_term( "Estonia" ),
			mc_node_term( "Finland" ),
			mc_node_term( "France" ),
			mc_node_term( "French Polynesia" ),
			mc_node_term( "Georgia" ),
			mc_node_term( "Germany" ),
			mc_node_term( "Greece" ),
			mc_node_term( "Guatemala" ),
			mc_node_term( "Honduras" ),
			mc_node_term( "Hong Kong" ),
			mc_node_term( "Hungary" ),
			mc_node_term( "Iceland" ),
			mc_node_term( "India" ),
			mc_node_term( "Indonesia" ),
			mc_node_term( "Iran" ),
			mc_node_term( "Ireland" ),
			mc_node_term( "Israel" ),
			mc_node_term( "Italy" ),
			mc_node_term( "Japan" ),
			mc_node_term( "Kazakhstan" ),
			mc_node_term( "Kenya" ),
			mc_node_term( "Kosovo" ),
			mc_node_term( "Latvia" ),
			mc_node_term( "Lebanon" ),
			mc_node_term( "Lithuania" ),
			mc_node_term( "Luxembourg" ),
			mc_node_term( "Malaysia" ),
			mc_node_term( "Malta" ),
			mc_node_term( "Mexico" ),
			mc_node_term( "Montenegro" ),
			mc_node_term( "Morocco" ),
			mc_node_term( "Mozambique" ),
			mc_node_term( "Namibia" ),
			mc_node_term( "Netherlands" ),
			mc_node_term( "New Zealand" ),
			mc_node_term( "Nigeria" ),
			mc_node_term( "Norway" ),
			mc_node_term( "Pakistan" ),
			mc_node_term( "Paraguay" ),
			mc_node_term( "Peru" ),
			mc_node_term( "Philippines" ),
			mc_node_term( "Poland" ),
			mc_node_term( "Portugal" ),
			mc_node_term( "Puerto Rico" ),
			mc_node_term( "Romania" ),
			mc_node_term( "Russia" ),
			mc_node_term( "Saudi Arabia" ),
			mc_node_term( "Serbia" ),
			mc_node_term( "Singapore" ),
			mc_node_term( "Slovakia" ),
			mc_node_term( "Slovenia" ),
			mc_node_term( "South Africa" ),
			mc_node_term( "South Korea" ),
			mc_node_term( "Spain" ),
			mc_node_term( "Sweden" ),
			mc_node_term( "Switzerland" ),
			mc_node_term( "Taiwan" ),
			mc_node_term( "Tanzania" ),
			mc_node_term( "Thailand" ),
			mc_node_term( "Turkey" ),
			mc_node_term( "Ukraine" ),
			mc_node_term( "United Arab Emirates" ),
			mc_node_term( "United Kingdom" ),
			mc_node_term( "United States" ),
			mc_node_term( "Uruguay" ),
			mc_node_term( "Venezuela" ),
			mc_node_term( "Vietnam" ),
		) ),
		mc_node_term( TERM_MULTIMEDIA, array(
			mc_node_term( "Video" ),
			mc_node_term( "Audio" )
		) ),
		mc_node_term( TERM_JOURNAL, array(
			mc_node_term( "News" ),
			mc_node_term( "Blog" ),
			mc_node_term( "Book" ),
			mc_node_term( "Article" ),
			mc_node_term( "Thesis" ),
			mc_node_term( "Website" )
		) )
	);
}


function mc_node_term( string $id, array $children = array() ): array {
	return array(
		"id"       => $id,
		"children" => $children
	);
}