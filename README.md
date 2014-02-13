philePiwik
==========

Add piwik tracking code to your Phile site


## Installation

```bash
git clone https://github.com/pschmitt/philePiwig.git ~http/plugins/philePiwig
# You may consider using a submodule for this
git submodule add http://github.com/pschmitt/philePiwig ~http/plugins/philePiwig
```

Then activate in your `config.php`:

```php
$config['plugins'] = array(
    // [...]
    'philePiwig' => array('active' => true),
);
```

## Configuration

By default this plugin will try to guess the server it has to report to.

You can change this by editing `config.php`:

```php
$config['piwik_host'] = 'piwig.example.com'; // default host: stats.$HOST
$config['piwik_site_id'] => '1'; // default site id
$config['piwik_image_tracking'] => false; // Whether to use image tracking instead of javascript
$config['piwik_image_page_name'] => ''; // Pagne name (Image tracking only)
$config['piwik_track_subdomains'] => false; // Track visitors across all subdomains
$config['piwik_prepend_domain'] => false; // Prepend the site domain to the page title when tracking
$config['piwik_hide_aliases'] =>  false; // In the "Outlinks" report, hide clicks to known alias URLs of your domain
```
