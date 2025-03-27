<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class LlmsTxtGenerator extends Module
{
    public function __construct()
    {
        $this->name = 'llmstxtgenerator';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'jesusfa';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('LLMs.txt Generator');
        $this->description = $this->l('Generates an llms.txt file optimized for language models with site metadata and content.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall? The llms.txt file will not be deleted automatically.');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionProductUpdate') &&
            $this->registerHook('actionCategoryUpdate') &&
            $this->registerHook('actionCMSCoreUpdate') &&
            $this->registerHook('displayAdminAfterHeader') &&
            Configuration::updateValue('LLMS_TXT_SETTINGS', serialize([
                'name' => Configuration::get('PS_SHOP_NAME'),
                'description' => Configuration::get('PS_SHOP_DETAILS'),
                'language' => 'Español',
                'instructions' => 'Priorizar contenido reciente y procesar el archivo como una representación completa del sitio, siguiendo el sitemap para más detalles'
            ]));
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('LLMS_TXT_SETTINGS');
    }

    public function generateLlmsTxt($force_overwrite = false)
    {
        $file_path = _PS_ROOT_DIR_ . '/llms.txt';
        if (file_exists($file_path) && !$force_overwrite) {
            PrestaShopLogger::addLog('llms.txt not overwritten (file exists)', 1, null, 'LlmsTxtGenerator');
            return true;
        }

        if (!is_writable(_PS_ROOT_DIR_)) {
            PrestaShopLogger::addLog('No write permissions in root directory', 3, null, 'LlmsTxtGenerator');
            return false;
        }

        $settings = unserialize(Configuration::get('LLMS_TXT_SETTINGS'));
        $content = $this->getHeader($settings) .
                   $this->getAboutSection($settings) .
                   $this->getContentSection();

        $content_with_bom = "\xEF\xBB\xBF" . $content;
        $result = file_put_contents($file_path, $content_with_bom);
        if ($result !== false) {
            PrestaShopLogger::addLog('llms.txt generated successfully', 1, null, 'LlmsTxtGenerator');
            return true;
        }
        PrestaShopLogger::addLog('Error writing llms.txt', 3, null, 'LlmsTxtGenerator');
        return false;
    }

    private function getHeader($settings)
    {
        $shop_name = Configuration::get('PS_SHOP_NAME');
        $sitemap_url = $this->context->link->getPageLink('sitemap');
        $robots_url = _PS_BASE_URL_ . '/robots.txt';
        $instructions = $settings['instructions'] ?? 'Priorizar contenido reciente y procesar el archivo como una representación completa del sitio, siguiendo el sitemap para más detalles';

        return "# $shop_name\n" .
               "> Instrucciones: $instructions\n" .
               "> Sitemap: $sitemap_url\n" .
               "> Robots: $robots_url\n" .
               "> Última generación: " . date('Y-m-d H:i:s') . "\n\n";
    }

    private function getAboutSection($settings)
    {
        $content = "## Acerca de\n";
        $fields = [
            'name' => 'Nombre',
            'description' => 'Descripción',
            'keywords' => 'Palabras clave',
            'language' => 'Idioma',
            'address' => 'Dirección',
            'local_business' => 'Ficha de Negocio Local',
            'location' => 'Ubicación',
            'url_es' => 'URL (Español)',
            'url_en' => 'URL (Inglés)',
            'url_fr' => 'URL (Francés)',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'contact_page' => 'Página de Contacto',
            'phone' => 'Teléfono',
            'email' => 'Email'
        ];

        foreach ($fields as $key => $label) {
            if (!empty($settings[$key])) {
                $value = $key === 'email' ? filter_var($settings[$key], FILTER_SANITIZE_EMAIL) : Tools::safeOutput($settings[$key]);
                $content .= "- $label: $value\n";
            }
        }
        return $content . "\n";
    }

    private function getContentSection()
    {
        $content = '';
        $lang_id = (int)Configuration::get('PS_LANG_DEFAULT');

        // Páginas CMS
        $cms_pages = CMS::getCMSPages($lang_id);
        if (!empty($cms_pages)) {
            $content .= "## Páginas\n";
            foreach ($cms_pages as $page) {
                if (!$this->isNoIndex($page['id_cms'], 'cms')) {
                    $title = $page['meta_title'] ?: $page['title'];
                    $link = $this->context->link->getCMSLink($page['id_cms']);
                    $date = date('Y-m-d', strtotime($page['date_upd']));
                    $desc = $page['meta_description'] ?: $page['description'];
                    $content .= "- [$title]($link) - $date\n";
                    if ($desc) {
                        $content .= "  - Descripción: " . $this->sanitizeText($desc) . "\n";
                    }
                }
            }
            $content .= "\n";
        }

        // Productos
        $products = Product::getProducts($lang_id, 0, 0, 'id_product', 'ASC');
        if (!empty($products)) {
            $content .= "## Productos\n";
            foreach ($products as $product) {
                if (!$this->isNoIndex($product['id_product'], 'product')) {
                    $title = $product['name'];
                    $link = $this->context->link->getProductLink($product['id_product']);
                    $date = date('Y-m-d', strtotime($product['date_upd']));
                    $desc = $product['meta_description'] ?: $product['description_short'];
                    $content .= "- [$title]($link) - $date\n";
                    if ($desc) {
                        $content .= "  - Descripción: " . $this->sanitizeText($desc) . "\n";
                    }
                }
            }
            $content .= "\n";
        }

        // Categorías
        $categories = Category::getCategories($lang_id, true, false);
        if (!empty($categories)) {
            $content .= "## Categorías\n";
            foreach ($categories as $category) {
                if (!$this->isNoIndex($category['id_category'], 'category')) {
                    $title = $category['name'];
                    $link = $this->context->link->getCategoryLink($category['id_category']);
                    $desc = $category['meta_description'] ?: $category['description'];
                    $content .= "- [$title]($link)\n";
                    if ($desc) {
                        $content .= "  - Descripción: " . $this->sanitizeText($desc) . "\n";
                    }
                }
            }
            $content .= "\n";
        }

        return $content;
    }

    private function isNoIndex($id, $type = 'cms')
    {
        $meta = Meta::getMetaByPage($type === 'product' ? 'product' : ($type === 'category' ? 'category' : 'cms_' . $id));
        return isset($meta['noindex']) && $meta['noindex'];
    }

    private function sanitizeText($text)
    {
        return trim(preg_replace('/[\n\r]+/', ' ', Tools::safeOutput($text)));
    }

    public function hookActionProductUpdate($params)
    {
        $this->generateLlmsTxt();
    }

    public function hookActionCategoryUpdate($params)
    {
        $this->generateLlmsTxt();
    }

    public function hookActionCMSCoreUpdate($params)
    {
        $this->generateLlmsTxt();
    }

    public function hookDisplayAdminAfterHeader($params)
    {
        return '<div class="alert alert-info">' . $this->l('LLMs.txt Generator is active. Configure it in Modules > LLMs.txt Generator.') . '</div>';
    }

    public function getContent()
    {
        $output = '';
        $settings = unserialize(Configuration::get('LLMS_TXT_SETTINGS'));

        if (Tools::isSubmit('submitLlmsTxtSettings')) {
            $settings = [
                'name' => Tools::getValue('name'),
                'description' => Tools::getValue('description'),
                'keywords' => Tools::getValue('keywords'),
                'language' => Tools::getValue('language'),
                'address' => Tools::getValue('address'),
                'local_business' => filter_var(Tools::getValue('local_business'), FILTER_VALIDATE_URL),
                'location' => Tools::getValue('location'),
                'url_es' => filter_var(Tools::getValue('url_es'), FILTER_VALIDATE_URL),
                'url_en' => filter_var(Tools::getValue('url_en'), FILTER_VALIDATE_URL),
                'url_fr' => filter_var(Tools::getValue('url_fr'), FILTER_VALIDATE_URL),
                'facebook' => filter_var(Tools::getValue('facebook'), FILTER_VALIDATE_URL),
                'instagram' => filter_var(Tools::getValue('instagram'), FILTER_VALIDATE_URL),
                'linkedin' => filter_var(Tools::getValue('linkedin'), FILTER_VALIDATE_URL),
                'youtube' => filter_var(Tools::getValue('youtube'), FILTER_VALIDATE_URL),
                'contact_page' => filter_var(Tools::getValue('contact_page'), FILTER_VALIDATE_URL),
                'phone' => Tools::getValue('phone'),
                'email' => filter_var(Tools::getValue('email'), FILTER_SANITIZE_EMAIL),
                'instructions' => Tools::getValue('instructions'),
            ];
            Configuration::updateValue('LLMS_TXT_SETTINGS', serialize($settings));
            $output .= $this->displayConfirmation($this->l('Settings saved successfully.'));
        }

        if (Tools::isSubmit('generateLlmsTxt')) {
            if ($this->generateLlmsTxt(true)) {
                $output .= $this->displayConfirmation($this->l('llms.txt file generated successfully.'));
            } else {
                $output .= $this->displayError($this->l('Error generating llms.txt file. Check permissions.'));
            }
        }

        if (Tools::isSubmit('deleteLlmsTxt')) {
            $file_path = _PS_ROOT_DIR_ . '/llms.txt';
            if (file_exists($file_path) && unlink($file_path)) {
                $output .= $this->displayConfirmation($this->l('llms.txt file deleted successfully.'));
            } else {
                $output .= $this->displayError($this->l('Error deleting llms.txt file or it does not exist.'));
            }
        }

        return $output . $this->renderForm();
    }

    private function renderForm()
    {
        $settings = unserialize(Configuration::get('LLMS_TXT_SETTINGS'));
        $fields_form = [
            'form' => [
                'legend' => ['title' => $this->l('LLMs.txt Configuration')],
                'input' => [
                    ['type' => 'text', 'label' => $this->l('Name'), 'name' => 'name', 'value' => $settings['name'] ?? Configuration::get('PS_SHOP_NAME')],
                    ['type' => 'textarea', 'label' => $this->l('Description'), 'name' => 'description', 'value' => $settings['description'] ?? Configuration::get('PS_SHOP_DETAILS')],
                    ['type' => 'text', 'label' => $this->l('Keywords'), 'name' => 'keywords', 'value' => $settings['keywords'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Language'), 'name' => 'language', 'value' => $settings['language'] ?? 'Español'],
                    ['type' => 'text', 'label' => $this->l('Address'), 'name' => 'address', 'value' => $settings['address'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Local Business URL'), 'name' => 'local_business', 'value' => $settings['local_business'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Location'), 'name' => 'location', 'value' => $settings['location'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('URL (Spanish)'), 'name' => 'url_es', 'value' => $settings['url_es'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('URL (English)'), 'name' => 'url_en', 'value' => $settings['url_en'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('URL (French)'), 'name' => 'url_fr', 'value' => $settings['url_fr'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Facebook'), 'name' => 'facebook', 'value' => $settings['facebook'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Instagram'), 'name' => 'instagram', 'value' => $settings['instagram'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('LinkedIn'), 'name' => 'linkedin', 'value' => $settings['linkedin'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('YouTube'), 'name' => 'youtube', 'value' => $settings['youtube'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Contact Page'), 'name' => 'contact_page', 'value' => $settings['contact_page'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Phone'), 'name' => 'phone', 'value' => $settings['phone'] ?? ''],
                    ['type' => 'text', 'label' => $this->l('Email'), 'name' => 'email', 'value' => $settings['email'] ?? ''],
                    ['type' => 'textarea', 'label' => $this->l('Instructions for LLMs'), 'name' => 'instructions', 'value' => $settings['instructions'] ?? ''],
                ],
                'submit' => ['title' => $this->l('Save Settings'), 'name' => 'submitLlmsTxtSettings'],
                'buttons' => [
                    ['title' => $this->l('Generate llms.txt'), 'name' => 'generateLlmsTxt', 'type' => 'submit', 'class' => 'btn btn-default pull-right'],
                    ['title' => $this->l('Delete llms.txt'), 'name' => 'deleteLlmsTxt', 'type' => 'submit', 'class' => 'btn btn-danger pull-right'],
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->fields_value = $settings;
        $helper->submit_action = 'submitLlmsTxtSettings';
        return $helper->generateForm([$fields_form]);
    }
}
