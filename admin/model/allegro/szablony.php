<?php

class ModelAllegroSzablony extends Model {

	public function addSzablon( $data ) {

		$this->db->query( "INSERT INTO allegro_szablony (name,title,value) values('$data[name]','$data[title]','$data[value]')" ) ;
	}

	public function editSzablon( $szablon_id, $data ) {

		$this->db->query( "UPDATE allegro_szablony SET name = '" . $this->db->escape( $data['name'] ) . "', title = '" . $this->db->escape( $data['title'] ) . "', value = '" . $this->db->escape( $data['value'] ) . "'WHERE szablon_id = '" . ( int )$szablon_id . "'" ) ;
	}

	public function deleteSzablon( $szablon_id ) {

		$this->db->query( "DELETE FROM allegro_szablony WHERE szablon_id = '" . ( int )$szablon_id . "'" ) ;
	}

	public function getSzablon( $szablon_id ) {

		$query = $this->db->query( "SELECT DISTINCT * FROM allegro_szablony WHERE szablon_id = '" . ( int )$szablon_id . "'" ) ;

		return $query->row ;
	}

	public function getSzablony() {
        
        $query = $this->db->query( "CREATE TABLE IF NOT EXISTS `allegro_szablony` (
          `szablon_id` int(7) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) DEFAULT NULL,
          `title` varchar(255) DEFAULT NULL,
          `value` text,
          UNIQUE KEY `id` (`szablon_id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;" ) ;
        
		$query = $this->db->query( "SELECT * FROM allegro_szablony" ) ;

		return $query->rows ;
	}
}

?>