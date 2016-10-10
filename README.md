# Remove HTTP 
Automatically scan your website and remove both HTTP and HTTPS protocols from your links. Helps resolve mixed content warnings.

## Description 
**Remove HTTP** is a plugin that a automatically scans your website and removes both `http:` and `https:` protocols from your links without permanently changing anything. This helps resolve websites that are having "mixed content warnings" when your website has assets (images, JavaScript, and CSS) loading both HTTP and HTTPS.

No configuration is required. Simply install and activate Remove HTTP plugin and you will immediately see your changes. Links that had `http:` or `https:` will only have `//` making them protocol relative URLs.

### Before 
```html
<link rel='stylesheet' id='some-id'  href='https://example.com/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='http://example.com/some/script.js'></script>
<a href="https://example.com" title="Fact Maven" rel="home">Some Link</a>
<img src="http://example.com/some/image.jpg" alt="Some Alt" width="150" height="50" />
```

### After 
```html
<link rel='stylesheet' id='some-id'  href='//example.com/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='//example.com/some/script.js'></script>
<a href="//example.com" title="Some Title" rel="home">Some Link</a>
<img src="//example.com/some/image.jpg" alt="Some Alt" width="150" height="50" />
```

## Installation 
1. Upload the plugin to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.