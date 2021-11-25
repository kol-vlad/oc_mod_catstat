<?php
class ControllerExtensionModuleVidcatstat extends Controller {
	public function index() {
		$this->load->language('extension/module/catstat');

		
		$this->load->model('extension/module/vidcatstat');

		$data['catst'] = array();
		
		
		
		foreach ($this->model_extension_module_vidcatstat->getCategoryes() as $result) {
		 
		   $trees = $this->model_extension_module_vidcatstat->getParents($result['parent']);
			$tree='';
            if (!empty($trees)) {
			foreach ($trees as $key => $tre) {$tree.=$key; if( next( $trees ) ) {$tree.='_'; }}}
			$url='';
			$tree != NULL ? $url = '&pathc='.$tree.'_' .$result['id'] : $url = '&pathc='.$result['id'];
			
			$data['catst'][] = array(
				'title' => $result['name'],
				'href'  => $this->url->link('extension/module/catstat', $url)
			);
		}

		
		

		$data['contact'] = $this->url->link('information/contact');
		$data['sitemap'] = $this->url->link('information/sitemap');

		return $this->load->view('extension/module/vidcatstat', $data);
	}
}