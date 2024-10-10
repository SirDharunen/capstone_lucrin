# Mastering SampleModule for Magento 2
NOTE: This repository doesnot contain the dummy database data file.
It contain only code files.
The database file should be imported manually though a zip file.
## Overview
Mastering SampleModule is a comprehensive Magento 2 module that demonstrates various aspects of Magento 2 development, including integration of live streaming data, reporting functionalities, and custom admin interfaces.

## Features
* Live Stream Integration Dashboard
* Daily Country Summary
* Data Import Functionality
* Custom ACL (Access Control List)
* Admin Grid

## Prerequisites
* XAMPP Version 8.1.12
* Composer Version 2.7.7
* Elasticsearch Version 7.17.0
* Magento 2 installation (version 2.4.5 p1)

## Installation
1. Clone this repository:
   ````
   git clone https://github.com/SirDharunen/capstone_lucrin.git
   ```
2. Move the `Mastering` folder to your Magento 2 `app/code/` directory.
3. Enable the module:
   ````
   php bin/magento module:enable Mastering_SampleModule
   ```
4. Run the database upgrades:
   ````
   php bin/magento setup:upgrade
   ```
5. Clear the cache:
   ````
   php bin/magento cache:clean
   ```

## Module Structure
The Mastering SampleModule folder follows a standard Magento 2 module structure:

```
app/code/Mastering/SampleModule/
├── Block/
├── Controller/
├── Model/
├── Setup/
├── Ui/
│   └── DataProvider/
├── etc/
├── view/
│   ├── adminhtml/
│   │   ├── layout/
│   │   ├── templates/
│   │   └── ui_component/
│   └── frontend/
│       ├── layout/
│       └── templates/
└── registration.php
```

### Key Directories:
* `Block/`: Contains block classes for rendering frontend and admin interfaces.
* `Controller/`: Handles admin and frontend routes and actions.
* `Model/`: Defines data models and resource models.
* `Setup/`: Contains installation and upgrade scripts.
* `Ui/DataProvider/`: Houses UI component data providers.
* `etc/`: Includes configuration files.
* `view/`: Contains layout files, templates, and UI components for both admin and frontend.

## Usage
1. Start your XAMPP MySQL server.
2. Open your web browser and navigate to your Magento 2 admin URL: `http://capstone.magento.com/admin/admin/dashboard/`
3. Log in with your admin credentials:
   - Username: admin
   - Password: Admin@12345
4. In the admin sidebar, you'll find a new menu item called "Mastering".
5. Expand this menu to access the following options:
   - Manage Items
   - Live Stream Integration
   - Daily Country Summary
   - Configuration

## Troubleshooting
If you encounter any issues during installation or usage, please check the following:
1. Ensure all prerequisites are correctly installed and configured.
2. Verify that your Magento 2 installation meets the module's compatibility requirements.



