# API Cheat Sheet
While developing this wrapper I identified a number of nuances to the exact implementation of the Inventory Plannner API. There are no formal docs for the API currently just a help sheet  

## Variant
### The variant url key
The official docs show the variant route as 
``` 
GET /api/v1/variant/{id}
```
However, the route is actually:
``` 
GET /api/v1/variants/{id}
```

## Purchase Order 
### Incorrect route for getting all purchase orders
Docs show route is:
```
GET api/v1/puchase-orders
```
Clearly a typo and correct route is:
```
GET api/v1/purchase-orders
```

### Updating an existing Purchase order or creating a new purchase order

The official docs show the date fields as date only fields in MySQL date format sometimes in RFC822 format ("2020-06-25T15:25:22+00:00").

However, to use the PUT, PATCH or POST methods you need to supply the date as timestamp. So:

``` 
PUT /api/v1/purchase-orders/{purchase_order_id}
```
```javascript
{
    'purchase-order':{
        'expected_date':1576619927
    }
}
```
returns:
```javascript
{
    'purchase-order':{
        ...
        "expected_date": "2019-12-17",
        ...
    }
}
```
However using the same URL with:
```javascript
{
    'purchase-order':{
        'expected_date':"2019-12-17"
    }
}
```
returns:
```javascript
{
    'purchase-order':{
        ...
        "expected_date": "1969-12-31",
        ...
    }
}
```
Ouch!

### Creating an existing Purchase order
The API allows a Purchase order to be created using a POST. The suggested structure in the docs is:
```
{
    "purchase-order": {
        "status": "OPEN",
        "reference": "MY_PO_20",
        "expected_date": "2018-03-15T10:00:00+01:00",
        "vendor": "vendor_1",
        "variants_filter": {
            "vendor": "vendor_1",
            "replenishment_gt": 3
        }
    }
}
```
However, this will not work as Inventory Planner now requires a warehouse to be set so the minimum requirement is:
```
{
    "purchase-order": {
        "status": "OPEN",
        "reference": "MY_PO_20",
        "expected_date": "2018-03-15T10:00:00+01:00",
        "vendor": "vendor_1",
        "warehouse": "warehouse_1",
        "variants_filter": {
            "vendor": "vendor_1",
            "replenishment_gt": 3
        }
    }
}
```
and you do need to fix the date too.
