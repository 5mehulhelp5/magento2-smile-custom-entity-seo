# Smile Custom Entity SEO Module

[![Latest Stable Version](https://img.shields.io/github/v/release/Amadeco/magento2-smile-custom-entity-seo)](https://github.com/Amadeco/magento2-smile-custom-entity-seo/releases)
[![License](https://img.shields.io/github/license/Amadeco/magento2-smile-custom-entity-seo)](https://github.com/Amadeco/magento2-smile-custom-entity-seo/blob/main/LICENSE)
[![Magento](https://img.shields.io/badge/Magento-2.4.x-brightgreen.svg)](https://magento.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://www.php.net)

[SPONSOR: Amadeco](https://www.amadeco.fr)

This module by Amadeco extends the [Smile Custom Entity](https://github.com/Smile-SA/magento2-module-custom-entity) module to add SEO capabilities.

![Screenshot of the new fields in custom entity form](https://github.com/user-attachments/assets/dedf41e7-394e-4010-9354-23f7d7c5531a)

## Features

- Adds SEO attributes to Custom Entities:
  - Meta Title
  - Meta Description
  - Meta Keywords
  - Meta Robots
- Creates a new "Search Engine Optimization" group in the Custom Entity edit form
- Automatically applies SEO metadata to Custom Entity pages

## Installation

```bash
composer require amadeco/module-custom-entity-seo
bin/magento module:enable Amadeco_SmileCustomEntitySeo
bin/magento setup:upgrade
```

## Requirements

- PHP 8.3+
- Magento 2.4.x
- Smile Custom Entity module version 1.3 or higher (https://github.com/Smile-SA/magento2-module-custom-entity) 

## Usage

1. Edit any Custom Entity from the admin panel
2. Navigate to the "Search Engine Optimization" section
3. Fill in the SEO fields as needed

## Technical Details

This module:

- Creates new EAV attributes for Custom Entities
- Extends the CustomEntity model to support SEO attributes
- Adds an observer to apply SEO metadata to pages automatically

## License

This module is licensed under the Open Software License ("OSL") v3.0. See the [LICENSE.txt](LICENSE.txt) file for details.
