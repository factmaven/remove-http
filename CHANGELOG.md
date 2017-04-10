## Changelog
### 2.1.0
*2017-04-09*
* Restored option to ignore external links

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