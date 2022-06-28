<?php
    class HeroBanner extends Plugin {
        
        public function init()
        {
            $this->dbFields = array(
                'heroBgColor'=>'#FFFFFF',
                'heroTitle'=>'Your hero banner heading goes here.',
                'heroTitleColor'=>'',
                'heroPhrase'=>'Your hero banner short text goes here',
                'heroPhraseColor'=>'',
                'heroBtnLabel'=>'Submit',
                'heroBtnLink'=>'#',
                'heroImage'=>''
            );
        }
        public function adminController()
        {
            global $layout;
            // $layout["title"] = "Hero Banner Plugin | Bludit";
            try {
                // Check if the form was sent
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // global $site;
                    // $site->set(array('title'=>$_POST['title']));
                    $this->setField('heroBgColor', Sanitize::html($_POST['heroBgColor']));
                    $this->setField('heroTitle', Sanitize::html($_POST['heroTitle']));
                    $this->setField('heroTitleColor', Sanitize::html($_POST['heroTitleColor']));
                    $this->setField('heroPhrase', Sanitize::html($_POST['heroPhrase']));
                    $this->setField('heroPhraseColor', Sanitize::html($_POST['heroPhraseColor']));
                    $this->setField('heroBtnLabel', Sanitize::html($_POST['heroBtnLabel']));
                    $this->setField('heroBtnLink', Sanitize::html($_POST['heroBtnLink']));
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        public function adminView()
        {
            // Token for send forms in Bludit
            global $security;
            $tokenCSRF = $security->getTokenCSRF();

            // Current site title
            global $site;
            // $title = $site->title();

            global $L;

            // HTML code for the form
            $html = ' <h2 class="mt-0 mb-3"><span class="fa fa-cog"></span><span>Hero Banner</span></h2>';
            $html .= '<div class="alert alert-primary" role="alert">';
            $html .= $this->description();
            $html .= '</div>';
            $html .= '
                <form class="plugin-form" id="jsform" method="post" action="" autocomplete="off">
                    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="'.$tokenCSRF.'">
                    <div class="form-group">
                        <label for="heroBgColor">'.$L->get('hero-bg-color').'</label><br>
                        <input type="color" id="heroBgColor" name="heroBgColor" value="'.$this->getValue('heroBgColor').'">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="heroTitle">'.$L->get('hero-title').'</label>
                        <input type="text" class="form-control" id="heroTitle" name="heroTitle" value="'.$this->getValue('heroTitle').'">
                    </div>
                    <div class="form-group">
                        <label for="heroTitleColor">'.$L->get('hero-title-color').'</label><br>
                        <input type="color" id="heroTitleColor" name="heroTitleColor" value="'.$this->getValue('heroTitleColor').'">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="heroPhrase">'.$L->get('hero-phrase').'</label>
                        <input type="text" class="form-control" id="heroPhrase" name="heroPhrase" value="'.$this->getValue('heroPhrase').'">
                    </div>
                    <div class="form-group">
                        <label for="heroPhraseColor">'.$L->get('hero-phrase-color').'</label><br>
                        <input type="color" id="heroPhraseColor" name="heroPhraseColor" value="'.$this->getValue('heroPhraseColor').'">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="heroBtnLabel">'.$L->get('hero-button-label').'</label>
                        <input type="text" class="form-control" id="heroBtnLabel" name="heroBtnLabel" value="'.$this->getValue('heroBtnLabel').'">
                    </div>
                    <div class="form-group">
                        <label for="heroBtnLink">'.$L->get('hero-button-link').'</label>
                        <input type="text" class="form-control" id="heroBtnLink" name="heroBtnLink" value="'.$this->getValue('heroBtnLink').'">
                    </div>
                    <button type="submit" class="btn btn-primary" name="save">'.$L->get('hero-save-btn').'</button>
                    <a class="btn btn-secondary" href="/admin/plugins" role="button">'.$L->get('hero-cancel-btn').'</a>
                </form>
            ';
            return $html;
        }

        public function afterAdminLoad(){
            global $L;
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                print('<script charset="utf-8">showAlert("'.$L->get('hero-save-msg').'");</script>');
            }
        }

        public function adminSidebar()
        {
            $pluginName = Text::lowercase(__CLASS__);
            $url = HTML_PATH_ADMIN_ROOT.'plugin/'.$pluginName;
            $html = '<a id="current-version" class="nav-link" href="'.$url.'">Hero Banner</a>';
            return $html;
        }
        
        // make the plugin call on site
        public function siteHead()
        {
            // Include plugin's CSS files
            $html .= $this->includeCSS('style.css');
            return $html;
        }
        public function siteBodyBegin() {
            global $url;
		    global $WHERE_AM_I;

            // Do not shows hero banner on page not found. Just in case home 404... u'never know!
            if ($url->notFound()) {
                return false;
            }

            $html = '<header class="hero-banner d-flex align-items-center" style="background: '.$this->getValue('heroBgColor').' url(\''.$this->domainPath().'img/bg-pattern.png\');background-repeat: repeat; background-position:center center;">
                        <div class="container hero-container">
                            <div class="row">
                                <div class="col mr-auto text-wrap">
                                    <h2 class="title" style="color:'.$this->getValue('heroTitleColor').'">'.$this->getValue('heroTitle').'</h2>
                                    <p style="color:'.$this->getValue('heroPhraseColor').'">'.$this->getValue('heroPhrase').'</p>
                                    <a href="'.$this->getValue('heroBtnLink').'" class="btn btn-primary mb-4">'.$this->getValue('heroBtnLabel').'</a>
                                </div>
                            </div>
                        </div>
                    </header>
            ';

            // Show hero banner in homepage only
            if($WHERE_AM_I=='home'):
                return $html;
            endif;
        }
    }
?>