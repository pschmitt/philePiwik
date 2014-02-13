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

By default this plugin will try to guess to server it has to report to.

You can change this by editing `config.php`:

```php
$config['piwik_host'] = 'piwig.example.com'; // default host: stats.$HOST
$config['piwik_site_id'] = '1';
```
