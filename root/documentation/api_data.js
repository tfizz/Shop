define({ "api": [
  {
    "type": " post ",
    "url": "/cart/add",
    "title": "Add to Cart",
    "name": "Add",
    "description": "<p>Add items to cart</p>",
    "header": {
      "examples": [
        {
          "title": "Header:",
          "content": "{\n    \"Content-Type\": \"application/json\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Product ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "quantity",
            "description": "<p>Quantity of items.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n    \"id\" : 1,\n    \"quantity\": 5\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status of request.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>User friendly message.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>cart object.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": true,\n  \"data\": {\n         \"id\": \"1\",\n         \"session_id\": \"1234567890\",\n         \"items\": \"[{\\\"id\\\":3,\\\"quantity\\\":1,\\\"price\\\":\\\"100.00\\\",\\\"total_price\\\":100}]\", // cart items stringyfied\n         \"created_at\": \"2019-01-15 04:05:07\",\n         \"updated_at\": null\n   }\n}",
          "type": "json"
        }
      ]
    },
    "group": "Cart",
    "version": "0.0.0",
    "filename": "application/controllers/api/Cart.php",
    "groupTitle": "Cart"
  },
  {
    "type": " get ",
    "url": "/cart",
    "title": "Cart",
    "name": "Cart_List",
    "description": "<p>List items in cart</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status of request.</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>List of items.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": true,\n  \"data\": [\n     {\n         \"id\": \"1\",\n         \"price\": \"100.00\",\n         \"quantity\": \"10\",\n         \"total_price\": \"1000.00\"\n     }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "group": "Cart",
    "version": "0.0.0",
    "filename": "application/controllers/api/Cart.php",
    "groupTitle": "Cart"
  },
  {
    "type": " get ",
    "url": "/cart/:session_id/checkout",
    "title": "Checkout",
    "name": "Checkout",
    "description": "<p>Checkout</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "session_id",
            "description": "<p>Session ID returned when cart created</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status of request.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>User friendly message.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "reference",
            "description": "<p>Order reference.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": true,\n  \"message\": \"message\",\n  \"reference\": \"1234567890\"\n}",
          "type": "json"
        }
      ]
    },
    "group": "Cart",
    "version": "0.0.0",
    "filename": "application/controllers/api/Cart.php",
    "groupTitle": "Cart"
  },
  {
    "type": " get ",
    "url": "/cart/:session_id/empty",
    "title": "Empty Cart",
    "name": "Empty",
    "description": "<p>Empty cart</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "session_id",
            "description": "<p>Session ID returned when cart created</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status of request.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>User friendly message.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": true,\n  \"message\": \"message\"\n}",
          "type": "json"
        }
      ]
    },
    "group": "Cart",
    "version": "0.0.0",
    "filename": "application/controllers/api/Cart.php",
    "groupTitle": "Cart"
  },
  {
    "type": " post ",
    "url": "/cart/:session_id/remove",
    "title": "Remove from Cart",
    "name": "Remove",
    "description": "<p>Remove items from cart</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "session_id",
            "description": "<p>Session ID returned when cart created</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Product ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "quantity",
            "description": "<p>Quantity of items.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n    \"id\" : 1,\n    \"quantity\": 5\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "examples": [
        {
          "title": "Header:",
          "content": "{\n    \"Content-Type\": \"application/json\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status of request.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>User friendly message.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>cart object.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": true,\n  \"message\": \"message\",\n  \"data\": {\n         \"id\": \"1\",\n         \"session_id\": \"1234567890\",\n         \"items\": \"[{\\\"id\\\":3,\\\"quantity\\\":1,\\\"price\\\":\\\"100.00\\\",\\\"total_price\\\":100}]\", // cart items stringyfied\n         \"created_at\": \"2019-01-15 04:05:07\",\n         \"updated_at\": null\n   }\n}",
          "type": "json"
        }
      ]
    },
    "group": "Cart",
    "version": "0.0.0",
    "filename": "application/controllers/api/Cart.php",
    "groupTitle": "Cart"
  },
  {
    "type": " get ",
    "url": "/products/:id/details",
    "title": "Product Details",
    "name": "Product_Details",
    "description": "<p>Get details of product specified by id</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Product ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status of request.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Product details.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": true,\n  \"data\": {\n         \"id\": \"1\",\n         \"title\": \"Product 1\",\n         \"price\": \"100.00\",\n         \"inventory_count\": \"10\"\n   }\n}",
          "type": "json"
        }
      ]
    },
    "group": "Products",
    "version": "0.0.0",
    "filename": "application/controllers/api/Products.php",
    "groupTitle": "Products"
  },
  {
    "type": " get ",
    "url": "/products/",
    "title": "Products",
    "name": "Products_List",
    "description": "<p>Get list of products. Filter products by availability. Pass query parameter quantity and the amount of items required</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status of request.</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>List of products.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\": true,\n  \"data\": [\n     {\n         \"id\": \"1\",\n         \"title\": \"Product 1\",\n         \"price\": \"100.00\",\n         \"inventory_count\": \"10\"\n     }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "group": "Products",
    "version": "0.0.0",
    "filename": "application/controllers/api/Products.php",
    "groupTitle": "Products"
  }
] });
