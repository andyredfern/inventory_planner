# API Cheat Sheet
While developing this wrapper I identified a number of nuances to the exact implementation of the Inventory Plannner API. There are no formal docs for the API currently just a help sheet  


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


### Using the imports API
The official docs make no reference to an imports API but the are two end points if you want to automate importing - files and imports.

