<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->ion_auth->set_hook('post_login_successful', 'login_ip', 'user', 'save_user_login_ip', array());

        $this->load->model('email_model', 'EmailQueue');
        $this->load->model('user_model', 'User');
        $this->load->library('session');

        $this->addStyleSheet(asset('styles/users.css'));
        //$this->output->enable_profiler(PROFILER_SETTING);
    }

    public function login() {
        if (!$this->isGuest)
            redirect('back_office');

        if ($this->input->post()) {
            // check how many times try to log in
            if ($this->session->userdata('loginTimes') == FALSE) {
                $times = 1;
            } else {
                $times = $this->session->userdata('loginTimes') + 1;
            }
            $this->session->set_userdata('loginTimes', $times);
            // check if is time to unlock
            if ($this->User->check_blocked_ip()) {
                $this->User->remove_blocked_ip();
                $this->session->set_userdata('loginTimes', 1);
            }
            if (LOGIN_IP_CHECK == 0 || $this->session->userdata('loginTimes') <= 5) {
                $data = NULL;

                /****
                 * google recpatcha validation
                 */
                if (ENVIRONMENT == 'production' &&  LOGIN_CAPTCHA) { //

                    $captcha = FALSE;

                    if (isset($_POST["g-recaptcha-response"])) {
                        $captcha = $_POST["g-recaptcha-response"];
                    }
                    if (!$captcha) {
                        echo json_encode(array(
                            'error' => 'Captcha invalid. Reloading page... please wait.',
                            'redirect' => 'reload'
                        ));
                        return;
                    }
                    $query = array(
                        'secret'    => RECAPTCHA_SECRET_KEY,
                        'response'  => $captcha,
                        'remote_ip' => $_SERVER['REMOTE_ADDR']
                    );

                    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?".http_build_query($query)));
                } else { // we all like some fudge
                    $response = new stdClass();
                    $response->success = TRUE;
                }

                if (isset($response->success) && $response->success == true) {

                    /** CAPTCHA VALID **/
                    if ($this->form_validation->run('user/login')) {
                        $username = $this->input->post('username');
                        $password = $this->input->post('password');
                        $remember = $this->input->post('remember');


                        if ($this->ion_auth->login($username, $password, $remember)) {
                            $this->load->helper('cookie');
                            if ($ref = get_cookie('ref')) {
//                              Clear the ref entry in the DB for that user as he has now logged in
                                //  $this->load->model('referral_model', 'Referral');
//                                $this->Referral->deleteClick($ref);
                                delete_cookie('ref');
                            }

                            // if locked in settings check IP
                            if (!$this->User->check_lock_ip(getRealIpAddr(), $username)) {
                                $data = array(
                                    'error' => 'IP address has changed. You can not log in from this IP '.$this->input->ip_address.'. An email has been sent to the address in your account with an link to unlock your account. If you have any problems please submit a support ticket to have your IP address unlocked.'
                                );
                                $userData = $this->ion_auth->user()->row();
                                $this->load->helper('guid');
                                $unlockCode = create_guid();
                                $username = $userData->username;
                                $userId = $userData->id;



                                $this->User->update($userData->id, array('unlock_ip_code' => $unlockCode));

                                $this->EmailQueue->store($userData->email, 'Unlock your '.SITE_NAME.' Account IP Address', 'emails/auth/unlock_ip', compact('userId', 'username', 'unlockCode'), 10); // Mark it as important!

                                $this->session->sess_destroy();
                                delete_cookie("identity");
                                $this->session->set_flashdata('logged_in', FALSE); //identify next page load as logged
                                $this->ion_auth->logout();
                            } else {
                                $this->session->set_flashdata('logged_in', TRUE); //identify next page load as logged


                                $usernameforchecking = $userData->username;

                                $useridforchecking = $userData->userId;

                                $dataforchecking = array(
                                    'username'=>$usernameforchecking,
                                    'useridforchecking'=>$useridforchecking
                                );

                                $this->session->set_flashdata('checkData',$dataforchecking);

                                $data = array(
                                    'success'  => 'success',
                                    'replace'  => array(
                                        'loginForm' => '<b>Login successful.</b><br/>
                                            Please wait while your account is loaded..'
                                    ),


                                    'redirect' => ($this->isAdmin) ? array('url' => site_url('/admin')) : array('url' => site_url('/back_office?u='.$username))
                                );
                            }
                        } else {
                            $data = array(
                                'error' => $this->ion_auth->errors()
                            );
                        }
                    } else {
                        $data = array(
                            'errorElements' => $this->form_validation->error_array()
                        );
                    }
                } else {
                    $data = array(
                        'error' => 'Captcha invalid. Reloading page... please wait.',
                        'redirect' => 'reload'
                    );
                }
            } else {
                // block IP
                $this->User->block_ip();
                $data = array(
                    'error' => 'Your IP has been blocked for 5 minutes after 5 failed login attempts.'
                );
            }
            echo json_encode($data);
        } else {
            //redirect(SITE_ADDRESS.'#login');

            //$this->session->set_userdata(array('rand1' => rand(1, 10), 'rand2' => rand(1, 10)));

            $this->data->content = $this->loadPartialView('user/login');
            if ($this->ajax) {
                echo $this->data->content;
            } else {
                $this->addJavascript(asset('scripts/forms.js'));
                $this->addStyleSheet('/layout/frontend/assets/css/form.css');
                $this->setLayout('layout/frontend/shell');
                $this->loadView('layout/default', 'Login');
            }
        }
    }

    public function logout() {
        $this->ion_auth->logout();
        if (get_cookie('ref'))
            delete_cookie('ref');

        redirect();
    }

    public function register() {
        if (!$this->isGuest) {
            if ($this->ajax) {
                $data = array(
                    'error' => 'You are logged in.',
                );
                echo json_encode($data);
                return;
            } else {
                redirect('back_office');
            }
        }

        if (defined('LAUNCH_TIME') && LAUNCH_TIME > now()) {
            $this->setLayout('layout/frontend/shell');
            $this->data->message = 'Registration not yet available. Please wait until '.date(DEFAULT_DATETIME_FORMAT, LAUNCH_TIME);
            $this->data->content = $this->loadPartialView('partial/error');
            $this->loadView('layout/default', SITE_NAME.'- Open Account');
        }

        $this->load->model('referral_model', 'Referral');

        $clickId = get_cookie('ref');

        if ($clickId) {
            $this->data->clickData = $this->Referral->getClick($clickId);
            $sponsor = $this->User->getData($this->data->clickData->user_id);
            if ($sponsor->locked == 1 || $sponsor->account_level == 0) {
                //$rand = mt_rand(1, 2);
                $default = DEFAULT_USER_ID;
                $sponsor = $this->User->getData($default);
            }
            $refCount = $this->Referral->countReferrals($sponsor->id, TRUE, TRUE);
            $spillOff = intval($this->User->getSetting($sponsor->id, 'spill_off', 0));

            $maxRefs = ($sponsor->account_level >= SPILL_OPTION_LEVEL && $spillOff == 1) ? MAX_REFERRALS : CYCLER_WIDTH;

            if ($refCount < $maxRefs) {
                $referrerId = $sponsor->id;
            } else {
                // $default = DEFAULT_USER_ID;
                // $default = 1;
                // $sponsor = $this->User->getData($default);
                // for wednesday
                $this->data->origSponsor = $sponsor;
                //var_dump($sponsor);
                $referrerId = $this->spill($sponsor->id);
            }
        } else {
            //$rand = mt_rand(1, 2);
            $default = DEFAULT_USER_ID;

            $referrerId = $this->spill($default);
        }

        $this->data->sponsor = $this->User->getData($referrerId);

        if ($this->ajax) {
            if ($this->input->post()) {
                //print_r($_POST);
                return $this->procRegForm($clickId);
            } else {
                echo $this->loadPartialView('user/register');
            }
        } else {
            $this->addJavascript(asset('scripts/forms.js'));
            $this->addStyleSheet('/layout/frontend/assets/css/form.css');

            $this->addJavascript(asset('scripts/generic.js'));
            $this->addStylesheet(asset('bootstrap/css/forms.css'));
            $this->setLayout('layout/frontend/shell');

            $this->data->content = $this->loadPartialView('user/register');
            $this->loadView('layout/default', SITE_NAME.'- Open Account');
            if( REGISTER_FIELD_COUNTRY ) {
                $this->data->country = $this->User->getCountry('93.180.183.100');
            }


        }
        return TRUE;
    }

    private function spill($userId) {

        $refs = $this->Referral->get($userId, FALSE); // array sorted by referral count descending; exclude free members

        if ($refs) {

            $i = $refCount = count($refs);
            do {
                if ($refs[$refCount - 1]->locked == 0 && $refs[$refCount - 1]->referrals < CYCLER_WIDTH) {
                    return $refs[$refCount - 1]->id;
                }
                $i--;
            } while ($i > 0);

            // Pick a random leg to drill down and find a member with less than 5 refs.
            $leg = mt_rand(0, $refCount - 1);

            return $this->spill($refs[$leg]->id);

        } else {
            return $userId;
        }
    }

    private function procRegForm($clickId, $activate = FALSE, $autoLogin = FALSE) {
        if ($this->input->post()) {

            if (REGISTER_IP_CHECK && $this->User->check_used_ip($this->input->ip_address())) { //ENVIRONMENT == 'production' &&
                echo json_encode(array(
                    'error' => 'Only one member is allowed to register from this IP address.',
                ));
                return;
            }

//            $salt = $this->session->userdata('salt');
//            if (!$salt) {
//                if ($this->ajax) {
//
//                    $data = array(
//                        'error'  => 'Invalid registration form. Reload the page and try again.',
//                        'redirect' => 'reload'
//                    );
//                    echo json_encode($data);
//                    return;
//                } else {
//
//                    $this->session->set_flashdata('error', 'Invalid registration form. Please try again.');
//                    redirect('register.html');
//                }
//            }

            $data = NULL;
            if ($this->form_validation->run('user/register')) {
                $post = $this->input->post();

                /****
                 * google recpatcha validation
                 */
                $captcha = TRUE;
                if (ENVIRONMENT == 'production' && REGISTER_CAPTCHA) { //

                    if (isset($_POST['g-recaptcha-response'])) {
                        $captcha = $_POST['g-recaptcha-response'];
                    }
                    if (!$captcha) {
                        echo json_encode(array(
                            'error' => 'Check the captcha.',
                        ));
                        return;
                    }
                    $query = array(
                        'secret'    => RECAPTCHA_SECRET_KEY,
                        'response'  => $captcha,
                        'remote_ip' => $_SERVER['REMOTE_ADDR']
                    );

                    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?".http_build_query($query)));

                    $captcha = isset($response->success) && $response->success == TRUE;
                }

                if ($captcha) { //($post['salt'] == $salt)
                    $username = $post['username'];
                    $password = $post['password'];
                    $email    = strtolower($post['email']);
                    $phone = $post['phone'];

                    $this->load->model('user_model', 'User');

                    $this->load->helper('guid');
                    $activation     = create_guid();
                    $refd = $this->User->getRefsRef($post['referrer_id']);
                  $randCode =   (rand(1000, 1000000));
                    $additionalData = array(
//                        'secret_question' => $post['secret_question'],
//                        'secret_answer'   => $post['secret_answer'],
                        'phone_verify_code' => $randCode,
                        'referrer_id' => $post['referrer_id'],
                        'sponsor_id'  => $refd->referrer_id,
                        //'sponsor_id'  => $post['sponsor_id'],
                        'activation_code' => (!$activate && ACTIVATION_EMAIL) ? $activation : NULL,
                        'ad_credits'      => 0,
                        'te_credits'      => 0,
                        'account_expires' => now() + (intval(FREE_MEMBER_EXPIRE) * CACHE_ONE_DAY),
                        'account_level'   => 1,
                        'active' => (!$activate && ACTIVATION_EMAIL) ? 0 : 1 ,
                    );

                    foreach(array('username', 'first_name','last_name','city','state','address','postal_code','phone','country_id') as $fieldname) {
                        if( isset($post[$fieldname]) && $post[$fieldname] ) {
                            $additionalData[$fieldname] = $post[$fieldname];
                        }
                    }

                    if ($userId = $this->ion_auth->register($username, $password, $email, $additionalData)) {

                        $this->load->model('referral_model', 'Referral');
                        if ($clickId) {
                            $this->Referral->registerClick($clickId, $userId);
                        }
                        delete_cookie('ref');

                        if(!$additionalData['active']) {
                            $this->EmailQueue->store($email, 'Activate your '.SITE_NAME.' Account', 'emails/auth/activate', compact('userId', 'username', 'password', 'activation'), 10); // Mark it as important!
                            $this->activesms($phone,$randCode);
                           // $this->compensate1($username,$userId);

                        } else {
                            $this->EmailQueue->store($email, 'Welcome to '.SITE_NAME, 'emails/auth/welcome', array('username' => $post['username'], 'password' => $post['password']));

                            $user     = (object)$additionalData;
                            $user->email = $email;
                            $user->id = $userId;
                            $this->notify_upline($user);
                        }

                        if ($invite = $this->session->userdata('invite')) {

                            $this->Referral->update('invite', $invite, array('referral_user_id' => $userId));
                        }

                        if ($autoLogin) {
                            $this->ion_auth->login($post['username'], $post['password'], TRUE);
                            $data = array(
                                'success' => 'success',
                                'redirect' => array(
                                    'url' => site_url('back_office')
                                )
                            );
                        } else {
                            $this->data->username = $username;
                            $this->data->email = $email;
                            $data = array(
                                'success' => 'success',
                                'replace' => array(
                                    'registerForm' => $this->loadPartialView('user/register_success')
                                )
                            );
                        }
                    } else {
                        $data = array(
                            'error' => $this->ion_auth->errors()
                        );
                    }
                } else {
                    $data = array(
                        'error' => 'Captcha invalid. Reloading page... please wait.',
                        'redirect' => 'reload'
                    );
                }
            } else {
                $data = array(
                    'errorElements' => $this->form_validation->error_array()
                );
            }
            echo json_encode($data);
        }
    }

   /* private function compensate1($username,$user_id){
        $this->load->model('ph_model', 'PH');
        //2.5k members
        $people = array("Geraldokoye","Dordor","Siri","IFESINACHI","Tunbol","rawdews","Nkechib7","Fafolahan","Timothyjames","Olushegnut","Kiss001","Vitaldollar","olonisakin02","Mayorkaka","Boko19","Vico","ody","Damilare1","daka23","Yetunde73","Maputohq","Dayo123","olawumi","Emmy7942","Itani","Adanne230","sammytex","Mojisol","Inuwa","habibtee","Ayokush","Quinnet","Progress378","Lollipopxy","jamestim","Winifred","lanreolofin","Agono","Miler","Emmawolex","Haruna20","Fitano","gben","Sheyishey","favoursky","Ajekudus435","Nzeribe","Orighoye","igianu","pjc4life","Ayobami11","Tobelex","Senchristian","Solidrock","Francis20","Jossicky","Olumayowa77","Pilove88","Ramsat","Arizona","Kabirat5643","Yetty234","Feyisayomi","Ayodele38","Chukwu92","Georgy","Abiscobabe","Dayo1","Lawalisky","Oluwaseun87","Oluwabusayo","Nelbi","johnjoel86","wajud","Holamy8","yinkybaby","dontaiwo","Kayflexzy","Karryfrank","Dami1","Sunny-14","Vivian2","Olaoluwa97","Jojo2","oldboi3","Titilayo87","Oweme","Onelove","Jamiu","Jordan01","Terlumun","T-cool","falilat76","Jayjaychiwar","opelek","Lekyzlobby","AyabaToye","Kamat","Bodilom4","jobjoy","Eyinna123","Reus","Chikeluba","Mimigreat","connect2014","Olamide123","Kelvin56","Gloriousking","Flourish01","Bukolagold","Anthony1960","Ifyviv","Olanipekun98","Benjamin030","Wemmy2016","Meshach18","Dominique08","frosh","bose123","oliver012","Akpai2222","wasco1","Picco","Adexjuwon1","heartstrings","Arua","Adebayo12","Ekem2019","Abdul1986","Rubochi","barade","Petite","Danielx","Emmason2","rasheedsodiq","Abyoung","Wale444","Sammy1","Kbhanty","Abdoolee","Eunice5643","Habeeodun","Demolapaul37","Vespucci","Nwacanada","stellaify","Mrsanchez","Ola4gud","Davina","kellymatrix","Mas10s","agbojj","onoh11","Simpafrancis","Emmanuelfred","Anipet20","Jube8love","Peter4love","OlufunkeB","Smart19","richsharpy","Azeez1","tarus","Ngozi200","Joshigo","jerry2","Veekyedet","olujohnbosco","oyin02","NiceGuy","Motunrayo12","Ikeoluwa","Gift1","Pamela24","Solape2019","Batty2019","Oloyede00","Ayokunle217","Mirauzor","Mary662","Smith","Edennah00","Amad","olajumoke13","PerezTony","Donchrist","Dab23","Mmacherry","Oyebode1","Timothy02","Hyman007","meshack","CHARILUV","Elechi1a","Zeefat","SOKENU392","Jazzyjeff","SSmadaki","Joseph247","Olola","Hizzyking","Sky_Angel","Seyispecial","Calistero","Jeffnosa73","Omooye","Davidjoseph","Kingkong","ikeson","Nancy","Hannah2019","Hapi","Image","asitaltd","Conforth","Temmyd","OGBLIMITED","Ayomikun22","08149707100","Oyekanmi447","Walexmedo","Easy26","John48","Aridunnulove","Shifu","Sule12","Abrahamly","Haflek","lola123","Elizabeth14","Ndu123","Harlequin","EPHRAIIM_S","Kolabbey","Chilin","Mariatosin","Computergodd","Karzeem","Timisa","Bigdayo","Wmariam","pelumiii","Tailorbaba","Acateria","ZUMA","Asuquo","Yetty87","Anyanwup","Abike2k","ISAACBEST","tapi388","Vinchy","Neyoo","Pammy25","Cokerseun","lizTess","Greategrace","Peter22","Opybanty","Anty40","Whitney18","ganiyat11","omolar","Divineben","PRECILIA","Patlescotts","Roland1","papillo","Deesor","Kweenayomi","seglof","Peace","Joyfull","helena","Blexiar","kchemerd","Esighasim","Felincoguy1","Aminatope","Austino1","Jnmadu","Meezy","Missgeorge","Debiflow","QueenTianah1","iomact","Bekky1111","Nkenna","Helpzibah","Adaammi002","Iprosper383","Adeshola001","kayode3","Eniola19","alexlove2","Femi1122","Teebamzy","babagbemi","rotimi80","Nnebuihe","Rupee4real","CollinsG","Oforchris","Dannie","Bobking12","sunom","Innocent5643","BankyB","Agbesuyi22","Tobe2002","Vevnerd1988","Akanudiong","Ekesam444","kemodavid","Bolugirl","Samsonpelumi","Robinwealth","abosede1","Motuns","Kate32","Admiral01","Saheed2019","mayor123","UGOCHI200","Praizgod","Amusan","Efenelson","Rosykay","Ale4040","iwasco","kerim20","uzo2019","osakwe","themmy","Adesiyan","Happiness11","Soji008","Abraham1","Sirtee","mimivik","Ese12345","dollarmagnet","bellodz","Olaitan99","Skinniee","Dahpson","sunshine0404","Abiola66","Vickram33","bigjoe1","Nuel","Mercly","Starich","Gabrielm","Qwinflorence","david070","Love24","Finest","Youthleader","eben","OWOLABI83","Joel32","Ijebaby","Cashyz","Owolabi12","Wale55","Typumping1","Rose32","Bimbite","Abdulrahmon","Obidi1","LizzyMerccy","Emmajoseph","slaido","Tolussy","Leena20","Anifowose","Benedictpaul","Mumolivia01","Dayo22","Ebez4157","emmyone1","Ismaillux","Midnight","Debby30","thebless","Oyetunde","opeyemi3","Esefjay","Mimiq","mavin","Ebynwao","paparanking","Tundun","Zeeko_Berry","Geomatician","goddyada","Rasaqsemiu22","Forgey50","Onakunlex","mojeed95","Olakunle95","Ogoostar","Tosynirabor","Zeeco","amatek","Timikay","Faith19","bassy1","suzzyrich","Tolu2r","Olab","Lateef72","Munazvic","Farayola","Adekunle29","Queenrayzee","Minstrel","Felicia2019","Durobolu","Tessy55","Engrayoosu","Peacechika","Habeebosan","Classic","Adepoju1999","Larry124","Covenant1234","Nkemdilim","Bowale","Nharseey","Honor2919","susan10","Vitalis1990","Febafa","maffiliate68","Aminat456","ayotope","Honestguy","Ming90","Emmaz","OFULUE","Ebi2014","Pasco9","Suleman93","Laxers","Jboy91","Lucybrawn","klub_k","Ayoolacuts","RealJoseph","Cliff","Moses1a","tdaniel","michel","Blaqgold","Udoh","JimScott","Mtukur","Kollym","Eghosa","ridwan99","Gideon1","Patrick54","Hilda_chizzy","Mrpress","Mohammed89","olusoji","dlect19","Teestar24","Bellafin","Mirald","awewealth","BroShaddy","Aremu59","Ore025","Bright336","Agnes550","samprince","LynnAngel","Ajayi20","Somayo","Bnsambo","Ayo111","QueenEather","Okpe","Olug410","sirkaykay","frankv73","Adesina59","freehafsy","Kaymet","Tanny","Ibironke105","Gift51","Uniqola","Praises","Oluwaseun47","Naehomie","Motun22","jaspa01","Lazman","mykelilo","Helvys002","Pedro","Sampson","Mammie","Lekan005","lampejo","Promise5238","okewale","Katemanas","Moses2","Grey1","Emmyyak","Iyeunusual","esat","joykai","oriyomi78","Enomos","Exlusive","Baba1","Rayofhope","Catherine12","Geral","Maxwell5","Sunnykite3","Shantymartin","Uniqueogoo","Surebanker","SophyGold","binaffan84","Onoruoiza","yahaya2020","omolara74","Aniyikaye","Tosdam61","Rasheed","babydoll","Badmoskunle","oluwasesan","Miley27","Rosalin","PRODIGY","Winni","Atasmicky","olikscharley","Abisoyekan","Ade-Ademola","nabil","Tolulorpeh","Jtube","Alburuj","Funminiyi","Water20016","Kemite","Alfred88","Cridy","jemist11","okwes","Daorigin","Theomedly55","EKO","Augmented","damayo123","SunshineA","Ogooluwa","Tsekar01","Prince04","Anthonyy","ZezeRepublic","Onu900","Godswilldick","neyo2010","Okokabenneth","Oyedelef","Lemmyspecial","Ogalarita","Nkemnoni","Barakat","chuksz","Peternico","Macdannyjane","Holdings01","Donchy","francephilia","Ijeoma2000","white","Jazzy","easyjay","Ij22","Abikebabe","Adrian01","iamjoshboost","Nenly23","orgustella","Ezeugo","Emmie","Yussuph02","Omowun","ronkemujidat","adebayo65","Folakemi123","Badejo","Aanuoluwapo","Bee_jay","Hizsmart","Soni","Chikaodiri","amanze003","Alexander","Adorable","Authority","Alaniade88","Ogebaby","Stephen1","Cheche111","bassy06","Ojotitilope","Fadama","Dolapo05","Jerry","FIyin2019","Emma3601992","Draymola","Katherine","bendave","Olaex","Chidinmaa","Oyinhardey","elvis","Id","foladan","BigJay93","Ebuka92","Johnaddxes","Stella80","Chime","Alvik","Wahab02","Emar","08076758001","Oyeniyi123","Funmi14","yesiru","oyetit87","Funmzzyy778","Melodi","Tolu","Pauline4God","tubowest","Baoluvbio","Atobas","zikkyjay","Chinedum","ijegood","TommyD2","Macmary02","Jackmoore","seyi19","Peaceo","Makman","Temmyblienjr","deborahchy","El-minnason","Olaomele","Lizzydop","Adeoti400","Great01","kingszeal","Bhunmie","Shashaa","Jefferson","Chi4life2019","MMB","Osaka7","peterboer","Emerald","chinonye2019","Praisie","shina","eazyblast","ebenezer2020","Joy23","Fav2","Ignatius","Emmanu","Lavida","Alpha","Chrome","Loginage","Bomalove","kesman","Extrovert","Seyibrown","Abdulkabir","Akpata","Suleman","ojo881","Helmagnifigo","Ogolove","Tworld2money","Nicee","Ennie","Deremi","Fachecoin","Akinbola41","Gudmos01","Ask4chuks","Emmardoc","LadyTee","Samluv87","Ruamedu","Omoniyi222","Amagwula","Muhammed","odunny123","Sheyitelecom","Hardeymi","Teejaytylan","adelere","dUSA123","Hilaria","alabionimisi","ARANI12","oluseyi1009","Max123","idmayor","temmypaint","sambest","dayofresh","sammyobasi","Suru","ALAGA","Smilez54","benji03","femirate","Danielniyi","IYKEMOORE","Oribhabor","Tosyngboye","CHINECHEREM","funke247","Osilo","GreatOneE","Zainabu","Thorpe","24mike","Imaobot","Ablat","Godspee56","AKDORT","ayobami222","Rena","CINOK","Ayoola","Dammybreez","Frankman","Saviour11","Mbahsamson","zuchi","Oyediran400","saywater82","patrick09","Akinyemi08","whykay","Solanke02","Ayoola92","Olalekan47","adex01","OnowuMikado","Dotman","Lastborn6943","Boye-15","Fowobi","felix23","Wilfredsoft","Olapick","JohnPatrick","Dawn1","Omotunde","bongus300","Ayomi23","Gavaq","Enny123","ladybest","Gratefulsoul","hadeola11","Emmysmart","Hardeytungy","lolykay","giftgawa","Damilola34","Victor1996","Jimklefddh","Joshua2015","Christess","Gabriel0907","oy-ido-0008","OtunbaBoye","Sho","Emmanuel020","Hayodejii","wunmi123","Asoprince","Godblessme","wendy","Ojonugwa","LIZZYBABY","Kessydorathy","mytop4sure","Edylicious","Bernard496","Adoo19","naniboy1","Shalomj","Mario1990","nickey4000","concord042","Princechidi","Ogbaje98","chigoke","Teelax","MacBrandy","08075258508","onwuetiaka","ShowpremBF","Andybest1","Sholape70","DonLboy","Serpent3514","Ocheson","Jaspa","amostheo","Raywilliams","Golfman","Iyadun11","Oladehinde","Salvation","Ngwu","Bright1","Lordrey10","olalekan01","Harjiboy77","Awizzie","08100377957","petersonkyle","sirlee88","fagbohun","starsunny","ituenjoe","Globally","Adekolamide","easytrust","Jagaban","Sholaomor","Vickey1","Yomite007","Ajetayi","Jewel01","Bobbyjay","Folarin","Folasumbo","abimbola2486","shaho","adun","Temitope2000","Blessedman","Bitrus556","Mercy200","Toryviki","Kevinekwueme","Aerielwrites","Tolani","Fm1","Nnedi","Damidara","Halimat","Buzuu4","Ifedolapo","laryjonx84","wahab","Olusola84","Goldentunz","Chavi","Focus","Sallyade","Shakina001","Chinazko","Rebekem","Samuel1","Timi2215","Darlington","ablazeking","Piuskalu5","Obigreat","ella659","clara39","Molz01","ofuremd","minaj","Toyosi","omodara","sojiyemi","virginprince","Lizzybezeh","Ayomid","Akuboti","Ayomiposi91","mayorvic","Christbaby","Richlove","Condition47","Euniceugonna","inioluwa856","peroh","yetubabe","Estar","xayorbaba","grtdebbie","Eyiowu","femtric","Peaceman","Bunmiii","HONOURABLE","Crown65","kodeniran","Dannysong","RANTIMI","Rayo","Johnny","Olusegun","Samuel4u1","Raimi","Crown","CharlieJ","Azeez","Tosrot15","seno","ITunesobanky","japhetson","adelij","Umanu","Otunba","soboj","oluchi2019","olufemiemmy","Vhokay","Tunyink","Khelex","Clevis","iamwinner","Dollypee","Softie45","Ugbusco","KlaciqMify","Johnteeotit","Tarilove","Linda72","Phemzyvee","Fabulous","DANDYBEST","Gbemisade","Prevailer","Adeleke123","Leekelv","Algasal","Akintade","molophil","stephenkingz","FairT","Franklin","Ekene22994","Juliana612","Succeed","Asiripepe","globabe","Peterson11","saidu","Queentina","kemdom","domkem","Ifylove","pecubabe","doosase","Kings1243","Skillful","Ajisafe952","Chilee","Tbabe","Noble-Vision","Damwem","Bukola69","Frannie","Gpower55","Itunu","Blaj2211","Zyola","Adaobi","Factorial","JulesJ","Toothpick","Lord111","Dariyo","ironbody","Iconex","Mayor","Bidemi2019","Shewacash","Yinks","Anenye","Zakka","Haybee","Moses2020","shady","Terry1971","Utodiya2","Joy222","Ajize","Morayo007","Baby4love","BECCALOVE","Shofem84","Chinwe","Ibnhussaini","Dhessy","DsNiCk","Chileo","Dave","Halimah7","Poopoleh","Holas","Peter46","Rhema","Adewunmi","dayo1993","johnnykay","olupumpin","Patsie","Goldenb27","Muhydeen","Adekunbi3","lily1995","giftsam01","fredakhigbe","Edu4luv","Emmy69","desgy777","Steven","Sonoflaw","olayakin","Angeles","Maximo","Adegbola1234","ADUNOLA","Mekus80","Amakagood","Nwanne","Oludare007","herbeasorlah","Marry1","Pastorr","holyghost","emjayaminu","wazzy","Onyilo","Kingjames","Freggy","Ebukachris","Samuelchuks","Adaoma","Foxybabe29","seyifunmi","greatness12","Abule5555","Omolade","K_MONEY","odunwole","Missb","Enons","Damiqueen29","Ayoyemi29","Ebendam","Ifymartha","Leemah","akeemenny","Rose-ruby","Odewole","Recheal1","Oyenike","Mankind","prince77","Ogunrinde","adarich","Sirjeiy","Dlaw1010","Vivy","mycompany","Kingsley","Owos","chixzyleadz","Phisky97","Joynkwa2","Sammy35","Fego","Shadlat123","Shadlat876","MikeyFizzy1","Freeman","ajiboyefm","Naughtyboy","hekad","Jeffrey","Adetoyese","ABOURICH","Mhizwealth","Akolade96","Feminiyi","Bolanle","Ogundare","Sunnyking200","Wokoma13","mrwolex","Jamoo","stephenking","Debby","onyi247","Yehsur","ayicorper","SUPREMACY","Billion","Treasures","krist9ice","Comfort","Yaks4real","Maryjane010","Hillary","Patricia2019","David","jami44","tosok38","ask","Amosdelrey","BonD","AIA2019","GracewololoA","kumutech5","esanfm","toju55");
        //5k members
        $people2 = array("Otega","okonta","Cynthy","tonibrent","Hawatola","debbypapa","debbypops","Bummykay","Babaoba","Doz","Abednego","Okesima","Olalomiokin","Emeka200","Geraldine","sdq","Miffy8880","arinze1131","ADIZA","Olami96","Percy429","IBIERE","BIGWEALTH","Zinny","BIGSAMUEL","Ebony52","jennyg","Kingwale","Neena11","Jollyman","MROGAZI","TGIRL","Asinasiali","Ejekson","simmccoy","Sssmansy","Thobeyd101","Gbabs","temideji001","Seyijohn","Nicelove","Kafilat1","tayolawrence","BOSSOGAR","JOINT","IBINABO","Darajoy","Gregoo","Lilianogo","Mamadolapo","oyetunde89","Dollipompom","Dupssy006","sammy1988","ibrahimule","hhbakabe","Adams001","Kollyboy10","ogunyoolu","Jaylord","Tireni01","Ejor","Titicelia","ikeprecious","Adeduyite80","Azimeyehappi","Ojooludare","Roundcity","Emmanuel3434","Halimat247","Sulyman1","adisbaba","engrnjose","Pstmike","Adebisi2019","Emmypinky","reoofune","Damilola190","Topman","Oladipupo12","folaomong","Jessy1","Chukwukaobi","NwamyChuks","MYLUV","Yegba004","Anabelpee","Mezie","oseghale4u","Sirp85","BOSSALLOY","OYNEKACHI","ZIONFAV","AyoD1987","roselove","GLOBELLA","Biology","CHAVIAN","GOLDENMUMMY","MAMAEDNA","Mahmud2","Babysha","BOSSDIKE","Almorcazy1","MISSSARIMA","Olowofe","CAREYS","CHINELE","NNENNE","Kayaccess01","Opesikoya","Excel2","snexzy0818","Olaitan007","88sanctus","Azuruchima19","Megalyno","Khalifakkd","Sebankaye","dammax","architect","Lawal19","Joanny","aboseo1","ezek","royalty74","Shemilore","enebaski","Sherry1","Ruthie19","Youngsix","boma48","Ezieone","Damilare09","Brandon","Akinkugbe","olaolu19","Oloko19","Oluwasogo","esy4sure","SAS10","goodmind","Peter1991","Lsamola07","Adirat","Rebecca1","Ezekiel07","Mihztega","Okezie","Topstick","WILSON4UK","Tamunokele","AnitaAla","Mikijoey","Gardez","EZEHENRY","Alujo12","Bolagrace","TaiQus","ToyoAde","Lizziewise81","Obim","Cosam","EuniceT","optimist","iwari","Genevieve","donsmart01","Grace50","irekenagba","Faithsophia","Quindiva","VALdeGREAT","Calugonabo","abuajayi","Cotillar","Modupe01","olowo","Debby11","Blessing86","Lawal74","Davidson","Nenny22","Shola1","aim1109","Olanike1","Danielchuks","Akara2019","megastar2019","Olufemi1","Josbod","innocent656","TI","Gana","Sarahbaby","Odebe","Olorunwa","Datuze","kendro","Comfort2","sdandi","Osaro123","Biola2019","Charly2k","Rosejay","qawbas1","MUMMYP","Amaka2k","Glojam007","Adey400","Ronxbronx","MAMAREGINA","Odiibabe","Abid40","Gbeshly123","Cheikh","ENTERPRISE","Sneh101","Enny94","flesxy_ray","tlopippy895","Preciousbaby","olushola","Davi1919","bolarinwa19","SWEET16","Robert0","Kennedy123","DonJoe","ayooluwa2019","Maryloly","Sammyola","Queenethzoin","Emmacom","oloyevick","Promise09","Lasolliy","Aderinoye93","Gracie","Psalms21","NANDI","Pattyreal","senator11","Mererah","stanzbj","HornyB","frances","Adex0000","Shola18","KENNETHLINUS","Omalicha","Tiwani","BIGMUMMY","Doniy","Hellen","Linda2","BUSINESSMAMA","SoloP","Adebisi1","kemmy44","ENGRSAM","Adebimpe2","adebisi55","Olascool","Ayomide002","OUALITY","Adeajayi","Patbillions","Amadichris","Bankytee1","diamondj12","Megakevo","Ugochi042","Collenpowen","Abi1000","Susu","oluseyiup","Babajeje14","CONFIDENZ081","Bukkyshow","Mishael24","Richard2019","Johnbaiyere","Chukwu08","OKOME","Praizrich","HAPPYDATO2","Man_shakiii","STEVEARUNA12","aanu1","rhoda19","paul22","Ann1","Cherrystar","Awa","Bolatayo","Iwuji3434","Kasonbabe","Royal-maje","Timmytech","sunshine12","Solydrock","Aksuccess","Helenhelen","derekdgreat","PHILOMINA","Abdull","ireoluwa","olajesus29","Oladipo","Kentop","Titi2020","Funmi31","aiodavid","Olasun93","surepay","Genius","ntata62","Joyce1","Hamisu35","sirdee","Rops","riya","Kennelly","Jhers","Nedy","Chiomzy","Alale","Temmytossy","Adunolayomzy","Rukayat13","Pluto","Kolly666","Noikiatoke","Amusan1","Dolu","OLAD","dtamuno","MummyJek","Bukkite","Ekok99","Adex4re","Realsod95","femat","TARGET4","ogolina2020","Dibulord","Progress44","Younglyno","Funsho234","Drechopper","Noblejosh","CHIBROS","Preshe","smartj","Ritzyeva","Prophetic","Aribiton","babaeso","Lawrenceoo2","Holaryemmy","adewaleb19","dayourk","Bestmicheal","owoade2019","Hamson","odundeb","Rosdeeq","Kachi","Bukkshey","Zacho","AkinHabib","MARYJAY","Walex2019","OwolabiAnike","Awilo-Tony","Demex","Ugo64","dhavido1991","Emelie","bamibank","inioluwa","Kafilat","Chen","Amans03","Adebolami","sunrise","Asamoka1","Akintex","tundebabs","Eucharia86","Kehinde123","john10","Ikeolumathew","Aderonke1","Adeolami","JALLO55","Amybaby","Charisworld","Logan","Tochez24","Bio","Joyce48","Saleee","Power","multy","Prepre","Adeee","Sasu","Oktura6","Feranms","AlwaysFrank","Loveline93","Babatailor","Gloriahouse2","esthnik","tundeyusuf","Mujibyinka","okaforv88","Sanuade","Rabbisco","DonDeeJay","helenify","Akorede5969","Adeshoye","Oke","Bona","Jadeboy","Egenafidel1","HORLAR","Temitope60","Thorllinho","elmodest","Auta","Bleble2222","Matt","Harrison67","Onyimicheal","Lizzy24","saheedaji","AFEEZCO01","Fidelix","Cleanmoney","Maama","Romiluyi","Olamide102","Benspecial","Chidinma94","Pessy","trademoney","Owoblow2312","PSAM740","Ayotee","Sheffylee","Idris12","Simeon","Tizzymoney","Bukia","Obikings","Seglascool","Walesayo14","Arabela","Duchy","Davidson0385","MrNat43","famoo2019","Daniel11","Oyet2","Ebifa26","MARY2019","Hassanah1","Munazara","Nazagreatest","Oyet1","Sir_Loyal","Logsycool","Snoww","gospel","Tattel","Kenkel95","Raski","Mykehl","Jannyboo","Mayegun30","Thayor","johnnyjoe","RonaldMC","Donatella","Fatkay","Deejayzee","FEYISAYO2019","Shinningd","Juwura101","Shola1980","Veegoo","Mmokutmfon","king80","Mahnuelle","Gambo23","Nasaokwu","Com4t","Moyo","Obyekechi","Papilo","abdamos7","Adeolafemi","Olabs86","Adexleo","Austine2019","Seun84","Estheregwu","commy","adewalealiu","Osekingzy","Minaskey","Kcwealth","Gorgor","Abiolaonly","Haweh5","Blossoms","Patrickekene","Princeo","Buchi5555","Uduak","IdangAhi","Tope676","Borngreat","Rasheed1","Andyheaven","Atan","Sunny1","Loju","chijoke","tinuwealth","malaysian","Doorcars","Senebanty88","Vivyken1","Mykes","Ikem99","FlourishVent","IMOLE","Bahubali","Lizcherish","Blessed2020","Akunna","kenny4sure86","MBK","Christymax","Usman1","Mukhie","osas11","De-facto1994","olorio1","Baba","teamwork","Allius","Khaffi","Abigael","Comsemerok","Dhiallo","Suliat","Vickafloxzy","Thaigal07","Olajuwon","Sarah2","Ajoke56","Jbosco","Soulmate1","shewunic","seuntee","Christian82","PSAM","Makiavelli","Aramide12","Efosa2020","tosynbayo","Walex1","Flexy1","Oludavid519","Winib12","Nnenna2019","Akpors","Alade2233","sunny4real","Raphael","Taiwotina","Ijesa","Oyema","Adexpara","greatff","Eco2019","chrisimo","McFire","Xcellence","Mario1981","Chukwuo","Abolams","Haru","adeleye","Joyaju","Ibk4life","Daramola","damijewe","jobillmiar","Ibro","Gelist","obiact102001","Smartdream","chimamanda","Josh444","Talexbola","Seun125","Yettymama08","JUST4YOU","Ybleeno","Johnbull","Janemic","SHONYBABA","EstyGold","Jules","Tessy4life","Babayo","missbee","Blood1","PrinceJacob","Emanuela","mosadioluwa","Ify2","OKboy1","Charity2","Ogochukwu887","Olamide0213","bestakon","olatoz","echilinda","Elder","Oyiboka","Tintres","joy1094","Quadri1","Bernice","Obinna2","Dove12","Friyo","KosisoGod","debbycrown","chigo4real","kunlekasim","Teemummies","favour2098","glowithblizz","gospelmania","Erena","Ejof","Ollams","Ire001","Oluwasekemi","abigail2019","kabiru2016","Oluwole19","Hephzee","Princessyemo","godisgod","akayjay45","Festus1","Odun31","Drimx","Lizzybliss","Emekakalu","Zina","Sunkon77","Nonso203020","Gifty4real","azubike","Okereke9","Ojuolah","Mbe","amakaike555","chiGod","Skibii5","Jtosin","princepaul","Ethan16","Bestleo19","bensol","Ementa","father","Easyumoh","Climax","mamalujesha","Alawo20","Joy231","nashtee","Rotesax","akanbimua","Tijan","Aggie","Bukky222","Rose1","MaryA","Gideon436","Minika","Myangel","musiliyu2019","EMMAR","Omobola","Kunletiwa","ceejay77","Rhoda105","Henrysino","chiedu311","waleade848","suzzy4life","snexzy0817","Tenitee","Tayekehinde","Princess19","Good_luck","Pone","Sina","tasahd","Gideon2506","bidexvic","Sammy001","Maryatt19","Twhy","maryanne","tajudeen22","LarryKay","Layin","iyaboojo22","deemama","Bodun01","Chinasa22","uchegladys22","slaymama","Ewatomi","Precious2019","funmite","Osajie","Taofeek2019","nwafather","BelovedKay","Phunmy","Kliberty","Francisowuno","Abimay","exodux119","Kuttydee","WONDERFUL","Wiffy","Uchenna1","Kalu","Oluwaseyi09","Franca5","Oak","Aishatu74","Kayprince","Christabel1","Abybola","Segun90","Tonymontana","Akinyemi1","godfreyewere","Abim225","Oh_el_hay","Shontella","Damilizzy","Omolade5","Sikemi","Awotee","bimpe","Onuwa","Betalife","Ijenen","Jose01","Joebuilders","soloweath","Ohi","Franzy","SEYIMAN","Gozifame","Coren","HORYAHDEH","Wizzybaby","QueenJ","temitope2019","Chrisben","COMFORTOP","Richma222","Crystyles","Lovelibabe","Zeaman","Steward","hortee345","GLORIA1","Nmartins","hebron","Adewealth007","Cynthia404","Roteh","Olalincoln","Godbless","Nenebaby","Harmonica","Rotimij","Ffg","Daswill","Jewel500","olabiggest","Philipson","Akinlomo","Adison","Thecitadel","Tomas002","Adetoye44","Ogaga","Ednutt2011","Abey4Real","Tjfas","Gallas4","Draze","Twinkle12","Gordon","AuntyMeg1","Sammietak","Blessing3","Hichief","linnyco","Bassey5","Politeness2","Onyeoziri","esan","Jokkie","Slyym","UDUjim","Salewa1","Brixa","Samuelsmart","abigclement9","Phaulow","Tboi","Mumtobi","Omakwele","moses31","spyke","anbo","Playboy","Davoyemy","Egoego","snocartistry","mmeso50","oriade2019","Esthi","oluchi50","Ladplux","Mimi27","Tonyfaith1","McQueen222","Muktar5689","Lbarclay3","Trezy","MartinsLeon","Judith144","richone090","Bimaru","Juliekay","Klight","foljok","Hazzan17","Graciously5","amuzienams","Petrichor","Henry557","Divinedyke","comfort1","olamercy1","Marvingold","preshila","Tmama","Theresa","tolu4top","michaeldavid","Dedun","Elisha2525","Mac","seyiade","marion","Estty","Oyidoma","samuel29454","Oteyi222","Ayinde1122","Kvngbiggy","Babysexy","Beye12","kpancy","Marleyplemz","Prebi45","Ekene35","mayokun","afiseun","klinshape","Olugbenga","fmlomo","Lovethee","Tijuana","Omieipiribi","Theejhay","Olatunji11","COMRADEIFY","Olaitan12345","Olajuwon56","Ademola22","Tomi","Adeshina48","Emeka22","Bishop1","Cjnliam","polalauro","Mavel94","keshmum","Nkem22","Festusmoa","Ezeudo","edith2019","Haryoff","Udoferd","Adewale11","kenneth08","maryogah","Funlola19","Abironside","PHEMO","LarryB","Joyz","GREAT123","Marigold","Lizzygold24","linda84","FAITHEHIS","Olamiji1988","ifedayo2222","PEJU","Lovechike","ayo4REAL","temitope2010","BADMUS","farouk","Lawal12500","Chinwe19","Samkay2435","Fifi","Ilasi1250","Marshalharry","DONLOVE","cletusjoe","ezeorah1","Klintz","george247","Faith18","yoj","Senator5","Holuwabukola","Ezra2019","pretty247","promise1","ArikeAde","Holuwatina","Thankgodvin","dakova1","Virgie","Ekolawole","Bsquare","Samtrack71","tolusefaith","johnflofocus","Hayomide","Prandex","Dnice01","Ibukunmi","Gustadee","Goggle","Artillery","Elibethy","Adeyemi3","Odafe","Mumeris","drdickjosh2","seandux","Olable","Karacter","kennyajk","Goshady","Olatunbosun","Meeday","benjyom001","tessy123","Honeykrown","Leyie","Sunlike","chiamaka1","Mirabel14","DEMARK","topaz9000","akuma","Princessg","Muriakanbi","Bala2627","ArtistiqP","Nkemdili","Majormiriam","Chichi162","EteS","Dammydee","adepoju1234","Ayjosh","HENJINU","Excel14","Ogun2019","oladiti1234","Andy1","Nenyeguchis","kaftol","Harm4less","CREAMY","bumzy","Susanfidelis","Quam","ismail9958","Wandiii","Alexos","princeafitos","Adigunma","sufii1085","waqxylaqxy","Bimbola5","Blood4","Ottun","Aso","Kallymoni","bobbytee20","Likman","EKPO","kunle067","stenyinna","Kelvinuyi","WUYEGOLD","dryemi2","Anzaku","Akinmade1","Tinaa","Emmy1992","ope4top","lekangold","Blessedrock","BROWNiE","Edekin4185","Kade","Sheyiman2000","Austin1234","ItunuX1","Lekan2000","Yinka12","blood3","Ujfranca","Adoke1","Walez","debbiemordi","Tbea","Bolade2000","Zionibe","Sheyi2000","Maryann111","Dammylove","Taydan","emekajacob","Obabire","Praise1","Virgy","Emmaeke","Floflo","yemifere","Emeka","Joycelyn","Hannaz","Agnesokpetu","Mikowworld","Edismart","Oyebadejo","Ajlocracy01","Kolex55","Bestdave","paulc4","ademola80","Abollade400","Annyblack202","Superman","Mnenge","Adelaide","Arike","Alabi2350","Temiafo2","Moyosore","BILLIONAL","Emmyberry","tobit1998","Utep","Eli114","ehivin","Mercy30","Agatha65","goodmummy","akaybabs","ajikeowelz","Gloryshine","mostay","Kennycoker","Sweetrachey","Ekeson","Ogehone","Ijeomaude","nasa","Korey24","Tijani","Olorunfemi","Shade266","Crondeluxe","chinagorom","rinokings","Omari007","Beauty444","Lordwin","Olutayo","Vianney222","donbobs","Mikkybee","buzybabe","moopdeep","Ayoferan","Jekwu","Segzy","sogoboy","Tim3416","evanchris","femiabbey","JZoe23","Rman","Damaristal","davour","Biggest","Dadi","Tonymaxz","MRJOSH001","Evanzoic","Sango","Beebah1","ogbobarth","Ikebobo2017","Iamenioba","Thexto123","ezinne1","Rossyberry","Goldenlady","dammytonz","pateki","Doriti","Hamidat95","Greatwealth","dhavid","Gbenke","UJUM2019","owolabol1","Standard88","COLEN","odinnn","Woleola","demmy01","FKD","Portable","Sugar88","mimiodaudu","Bisola","Waifem","Dckish","Nissikyle","Adesusi","shofuyi","Maris","mimilovesong","Hassan12","Onyedi","Maycash","LENNARD","Peniel","Esther1950","Snowash247","Salome","Londonb","embus01","Cuteashley","Vickkymt","mammy16","Efedesmond","Omoade58","Yemifash","Pace","Warryjay","Marisam","salosalo","Deeman","Damilola01","bukola887","Spiahno","Ngozi555","Dotun1987","Godson4luv","Emmaude","winco","nkemummy","Justskb","boluwatife1","Damilare5","Osasco2019","Debbylee","Coolmykels","Ade01","Clementdodo","Bizzybrain","Tonyguy","Stellay2k","Ajadi","Folulani","joyc","Babylon","Favouredme","Okonkwor","Kemit","Phylips2020","DOCFEGA","Charliebioe","Adexybanty","Jboi09","CHINONYE","Cordelia19","Odeyemi","Olumideozan","Busman","Praise900","Adeyemikemi","Amara1925","floxyomo","Endowed24","Ineke","bigsambaba","Ritzy","Omobaba","Bonphy97","Akobundu","Pstdaniel","Sebiotimo007","johnudeh","adeniyi10","Mirian1234","Omotee86","Mrswt","Joke","Ebony3013","Jjekwuegege","gamez","Crownlaja1","Kigbu20","Kosiso","Timigold","Ogundeji1992","Mycharley","Pelumitebabe","Light11","Jaywest","ezinneamaka","sambalito","Sammy01","Vikkybliz","Otunba2019","Bukunmi2019","Beulahtrg","Job20","Taiwo2843","Femi-15","dukenexy","Peculiarlady","Ksolo0087","phemous02","Abisoh","Petchrist","odunpearl","Chijoy","Ayomide1995","Midun","Thoekaa3","Malachy18","MideChrissie","wazben","biola","MATU","Sylvanus19","Sheridollar","Prodigy360","segbee16","AkinKenny","Elodeking1","DorisB","Maruf","Izutex","Zacch","Hossana","Omusopeyemi","Haywhypuch","hadiat2","Oladoyin23","Muftaukenny","Bibek","ayomide66000","Baralistik","Ezinwaanyi","TeddyA","kchiny","Ugobaby","VICKDOLLAR","Jawjee","Babyking","Dinkynony","chorister","Seconds01","Korede","Keke","Ben4peace","BethEbi","Pstdan001","9098964749","Ochigbo","demartino","Easylondonal","Derealgodson","Hayomi","Temmyfidodo","topekanbai","Vivianchris","Yettyade","Fawas100","Ladycash","Uthman","Nanahbabs","Gbogloria","wofagod","Loveth11","Lansone","Emybest","myme","Topsine","Aileru350","Shedbabao","Johnisah","Mayord63","Ogabright","Elemosho","Alaba","Hogundana","Hepzibah1998","Petersheru","Otugalu","Emmy2","dammyfola","samkingsam5","Omega7","ochomma","Benjamin04","zainny","Beebah","Timmyturner","Ayanamos51","Kennethed","aaaa","melisi","Doubledoctor","Nathaniel","Don104","Islamiatoje","MARGARET","Pat4good","Omj","donsolex","David2019","funky3","Cutiee","alubert","festak","iruhdaniel","Tsadomujidat","twins4real","Onyinye2019","funk2011","Ksam","Ajeka","Amstamon2","Chizua","Chuddy","Walex2210","Delepet28","Yetty-T","Lolly","Adeyemi2","ayettymama","Celeb","Uchenna","Hopy","Bigboy5","Igomuemmy","adebayo2019","Jtender","sogzy","Larrygold","Sylvester","Olujobi","Olumide1850","Ifymadu","Joy123","Christleligh","Manafa2","Bright22","Theophilus","Justicewoka","Tosbal","Racheal80","Udu","Binsamin","Kunmex","LuisRich","Micho9","Anyi4ril","Prosperu","Okenwa","Paco84","bolu439","Jendorlisco","jedar","Bigboy4","Angelchidi","Innocentjohn","Stanokosun","Royisrael","favour1","dotun222","Toluflexing","Konqueror55","Tianah","Shekky001","wilgoodluck","chi0","Licejay","Barnacles","Temple99","Dollarin","HeisAble","Obiorafifa","Hurpy2820","adenarph","geehem","Blessediky","lord76","Hardeeho7","Yetunde2019","Nk4Jesus","Gifted","Emcee","Bimpe224","Lolly2babe","Aako","Saraphina","olateru","Salaomot","Ojoeben85","AYODELE","nma","tobson","Alayo01","Kingdavid","Aghomo","Dipgaf","paultiti","Bolaji4real","TGold","Chibilizino1","petsy","Ekpos","Bridget89","Kemigold","Kennyboy","Garuba","Deco","Maureen","Mayod","chimaalways","Oyinlardeh","Mum","dpee","Royal","bashir94","dianaola","Calebo","Joke5","Chinelo4141","Seth","Olukaywilly","LadyT19","Chisimdi","Balami","olusegun54","OMOREGIE24","Lovede","Francis1987","Onibeji","Horllabale","Mogadishu","Maprince14","Akintinde120","OZIOMA2016","jimmy","Umeh","Ajani","Camchisco","Golden","fynface","Udor17","Ariyike","Grace6","igoh","Anthelm","samminclus","Musa791","Mujidat1969","Danny35","kaptinbmc","bestman247","JURSH","Ifeoma01","Haryoe01","Dignity","Vickypounds1","Emma2019","Oluwarotimi","Adexkay","nelson9ice","Emroid","chuks4life","Sholly","Otukoyaa","Babymimi","NWAKANMA","Favourchichi","OgeJJ","Brownsuga","Odukeye01","Prama","Kenny28","Mine14","Abbe","Oladapo63","Jacobmoses","henrygreat","Sammyeffy","Home45","nel85","Nonye","suyi","oyetunde209","Olusholat","DJSANKO","Kendukay","demoo4real","uche2019","Funmex","Jofonwealth","King-1010","AdaMark","opelolagold","kolawole","Mrisaiah","pinnacle76","Malikkgomezz","Fuad","Abukolacan83","ngene","Rof","Demola","Ngozi09","ifeoluwa1","rachael1","C4","sophia","ejike50","eric2saw","Vinex01","sunny","Tennyolar","Testimony","Onwugha4","Essyluv3","Maryrose4","lulurich","Jacinta23","Rukelly","folakemi2","Ajoky447","Favbube","Janet","Nutritious","Mayowa933","Kayz","Chibuike22","Uzoma555","Oniseyifunmi","georgeclin","Details2020","UsmanBaba25","Olumide","chiomaosondu","taiwotyk","Layemi","opeanis","Chrisola","Donalink","Twinmom","tolani4me","evanokoye","Blessor","Tontova","Oladayo02","Gud","Sefanu","Chrixsaint","agbeone","miimii","Alice619","Lucy12","Murphy08","Solomon","manjosh2020","Maata","Sureboi","Bosslady","Tarikris","ADEPOJU","Owonifari887","tommex","Badruabdul","Evergreen234","Jomlivia","Babz1234","OBAKO","Angelaa","Daniel24352","HOSSYDAN","Nisatron","kareem01","Okuns","Hart","eisteinralph","Goddytex","Sirraj","IN","amikabebi","EDUBEST","Rose","Sweetsammy","Okeke2019","Foluke015","Agbo","Sybil90","Neves","debbyposi","Fortune","Optimusprime","Tpraise","Fayaeru","morena","loveth2019","jokemama","SIRCHUKS","uncleife","Morprecious","Ejesijumbo","Icekid007","KenD","Adeoye50","Abiodunolu","Promizy","Ezekwenna","Amire83","Aremo1","Nazachi","Banky","Irechukwu","deeper","DamiKings","SIKAY","Oojosamson","Joyous","Emmyscott","nelson2","Maduka","maprince","doyin77","Mickeyjay525","Henry2012","Olufissy","Amaka86","Chukwubuikem","Olaomopo","Shrek","Omoba","ron","Layinka","Isah","Oluwasogo42","Olayimika","Nkem","metatrader4","mamiejay","Annielove","Ify930","Ngo22","okoye","sarahokedu","Zipamoh22","Saviour","Teeone68","Emmadox","Rebo1","Shineroz","Paulosagie","emenikeu","Blackglod","Femmi","Ejike13","Diamond28","Suleiman445","Onabajofun34","kaakaa","Sir_imd01","Prityliz","Blessingo","Thomas10","pauli","Airzra","Justice94","Evelyn","Lily","Osa590","Blued2019","babafm","Nobility1996","Lamidi1","Omolola87","Kelz","Tiwatope17","Chukas","Rita12345","Ify","Fregene","Bestie28","BabaFrankie","OKUNUGA","Oyebisi","Obyrich","Ohazjenny","KingLucky","Sarablizzy","temitelecom","Superwhales","Darianazie","Ogochukwu2","pascalino44","Skill4real","ExcelExcel","jattolee","ThankGod01","Abodunrin1","jummypweety","Distinct01","Lumilumi","Riches2000","Tochi2019","olubaby","Ng2019","Emmyginger","Fadayomi","ru4life","islamiat","Nazzy","Ademola1656","idris","Jessica1","Grace2019","Mag","Temmywumite","Loveth72","Abani","Osenium","stellabeauty","ADEYINKA","Tahir001","Kingso","Mustapha","Beebee","Ladis","Gudman8","emmy4ril2k5","Ola111","Kingjude","Surelove","buki27","Harkeju","Oyedokun","Patience","Azeezat","Oriji","Nadia19","Ade19","GODBLESS1","Cahyorde","teatea","Ceciliak","Udokay","Okeowo2019","Godwin01","Segment","toyin1","Bosede79","Funmi74","Oche","alfanla","Jendor1990","Benray","Wave","Paulsayme93","Ogoking","Festus105","Valerie4","MaryV2","Kellydynamic","Greatdanno","Wisdom22","Femrant","Motunrayo","John8","Kunlesh","Holyson","Mron","tundexysam","Oseghale123","Osejoy","mich2009","Lynkaris","Abigail","akinbola","Amaka","Samso","Dotken","Ibrocini","Sogbe","jennifer","Joepraise","Amakaobim","zenith","Rhoda1","timileyin1","Abiomatus","viclaw","Ng22","Starlight","OZ","Oghenerume","Ify4real","Moabs","Kay4real","Mmesoma","donadex","Mario11823","musa04","Ene","Bolu","kansola","favourIDONGE","Falegan","Juliet","Greatbonus","Jadesolak","Tboizzy","Tofunmi85","Emmabassy","Ositaakigwe","ujulyn","Amy","Alwayssmile","Ebukasamuel","Dauda","Uchenna3010","Royalty","Chatwitslyb","COLLINS","Abugod","Eziejay","Doro","DoubleG246","Eberex","Danielz","Isiono","Chinedu22","anabor","Sylvia","rose01","Bosfat","Happy","Stevejack89","Lilianamans","Rodafel","Ifeanyi","Precious40","RuthEasy","Opelola","OnyiiGod","destiny","Manma","profits1","Yemqueen","Solex1","Ahmed83","Smart33","Donrichard","Hollycisse","peerezy","ogbonna44","Adebayo","Emrys","Simon","Dvgreat","Edroyal","Mega3","Stardol","Chiadika","Loretta22","bjshegzz","Tollybaby","Olarewaju","Preshbaby","Don","nkwontaju","Jojo","paulash","zullybello","laurel","migwilo","dskills","alqaabir2324","Ekene","ebooco12","Hajimutiu","Oluwayemisi","Eroxtion","Eniola","Ikuku","presisi","Abeatrice","Wasiu","Kunlegbemi","Bunmi","albertheo","ARUH","Cherechi","Cockson","XEMDEE","Steve","djwilliams","Bleemma","Salaye1","Adeola","Perrie","Yemgrace","roseesie","Habiolah","Gudguy","Oluwaseyi","mac4y","westpoint77","THEGREAT","Djyoungstar","Olalekan","Gil","Bestty","Ibro1122","LAW","Danosi","Bless","Sewe","Prince14604","lerepaul","Gozie","Emilian2019","honeyisland","charles2019","ORIMOLADE","Enkay","Sungate","Sidophobia","sweetrush","Agbontaen","bonjos","chommy3572","Iyke22","Abayourmmy","Flaky","Baresi4rever","sparkle2198","Blade","oluwamykel","Wolexy17","Felicia505","tunde1","Paschal50","Sirkiloo","Shawty50","Danielson16","Baby","Dagrace","Divine","newness","chichi","Over200","doland");

        if (in_array($username, $people))
        {
            $data = array(
                "user_id" => $user_id,
                "first_bonus_id" => 1,
                "first_bonus_username" => "paul",
                "first_bonus_status" => "1",
                "second_bonus_id" => 1,
                "second_bonus_username" => "paul",
                "second_bonus_status" => "1",
                "username" => $username,
                "amount" => 2500,
                "rem_amount" => 0,
                "date_of_ph" => "2019-04-21",
                "date_of_gh" => "2019-05-06",
                "status" => "4"
            );
            $this->PH->create($data);
        }
        elseif (in_array($username, $people2)){
            $data = array(
                "user_id" => $user_id,
                "first_bonus_id" => 1,
                "first_bonus_username" => "paul",
                "first_bonus_status" => "1",
                "second_bonus_id" => 1,
                "second_bonus_username" => "paul",
                "second_bonus_status" => "1",
                "username" => $username,
                "amount" => 5000,
                "rem_amount" => 0,
                "date_of_ph" => "2019-04-21",
                "date_of_gh" => "2019-05-06",
                "status" => "4"
            );
            $this->PH->create($data);
        }
        else
        {
            return false;
        }

    }
   */

    public function smsactivation(){
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        } else {
            if ($this->input->post()) {
                $this->form_validation->set_rules('smscode', 'SMS Code', 'trim|required|xss_clean');

                if ($this->form_validation->run('user/smsactivation')) {
                    $this->load->model('user_model', 'User');
                    $smscode = $this->input->post('smscode');
                    $username = $this->input->post('username');
                    $pp = $this->User->getByUsername($username,array('phone_verify_code','id'));

                    if ($smscode == $pp->phone_verify_code) {

                        $this->ion_auth->update($pp->id, array(
                                'active' => 1,
                                'phone_verify_count'   => 1
                            )
                        );

                        $this->session->set_flashdata('success', 'Verified Successfully. Procced to login');

                        $data = array(
                            'success' => 'success',
                            'redirect' => array(
                                'url' => site_url('back_office')
                            )
                        );
                    } else {
                        $data = array(
                            'errorElements' => array(
                                'smscode' => '* incorrect'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }
            } else {
                $data = array('error' => 'Invalid entry.');
            }

            echo json_encode($data);
        }
    }

    public function activate($userId, $activation) {
        if(empty($userId)){
            show_404();
        }

        $login            = '';
        $this->data->user = $this->User->getData($userId);
        if ($this->data->user->activation_code != $activation) {

            $this->data->error   = TRUE;
            $this->data->err_msg = "Activation Failed.";
            // generate a resend activation email link.
        } else {
            $m =  $this->User->update($userId, array(
                'activation_code' => NULL,
                'active'          => 1,
                'account_expires' => now() + (intval(FREE_MEMBER_EXPIRE) * CACHE_ONE_DAY)
            ));

            if ($m) {
                $startDate = time();
                $dd = date('Y-m-d H:i', strtotime('+1 day', $startDate));
                $da =  urlencode($dd);

              //  $this->sendsms2($this->data->user->phone,$this->data->user->username,$da,2000);


            }
            $this->EmailQueue->store($this->data->user->email, 'Welcome to '.SITE_NAME, 'emails/auth/welcome', array('username' => $this->data->user->username));

            $this->notify_upline($this->data->user);

            $login = $this->loadPartialView('user/login');
        }

        $this->addJavascript(asset('scripts/forms.js'));
        $this->addStyleSheet('/layout/frontend/assets/css/form.css');
        $this->setLayout('layout/frontend/shell');
        $this->data->content = $this->loadPartialView('user/activated').$login;
        $this->loadView('layout/default', 'Account Activated');
    }

    private function sendsms2($phone,$user,$date,$amount){
        $message =   urlencode("Dear ".$user.",You Missed a donation of NGN".$amount." on tradermoni bcos u are yet to upgrade. You Still Have 24hours more to Upgrade. Ignore this if u have upgraded");

        $jj = "https://smartsmssolutions.com/api/?message=".$message."&to=".$phone."&sender=tradermoni&type=0&routing=3&schedule=$date&token=";

        // $kk =   "http://www.50kobo.com/tools/geturl/Sms.php?username=isaacmomoh2000@gmail.com&password=big50kobo&sender=tradermoni&message=".$message."&sendtime=".$date."&recipients=".$phone."";
        $m =  file_get_contents($jj);
        $s =  json_decode($m);
        return $s;
    }

    private function activesms($phone,$code){
        $message =   urlencode("Your entry digit for tradermoni.net is  $code . Thanks");

        $jj = "https://smartsmssolutions.com/api/?message=".$message."&to=".$phone."&sender=tradermoni&type=0&routing=3&token=";

        // $kk =   "http://www.50kobo.com/tools/geturl/Sms.php?username=isaacmomoh2000@gmail.com&password=big50kobo&sender=tradermoni&message=".$message."&sendtime=".$date."&recipients=".$phone."";
        $m =  file_get_contents($jj);
        $s =  json_decode($m);
        return $s;
    }

    public function unlock_ip($userId, $unlockCode) {

        $login            = '';
        $this->data->user = $this->User->getData($userId, array('email', 'username', 'unlock_ip_code'));
        if ($this->data->user->unlock_ip_code != $unlockCode) {

            $this->data->error   = TRUE;
            $this->data->err_msg = "Unlock IP Failed.";
            // generate a resend activation email link.
        } else {
            $this->User->update($userId, array(
                'unlock_ip_code' => NULL,
            ));
            $this->User->addSetting($userId, 'lock_my_ip', 0);
            $this->EmailQueue->store($this->data->user->email, SITE_NAME.' account unlocked', 'emails/auth/ip_unlocked', array('username' => $this->data->user->username));

            $login = $this->loadPartialView('user/login');
        }

        $this->addJavascript(asset('scripts/forms.js'));
        $this->addStyleSheet('/layout/frontend/assets/css/form.css');
        $this->setLayout('layout/frontend/shell');
        $this->data->content = $this->loadPartialView('user/unlock_ip').$login;
        $this->loadView('layout/default', 'Unlock Account');
    }

    private function notify_upline($referral) {

        // Add referral and notify if set
        $this->load->model('referral_model', 'Referral');

        //$referral = $ref->username;
        $sponsorId = $referral->referrer_id;

        $level     = 1;
        while ($sponsorId > 0 && $level <= intval(CYCLER_DEPTH)) {

            $this->Referral->storeNewReferral($referral->id, $sponsorId, $level);

            $sponsor   = $this->User->getData($sponsorId, array('username', 'email', 'email_settings', 'referrer_id'));
            $setting = $this->User->getSetting($sponsorId, 'email_all_levels', 1);

            if ($level == 1 || intval($setting)) {

                $username = $sponsor->username;
                $this->EmailQueue->store($sponsor->email, 'You have a New Referral', 'emails/referral/new_referral_l1', compact('referral', 'username', 'level'));
            }

            $sponsorId = $sponsor->referrer_id;
            $level++;
        }
    }

    public function invite($code="5fbf03ec0b") {

        if (!$this->isGuest) {
            if ($this->ajax) {
                $data = array(
                    'error' => 'You are logged in.',
                );
                echo json_encode($data);
                return;
            } else {
                redirect('back_office');
            }
        }

        $this->load->model('referral_model', 'Referral');
        if ($this->ajax && $_POST) {


            $this->procRegForm(FALSE, TRUE, TRUE);

        } else {

            $this->setLayout('layout/frontend/shell');

            if ($invite = $this->Referral->getInvite($code)) {

                $this->session->set_userdata('invite', $invite->id);


                if ($invite->sponsor_user_id) {
                    $this->data->sponsor =  $this->User->getData($invite->sponsor_user_id);

                } else {
                    $this->data->sponsor = $this->User->getData($invite->user_id);
                }

                $clickId = $this->Referral->recordClick($this->data->sponsor->id, $this->uri->uri_string());

                $cookie = array(
                    'name'   => 'ref',
                    'value'  => $clickId,
                    'expire' => CACHE_ONE_HOUR
                );

                $this->input->set_cookie($cookie);

                if ($this->data->sponsor->account_level == 0) {
                    //$rand = mt_rand(1, 2);
                    $default = DEFAULT_USER_ID;

                    $this->data->sponsor = $this->User->getData($default);
                }

                $refCount = $this->Referral->countReferrals($this->data->sponsor->id. TRUE, TRUE);
                $spillOff = intval($this->User->getSetting($this->data->sponsor->id, 'spill_off', 0));

                $maxRefs = ($this->data->sponsor->account_level >= SPILL_OPTION_LEVEL && $spillOff == 1) ? MAX_REFERRALS : CYCLER_WIDTH;

                if ($refCount >= $maxRefs) {
                    $this->data->origSponsor = $this->data->sponsor;

                    $referrerId = $this->spill($this->data->sponsor->id);
                    $this->data->sponsor = $this->User->getData($referrerId);
                }

                $this->data->first_name = $invite->first_name;
                $this->data->last_name = $invite->last_name;
                $this->data->email = $invite->email;


                $this->addJavascript(asset('scripts/forms.js'));

                $this->addJavascript(asset('scripts/generic.js'));
                $this->addStylesheet(asset('bootstrap/css/forms.css'));

                if (REGISTER_FIELD_COUNTRY) {
                    $this->data->country = ''; //$this->User->getCountry($this->input->ip_address());
                }

                $this->data->content = $this->loadPartialView('user/register');
                $this->loadView('layout/default', SITE_NAME.'- Open Account');

            } else {
                $this->data->message = "Invalid invitation.";
                $this->data->content = $this->loadPartialView('partial/error');
                $this->loadView('layout/default', SITE_NAME.'- Error');
            }
        }
    }

    public function forgot_password() {
        if ($this->ajax && $this->input->post()) {
            $data = NULL;
            if ($this->form_validation->run('user/forgot_password')) {
                $post = $this->input->post();

                $user = $this->ion_auth->where('email', $post['email'])
                    ->select('username')
                    ->users()->row();
                if ($user) {
                    if ($this->ion_auth->forgotten_password($user->username)) {
                        $message = 'We just sent you an email with a link to reset your password!';
                        $data = array('html' => $this->loadPartialView('partial/success', compact('message')));
                    } else {
                        $data = array(
                            'error' => $this->ion_auth->errors()
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => array(
                            'email' => '* Not found.'
                        )
                    );
                }
            } else {
                $data = array(
                    'errorElements' => $this->form_validation->error_array()
                );
            }

            echo json_encode($data);
        } else {
//            $this->session->set_userdata(array('rand1' => rand(1, 10), 'rand2' => rand(1, 10)));

//            $this->data->salt = random_string().'_'.now();
//            $this->session->set_userdata('salt', $this->data->salt);

            $this->data->content = $this->loadPartialView('user/forgot_password');
            if ($this->ajax) {
                echo $this->data->content;
            } else {
                $this->addJavascript(asset('scripts/forms.js'));
                $this->addStyleSheet('/layout/frontend/assets/css/form.css');
                $this->setLayout('layout/frontend/shell');
                $this->loadView('layout/default', 'Forgot Password');
            }
        }
    }

    public function resend() {
        if ($this->ajax && $this->input->post()) {
            $data = NULL;
            if ($this->form_validation->run('user/forgot_password')) {
                $post = $this->input->post();

                $user = $this->ion_auth->where('email', $post['email'])
                    ->select('id, username, email, activation_code')
                    ->users()->row();
                if ($user) {
                    if (!is_null($user->activation_code)) {
                        $this->EmailQueue->store($user->email, 'Activate your account', 'emails/auth/resend', compact('user'), 10);

                        $data = array(
                            'html' => 'We just sent you an email with a link to activate your account!'
                        );
                    } else {

                        $data = array(
                            'error' => 'That account is active. Send a '.anchor(SITE_ADDRESS.'support', 'support ticket').' if you need assistance.'
                        );
                    }
                } else {
                    $data = array(
                        'error' => 'There is no account with that email. Send a '.anchor(SITE_ADDRESS.'support', 'support ticket').' if you need assistance.'
                    );
                }
            } else {
                $data = array(
                    'errorElements' => $this->form_validation->error_array()
                );
            }

            echo json_encode($data);
        } else {
//            $this->session->set_userdata(array('rand1' => rand(1, 10), 'rand2' => rand(1, 10)));
//
//            $this->data->salt = random_string().'_'.now();
//            $this->session->set_userdata('salt', $this->data->salt);

            $this->data->content = $this->loadPartialView('user/resend_activation');

            if ($this->ajax) {
                echo $this->data->content;
            } else {
                $this->addJavascript(asset('scripts/forms.js'));
                $this->addStyleSheet('/layout/frontend/assets/css/form.css');
                $this->setLayout('layout/frontend/shell');
                $this->loadView('layout/default', 'Forgot Password');
            }
        }
    }

    public function reset_password($code) {
        if ($this->ion_auth->forgotten_password_complete($code))
            $this->session->set_flashdata('success', $this->ion_auth->messages());
        else
            $this->session->set_flashdata('error', $this->ion_auth->errors());

        redirect('user/login');
    }

    public function user_check($param) {
        $banned = array('www', 'dev', 'cdn', 'support', 'admin');

        if (in_array($param, $banned)) {
            $this->form_validation->set_message('user_check', '* banned username');
            return FALSE;
        }
        if ($this->ion_auth->username_check($param)) {
            $this->form_validation->set_message('user_check', '* already registered');
            return FALSE;
        } else if (!preg_match('/^[\w\-]+$/i', $param)) {
            $this->form_validation->set_message('user_check', '* only alpha-numerical characters');
            return FALSE;
        }

        return TRUE;
    }

    public function email_check($param) {
        if ($user = $this->ion_auth->email_check($param)) {
            if (!$this->ion_auth->logged_in() || $user->id != $this->session->userdata('id')) {
                $this->form_validation->set_message('email_check', '* already in use');
                return FALSE;
            }
        }

        return TRUE;
    }

    function valid_date() {
        if (!checkdate($this->input->post('month'), $this->input->post('day'), $this->input->post('year'))) {
            $this->form_validation->set_message('valid_date', '* invalid');
            return FALSE;
        }
        return TRUE;
    }

    function valid_sum() {
        return TRUE;
        if (intval($this->session->userdata('rand1')) + intval($this->session->userdata('rand2')) != intval($this->input->post('sum'))) {
            $dataDiv = 'turingTest'; //(isset($_POST['remember'])) ? 'loginTuring' : 'registerTuring';
            $this->form_validation->set_message('valid_sum', '* invalid - <a href="'.SITE_ADDRESS.'user/reset_turing" class="replaceClass" data-div="'.$dataDiv.'">Click here for new numbers</a>');
            return FALSE;
        }
    }

    public function save_user_login_ip() {
        $ci = get_instance();
        $ci->load->model('user_model', 'User');

        $ci->User->storeLogin();
    }

    public function change_secret() {
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        } else {
            if ($this->input->post()) {
                if ($this->form_validation->run('user/change_secret')) {
                    $password = $this->input->post('passwd');
                    if (sha1($password.$this->profile->salt) == $this->profile->password) {

                        $this->ion_auth->update($this->userId, array(
                                'secret_question' => $this->input->post('secret_question'),
                                'secret_answer'   => $this->input->post('secret_answer'))
                        );

                        $this->load->model('user_model', 'User');
                        $this->User->storeFieldChange($this->userId, 'secret', 'xxx', 'xxx');
                        $this->session->set_flashdata('success', 'Secret updated');

                        $data = array(
                            'success'  => 'success',
                            'redirect' => 'reload'
                        );
                    } else {
                        $data = array(
                            'errorElements' => array(
                                'passwd' => '* incorrect'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }
            } else {
                $data = array('error' => 'Invalid entry.');
            }

            echo json_encode($data);
        }
    }

    //Set password of user
    public function change_password() { //AJAX file, set user email notifications
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        } else {
            if ($this->input->post()) {
                if ($this->form_validation->run('user/change_password')) {
                    if ($this->input->post('secret_answer') == $this->profile->secret_answer) {
                        $oldPassword = $this->input->post('oldpass');
                        $newPassword = $this->input->post('password');

                        $user = $this->ion_auth->select('id, email, username')->user()->row();

                        if ($this->ion_auth->change_password($user->username, $oldPassword, $newPassword) === TRUE) {
                            // We don't want to save the actual passwords but an indication it has been changed
                            $this->load->model('user_model', 'User');
                            $this->User->storeFieldChange($user->id, 'password', 'xxx', 'xxx');

                            $this->session->set_flashdata('success', 'Password updated');

                            $data = array(
                                'success'  => 'success',
                                'redirect' => 'reload'
                            );
                        } else {
                            $data = array(
                                'errorElements' => array(
                                    'oldpass' => '* incorrect'
                                )
                            );
                        }
                    } else {
                        $data = array(
                            'errorElements' => array(
                                'secret_answer' => '* incorrect'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
            } else {
                echo json_encode(array('error' => 'Invalid entry.'));
            }
        }
    }

    public function change_email($hash = '', $email = '', $user_id = NULL) { //AJAX file, set user email
        if (!$this->ajax) {

            $email = base64_decode($email);

            $user = $this->ion_auth->select('id, email')->user()->row();

            if( $this->User->isChangeEmailLinkValid($hash, $user_id) ) {
                if ($this->ion_auth->update($user->id, array('email' => $email, 'email_change_code' => NULL)) === TRUE) {
                    $this->load->model('user_model', 'User');
                    $this->User->storeFieldChange($user->id, 'email', $user->email, $email);

                    $this->session->set_flashdata('success', 'Email address updated');

                    //echo 'email changed';
                    redirect('back_office/profile');
                }
            } else {
                //echo 'wrong link';
                $this->session->set_flashdata('error', 'Wrong e-mail change link');

                redirect('back_office/profile');
            }

        } else {
            $user = $this->ion_auth->select('id, email, username')->user()->row();

            if ($this->input->post()) {
                if ($this->form_validation->run('user/change_email')) {
                    $email = strtolower($this->input->post('email'));

                    if ($this->ion_auth->email_check($email) === FALSE) { //If true, mail is already stored, not change it
                        //$this->load->model('user_model', 'User');

                        $data = NULL;

                        $email_change_code = sha1(microtime().$email);
                        $this->ion_auth->update($user->id, array('email_change_code' => $email_change_code));

                        $data = array(
                            'username'          => $user->username,
                            'email_change_code' => $email_change_code,
                            'email' => base64_encode($email),
                            'user_id' => $user->id,
                        );




                        $this->EmailQueue->store($email, 'Account E-mail Change', 'emails/user/email_change_link', $data, 10);

                        $this->session->set_flashdata('success', 'Confirmation e-mail sent.');

                        $data = array(
                            'success'  => 'success',
                            'redirect' => 'reload'
                        );

                    } else {
                        $data = array(
                            'errorElements' => array(
                                'email' => '* already in use'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
            } else {
                echo json_encode(array('error' => 'Invalid entry.'));
            }
        }
    }

    public function change_address() {
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        } else {
            $this->load->model('user_model', 'User');

            if ($this->input->post()) {
                if ($this->form_validation->run('user/change_address')) {
                    $address = $this->input->post('address');
                    $city = $this->input->post('city');
                    $state = $this->input->post('state');
                    $postal_code = $this->input->post('postal_code');
                    $user       = $this->profile;

                    $data = NULL;
                    if ($this->ion_auth->update($this->profile->id, array('address' => $address, 'city' => $city, 'state' => $state, 'postal_code' => $postal_code)) === TRUE) {
                        $this->session->set_flashdata('success', 'Address updated');

                        $data = array(
                            'success'  => 'success',
                            'redirect' => 'reload'
                        );
                    } else {
                        $data = array(
                            'errorElements' => array(
                                'country' => '* error'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
            } else {
                echo $this->loadPartialView('member/my_account/partial/change_address');
            }
        }
    }

    public function change_names() {
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        } else {
            $this->load->model('user_model', 'User');

            if ($this->input->post()) {
                if ($this->form_validation->run('user/change_names')) {
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $user       = $this->profile;

                    $data = NULL;
                    if ($this->ion_auth->update($this->profile->id, array('first_name' => $first_name, 'last_name' => $last_name)) === TRUE) {
                        $this->session->set_flashdata('success', 'Names updated');

                        $data = array(
                            'success'  => 'success',
                            'redirect' => 'reload'
                        );
                    } else {
                        $data = array(
                            'errorElements' => array(
                                'country' => '* error'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
            } else {
                echo $this->loadPartialView('member/my_account/partial/change_names');
            }
        }
    }

    public function change_phone() {
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        } else {
            $this->load->model('user_model', 'User');

            if ($this->input->post()) {
                if ($this->form_validation->run('user/change_phone')) {
                    $phone = $this->input->post('phone');
                    $user       = $this->profile;

                    $data = NULL;
                    if ($this->ion_auth->update($this->profile->id, array('phone' => $phone)) === TRUE) {
                        $this->session->set_flashdata('success', 'Phone updated');

                        $data = array(
                            'success'  => 'success',
                            'redirect' => 'reload'
                        );
                    } else {
                        $data = array(
                            'errorElements' => array(
                                'country' => '* error'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
            } else {
                echo $this->loadPartialView('member/my_account/partial/change_phone');
            }
        }
    }

    public function change_country() {
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        } else {
            $this->load->model('user_model', 'User');


            $country    = $this->picklist->select_value('country_list', $this->profile->country_id);
            $country_id = $this->profile->country_id;

            if ($this->input->post()) {
                if ($this->form_validation->run('user/change_country')) {
                    $country_id = $this->input->post('country');
                    $user       = $this->profile;

                    $data = NULL;
                    if ($country_id == $this->profile->country_id) {
                        $data = array(
                            'errorElements' => array(
                                'country' => '* unchanged'
                            )
                        );
                    } else if ($this->ion_auth->update($this->profile->id, array('country_id' => $country_id)) === TRUE) {
                        $this->load->model('user_model', 'User');
                        $this->User->storeFieldChange($this->profile->id, 'country_id', $user->country_id, $country_id);

                        $this->session->set_flashdata('success', 'Country updated');

                        $data = array(
                            'success'  => 'success',
                            'redirect' => 'reload'
                        );
                    } else {
                        $data = array(
                            'errorElements' => array(
                                'country' => '* error'
                            )
                        );
                    }
                } else {
                    $data = array(
                        'errorElements' => $this->form_validation->error_array()
                    );
                }

                echo json_encode($data);
            } else {
                $countries = $this->picklist->select_values('country_list');
                echo $this->loadPartialView('member/my_account/partial/change_country', compact('country', 'countries', 'country_id'));
            }
        }
    }

    public function email_settings() {
        if (!$this->ajax) {
            show_404(); // prevents direct access to this function - Request has to be AJAX
        }

        $user     = $this->ion_auth->select('id, email_settings')->user()->row();
        $settings = $this->input->post('settings');

        // Just make sure the data passed to us is proper and set a default
        if (intval($settings) < 0 || intval($settings) > EMAIL_ALL)
            $settings = EMAIL_ALL;

        if ($this->ion_auth->update($user->id, array('email_settings' => $settings))) {
            $this->load->model('user_model', 'User');
            $this->User->storeFieldChange($user->id, 'email_settings', $user->email_settings, $settings);

            $data = array(
                'success' => '<img src="'.asset('images/icons/ok.png').'" title="Success!" /> Email Settings updated!',
            );
        } else {
            $data = array(
                'error' => 'An unknown error has occurred'
            );
        }

        echo json_encode($data);
    }
}
