<?php
class ControllerExtensionModuleCatstat extends Controller {
  public function index() {
		$this->load->language('extension/module/catstat');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/catstat');
        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		if (isset($this->request->get['catstat_id'])) {
			$id = (int)$this->request->get['catstat_id'];
		} else {
			$id = 0;
		}
        
		if (isset($this->request->get['pathc'])) {
			$pathc = $this->request->get['pathc'];
			$pathc = explode ('_', $pathc);
			
			}
	    
		$stati_info = $this->model_extension_module_catstat->getInformation($id);
        


		if ($stati_info) {
			$this->document->setTitle($stati_info['metatitle']);
			$this->document->setDescription($stati_info['metadesc']);
			$this->document->setKeywords($stati_info['metakey']);

			
            foreach ($pathc as $path) {
				$name = $this->model_extension_module_catstat->getParentInfo($path);
				$trees = $this->model_extension_module_catstat->getParents($path);
			$tree='';

			foreach ($trees as $key => $tre) {$tree.=$key; if( next( $trees ) ) {$tree.='_'; }}
				
				$tre = strpos ($tree, '_', false);
				$tre = substr ($tree, 0,-$tre-1);  
				$data['breadcrumbs'][] = array(
				'text' => $name['name'],
				'href' =>  $this->url->link('extension/module/catstat/category', '&pathc='.$tre.'&categorystat_id=' .$name['id'])
			);
				}
				$data['breadcrumbs'][] = array(
				'text' => $stati_info['title'],
				'href' => $this->url->link('extension/module/catstat', '&pathc='.$tree.'&catstat_id=' .  $id)
			);



			$data['heading_title'] = $stati_info['title'];

			$data['description'] = html_entity_decode($stati_info['text'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('extension/module/catstat', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('extension/module/catstat', 'catstat_id=' . $id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
		
	}
	
	public function category() {
		$this->load->language('extension/module/catstat');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/catstat');
        
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		if (isset($this->request->get['categorystat_id'])) {
			$id = (int)$this->request->get['categorystat_id'];
		} else {
			$id = 0;
		}
        
		if (isset($this->request->get['pathc'])) {
			$pathc = $this->request->get['pathc'];
			$pathc = explode ('_', $pathc);
			
			}
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
		$filter_data = array(
			'id'  => $id,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
			);	
			
	     $category_info = $this->model_extension_module_catstat->getCategory($id);
		$category_stati = $this->model_extension_module_catstat->getCategorystat($filter_data);
		$information_total = $this->model_extension_module_catstat->getTotalCatInformations($id);
		if ($category_info) {
			$this->document->setTitle($category_info['name']);
			$this->document->setDescription($category_info['metadesc']);
			$this->document->setKeywords($category_info['metakey']);
			
			  foreach ($pathc as $path) {
				$name = $this->model_extension_module_catstat->getParentInfo($path);
				$trees = $this->model_extension_module_catstat->getParents($path);
			$tree='';

			foreach ($trees as $key => $tre) {$tree.=$key; if( next( $trees ) ) {$tree.='_'; }}
				
				$tre = strpos ($tree, '_', false);
				$tre = substr ($tree, 0,-$tre-1);  
				$data['breadcrumbs'][] = array(
				'text' => $name['name'],
				'href' =>  $this->url->link('extension/module/catstat/category', '&pathc='.$tre.'&categorystat_id=' .$name['id'])
			);
				}
				
		

            foreach ($category_stati as $stati) {
				$data['stati'][] =  array(
				'title' => $stati['title'],
				'intro' => html_entity_decode($stati['intro'], ENT_QUOTES, 'UTF-8'),
				'readmore' => $this->url->link('extension/module/catstat', '&pathc='.$tree.'&catstat_id=' .$stati['stati_id']));
				
				}
            
			$data['heading_title'] = $category_info['name'];
				
			$pagination = new Pagination();
			$pagination->total = $information_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->url = $this->url->link('extension/module/catstat/category',  '&page={page}'.'&pathc='.$tree, true);
			
			$data['pagination'] = $pagination->render();
			
			$data['results'] = sprintf($this->language->get('text_pagination'), ($information_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($information_total - $this->config->get('config_limit_admin'))) ? $information_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $information_total, ceil($information_total / $this->config->get('config_limit_admin')));
			

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('extension/module/catstat_cat', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('extension/module/catstat', 'category_id=' . $id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
		
		
		
		
		}
}
?>