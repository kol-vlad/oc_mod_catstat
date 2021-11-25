<?php
	class ModelExtensionModuleCatstat extends Model {
		
		// Запись настроек в базу данных
		public function SaveSettings() {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('module_catstat', $this->request->post);
		}
		
		// Загрузка настроек из базы данных
		public function LoadSettings() {
			return $this->config->get('module_catstat_status');
		}
		
		// Получить списко статей
		public function getInformations($data = array()) {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "stati ";
			
			$sort_data = array(
			'title',
			'category'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
				} else {
				$sql .= " ORDER BY stati_id";
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
		
		// Получить список категорий
	    public function getCatInformations($data = array()) {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "category_stati ";
			
			if (!empty($data['filter_name'])) {
				$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}
			
			
			$sort_data = array(
			'title',
			'i.sort_order'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
				} else {
				$sql .= " ORDER BY name";
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
		
		// Получить категорию
		public function getCatInformation($id) {
			$cat_data = array();
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category_stati WHERE id = '" . (int)$id . "'");
			foreach ($query->rows as $id => $result) {
				$cat_data[$result['language']] = array(
				'name'            => $result['name'],
				'parent'            => $result['parent'],
				'metakey'            => $result['metakey'],
				'metadesc'            => $result['metadesc'],
				
				);
				
				
			}
			
			
			return $cat_data;
		}
		
		// Получить статью
		public function getInformation($id) {
			$stati_data = array();
			
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "stati WHERE stati_id = '" . (int)$id . "'");
			
			
			foreach ($query->rows as $id => $result) {
				$stati_data[$result['language']] = array(
				'title'            => $result['title'],
				'intro'            => $result['intro'],
				'text'      => $result['text'],
				'category'      => $result['category'],
				'metatitle'       => $result['metatitle'],
				'metadesc' => $result['metadesc'],
				'metakey'     => $result['metakey']
				);
				
				
			}
			
			return $stati_data;
			
			
			
		}
		// Информация о категориях
		public function getTotalCatInformations() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_stati");
			
			return $query->row['total'];
		}
		// Информация о статьях
		public function getTotalInformations() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stati");
			
			return $query->row['total'];
		}
		
		
		// редактировать статью
		public function editInformation($id, $data) {
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "stati WHERE stati_id = '" . (int)$id . "'");
			
			foreach ($data['stati'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "stati SET stati_id = '" . (int)$id . "', category = '" . (int)$value['category'] ."', language = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',  metatitle = '" . $this->db->escape($value['metatitle']) . "',  text = '" . $this->db->escape($value['text']) . "', metadesc = '" . $this->db->escape($value['metadesc']) . "', metakey = '" . $this->db->escape($value['metakey']) . "', intro='".$value['intro']."'");
			}	
			
			
			
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'catstat_id=" . (int)$id . "'");
			
			if (isset($data['url'])) {
				foreach ($data['url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (trim($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'catstat_id=" . (int)$id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
			
			
			// }
			
			// $this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_layout` WHERE information_id = '" . (int)$information_id . "'");
			
			// if (isset($data['information_layout'])) {
			// foreach ($data['information_layout'] as $store_id => $layout_id) {
			// $this->db->query("INSERT INTO `" . DB_PREFIX . "information_to_layout` SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			// }
			// }
			
			$this->cache->delete('information');
		}
		// Редактировать категорию
		public function editCatInformation($id, $data) {
			
			foreach ($data['cat'] as $language_id => $value) {
				$this->db->query("UPDATE " . DB_PREFIX . "category_stati SET name = '" . $value['name'] . "',metadesc = '" . $value['metadesc']  . "',metakey = '" . $value['metakey']  . "',  parent = '" . $value['parent'] . "' WHERE id = '" . (int)$id . "'");
			}
			
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'categorystat_id=" . (int)$id . "'");
			
			if (isset($data['url'])) {
				
				foreach ($data['url'] as $language_id => $keyword) {
					if (trim($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '" . (int)$language_id . "', query = 'categorystat_id=" . (int)$id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
				
			}
			// $this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_layout` WHERE information_id = '" . (int)$information_id . "'");
			
			// if (isset($data['information_layout'])) {
			// foreach ($data['information_layout'] as $store_id => $layout_id) {
			// $this->db->query("INSERT INTO `" . DB_PREFIX . "information_to_layout` SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			// }
			// }
			
			$this->cache->delete('information');
		}
		// Добавить категорию
		public function addCatInformation ($data) {
			
			foreach ($data['cat'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_stati SET  name = '" . $value['name']  . "',metadesc = '" . $value['metadesc']  . "',metakey = '" . $value['metakey']  . "',  parent = '" . $value['parent'] ."', language = '" . $language_id ."'" );
			}
			
			
			
			$id = $this->db->getLastId();
			
			if (isset($data['url'])) {
				
				foreach ($data['url'] as $language_id => $keyword) {
					if (trim($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '" . (int)$language_id . "', query = 'categorystat_id=" . (int)$id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
			return $id;
		}
		
		
		
		//Добавить статью
		public function addInformation($data) {
			
			
			
			foreach ($data['stati'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "stati SET  language = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', category = '" . $this->db->escape($value['category']) . "', text = '" . $this->db->escape($value['text']) . "', metadesc = '" . $this->db->escape($value['metadesc']) . "', metatitle = '" . $this->db->escape($value['metatitle']) . "', metakey = '" . $this->db->escape($value['metakey']) . "', intro='".$value['intro']."'");
			}
			
			$id = $this->db->getLastId();
			
			if (isset($data['url'])) {
				foreach ($data['url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (trim($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'catstat_id=" . (int)$id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
			
			
			$this->cache->delete('information');
			
			return $id;
		}
		//Удалить статью
		public function deleteInformation($id) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "stati` WHERE stati_id = '" . (int)$id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'catstat_id=" . (int)$id . "'");
			
			$this->cache->delete('information');
		}
		//Удалить категорию
		public function deleteCatInformation($id) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_stati` WHERE id = '" . (int)$id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'categorystat_id=" . (int)$id . "'");
			
			$this->cache->delete('information');
		}
		
		// Информация о родительских элементах
		public function getParentInfo($id) {
			$sql= "SELECT * FROM `" . DB_PREFIX . "category_stati` WHERE id = '" . (int)$id . "'";
			$query = $this->db->query($sql);
			
		return $query->row;}
		
		// Список родительских элементов
		public function getParents($id) {
		    
		    $parent = $this->getParentInfo($id);
			if (!empty($parent)){
				$tree = [$parent['id'] => $parent['name']];
				
				$i = 0; do {$i++;
					
					$newcat = $this->getParentInfo($parent['parent']);
					
					if (!empty($newcat)) {
						$parent['parent'] = $newcat['parent'];
					$tree[ $newcat['id'] ]  = $newcat['name'];}
				} while (!empty($newcat) and $i < 10 );
				
				
				return $tree;
			}
		}
		
		// URL статей	
		public function getInformationSeoUrls($id) {
			$seo_url_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'catstat_id=" . (int)$id . "'");
			
			foreach ($query->rows as $result) {
				$seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
			}
			
			return $seo_url_data;
		}	
		
		// URL категорий
		public function getCatInformationSeoUrls($id) {
			$seo_url_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'categorystat_id=" . (int)$id . "'");
			
			foreach ($query->rows as $result) {
				$seo_url_data[$result['language_id']] = $result['keyword'];
			}
			
			return $seo_url_data;
		}	
		//импорт статей csv
		public function addCSVInformation($data) {
			
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "stati SET  language = '1', title = '" . $this->db->escape($data['title']) . "', category = '" . $this->db->escape($data['category']) . "', text = '" . $this->db->escape($data['text']) . "', metadesc = '" . $this->db->escape($data['metadesc']) . "', metatitle = '" . $this->db->escape($data['metatitle']) . "', metakey = '" . $this->db->escape($data['metakey']) . "', intro='".$this->db->escape($data['intro'])."'");
			
			
			$id = $this->db->getLastId();
			
			if (isset($data['url'])) {
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '1', query = 'catstat_id=" . (int)$id . "', keyword = '" . $this->db->escape($data['url']) . "'");
				
				
				
			}
		}
		// импорт категорий csv
		public function addCSVCatInformation($data) {
			
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_stati SET  language = '1',id = '" . $this->db->escape($data['id']) . "', name = '" . $this->db->escape($data['name']) . "', parent = '" . $this->db->escape($data['parent']) . "',  metadesc = '" . $this->db->escape($data['metadesc']) . "',  metakey = '" . $this->db->escape($data['metakey']) ."'");
			
			
			$id = $this->db->getLastId();
			
			if (isset($data['url'])) {
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '1', query = 'categorystat_id=" . (int)$id . "', keyword = '" . $this->db->escape($data['url']) . "'");
				
				
				
			}
		}
	}
	
	
	
    
	
?>