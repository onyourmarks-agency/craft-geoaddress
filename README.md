# TDE - Geo Address plugin for Craft CMS 4.x

Geo Address field for Craft 4 Sections.

## Requirements

This plugin requires Craft CMS 4.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require tde/craftplugin-geoaddress

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for TDE - Geo Address.

4. In the Control Panel, go to Settings → Plugins and click the “Settings” link to insert your Google Maps Geocoding API key to increase the allowed API calls.

5. Copy and rename the file geoaddress-sample.php to [your craft directory]/config/geoaddress.php and modify it to include only those countries you want enabled.
