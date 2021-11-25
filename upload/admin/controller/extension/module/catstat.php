<?php
	class ControllerExtensionModuleCatstat extends Controller {
		private $error = array();
		
		public function Install() { 
			
			$sql = "CREATE TABLE `oc_category_stati` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `query` varchar(255) NOT NULL,
  `language` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `metadesc` varchar(255) NOT NULL,
  `metakey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$this->db->query($sql);

 $sql = "ALTER TABLE `oc_category_stati`
  ADD PRIMARY KEY (`id`)";

 $this->db->query($sql);
 
 
 $sql = "ALTER TABLE `oc_category_stati`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";

 $this->db->query($sql);



  
           $sql = "CREATE TABLE `oc_stati` (
  `stati_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `language` int(11) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metatitle` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `intro` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$this->db->query($sql);

$sql = "ALTER TABLE `oc_stati`
  ADD PRIMARY KEY (`stati_id`,`language`) USING BTREE";
  $this->db->query($sql);
  
$sql = "  ALTER TABLE `oc_stati`
  MODIFY `stati_id` int(11) NOT NULL AUTO_INCREMENT";
$this->db->query($sql);
  
           } 
		
		
		public function Setings () {
			$this->load->language('extension/module/catstat');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('extension/module/catstat');
			// Сохранение настроек модуля, когда пользователь нажал "Записать"
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				// Вызываем метод "модели" для сохранения настроек
				$this->model_extension_module_catstat->SaveSettings();
				// Выходим из настроек с выводом сообщения
				$this->session->data['success'] = 'Настройки сохранены';
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
			
			// Загружаем настройки через метод "модели"
			$data = array();
			$data['module_catstat_status'] = $this->model_extension_module_catstat->LoadSettings();
			// Загружаем языковой файл
			$data += $this->load->language('extension/module/catstat');
			// Загружаем "хлебные крошки"
			$data += $this->GetBreadCrumbs();
			
			// Кнопки действий
			$data['action'] = $this->url->link('extension/module/catstat/setings', 'user_token=' . $this->session->data['user_token'], true);
			$data['actioncsv'] = $this->url->link('extension/module/catstat/importcsv', 'user_token=' . $this->session->data['user_token'], true);
			$data['actioncsvcat'] = $this->url->link('extension/module/catstat/importcsvcat', 'user_token=' . $this->session->data['user_token'], true);
			$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
			// Загрузка шаблонов для шапки, колонки слева и футера
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			// Выводим в браузер шаблон
			$this->response->setOutput($this->load->view('extension/module/catstat_set', $data));
			
		}
		
		public function ImportCSV () {
		
		$this->load->model('extension/module/catstat');
			$data = array();
			if ((($this->request->server['REQUEST_METHOD'] == 'POST')))
			
			{
				$uploaddir=DIR_STORAGE.'upload/';
				$uploadfile = $uploaddir. basename($_FILES['csv']['name']);
				move_uploaded_file($_FILES['csv']['tmp_name'],$uploadfile);
				$data= array();
				$header = NULL;
				$rov=0;
				
				if (($handle = fopen($uploadfile, "r")) !== FALSE) {
					while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
						if(!$header){
						$header = $row;}
						else{
							$input = array_combine($header, $row);
							
							$data['stati_id'] = $input[$this->request->post['stati_id']];
							$data['title'] = $input[$this->request->post['title']];
							$data['text'] = $input[$this->request->post['text']];
							$data['intro'] = $input[$this->request->post['intro']];
							$data['category'] = $input[$this->request->post['category']];
							$data['url'] = $input[$this->request->post['url']];
							$data['metatitle'] = $input[$this->request->post['metatitle']];
							$data['metadesc'] = $input[$this->request->post['metadesc']];
							$data['metakey'] = $input[$this->request->post['metakey']];
							$this->model_extension_module_catstat->addCSVInformation($data);
							$rov++;
							$this->session->data['success']= 'Импортированно'.$rov.' статей.';
						}
						
						
					}
					
					fclose($handle);
				}
				
				
			}
			
			$this->load->model('extension/module/catstat');
			
			$data['module_catstat_status'] = $this->model_extension_module_catstat->LoadSettings();
			// Загружаем языковой файл
			$data += $this->load->language('extension/module/catstat');
			// Загружаем "хлебные крошки"
			$data += $this->GetBreadCrumbs();
		
		// Кнопки действий
		$data['action'] = $this->url->link('extension/module/catstat/setings', 'user_token=' . $this->session->data['user_token'], true);
		$data['actioncsv'] = $this->url->link('extension/module/catstat/importcsv', 'user_token=' . $this->session->data['user_token'], true);
		$data['actioncsvcat'] = $this->url->link('extension/module/catstat/importcsvcat', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		// Загрузка шаблонов для шапки, колонки слева и футера
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		
		$this->response->setOutput($this->load->view('extension/module/catstat_set',$data));
		
		
		}
		
		
		public function ImportCSVcat () {
		
		$this->load->model('extension/module/catstat');
			$data = array();
			if ((($this->request->server['REQUEST_METHOD'] == 'POST')))
			
			{
				$uploaddir=DIR_STORAGE.'upload/';
				$uploadfile = $uploaddir. basename($_FILES['csv']['name']);
				move_uploaded_file($_FILES['csv']['tmp_name'],$uploadfile);
				$data= array();
				$header = NULL;
				$rov=0;
				
				if (($handle = fopen($uploadfile, "r")) !== FALSE) {
					while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
						if(!$header){
						$header = $row;}
						else{
							$input = array_combine($header, $row);
							
							$data['id'] = $input[$this->request->post['id']];
							$data['name'] = $input[$this->request->post['title']];
							
							$data['parent'] = $input[$this->request->post['parent']];
							$data['url'] = $input[$this->request->post['url']];
							
							$data['metadesc'] = $input[$this->request->post['metadesc']];
							$data['metakey'] = $input[$this->request->post['metakey']];
							$this->model_extension_module_catstat->addCSVCatInformation($data);
							$rov++;
							$this->session->data['success']= 'Импортированно '.$rov.' Категорий.';
							
						}
						
						
					}
					
					fclose($handle);
				}
				
				
			}
			
			$this->load->model('extension/module/catstat');
			
			$data['module_catstat_status'] = $this->model_extension_module_catstat->LoadSettings();
			// Загружаем языковой файл
			$data += $this->load->language('extension/module/catstat');
			// Загружаем "хлебные крошки"
			$data += $this->GetBreadCrumbs();
		
		// Кнопки действий
		$data['action'] = $this->url->link('extension/module/catstat/setings', 'user_token=' . $this->session->data['user_token'], true);
		$data['actioncsv'] = $this->url->link('extension/module/catstat/importcsv', 'user_token=' . $this->session->data['user_token'], true);
		$data['actioncsvcat'] = $this->url->link('extension/module/catstat/importcsvcat', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		// Загрузка шаблонов для шапки, колонки слева и футера
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		
		$this->response->setOutput($this->load->view('extension/module/catstat_set',$data));
		
		
		}
		
		
		
		
		
		
		// Хлебные крошки
		private function GetBreadCrumbs() {
		$data = array(); $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_extension'),
		'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('heading_title'),
		'href' => $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'], true)
		);
		return $data;
		}
		
		public function index() {
		$this->load->language('extension/module/catstat');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/module/catstat');
		
		$this->getList();
		}
		public function catlist() {
		$this->load->language('extension/module/catstat');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/module/catstat');
		
		$this->getCatList();
		
		
		}
		// Добавление категории
		public function addcat () {
		$this->load->language('extension/module/catstat');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/catstat');
		if ($this->request->server['REQUEST_METHOD']=='POST' && $this->validateCatForm())
		{$this->model_extension_module_catstat->addCatInformation($this->request->post);
		
		$this->session->data['success']= $this->language->get('text_success');
		
		$url='';
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->response->redirect($this->url->link('extension/module/catstat/catlist', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
		$this->getForm2();
		
		}
		
		
		//....................................... Добавление статьи
		public function additem() {
		
		$this->load->language('extension/module/catstat');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/catstat');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
		
		
		$this->model_extension_module_catstat->addInformation($this->request->post);
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->response->redirect($this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
		$this->getForm();
		
		}
		//............................................удаление статьи
		public function delete() {
		$this->load->language('extension/module/catstat');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/catstat');
		
		if (isset($this->request->post['selected']) ) {
		foreach ($this->request->post['selected'] as $id) {
		$this->model_extension_module_catstat->deleteInformation($id);
		}
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->response->redirect($this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
		$this->getList();
		}
		//................................. Удаление категории
		public function deletecat() {
		$this->load->language('extension/module/catstat');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/catstat');
		
		if (isset($this->request->post['selected']) ) {
		foreach ($this->request->post['selected'] as $id) {
		$this->model_extension_module_catstat->deleteCatInformation($id);
		}
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->response->redirect($this->url->link('extension/module/catstat/catlist', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
		$this->getCatList();
		}
		
		///........................................................... Редактирование статьи 
		public function edit() {
		$this->load->language('extension/module/catstat');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/catstat');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST'  ) && $this->validateForm()  ) {
		$this->model_extension_module_catstat->editInformation($this->request->get['id'], $this->request->post);
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->response->redirect($this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
		$this->getForm();
		}
		
		////.............................................. Редактирование категории
		public function catedit() {
		$this->load->language('extension/module/catstat');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/module/catstat');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCatForm() ) {
		$this->model_extension_module_catstat->editCatInformation($this->request->get['id'], $this->request->post);
		
		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->response->redirect($this->url->link('extension/module/catstat/catlist', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
		$this->getForm2();
		}
		
		
		protected function getList() {
		if (isset($this->request->get['sort'])) {
		$sort = $this->request->get['sort'];
		} else {
		$sort = 'id';
		}
		
		if (isset($this->request->get['order'])) {
		$order = $this->request->get['order'];
		} else {
		$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
		} else {
		$page = 1;
		}
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('heading_title'),
		'href' => $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		$data['catlist'] = $this->url->link('extension/module/catstat/catlist', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['add'] = $this->url->link('extension/module/catstat/additem', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/module/catstat/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['setings'] = $this->url->link('extension/module/catstat/setings', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['informations'] = array();
		
		$filter_data = array(
		'sort'  => $sort,
		'order' => $order,
		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit' => $this->config->get('config_limit_admin')
		);
		
		$information_total = $this->model_extension_module_catstat->getTotalInformations();
		
		$results = $this->model_extension_module_catstat->getInformations($filter_data);
		
		foreach ($results as $result) {
		$data['informations'][] = array(
		'id' => $result['stati_id'],
		'title'          => $result['title'],
		'category'     => $result['category'],
		'edit'           => $this->url->link('extension/module/catstat/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['stati_id'] . $url, true)
		);
		}
		
		if (isset($this->error['warning'])) {
		$data['error_warning'] = $this->error['warning'];
		} else {
		$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
		$data['success'] = $this->session->data['success'];
		
		unset($this->session->data['success']);
		} else {
		$data['success'] = '';
		}
		
		if (isset($this->request->post['selected'])) {
		$data['selected'] = (array)$this->request->post['selected'];
		} else {
		$data['selected'] = array();
		}
		
		$url = '';
		
		if ($order == 'ASC') {
		$url .= '&order=DESC';
		} else {
		$url .= '&order=ASC';
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_title'] = $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . '&sort=category' . $url, true);
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($information_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($information_total - $this->config->get('config_limit_admin'))) ? $information_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $information_total, ceil($information_total / $this->config->get('config_limit_admin')));
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/catstat', $data));
		}
		
		
		protected function getCatList() {
		if (isset($this->request->get['sort'])) {
		$sort = $this->request->get['sort'];
		} else {
		$sort = 'id.title';
		}
		
		if (isset($this->request->get['order'])) {
		$order = $this->request->get['order'];
		} else {
		$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
		} else {
		$page = 1;
		}
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('heading_title'),
		'href' => $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_cat'),
		'href' => $this->url->link('extension/module/catstat/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		
		$data['add'] = $this->url->link('extension/module/catstat/addcat', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/module/catstat/deletecat', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$data['informations'] = array();
		
		$filter_data = array(
		'sort'  => $sort,
		'order' => $order,
		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit' => $this->config->get('config_limit_admin')
		);
		
		$information_total = $this->model_extension_module_catstat->getTotalCatInformations();
		$data['cancel'] = $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$results = $this->model_extension_module_catstat->getCatInformations($filter_data);
		
		foreach ($results as $result) {
		$data['informations'][] = array(
		'id' => $result['id'],
		'title'          => $result['name'],
		'category'     => $result['query'],
		'parent'     => $result['parent'],
		'edit'           => $this->url->link('extension/module/catstat/catedit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'] . $url, true)
		);
		}
		
		if (isset($this->error['warning'])) {
		$data['error_warning'] = $this->error['warning'];
		} else {
		$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
		$data['success'] = $this->session->data['success'];
		
		unset($this->session->data['success']);
		} else {
		$data['success'] = '';
		}
		
		if (isset($this->request->post['selected'])) {
		$data['selected'] = (array)$this->request->post['selected'];
		} else {
		$data['selected'] = array();
		}
		
		$url = '';
		
		if ($order == 'ASC') {
		$url .= '&order=DESC';
		} else {
		$url .= '&order=ASC';
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_title'] = $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'] . '&sort=id.title' . $url, true);
		$data['sort_sort_order'] = $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'] . '&sort=i.sort_order' . $url, true);
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/catstat/catlist', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($information_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($information_total - $this->config->get('config_limit_admin'))) ? $information_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $information_total, ceil($information_total / $this->config->get('config_limit_admin')));
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/catstat_cat', $data));
		}
		
		
		protected function getForm2() {
		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		if (isset($this->error['warning'])) {
		$data['error_warning'] = $this->error['warning'];
		} else {
		$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
		$data['error_name'] = $this->error['name'];
		} else {
		$data['error_name'] = array();
		}
		
		
		
		if (isset($this->error['parent'])) {
		$data['error_parent'] = $this->error['parent'];
		} else {
		$data['error_parent'] = array();
		}
		
		if (isset($this->error['keyword'])) {
		$data['error_keyword'] = $this->error['keyword'];
		} else {
		$data['error_keyword'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('heading_title'),
		'href' => $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		
		if (!isset($this->request->get['id'])) {
		$data['action'] = $this->url->link('extension/module/catstat/addcat', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
		$data['action'] = $this->url->link('extension/module/catstat/catedit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'] . $url, true);
		}
		
		$data['cancel'] = $this->url->link('extension/module/catstat/catlist', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
		$information_info = $this->model_extension_module_catstat->getCatInformation($this->request->get['id']);
		}
		
		
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['cat'])) {
		$data['cat'] = $this->request->post['cat'];
		} elseif (isset($this->request->get['id'])) {
		$data['cat'] = $this->model_extension_module_catstat->getCatInformation($this->request->get['id']);
		} else {
		$data['cat'] = array();
		}
		
		if (isset($data['cat'][$this->config->get('config_language_id')]['parent'])) {
		$parent = $data['cat'][$this->config->get('config_language_id')]['parent'];
		$data['cat']['path'] = array();
		$i = 0; do {$i++; $newcat = $this->model_extension_module_catstat->getParentInfo($parent);
		if (!empty($newcat)) {
		$parent = $newcat['parent'];
		array_push($data['cat']['path'], $newcat['name']);}
		} while (!empty($newcat) and $i < 10 );
		$data['cat']['path'] = array_reverse($data['cat']['path']);
		$data['cat']['path'] = implode (' > ', $data['cat']['path']);
		}
		//var_dump($data['cat']);
		
		
		
		$this->load->model('setting/store');
		
		$data['stores'] = array();
		
		$data['stores'][] = array(
		'store_id' => 0,
		'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();
		
		foreach ($stores as $store) {
		$data['stores'][] = array(
		'store_id' => $store['store_id'],
		'name'     => $store['name']
		);
		}
		
		if (isset($this->request->post['information_store'])) {
		$data['information_store'] = $this->request->post['information_store'];
		} elseif (isset($this->request->get['information_id'])) {
		$data['information_store'] = $this->model_catalog_information->getInformationStores($this->request->get['information_id']);
		} else {
		$data['information_store'] = array(0);
		}
		
		
		
		// if (isset($this->request->post['status'])) {
		// $data['status'] = $this->request->post['status'];
		// } elseif (!empty($information_info)) {
		// $data['status'] = $information_info['status'];
		// } else {
		// $data['status'] = true;
		// }
		
		// if (isset($this->request->post['sort_order'])) {
		// $data['sort_order'] = $this->request->post['sort_order'];
		// } elseif (!empty($information_info)) {
		// $data['sort_order'] = $information_info['sort_order'];
		// } else {
		// $data['sort_order'] = '';
		// }
		
		if (isset($this->request->post['url'])) {
		$data['url'] = $this->request->post['url'];
		} elseif (isset($this->request->get['id'])) {
		$data['url'] = $this->model_extension_module_catstat->getCatInformationSeoUrls($this->request->get['id']);
		} else {
		$data['url'] = array();
		}
		
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/catstat_formcat', $data));
		}
		
		
		protected function getForm() {
		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		if (isset($this->error['warning'])) {
		$data['error_warning'] = $this->error['warning'];
		} else {
		$data['error_warning'] = '';
		}
		
		if (isset($this->error['title'])) {
		$data['error_title'] = $this->error['title'];
		} else {
		$data['error_title'] = array();
		}
		
		if (isset($this->error['text'])) {
		$data['error_text'] = $this->error['text'];
		} else {
		$data['error_text'] = array();
		}
		
		if (isset($this->error['category'])) {
		$data['error_cat'] = $this->error['category'];
		} else {
		$data['error_cat'] = array();
		}
		
		if (isset($this->error['keyword'])) {
		$data['error_keyword'] = $this->error['keyword'];
		} else {
		$data['error_keyword'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
		$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
		$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('heading_title'),
		'href' => $this->url->link('catalog/information', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		
		if (!isset($this->request->get['id'])) {
		$data['action'] = $this->url->link('extension/module/catstat/additem', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
		$data['action'] = $this->url->link('extension/module/catstat/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'] . $url, true);
		}
		
		$data['cancel'] = $this->url->link('extension/module/catstat', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
		$information_info = $this->model_extension_module_catstat->getInformation($this->request->get['id']);
		}
		
		
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['stati'])) {
		$data['stati'] = $this->request->post['stati'];
		} elseif (isset($this->request->get['id'])) {
		$data['stati'] = $this->model_extension_module_catstat->getInformation($this->request->get['id']);
		} else {
		$data['stati'] = array();
		}
		
		
		
		
		if (isset($data['stati'][$this->config->get('config_language_id')]['category'])) {
		$tree = $this->model_extension_module_catstat->getParents($data['stati'][$this->config->get('config_language_id')]['category']);
		
		if (!empty($tree)){
		$tree = array_reverse($tree);
		$tree = implode (' > ', $tree);
		
		$data['stati']['path'] = $tree;
		}
		}
		
		
		
		
		
		$this->load->model('setting/store');
		
		$data['stores'] = array();
		
		$data['stores'][] = array(
		'store_id' => 0,
		'name'     => $this->language->get('text_default')
		);
		
		
		
		$stores = $this->model_setting_store->getStores();
		
		foreach ($stores as $store) {
		$data['stores'][] = array(
		'store_id' => $store['store_id'],
		'name'     => $store['name']
		);
		}
		
		if (isset($this->request->post['information_store'])) {
		$data['information_store'] = $this->request->post['information_store'];
		} elseif (isset($this->request->get['information_id'])) {
		$data['information_store'] = $this->model_catalog_information->getInformationStores($this->request->get['information_id']);
		} else {
		$data['information_store'] = array(0);
		}
		
		
		
		// if (isset($this->request->post['status'])) {
		// $data['status'] = $this->request->post['status'];
		// } elseif (!empty($information_info)) {
		// $data['status'] = $information_info['status'];
		// } else {
		// $data['status'] = true;
		// }
		
		// if (isset($this->request->post['sort_order'])) {
		// $data['sort_order'] = $this->request->post['sort_order'];
		// } elseif (!empty($information_info)) {
		// $data['sort_order'] = $information_info['sort_order'];
		// } else {
		// $data['sort_order'] = '';
		// }
		
		if (isset($this->request->post['url'])) {
		$data['url'] = $this->request->post['url'];
		} elseif (isset($this->request->get['id'])) {
		$data['url'] = $this->model_extension_module_catstat->getInformationSeoUrls($this->request->get['id']);
		} else {
		$data['url'] = array();
		}
		
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/catstat_form', $data));
		}
		
		// Автозаполнение категорий
		public function autocomplete() {
		$json = array();
		
        if (isset($this->request->get['filter_name'])) {
		$this->load->model('extension/module/catstat');
		
		$filter_data = array(
		'filter_name' => $this->request->get['filter_name'],
		'sort'        => 'name',
		'order'       => 'ASC',
		'start'       => 0,
		'limit'       => 5
		);
		
		$results = $this->model_extension_module_catstat->getCatInformations($filter_data);
		
		foreach ($results as $result) {
		$json[] = array(
		'id' => $result['id'],
		'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
		);
		}
		}
		
		$sort_order = array();
		
		foreach ($json as $key => $value) {
		$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		}
		// Валидация Формы статьи
		protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/catstat')) {
		$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		foreach ($this->request->post['stati'] as $language_id => $value) {
		if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 64)) {
		$this->error['title'][$language_id] = $this->language->get('error_title');
		}
		
		
		if (utf8_strlen($value['text']) < 3) {
		$this->error['text'][$language_id] = $this->language->get('error_description');
		}
		
		if ((empty($value['category_name'])) ) {
		$this->error['category'][$language_id] = $this->language->get('error_cat');
		}
		}
		
		
		
		if ($this->request->post['url']) {
		$this->load->model('design/seo_url');
		
		foreach ($this->request->post['url'] as $store_id => $language) {
		foreach ($language as $language_id => $keyword) {
		if (!empty($keyword)) {
		if (count(array_keys($language, $keyword)) > 1) {
		$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
		}						
		
		$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
		
		foreach ($seo_urls as $seo_url) {
		if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['id']) || ($seo_url['query'] != 'catstat_id=' . $this->request->get['id']))) {
		$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
		}
		}
		}
		}
		}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
		$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
		}
		
		
		// Валидация Формы Категорий
		protected function validateCatForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/catstat')) {
		$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		foreach ($this->request->post['cat'] as $language_id => $value) {
		if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
		$this->error['name'][$language_id] = $this->language->get('error_title');
		}
		
		
		// if ((empty($value['parent'])) ) {
		// $this->error['parent'][$language_id] = $this->language->get('error_cat');
		// }
		}
		
		
		
		if ($this->request->post['url']) {
		$this->load->model('design/seo_url');
		
		
		foreach ($this->request->post['url'] as $language_id => $keyword) {
		if (!empty($keyword)) {
		if (count(array_keys($this->request->post['url'], $keyword)) > 1) {
		$this->error['keyword'][$language_id] = $this->language->get('error_unique');
		}						
		
		$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
		
		foreach ($seo_urls as $seo_url) {
		if (($seo_url['store_id'] == 0) && (!isset($this->request->get['id']) || ($seo_url['query'] != 'categorystat_id=' . $this->request->get['id']))) {
		$this->error['keyword'][$language_id] = $this->language->get('error_keyword');
		}
		}
		}
		}
		
		}
		
		if ($this->error && !isset($this->error['warning'])) {
		$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
		}
		
		
		
		}
		?>