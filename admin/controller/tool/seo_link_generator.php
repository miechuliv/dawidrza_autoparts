<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24.09.14
 * Time: 09:23
 */

class ControllerToolSeoLinkGenerator extends Controller{

    public function index()
    {
        $this->load->model('tool/seo_link_generator');

        $this->model_tool_seo_link_generator->setLanguage('de');
        $this->model_tool_seo_link_generator->setSeoLinkTemplate('{name}');
        $this->model_tool_seo_link_generator->generateProductsSeoLinks();

        $this->model_tool_seo_link_generator->generateCategorySeoLinks();
    }

} 