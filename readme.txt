=== Remove HTTP: Fix Mixed Content Warning ===
Contributors: factmaven, ethanosullivan
Tags: protocol relative url, relative protocol, protocol rewriting, remove http, remove https, url, http, https, rewrite, cloudflare, mixed content warning, insecure content, mixed content, partially encrypted, ssl
Requires at least: 3.0.0
Tested up to: 4.6.1
Stable tag: 1.1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Fixes all mixed content warnings. Removes both HTTP and HTTPS protocols from all links.

== Description ==
>**Remove HTTP** is a plugin that a automatically scans and removes both `http:` and `https:` protocols from all links. This helps resolve websites that are having "[mixed content warnings](https://wordpress.org/plugins/remove-http/faq/)" which is when the website has assets (images, JavaScript, and CSS) loading both HTTP and HTTPS.

No changes to the links are made in the database. Simply install and activate the plugin and the changes will be immediate. Links that have `http://` or `https://` will only have `//`, making them protocol relative URLs. Below is a before and after example.

= Before =
`
<link rel='stylesheet' id='some-id'  href='https://example.com/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='http://example.com/some/script.js'></script>
<a href="https://example.com" title="Some Title" rel="home">Some Link</a>
<img src="http://example.com/some/image.jpg" alt="Some Alt" width="150" height="50" />
`

= After =
`
<link rel='stylesheet' id='some-id'  href='//example.com/some/style.css' type='text/css' media='all' />
<script type='text/javascript' src='//example.com/some/script.js'></script>
<a href="//example.com" title="Some Title" rel="home">Some Link</a>
<img src="//example.com/some/image.jpg" alt="Some Alt" width="150" height="50" />
`

= Apply to internal links only =
Additionally, there is an option (under the *General* settings) to apply changes to internal links only. Below an an example output if the website's site address is `http://example.com`:
`
<link rel="profile" href="http://gmpg.org/xfn/11">
<script type='text/javascript' src='//example.com/some/script.js'></script>
<a href="http://www.factmaven.com" title="Fact Maven" rel="home">Fact Maven</a>
<img src="//example.com/some/image.jpg" alt="Some Alt" width="150" height="50" />
`


= Contribute on GitHub =
Want to help improve this plugin? Head over to our [GitHub page](https://github.com/factmaven/remove-http/).

== Installation ==
1. Upload the plugin to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.

== Frequently Asked Questions ==
= What is mixed content? =
According to [Google](https://developers.google.com/web/fundamentals/security/prevent-mixed-content/what-is-mixed-content): 
> Mixed content occurs when initial HTML is loaded over a secure HTTPS connection, but other resources (such as images, videos, stylesheets, scripts) are loaded over an insecure HTTP connection. This is called mixed content because both HTTP and HTTPS content are being loaded to display the same page, and the initial request was secure over HTTPS. Modern browsers display warnings about this type of content to indicate to the user that this page contains insecure resources.

In short, mixed content degrades the security and user experience of your HTTPS site.

== Changelog ==
= 1.1.0 =
* **[2016-10-25]**
* **Feature**: Option to remove protocols from internal links only (see *General* > *Protocol Relative URL*)
* Added uninstall to delete custom plugin options
* Improved regex to find all protocols
* Simplified and removed redundant code

= 1.0.2 =
* **[2016-10-24]**
* Function will run once WordPress, all plugins, and the theme are fully loaded
* Reverted back to original regex from v1.0.0
* Upgrade notice shown when update is available
* **Fix**: Conflict with [Visual Composer](https://vc.wpbakery.com) plugin
* **Fix**: YouTube videos in [Revolution Slider](https://revolution.themepunch.com) plugin stopped playing

= 1.0.1 =
* **[2016-10-10]**
* Improved regex to find all protocols
* Comments added for code explanation

= 1.0.0 =
* **[2016-09-05]**
* Initial release, huzzah!

== Upgrade Notice ==
= 1.1.0 =
New plugin option added. Protocol-relative URLs can be applied to internal links only.

= 1.0.2 =
Fixed issue with plugin conflicts such as Visual Composer and Revolution slider.