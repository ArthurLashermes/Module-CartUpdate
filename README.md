
# Cart Update

It's a thelia module (open source software allowing the creation of e-commerce site running under symfony) which allows to update the prices of the users cart and to delete the cart when it is more than 15 days old. This service is triggered when the user connects or when he wants to make an order.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is CartUpdate.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require your-vendor/cart-update-module:~1.0
```
