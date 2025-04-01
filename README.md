# Smile Custom Entity SEO Module

This module by Amadeco extends the Smile Custom Entity (https://github.com/Smile-SA/magento2-module-custom-entity) module to add SEO capabilities.

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

- PHP 8.1, 8.2 or 8.3
- Magento 2.4.x
- Smile Custom Entity module version 1.3 or higher

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

This module is licensed under the Open Software License ("OSL") v3.0.
