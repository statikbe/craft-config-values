# Config Values Field plugin for Craft CMS

Populate field options from a centralized configuration file, providing consistent predefined values across multiple fields without hardcoding them into individual field settings.

## Requirements
This plugin requires Craft CMS 5.0.0 or later.

## Features

![CleanShot 2025-06-19 at 16 53 21](https://github.com/user-attachments/assets/dc8c3c42-e72f-4414-89be-1e5b677be020)


## Installation

1. Open your terminal and go to your Craft project:
   ```bash
   cd /path/to/project
   ```

2. Install via Composer:
   ```bash
   composer require statikbe/craft-config-values
   ```

3. Install the plugin via Craft CLI:
   ```bash
   ./craft plugin/install config-values-field
   ```

## Configuration

Create a configuration file at `config/config-values-field.php`:

```php
<?php

return [
    'data' => [
        // Basic dropdown/radio/checkbox options
        'ctaStyles' => [
            'primary' => 'Primary Button',
            'secondary' => 'Secondary Button',
            'outline' => 'Outline Button',
            'link' => 'Text Link',
        ],
        
        // Color options (supports hex values)
        'brandColors' => [
            '' => 'none',      // Special: shows striped pattern
            'random' => 'random',  // Special: shows rainbow gradient
            'primary' => '#3B82F6',
            'secondary' => '#10B981',
            'accent' => '#F59E0B',
            'danger' => '#EF4444',
        ],
        
        // Gradient colors (2-3 colors)
        'headerColors' => [
            '' => 'none',      // Special: shows striped pattern
            'random' => 'random',  // Special: shows rainbow gradient
            'sunset' => ['#FF6B6B', '#FFE66D'],
            'ocean' => ['#667eea', '#764ba2', '#f093fb'],
            'forest' => ['#134e5e', '#71b280'],
        ],
        
        // Shape options (requires SVG files)
        'icons' => [
            'path' => '@webroot/assets/icons/',
            'shapes' => [
                '' => 'none',
                'random' => 'random',
                'arrow' => 'Arrow',
                'star' => 'Star',
                'heart' => 'Heart',
                'check' => 'Checkmark',
            ],
        ],
    ],
];
```

## Field Types

The plugin supports 5 different field display types:

### 1. Dropdown
Standard select dropdown for single selection.

### 2. Radio Buttons
Radio button group for single selection with visual options.

### 3. Checkboxes
Multiple selection checkboxes for choosing multiple values.

### 4. Color
Visual color picker with special features:
- Hex colors: Display as color swatches
- Gradients: Support 2-3 color arrays for gradient backgrounds
- Special values:
  - `'random'`: Shows rainbow gradient indicator
  - `'none'`: Shows striped "no color" pattern

### 5. Shape
SVG shape selector for icon/graphic selection:
- Requires `path` configuration pointing to SVG directory
- Uses `shapes` array to map filenames to labels
- Validates file existence
- Special values
  - `'random'`
  - `'none'`




---

Brought to you by [Statik.be](https://www.statik.be)
