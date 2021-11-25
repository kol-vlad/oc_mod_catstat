<?php
class ModelExtensionModuleCatstat extends Model {
  // Загрузка настроек из базы данных
  public function LoadSettings() {
    return $this->config->get('module_catstat_status');
  }


         public function getInformations() {
	
			$sql = "SELECT * FROM " . DB_PREFIX . "stati ";

			$sort_data = array(
				'title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY title";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		
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
		
		
		public function getInformation($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "stati WHERE stati_id = '" . (int)$id . "'");
		
		return $query->row;
	}
	
	
	
	public function getTotalCatInformations($id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stati WHERE category = '" .(int)$id . "'");

		return $query->row['total'];
	}
	
	public function getCategorystat($data = array()) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "stati WHERE category = '" . (int)$data['id'] . "'";
		$query = $this->db->query($sql);
		
		
		
		if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

       $query = $this->db->query($sql);
		
	    return $query->rows;
		
		}
		
		public function getCategory($id) {
		
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "category_stati WHERE id = '" . (int)$id . "'";
		$query = $this->db->query($sql);
		
	    return $query->row;
		
		}


}


?>