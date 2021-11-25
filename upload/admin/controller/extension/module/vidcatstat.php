<?php
	class ControllerExtensionModuleVidCatstat extends Controller {
		private $error = array();
		
		
		public function index () {
			$this->load->language('extension/module/vidcatstat');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('extension/module/vidcatstat');
			// Сохранение настроек модуля, когда пользователь нажал "Записать"
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				// Вызываем метод "модели" для сохранения настроек
				$this->model_extension_module_vidcatstat->SaveSettings();
				// Выходим из настроек с выводом сообщения
				$this->session->data['success'] = 'Настройки сохранены';
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
			
			// Загружаем настройки через метод "модели"
			$data = array();
			$data['module_vidcatstat_status'] = $this->model_extension_module_vidcatstat->LoadSettings();
			
			
			
			// Кнопки действий
			
			$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
			// Загрузка шаблонов для шапки, колонки слева и футера
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			// Выводим в браузер шаблон
			$this->response->setOutput($this->load->view('extension/module/vidcatstat', $data));
			
		}
		
		
		
		
		}
		?>