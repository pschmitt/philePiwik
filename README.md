philePiwik
==========

Add piwik tracking code to your Phile site


## Installation

```bash
mkdir -p ~http/plugins/pschmitt
git clone https://github.com/pschmitt/philePiwik.git ~http/plugins/pschmitt/piwik
# You may consider using a submodule for this
git submodule add http://github.com/pschmitt/philePiwik.git ~http/plugins/pschmitt/piwik
```

Then activate in your `config.php`:

```php
$config['plugins'] = array(
    // [...]
    'pschmitt\\piwik' => array('active' => true),
);
```

## Configuration

By default this plugin will try to guess the server it has to report to.

You can change this by editing `config.php`:

```php
$config['piwik_host'] = 'piwig.example.com'; // default host: stats.$HOST
$config['piwik_do_not_track'] = true; // client side DoNotTrack detection
$config['piwik_site_id'] => '1'; // default site id
$config['piwik_image_tracking'] => false; // Whether to use image tracking instead of javascript
$config['piwik_image_page_name'] => ''; // Page name (Image tracking only)
$config['piwik_track_subdomains'] => false; // Track visitors across all subdomains
$config['piwik_prepend_domain'] => false; // Prepend the site domain to the page title when tracking
$config['piwik_hide_aliases'] =>  false; // In the "Outlinks" report, hide clicks to known alias URLs of your domain
```

### Warning

Client side DoNotTrack is enabled by default.

