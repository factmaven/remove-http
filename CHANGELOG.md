## Changelog
### 2.2.0
*yyyy-mm-dd*
* **Fix**: Protocol is only removed on frontend [#11](https://github.com/factmaven/remove-http/issues/11)
* **Fix**: Settings icon is enlarged on Plugin page [#9](https://github.com/factmaven/remove-http/issues/9)

### 2.1.1
*2017-09-09*
* Added support for relative domain paths [#8](https://github.com/factmaven/remove-http/issues/8)

### 2.1.0
*2017-04-09*
* Restored option to ignore external links [#6](https://github.com/factmaven/remove-http/issues/6)

### 2.0.0
*2017-03-28*
* **Feature**: Switch between protocol-relative or relative URLs
* Ignore `<link>` tags with `rel="canonical"` because [they must be absolute URLs](https://support.google.com/webmasters/answer/139066#2)
* **Fix**: Links in "style" tags weren't affected

### 1.1.1
*2017-01-16*
* Minor code improvements

### 1.1.0
*2016-10-25*
* **Feature**: Remove protocols from internal links only (see *General* > *Protocol Relative URL*)
* Added uninstall to delete custom plugin options
* Improved regex to find all protocols
* Simplified and removed redundant code

### 1.0.2
*2016-10-24*
* Function will run once WordPress, all plugins, and the theme are fully loaded
* Reverted back to original regex from v1.0.0
* Upgrade notice shown when update is available
* **Fix**: Conflict with [Visual Composer](https://vc.wpbakery.com) plugin
* **Fix**: YouTube videos in [Revolution Slider](https://revolution.themepunch.com) plugin stopped playing

### 1.0.1
*2016-10-10*
* Improved regex to find all protocols
* Comments added for code explanation

### 1.0.0
*2016-09-05*
* Initial release, huzzah!