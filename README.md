# Remove HTTP 
Automatically scan your website and removes both HTTP and HTTPS protocols from your URL links. Helps resolves the mixed content warnings.

## Description 
**Remove HTTP** is a plugin that a automatically scans your website and removes both `http:` and `https:` protocols from your links without permanently changing anything. This helps resolve websites that are having "mixed content warnings" when your website has assets (images, JavaScript, and CSS) loading both HTTP and HTTPS.

No configuration is required. Simply install and activate Remove HTTP plugin and you will immediately see your changes. Links that had `http:` or `https:` will only have `//` making them protocol relative URLs.

### Before 
```html
<link rel='stylesheet' id='some-id'  href='https://link.to/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='http://link.to/some/script.js'></script>
<a href="https://web.site" title="Fact Maven" rel="home">Some Link</a>
<img src="http://link.to/some/image.jpg" alt="Some Alt" width="150" height="50" />
```

### After 
```html
<link rel='stylesheet' id='some-id'  href='//link.to/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='//link.to/some/script.js'></script>
<a href="//web.site" title="Some Title" rel="home">Some Link</a>
<img src="//link.to/some/image.jpg" alt="Some Alt" width="150" height="50" />
```

## Installation 
1. Upload the plugin to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.