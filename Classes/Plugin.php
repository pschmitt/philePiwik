<?php

namespace Phile\Plugin\Pschmitt\Piwik;

/**
 * Piwik plugin for Phile
 *
 * @version 1.0.1
 * @author Philipp Schmitt <philipp@schmitt.co>
 * @link https://github.com/pschmitt/philePiwik
 * @license http://opensource.org/licenses/MIT
 * @package Phile\Plugin\Pschmitt\Piwik
 *
 */

class Plugin extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {

    private $config;
    private $site_id;
    private $server_name;
    private $piwik_host;
    private $image_tracking;
    private $image_page_name;
    private $track_subdomains;
    private $prepend_domain;
    private $hide_aliases;
    private $do_not_track;

    public function __construct() {
        \Phile\Event::registerEvent('config_loaded', $this);
        \Phile\Event::registerEvent('before_render_template', $this);
        $this->config = \Phile\Registry::get('Phile_Settings');

        // Init
        $this->site_id = 1;
        // Guess host name
        $this->server_name = $_SERVER['SERVER_NAME'];      // example.com
        $this->piwik_host = 'stats.' . $this->server_name; // stats.example.com
        $this->image_tracking = false;
        $this->image_page_name = '';
        $this->track_subdomains = false;
        $this->prepend_domain = false;
        $this->hide_aliases = false;
    }

    public function on($eventKey, $data = null) {
        if ($eventKey == 'config_loaded') {
            $this->config_loaded();
        } else if ($eventKey == 'before_render_template') {
            $this->export_twig_vars();
        }
    }

    private function config_loaded() {
        // merge the arrays to bind the settings to the view
        // Note: this->config takes precedence
        $this->config = array_merge($this->settings, $this->config);
        if (isset($this->config['piwik_host'])) {
            $this->piwik_host = $this->config['piwik_host'];
        }
        if (isset($this->config['piwik_site_id'])) {
            $this->site_id = $this->config['piwik_site_id'];
        }
        if (isset($this->config['piwik_image_tracking'])) {
            $this->image_tracking = $this->config['piwik_image_tracking'];
        }
        if (isset($this->config['piwik_image_page_name'])) {
            $this->image_page_name = $this->config['piwik_image_page_name'];
        }
        if (isset($this->config['piwik_track_subdomains'])) {
            $this->track_subdomains = $this->config['piwik_track_subdomains'];
        }
        if (isset($this->config['piwik_prepend_domain'])) {
            $this->prepend_domain = $this->config['piwik_prepend_domain'];
        }
        if (isset($this->config['piwik_hide_aliases'])) {
            $this->hide_aliases = $this->config['piwik_hide_aliases'];
        }
        if (isset($this->config['piwik_do_not_track'])) {
            $this->do_not_track = $this->config['piwik_do_not_track'];
        }
    }

    private function get_image_tracking_code() {
        $image_code = '
                 <!-- Piwik Image Tracker -->
                     <img src="http://stats.lxl.io/piwik.php?idsite=1&amp;rec=1%s" style="border:0" alt="" />
                 <!-- End Piwik -->';
        $image_code = sprintf($image_code, isset($this->image_page_name) ? '&amp;action_name=' . $this->image_page_name : '');
        return $image_code;
    }

    private function get_js_tracking_code() {
        $js_code = '<!-- Piwik -->
            <script type="text/javascript">
              var _paq = _paq || [];
              _paq.push(["trackPageView"]);
              _paq.push(["enableLinkTracking"]);

              (function() {
                var u=(("https:" == document.location.protocol) ? "https" : "http") + "://%s/";
                %s
                _paq.push(["setTrackerUrl", u+"piwik.php"]);
                _paq.push(["setSiteId", "%d"]);
                var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
                g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
              })();
            </script>
        <!-- End Piwik Code -->';
        $dnt_txt = $this->do_not_track ? '_paq.push(["setDoNotTrack", true]);' . "\n" : '';
        $subdomain_txt = $this->track_subdomains ? sprintf('_paq.push(["setCookieDomain", "*.%s"]);' . "\n", $this->server_name) : '';
        $prepend_txt = $this->prepend_domain ? '_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);' . "\n" : '';
        $alias_txt = $this->hide_aliases ? sprintf('_paq.push(["setDomains", ["*.%s"]]);' . "\n", $this->server_name) : '';

        $js_code = sprintf($js_code, $this->piwik_host, $dnt_txt . $subdomain_txt . $prepend_txt . $alias_txt, $this->site_id);

        return $js_code;
    }

    private function export_twig_vars() {
        if (\Phile\Registry::isRegistered('templateVars')) {
            $twig_vars = \Phile\Registry::get('templateVars');
        } else {
            $twig_vars = array();
        }

        $twig_vars['piwik_tracking_code'] = $this->image_tracking ? $this->get_image_tracking_code() : $this->get_js_tracking_code();

        \Phile\Registry::set('templateVars', $twig_vars);
    }
}
