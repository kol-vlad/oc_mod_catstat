<?php
class ModelExtensionModuleVidCatstat extends Model {

  // Запись настроек в базу данных
  public function SaveSettings() {
    $this->load->model('setting/setting');
    $this->model_setting_setting->editSetting('module_vidcatstat', $this->request->post);
  }

  // Загрузка настроек из базы данных
  public function LoadSettings() {
    return $this->config->get('module_vidcatstat_status');
  }

}

?>