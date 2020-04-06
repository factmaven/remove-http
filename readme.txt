=== Remove HTTP: Fix Mixed Content Warning ===
Contributors: factmaven, ethanosullivan
Donate link: https://www.factmaven.com/
Tags: cloudflare, http, https, insecure content, mixed content, mixed content warning, partially encrypted, protocol relative url, protocol rewriting, relative protocol, remove http, remove https, rewrite, ssl, url
Requires at least: 3.0.0
Tested up to: 5.4
Requires PHP: 4.3
Stable tag: 2.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Fixes all mixed content warnings. Removes both HTTP and HTTPS protocols from all links from the front-end and back-end.

== Description ==
>**Remove HTTP** is a plugin that automatically scans and removes both `http:` and `https:` protocols from all links. This helps resolve websites that are having "[mixed content warnings](https://wordpress.org/plugins/remove-http/faq/)" which is when the website has assets (images, JavaScript, and CSS) loading both HTTP and HTTPS.

No changes to the links are made in the database. Simply install and activate the plugin and the changes will be immediate. Links that have `http://` or `https://` will only have `//`, making them protocol relative URLs. Below is a before and after example.

= Before =
`
<link rel='stylesheet' href='https://example.com/style.css' type='text/css' />
<script type='text/javascript' src='http://example.com/script.js'></script>
<a href="https://example.com" title="Example">Example</a>
<img src="http://example.com/image.jpg" width="150" height="50" />
`

= After =
`
<link rel='stylesheet' href='//example.com/style.css' type='text/css' />
<script type='text/javascript' src='//example.com/script.js'></script>
<a href="//example.com" title="Example">Example</a>
<img src="//example.com/image.jpg" width="150" height="50" />
`

= Switch to Relative URLs =
There is an option (under the *General* settings) to change internal links to relative URLs.

= Contribute on GitHub =
Want to help improve this plugin? Head over to our [GitHub page](https://github.com/factmaven/remove-http/).

== Installation ==
1. Upload the plugin to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in for a minute and be amazed.

== Frequently Asked Questions ==
= What is mixed content? =
According to [Google](https://developers.google.com/web/fundamentals/security/prevent-mixed-content/what-is-mixed-content): 
> Mixed content occurs when initial HTML is loaded over a secure HTTPS connection, but other resources (such as images, videos, stylesheets, scripts) are loaded over an insecure HTTP connection. This is called mixed content because both HTTP and HTTPS content are being loaded to display the same page, and the initial request was secure over HTTPS. Modern browsers display warnings about this type of content to indicate to the user that this page contains insecure resources.

= How can I make my website fully SSL (on https://)? =
You can actually remove the dependency of this plugin by following these step-by-step instructions below:

[https://wordpress.stackexchange.com/a/238842/98212](https://wordpress.stackexchange.com/a/238842/98212)

It's slightly technical because you'll need to connect to your website's host file through an FTP (such as FileZilla). After that, all of your links will be pointing towards your new HTTPS protocol.

== Changelog ==
= 2.2.0 =

*2020-04-06*

* Protocol is only removed on frontend [#11](https://github.com/factmaven/remove-http/issues/11)
* **Fix**: Settings not showing under _Site Address (URL)_
* **Fix**: Settings icon is enlarged on Plugin page [#9](https://github.com/factmaven/remove-http/issues/9)

= 2.1.1 =

*2017-09-09*

* Added support for relative domain paths

= 2.1.0 =

*2017-04-09*

* Restored option to ignore external links

= 2.0.0 =

*2017-03-28*

* **Feature**: Switch between protocol-relative or relative URLs
* Ignore `<link>` tags with `rel="canonical"` because [they must be absolute URLs](https://support.google.com/webmasters/answer/139066#2)
* **Fix**: Links in "style" tags weren't affected