<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CAdminSettingsPermalinks extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('permalinks'):
                    // calling the permalinks view
                    $htaccess = Params::getParam('htaccess_status');
                    $file     = Params::getParam('file_status');

                    $this->_exportVariableToView('htaccess', $htaccess);
                    $this->_exportVariableToView('file', $file);

                    $this->doView('settings/permalinks.php');
                break;
                case('permalinks_post'):
                    // updating permalinks option
                    osc_csrf_check();
                    $htaccess_file  = osc_base_path() . '.htaccess';
                    $rewriteEnabled = (Params::getParam('rewrite_enabled') ? true : false);

                    $rewrite_base = REL_WEB_URL;
                    $htaccess     = <<<HTACCESS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$rewrite_base}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$rewrite_base}index.php [L]
</IfModule>

# 7G Firewall:[CORE]
ServerSignature Off
Options -Indexes

# 7G Firewall:[QUERY STRING]
<IfModule mod_rewrite.c>

	RewriteCond %{QUERY_STRING} ([a-z0-9]{2000,}) [NC,OR]
	RewriteCond %{QUERY_STRING} (/|%2f)(:|%3a)(/|%2f) [NC,OR]
	RewriteCond %{QUERY_STRING} (order(\s|%20)by(\s|%20)1--) [NC,OR]
	RewriteCond %{QUERY_STRING} (/|%2f)(\*|%2a)(\*|%2a)(/|%2f) [NC,OR]
	RewriteCond %{QUERY_STRING} (`|<|>|\^|\|\\|0x00|%00|%0d%0a) [NC,OR]
	RewriteCond %{QUERY_STRING} (ckfinder|fck|fckeditor|fullclick) [NC,OR]
	RewriteCond %{QUERY_STRING} ((.*)header:|(.*)set-cookie:(.*)=) [NC,OR]
	RewriteCond %{QUERY_STRING} (cmd|command)(=|%3d)(chdir|mkdir)(.*)(x20) [NC,OR]
	RewriteCond %{QUERY_STRING} .*mosConfig.* [OR]
	RewriteCond %{QUERY_STRING} (/|%2f)((wp-)?config)((\.|%2e)inc)?((\.|%2e)php) [NC,OR]
	RewriteCond %{QUERY_STRING} (thumbs?(_editor|open)?|tim(thumbs?)?)((\.|%2e)php) [NC,OR]
	RewriteCond %{QUERY_STRING} (absolute_|base|root_)(dir|path)(=|%3d)(ftp|https?) [NC,OR]
	RewriteCond %{QUERY_STRING} (localhost|loopback|127(\.|%2e)0(\.|%2e)0(\.|%2e)1) [NC,OR]
	RewriteCond %{QUERY_STRING} (s)?(ftp|inurl|php)(s)?(:(/|%2f|%u2215)(/|%2f|%u2215)) [NC,OR]
	RewriteCond %{QUERY_STRING} (\.|20)(get|the)(_|%5f)(permalink|posts_page_url)(\(|%28) [NC,OR]
	RewriteCond %{QUERY_STRING} ((boot|win)((\.|%2e)ini)|etc(/|%2f)passwd|self(/|%2f)environ) [NC,OR]
	RewriteCond %{QUERY_STRING} (((/|%2f){3,3})|((\.|%2e){3,3})|((\.|%2e){2,2})(/|%2f|%u2215)) [NC,OR]
	RewriteCond %{QUERY_STRING} (benchmark|char|exec|fopen|function|html)(.*)(\(|%28)(.*)(\)|%29) [NC,OR]
	RewriteCond %{QUERY_STRING} (php)([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}) [NC,OR]
	RewriteCond %{QUERY_STRING} (e|%65|%45)(v|%76|%56)(a|%61|%31)(l|%6c|%4c)(.*)(\(|%28)(.*)(\)|%29) [NC,OR]
	RewriteCond %{QUERY_STRING} (/|%2f)(=|%3d|$&|_mm|cgi(\.|-)|inurl(:|%3a)(/|%2f)|(mod|path)(=|%3d)(\.|%2e)) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(e|%65|%45)(m|%6d|%4d)(b|%62|%42)(e|%65|%45)(d|%64|%44)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(i|%69|%49)(f|%66|%46)(r|%72|%52)(a|%61|%41)(m|%6d|%4d)(e|%65|%45)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(o|%4f|%6f)(b|%62|%42)(j|%4a|%6a)(e|%65|%45)(c|%63|%43)(t|%74|%54)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (<|%3c)(.*)(s|%73|%53)(c|%63|%43)(r|%72|%52)(i|%69|%49)(p|%70|%50)(t|%74|%54)(.*)(>|%3e) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(d|%64|%44)(e|%65|%45)(l|%6c|%4c)(e|%65|%45)(t|%74|%54)(e|%65|%45)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(i|%69|%49)(n|%6e|%4e)(s|%73|%53)(e|%65|%45)(r|%72|%52)(t|%74|%54)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(s|%73|%53)(e|%65|%45)(l|%6c|%4c)(e|%65|%45)(c|%63|%43)(t|%74|%54)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\+|%2b|%20)(u|%75|%55)(p|%70|%50)(d|%64|%44)(a|%61|%41)(t|%74|%54)(e|%65|%45)(\+|%2b|%20) [NC,OR]
	RewriteCond %{QUERY_STRING} (\\x00|(\"|%22|\'|%27)?0(\"|%22|\'|%27)?(=|%3d)(\"|%22|\'|%27)?0|cast(\(|%28)0x|or%201(=|%3d)1) [NC,OR]
	RewriteCond %{QUERY_STRING} (g|%67|%47)(l|%6c|%4c)(o|%6f|%4f)(b|%62|%42)(a|%61|%41)(l|%6c|%4c)(s|%73|%53)(=|\[|%[0-9A-Z]{0,2}) [NC,OR]
	RewriteCond %{QUERY_STRING} (_|%5f)(r|%72|%52)(e|%65|%45)(q|%71|%51)(u|%75|%55)(e|%65|%45)(s|%73|%53)(t|%74|%54)(=|\[|%[0-9A-Z]{2,}) [NC,OR]
	RewriteCond %{QUERY_STRING} (j|%6a|%4a)(a|%61|%41)(v|%76|%56)(a|%61|%31)(s|%73|%53)(c|%63|%43)(r|%72|%52)(i|%69|%49)(p|%70|%50)(t|%74|%54)(:|%3a)(.*)(;|%3b|\)|%29) [NC,OR]
	RewriteCond %{QUERY_STRING} (b|%62|%42)(a|%61|%41)(s|%73|%53)(e|%65|%45)(6|%36)(4|%34)(_|%5f)(e|%65|%45|d|%64|%44)(e|%65|%45|n|%6e|%4e)(c|%63|%43)(o|%6f|%4f)(d|%64|%44)(e|%65|%45)(.*)(\()(.*)(\)) [NC,OR]
	RewriteCond %{QUERY_STRING} (@copy|\$_(files|get|post)|allow_url_(fopen|include)|auto_prepend_file|blexbot|browsersploit|(c99|php)shell|curl(_exec|test)|disable_functions?|document_root|elastix|encodeuricom|exploit|fclose|fgets|file_put_contents|fputs|fsbuff|fsockopen|gethostbyname|grablogin|hmei7|input_file|null|open_basedir|outfile|passthru|phpinfo|popen|proc_open|quickbrute|remoteview|root_path|safe_mode|shell_exec|site((.){0,2})copier|sux0r|trojan|user_func_array|wget|xertive) [NC,OR]
	RewriteCond %{QUERY_STRING} (;|<|>|\'|\"|\)|%0a|%0d|%22|%27|%3c|%3e|%00)(.*)(/\*|alter|base64|benchmark|cast|concat|convert|create|encode|declare|delete|drop|insert|md5|request|script|select|set|union|update) [NC,OR]
	RewriteCond %{QUERY_STRING} ((\+|%2b)(concat|delete|get|select|union)(\+|%2b)) [NC,OR]
	RewriteCond %{QUERY_STRING} (union)(.*)(select)(.*)(\(|%28) [NC,OR]
	RewriteCond %{QUERY_STRING} (concat|eval)(.*)(\(|%28) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_QUERY_STRING:%1___%2___%3]

</IfModule>

# 7G Firewall:[REQUEST URI]
<IfModule mod_rewrite.c>

	RewriteCond %{REQUEST_URI} (\^|`|<|>|\|\|) [NC,OR]
	RewriteCond %{REQUEST_URI} ([a-z0-9]{2000,}) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(\*|\"|\'|\.|,|&|&amp;?)/?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (\.)(php)(\()?([0-9]+)(\))?(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(vbulletin|boards|vbforum)(/)? [NC,OR]
	RewriteCond %{REQUEST_URI} /((.*)header:|(.*)set-cookie:(.*)=) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(ckfinder|fck|fckeditor|fullclick) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.(s?ftp-?)config|(s?ftp-?)config\.) [NC,OR]
	RewriteCond %{REQUEST_URI} (\{0\}|\"?0\"?=\"?0|\(/\(|\.\.\.|\+\+\+|\\") [NC,OR]
	RewriteCond %{REQUEST_URI} (thumbs?(_editor|open)?|tim(thumbs?)?)(\.php) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.|20)(get|the)(_)(permalink|posts_page_url)(\() [NC,OR]
	RewriteCond %{REQUEST_URI} (///|\?\?|/&&|/\*(.*)\*/|/:/|\\|0x00|%00|%0d%0a) [NC,OR]
	RewriteCond %{REQUEST_URI} (/%7e)(root|ftp|bin|nobody|named|guest|logs|sshd)(/) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(etc|var)(/)(hidden|secret|shadow|ninja|passwd|tmp)(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (s)?(ftp|http|inurl|php)(s)?(:(/|%2f|%u2215)(/|%2f|%u2215)) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.)(ds_store|htaccess|htpasswd|init?|mysql-select-db)(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(bin)(/)(cc|chmod|chsh|cpp|echo|id|kill|mail|nasm|perl|ping|ps|python|tclsh)(/)?$ [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(::[0-9999]|%3a%3a[0-9999]|127\.0\.0\.1|localhost|loopback|makefile|pingserver|wwwroot)(/)? [NC,OR]
	RewriteCond %{REQUEST_URI} (/)?j((\s)+)?a((\s)+)?v((\s)+)?a((\s)+)?s((\s)+)?c((\s)+)?r((\s)+)?i((\s)+)?p((\s)+)?t((\s)+)?(%3a|:) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(awstats|(c99|php|web)shell|document_root|error_log|listinfo|muieblack|remoteview|site((.){0,2})copier|sqlpatch|sux0r) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)((php|web)?shell|crossdomain|fileditor|locus7|nstview|php(get|remoteview|writer)|r57|remview|sshphp|storm7|webadmin)(.*)(\.|\() [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(author-panel|bitrix|class|database|(db|mysql)-?admin|filemanager|htdocs|httpdocs|https?|mailman|mailto|msoffice|mysql|_?php-my-admin(.*)|tmp|undefined|usage|var|vhosts|webmaster|www)(/) [NC,OR]
	RewriteCond %{REQUEST_URI} (base64_(en|de)code|benchmark|child_terminate|curl_exec|e?chr|eval|function|fwrite|(f|p)open|html|leak|passthru|p?fsockopen|phpinfo|posix_(kill|mkfifo|setpgid|setsid|setuid)|proc_(close|get_status|nice|open|terminate)|(shell_)?exec|system)(.*)(\()(.*)(\)) [NC,OR]
	RewriteCond %{REQUEST_URI} (/)(^$|00.temp00|0day|3index|3xp|70bex?|admin_events|bkht|(php|web)?shell|c99|config(\.)?bak|curltest|db|dompdf|filenetworks|hmei7|index\.php/index\.php/index|jahat|kcrew|keywordspy|libsoft|marg|mobiquo|mysql|nessus|php-?info|racrew|sql|vuln|(web-?|wp-)?(conf\b|config(uration)?)|xertive)(\.php) [NC,OR]
	RewriteCond %{REQUEST_URI} (\.)(7z|ab4|ace|afm|ashx|aspx?|bash|ba?k?|bin|bz2|cfg|cfml?|cgi|conf\b|config|ctl|dat|db|dist|dll|eml|engine|env|et2|exe|fec|fla|git|hg|inc|ini|inv|jsp|log|lqd|make|mbf|mdb|mmw|mny|module|old|one|orig|out|passwd|pdb|phtml|pl|profile|psd|pst|ptdb|pwd|py|qbb|qdf|rar|rdf|save|sdb|sql|sh|soa|svn|swf|swl|swo|swp|stx|tar|tax|tgz|theme|tls|tmd|wow|xtmpl|ya?ml|zlib)$ [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_REQUEST_URI:%1___%2___%3]

</IfModule>

# 7G Firewall:[USER AGENT]
<IfModule mod_rewrite.c>

	RewriteCond %{HTTP_USER_AGENT} ([a-z0-9]{2000,}) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (&lt;|%0a|%0d|%27|%3c|%3e|%00|0x00) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (alexibot|majestic|mj12bot|rogerbot) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} ((c99|php|web)shell|remoteview|site((.){0,2})copier) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (econtext|eolasbot|eventures|liebaofast|nominet|oppo\sa33) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (base64_decode|bin/bash|disconnect|eval|lwp-download|unserialize|\\\x22) [NC,OR]
	RewriteCond %{HTTP_USER_AGENT} (acapbot|acoonbot|asterias|attackbot|backdorbot|becomebot|binlar|blackwidow|blekkobot|blexbot|blowfish|bullseye|bunnys|butterfly|careerbot|casper|checkpriv|cheesebot|cherrypick|chinaclaw|choppy|clshttp|cmsworld|copernic|copyrightcheck|cosmos|crescent|cy_cho|datacha|demon|diavol|discobot|dittospyder|dotbot|dotnetdotcom|dumbot|emailcollector|emailsiphon|emailwolf|extract|eyenetie|feedfinder|flaming|flashget|flicky|foobot|g00g1e|getright|gigabot|go-ahead-got|gozilla|grabnet|grafula|harvest|heritrix|httrack|icarus6j|jetbot|jetcar|jikespider|kmccrew|leechftp|libweb|linkextractor|linkscan|linkwalker|loader|masscan|miner|mechanize|morfeus|moveoverbot|netmechanic|netspider|nicerspro|nikto|ninja|nutch|octopus|pagegrabber|petalbot|planetwork|postrank|proximic|purebot|pycurl|python|queryn|queryseeker|radian6|radiation|realdownload|scooter|seekerspider|semalt|siclab|sindice|sistrix|sitebot|siteexplorer|sitesnagger|skygrid|smartdownload|snoopy|sosospider|spankbot|spbot|sqlmap|stackrambler|stripper|sucker|surftbot|sux0r|suzukacz|suzuran|takeout|teleport|telesoft|true_robots|turingos|turnit|vampire|vikspider|voideye|webleacher|webreaper|webstripper|webvac|webviewer|webwhacker|winhttp|wwwoffle|woxbot|xaldon|xxxyy|yamanalab|yioopbot|youda|zeus|zmeu|zune|zyborg) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_USER_AGENT:%1]

</IfModule>

# 7G Firewall:[REMOTE HOST]
<IfModule mod_rewrite.c>

	RewriteCond %{REMOTE_HOST} (163data|amazonaws|colocrossing|crimea|g00g1e|justhost|kanagawa|loopia|masterhost|onlinehome|poneytel|sprintdatacenter|reverse.softlayer|safenet|ttnet|woodpecker|wowrack) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_REMOTE_HOST:%1]

</IfModule>

# 7G Firewall:[HTTP REFERRER]
<IfModule mod_rewrite.c>

	RewriteCond %{HTTP_REFERER} (semalt.com|todaperfeita) [NC,OR]
	RewriteCond %{HTTP_REFERER} (order(\s|%20)by(\s|%20)1--) [NC,OR]
	RewriteCond %{HTTP_REFERER} (blue\spill|cocaine|ejaculat|erectile|erections|hoodia|huronriveracres|impotence|levitra|libido|lipitor|phentermin|pro[sz]ac|sandyauer|tramadol|troyhamby|ultram|unicauca|valium|viagra|vicodin|xanax|ypxaieo) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_HTTP_REFERRER:%1]

</IfModule>

# 7G Firewall:[REQUEST METHOD]
<IfModule mod_rewrite.c>

	RewriteCond %{REQUEST_METHOD} ^(connect|debug|move|trace|track) [NC]

	RewriteRule .* - [F,L]

	# RewriteRule .* /7G_log.php?log [END,NE,E=7G_REQUEST_METHOD:%1]

</IfModule>

# 7G Addon: Stop Aggressive Scanning for Uploads-Related Targets
<IfModule mod_rewrite.c>

	# RewriteCond %{REQUEST_URI} /php(unit)?/ [NC,OR]
	RewriteCond %{REQUEST_URI} \.(aspx?|env|git(ignore)?|phtml|rar) [NC,OR]
	RewriteCond %{REQUEST_URI} /(cms|control_panel|home_url=|lr-admin|manager|panel|staff|webadmin) [NC,OR]
	RewriteCond %{REQUEST_URI} /(adm(in)?|controlpanel|magento(-1|web)?|mg|onli(n|k)e|tmplconnector|uxm|web?store)/ [NC,OR]

	RewriteCond %{REQUEST_URI} (_timthumb_|timthumb.php) [NC,OR]
	RewriteCond %{REQUEST_URI} /(install|wp-config|xmlrpc)\.php [NC,OR]
	RewriteCond %{REQUEST_URI} /(uploadify|uploadbg|up__uzegp)\.php [NC,OR]
	RewriteCond %{REQUEST_URI} /(comm\.js|mysql-date-function|simplebootadmin|vuln\.htm|www\.root\.) [NC,OR]
	RewriteCond %{REQUEST_URI} /(admin-uploadify|fileupload|jquery-file-upload|upload_file|upload|uploadify|webforms)/ [NC,OR]
	RewriteCond %{REQUEST_URI} /(ajax_pluginconf|apikey|connector(.minimal)?|eval-stdin|f0x|login|router|setup-config|sssp|vuln|xattacker)\.php [NC]

	RewriteRule .* - [F,L]

</IfModule>
HTACCESS;

                    if( $rewriteEnabled ) {
                        osc_set_preference('rewriteEnabled', '1');;

                        // 1. OK (ok)
                        // 2. OK no apache module detected (warning)
                        // 3. No se puede crear + apache
                        // 4. No se puede crear + no apache
                        // 5. .htaccess exists, no overwrite
                        $status = 3;
                        if( file_exists($htaccess_file) ) {
                            $status = 5;
                        } else {
                            if( is_writable(osc_base_path()) && file_put_contents($htaccess_file, $htaccess) ) {
                                $status = 1;
                            }
                        }

                        if( !@apache_mod_loaded('mod_rewrite') ) {
                            $status++;
                        }

                        $errors = 0;
                        $item_url = substr(str_replace('//', '/', Params::getParam('rewrite_item_url').'/'), 0, -1);
                        if(!osc_validate_text($item_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_url', $item_url);;
                        }
                        $page_url = substr(str_replace('//', '/', Params::getParam('rewrite_page_url').'/'), 0, -1);
                        if(!osc_validate_text($page_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_page_url', $page_url);;
                        }
                        $cat_url = substr(str_replace('//', '/', Params::getParam('rewrite_cat_url').'/'), 0, -1);
                        // DEPRECATED: backward compatibility, remove in 3.4
                        $cat_url = str_replace('{CATEGORY_SLUG}', '{CATEGORY_NAME}', $cat_url);
                        if(!osc_validate_text($cat_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_cat_url', $cat_url);;
                        }
                        $search_url = substr(str_replace('//', '/', Params::getParam('rewrite_search_url').'/'), 0, -1);
                        if(!osc_validate_text($search_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_url', $search_url);;
                        }

                        if(!osc_validate_text(Params::getParam('rewrite_search_country'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_country', Params::getParam('rewrite_search_country'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_region'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_region', Params::getParam('rewrite_search_region'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_city'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_city', Params::getParam('rewrite_search_city'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_city_area'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_city_area', Params::getParam('rewrite_search_city_area'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_category'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_category', Params::getParam('rewrite_search_category'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_user'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_user', Params::getParam('rewrite_search_user'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_pattern'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_pattern', Params::getParam('rewrite_search_pattern'));;
                        }

                        $rewrite_contact = substr(str_replace('//', '/', Params::getParam('rewrite_contact').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_contact)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_contact', $rewrite_contact);;
                        }
                        $rewrite_feed = substr(str_replace('//', '/', Params::getParam('rewrite_feed').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_feed)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_feed', $rewrite_feed);;
                        }
                        $rewrite_language = substr(str_replace('//', '/', Params::getParam('rewrite_language').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_language)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_language', $rewrite_language);;
                        }
                        $rewrite_item_mark = substr(str_replace('//', '/', Params::getParam('rewrite_item_mark').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_mark)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_mark', $rewrite_item_mark);;
                        }
                        $rewrite_item_send_friend = substr(str_replace('//', '/', Params::getParam('rewrite_item_send_friend').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_send_friend)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_send_friend', $rewrite_item_send_friend);;
                        }
                        $rewrite_item_contact = substr(str_replace('//', '/', Params::getParam('rewrite_item_contact').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_contact)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_contact', $rewrite_item_contact);;
                        }
                        $rewrite_item_new = substr(str_replace('//', '/', Params::getParam('rewrite_item_new').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_new)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_new', $rewrite_item_new);;
                        }
                        $rewrite_item_activate = substr(str_replace('//', '/', Params::getParam('rewrite_item_activate').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_activate)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_activate', $rewrite_item_activate);;
                        }
                        $rewrite_item_renew = substr(str_replace('//', '/', Params::getParam('rewrite_item_renew').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_renew)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_renew', $rewrite_item_renew);;
                        }
                        $rewrite_item_edit = substr(str_replace('//', '/', Params::getParam('rewrite_item_edit').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_edit)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_edit', $rewrite_item_edit);;
                        }
                        $rewrite_item_delete = substr(str_replace('//', '/', Params::getParam('rewrite_item_delete').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_delete)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_delete', $rewrite_item_delete);;
                        }
                        $rewrite_item_resource_delete = substr(str_replace('//', '/', Params::getParam('rewrite_item_resource_delete').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_resource_delete)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_resource_delete', $rewrite_item_resource_delete);;
                        }
                        $rewrite_user_login = substr(str_replace('//', '/', Params::getParam('rewrite_user_login').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_login)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_login', $rewrite_user_login);;
                        }
                        $rewrite_user_dashboard = substr(str_replace('//', '/', Params::getParam('rewrite_user_dashboard').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_dashboard)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_dashboard', $rewrite_user_dashboard);;
                        }
                        $rewrite_user_logout = substr(str_replace('//', '/', Params::getParam('rewrite_user_logout').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_logout)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_logout', $rewrite_user_logout);;
                        }
                        $rewrite_user_register = substr(str_replace('//', '/', Params::getParam('rewrite_user_register').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_register)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_register', $rewrite_user_register);;
                        }
                        $rewrite_user_activate = substr(str_replace('//', '/', Params::getParam('rewrite_user_activate').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_activate)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_activate', $rewrite_user_activate);;
                        }
                        $rewrite_user_activate_alert = substr(str_replace('//', '/', Params::getParam('rewrite_user_activate_alert').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_activate_alert)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_activate_alert', $rewrite_user_activate_alert);;
                        }
                        $rewrite_user_profile = substr(str_replace('//', '/', Params::getParam('rewrite_user_profile').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_profile)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_profile', $rewrite_user_profile);;
                        }
                        $rewrite_user_items = substr(str_replace('//', '/', Params::getParam('rewrite_user_items').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_items)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_items', $rewrite_user_items);;
                        }
                        $rewrite_user_alerts = substr(str_replace('//', '/', Params::getParam('rewrite_user_alerts').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_alerts)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_alerts', $rewrite_user_alerts);;
                        }
                        $rewrite_user_recover = substr(str_replace('//', '/', Params::getParam('rewrite_user_recover').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_recover)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_recover', $rewrite_user_recover);;
                        }
                        $rewrite_user_forgot = substr(str_replace('//', '/', Params::getParam('rewrite_user_forgot').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_forgot)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_forgot', $rewrite_user_forgot);;
                        }
                        $rewrite_user_change_password = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_password').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_password)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_password', $rewrite_user_change_password);;
                        }
                        $rewrite_user_change_email = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_email').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_email)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_email', $rewrite_user_change_email);;
                        }
                        $rewrite_user_change_username = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_username').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_username)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_username', $rewrite_user_change_username);
                        }
                        $rewrite_user_change_email_confirm = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_email_confirm').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_email_confirm)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_email_confirm', $rewrite_user_change_email_confirm);
                        }

                        osc_reset_preferences();

                        $rewrite = Rewrite::newInstance();
                        osc_run_hook("before_rewrite_rules", array(&$rewrite));
                        $rewrite->clearRules();

                        /*****************************
                         ********* Add rules *********
                         *****************************/

                        // Contact rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_contact').'/?$', 'index.php?page=contact');

                        // Feed rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_feed').'/?$', 'index.php?page=search&sFeed=rss');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_feed').'/(.+)/?$', 'index.php?page=search&sFeed=$1');

                        // Language rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_language').'/(.*?)/?$', 'index.php?page=language&locale=$1');

                        // Search rules
                        $rewrite->addRule('^'.$search_url.'$', 'index.php?page=search');
                        $rewrite->addRule('^'.$search_url.'/(.*)$', 'index.php?page=search&sParams=$1');

                        // Item rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_mark').'/(.*?)/([0-9]+)/?$', 'index.php?page=item&action=mark&as=$1&id=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_send_friend').'/([0-9]+)/?$', 'index.php?page=item&action=send_friend&id=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_contact').'/([0-9]+)/?$', 'index.php?page=item&action=contact&id=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_new').'/?$', 'index.php?page=item&action=item_add');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_new').'/([0-9]+)/?$', 'index.php?page=item&action=item_add&catId=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_activate').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=activate&id=$1&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_renew').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=renew&id=$1&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_edit').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_edit&id=$1&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_delete').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_delete&id=$1&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_resource_delete').'/([0-9]+)/([0-9]+)/([0-9A-Za-z]+)/?(.*?)/?$', 'index.php?page=item&action=deleteResource&id=$1&item=$2&code=$3&secret=$4');

                        // Item rules
                        $id_pos = stripos($item_url, '{ITEM_ID}');
                        $title_pos = stripos($item_url, '{ITEM_TITLE}');
                        $cat_pos = stripos($item_url, '{CATEGORIES');
                        $param_pos = 1;
                        if($title_pos!==false && $id_pos>$title_pos) {
                            $param_pos++;
                        }
                        if($cat_pos!==false && $id_pos>$cat_pos) {
                            $param_pos++;
                        }
                        $comments_pos = 1;
                        if($id_pos!==false) { $comments_pos++; }
                        if($title_pos!==false) { $comments_pos++; }
                        if($cat_pos!==false) { $comments_pos++; }
                        $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url.'\?comments-page=([0-9al]*)')))).'$', 'index.php?page=item&id=$3&lang=$1_$2&comments-page=$4');
                        $rewrite->addRule('^'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url.'\?comments-page=([0-9al]*)')))).'$', 'index.php?page=item&id=$1&comments-page=$2');
                        $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url)))).'$', 'index.php?page=item&id=$3&lang=$1_$2');
                        $rewrite->addRule('^'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url)))).'$', 'index.php?page=item&id=$1');

                        // User rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_login').'/?$', 'index.php?page=login');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_dashboard').'/?$', 'index.php?page=user&action=dashboard');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_logout').'/?$', 'index.php?page=main&action=logout');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_register').'/?$', 'index.php?page=register&action=register');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_activate').'/([0-9]+)/(.*?)/?$', 'index.php?page=register&action=validate&id=$1&code=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_activate_alert').'/([0-9]+)/([a-zA-Z0-9]+)/(.+)$', 'index.php?page=user&action=activate_alert&id=$1&email=$3&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/?$', 'index.php?page=user&action=profile');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/([0-9]+)/?$', 'index.php?page=user&action=pub_profile&id=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/(.+)/?$', 'index.php?page=user&action=pub_profile&username=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_items').'/?$', 'index.php?page=user&action=items');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_alerts').'/?$', 'index.php?page=user&action=alerts');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_recover').'/?$', 'index.php?page=login&action=recover');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_forgot').'/([0-9]+)/(.*)/?$', 'index.php?page=login&action=forgot&userId=$1&code=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_password').'/?$', 'index.php?page=user&action=change_password');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_email').'/?$', 'index.php?page=user&action=change_email');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_username').'/?$', 'index.php?page=user&action=change_username');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_email_confirm').'/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=change_email_confirm&userId=$1&code=$2');

                        // Page rules
                        $pos_pID   = stripos($page_url, '{PAGE_ID}');
                        $pos_pSlug = stripos($page_url, '{PAGE_SLUG}');
                        $pID_pos   = 1;
                        $pSlug_pos = 1;
                        if( is_numeric($pos_pID) && is_numeric($pos_pSlug) ) {
                            // set the order of the parameters
                            if($pos_pID > $pos_pSlug) {
                                $pID_pos++;
                            } else {
                                $pSlug_pos++;
                            }

                            $rewrite->addRule('^' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', str_replace('{PAGE_ID}', '([0-9]+)', $page_url)) . '/?$', 'index.php?page=page&id=$' . $pID_pos . "&slug=$" . $pSlug_pos);
                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', str_replace('{PAGE_ID}', '([0-9]+)', $page_url)) . '/?$', 'index.php?page=page&lang=$1_$2&id=$' . ($pID_pos + 2) . '&slug=$' . ($pSlug_pos + 2) );
                        } else if( is_numeric($pos_pID) ) {
                            $rewrite->addRule('^' .  str_replace('{PAGE_ID}', '([0-9]+)', $page_url) . '/?$', 'index.php?page=page&id=$1');
                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_ID}', '([0-9]+)', $page_url) . '/?$', 'index.php?page=page&lang=$1_$2&id=$3' );
                        } else {
                            $rewrite->addRule('^' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', $page_url) . '/?$', 'index.php?page=page&slug=$1');
                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', $page_url) . '/?$', 'index.php?page=page&lang=$1_$2&slug=$3' );
                        }

                        // Clean archive files
                        $rewrite->addRule('^(.+?)\.php(.*)$', '$1.php$2');

                        // Category rules
                        $id_pos = stripos($item_url, '{CATEGORY_ID}');
                        $title_pos = stripos($item_url, '{CATEGORY_NAME}');
                        $cat_pos = stripos($item_url, '{CATEGORIES');
                        $param_pos = 1;
                        if($title_pos!==false && $id_pos>$title_pos) {
                            $param_pos++;
                        }
                        if($cat_pos!==false && $id_pos>$cat_pos) {
                            $param_pos++;
                        }
                        $rewrite->addRule('^'.str_replace('{CATEGORIES}', '(.+)', str_replace('{CATEGORY_NAME}', '([^/]+)', str_replace('{CATEGORY_ID}', '([0-9]+)', $cat_url))).'/([0-9]+)$', 'index.php?page=search&sCategory=$'.$param_pos.'&iPage=$'.($param_pos+1));
                        $rewrite->addRule('^'.str_replace('{CATEGORIES}', '(.+)', str_replace('{CATEGORY_NAME}', '([^/]+)', str_replace('{CATEGORY_ID}', '([0-9]+)', $cat_url))).'/?$', 'index.php?page=search&sCategory=$'.$param_pos);

                        $rewrite->addRule('^(.+)/([0-9]+)$', 'index.php?page=search&iPage=$2');
                        $rewrite->addRule('^(.+)$', 'index.php?page=search');

                        osc_run_hook("after_rewrite_rules", array(&$rewrite));

                        //Write rule to DB
                        $rewrite->setRules();

                        osc_set_preference('seo_url_search_prefix', rtrim(Params::getParam('seo_url_search_prefix'), '/'));

                        $msg_error = '<br/>'._m('All fields are required.')." ".sprintf(_mn('One field was not updated', '%s fields were not updated', $errors), $errors);
                        switch($status) {
                            case 1:
                                $msg  = _m("Permalinks structure updated");
                                if($errors>0) {
                                    $msg .= $msg_error;
                                    osc_add_flash_warning_message($msg, 'admin');
                                } else {
                                    osc_add_flash_ok_message($msg, 'admin');
                                }
                            break;
                            case 2:
                                $msg  = _m("Permalinks structure updated");
                                $msg .= " ";
                                $msg .= _m("However, we can't check if Apache module <b>mod_rewrite</b> is loaded. If you experience some problems with the URLs, you should deactivate <em>Friendly URLs</em>");
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                osc_add_flash_warning_message($msg, 'admin');
                            break;
                            case 3:
                                $msg  = _m("File <b>.htaccess</b> couldn't be filled out with the right content.");
                                $msg .= " ";
                                $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't create the file, please deactivate the <em>Friendly URLs</em> option.");
                                $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>';
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                osc_add_flash_error_message($msg, 'admin');
                            break;
                            case 4:
                                $msg  = _m("File <b>.htaccess</b> couldn't be filled out with the right content.");
                                $msg .= " ";
                                $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't create the file or experience some problems with the URLs, please deactivate the <em>Friendly URLs</em> option.");
                                $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>';
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                osc_add_flash_error_message($msg, 'admin');
                            break;
                            case 5:
                                $warning = false;
                                if( file_exists($htaccess_file) ) {
                                    $htaccess_content = file_get_contents($htaccess_file);
                                    if($htaccess_content!=$htaccess) {
                                        $msg  = _m("File <b>.htaccess</b> already exists and was not modified.");
                                        $msg .= " ";
                                        $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't modify the file or experience some problems with the URLs, please deactivate the <em>Friendly URLs</em> option.");
                                        $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>';
                                        $warning = true;
                                    } else {
                                        $msg  = _m("Permalinks structure updated");
                                    }
                                }
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                if($errors>0 || $warning) {
                                    osc_add_flash_warning_message($msg, 'admin');
                                } else {
                                    osc_add_flash_ok_message($msg, 'admin');
                                }
                            break;
                        }
                    } else {
                        osc_set_preference('rewriteEnabled', 0);
                        osc_set_preference('mod_rewrite_loaded', 0);

                        $deleted = true;
                        if( file_exists($htaccess_file) ) {
                            $htaccess_content = file_get_contents($htaccess_file);
                            if($htaccess_content==$htaccess) {
                                $deleted = @unlink($htaccess_file);
                                $same_content = true;
                            } else {
                                $deleted = false;
                                $same_content = false;
                            }
                        }
                        if($deleted) {
                            osc_add_flash_ok_message(_m('Friendly URLs successfully deactivated'), 'admin');
                        } else {
                            if($same_content) {
                                osc_add_flash_warning_message(_m('Friendly URLs deactivated, but .htaccess file could not be deleted. Please, remove it manually'), 'admin');
                            } else {
                                osc_add_flash_warning_message(_m('Friendly URLs deactivated, but .htaccess file was modified outside Osclass and was not deleted'), 'admin');
                            }
                        }
                    }

                    $this->redirectTo( osc_admin_base_url(true) . '?page=settings&action=permalinks' );
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/permalinks.php