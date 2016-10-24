# Remove HTTP: Fix Mixed Content Warning [![Version](https://img.shields.io/wordpress/plugin/v/remove-http.svg?style=flat-square)](https://wordpress.org/plugins/remove-http/)

Fixes all mixed content warnings. Removes both HTTP and HTTPS protocols from all links.

>**Remove HTTP** is a plugin that a automatically scans and removes both `http:` and `https:` protocols from all links. This helps resolve websites that are having "[mixed content warnings](https://wordpress.org/plugins/remove-http/faq)" which is when the website has assets (images, JavaScript, and CSS) loading both HTTP and HTTPS.

No configuration is required and no changes are made in the database. Simply install and activate the plugin and the changes will be immediate. Links that have `http://` or `https://` will only have `//`, making them protocol relative URLs. Below is a before and after example.

#### Before
```html
<link rel='stylesheet' id='some-id'  href='https://example.com/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='http://example.com/some/script.js'></script>
<a href="https://example.com" title="Fact Maven" rel="home">Some Link</a>
<img src="http://example.com/some/image.jpg" alt="Some Alt" width="150" height="50" />
```
#### After
```html
<link rel='stylesheet' id='some-id'  href='//example.com/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='//example.com/some/script.js'></script>
<a href="//example.com" title="Some Title" rel="home">Some Link</a>
<img src="//example.com/some/image.jpg" alt="Some Alt" width="150" height="50" />
```

#### [Changelog](https://github.com/factmaven/remove-http/blob/master/CHANGELOG.md)