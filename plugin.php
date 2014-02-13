<?php

/**
 * The file description. *
 * @package Phile
 * @subpackage PhilePiwik
 * @version 1.0.0
 * @author Philipp Schmitt <philipp@schmitt.co>
 *
 */
class PhilePiwik extends \Phile\Plugin\AbstractPlugin implements \Phile\EventObserverInterface {

    private $config;
    private $piwikSiteId;
    private $piwikHost;

    public function __construct() {
        \Phile\Event::registerEvent('config_loaded', $this);
        \Phile\Event::registerEvent('before_render_template', $this);
        $this->config = \Phile\Registry::get('Phile_Settings');

        // init
        $this->piwikSiteId = 1;
        $this->piwikHost = 'stats.' . $_SERVER['SERVER_NAME'];
    }

    public function on($eventKey, $data = null) {
        if ($eventKey == 'config_loaded') {
                if (isset($this->config['piwik_host'])) {
                    $this->piwikHost = $this->config['piwik_host'];
                }
                if (isset($this->config['piwik_site_id'])) {
                    $this->piwikSiteId = $this->config['piwik_site_id'];
                }
        } else if ($eventKey == 'before_render_template') {
                if (\Phile\Registry::isRegistered('templateVars')) {
                    $twig_vars = \Phile\Registry::get('templateVars');
                } else {
                    $twig_vars = array();
                }

                $twig_vars['piwik_tracking_code'] = '<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://' . $this->piwikHost . '/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "'. $this->piwikSiteId. '"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->';
                \Phile\Registry::set('templateVars', $twig_vars);
        }
    }
}
