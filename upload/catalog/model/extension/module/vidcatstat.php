<?php
class ModelExtensionModuleVidcatstat extends Model {
  // Загрузка настроек из базы данных
  public function LoadSettings() {
    return $this->config->get('module_vidcatstat_status');
  }


       
			public function getParentInfo($id) {
		    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_stati WHERE id = '" . (int)$id . "'");
	        return $query->row;}
		 
		 
		 
		 
			 public function getParents($id) {
		    $parent = $this->getParentInfo($id);
			if (!empty($parent)) {
			$tree = [$parent['id'] => $parent['name']];
			$i = 0; do {$i++;
			$newcat = $this->getParentInfo($parent['parent']);
			if (!empty($newcat)) {
			$parent['parent'] = $newcat['parent'];
			$tree[ $newcat['id'] ]  = $newcat['name'];}
			} while (!empty($newcat) and $i < 10 );
			return array_reverse($tree, true);}
		}
		
		
	
	
	public function getTotalCatInformations($id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stati WHERE category = '" .(int)$id . "'");

		return $query->row['total'];
	}
	
	
		
		public function getCategoryes() {
		
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "category_stati";
		$query = $this->db->query($sql);
		
	    return $query->rows;
		
		}


}


?>