# ERD Inventory and Sales Management

```mermaid
erDiagram
    USERS ||--o{ PURCHASES : creates
    USERS ||--o{ SALES : creates
    CATEGORIES ||--o{ PRODUCTS : contains
    SUPPLIERS ||--o{ PURCHASES : supplies
    PURCHASES ||--|{ PURCHASE_ITEMS : contains
    SALES ||--|{ SALE_ITEMS : contains
    PRODUCTS ||--o{ PURCHASE_ITEMS : appears_in
    PRODUCTS ||--o{ SALE_ITEMS : appears_in

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        string role
    }
    CATEGORIES {
        bigint id PK
        string name UK
        text description
    }
    PRODUCTS {
        bigint id PK
        bigint category_id FK
        string sku UK
        string name
        decimal purchase_price
        decimal selling_price
        integer stock
        integer minimum_stock
    }
    SUPPLIERS {
        bigint id PK
        string name
        string phone
        string email
        text address
    }
    PURCHASES {
        bigint id PK
        bigint supplier_id FK
        bigint user_id FK
        string purchase_number UK
        decimal total
        string status
        datetime purchased_at
    }
    PURCHASE_ITEMS {
        bigint id PK
        bigint purchase_id FK
        bigint product_id FK
        integer quantity
        decimal unit_cost
        decimal subtotal
    }
    SALES {
        bigint id PK
        bigint user_id FK
        string sale_number UK
        decimal total
        string status
        datetime sold_at
    }
    SALE_ITEMS {
        bigint id PK
        bigint sale_id FK
        bigint product_id FK
        integer quantity
        decimal unit_price
        decimal subtotal
    }
```

Each purchase or sale has one header and one or more item rows. Each item stores the price used at the time of the transaction so later product price changes do not alter transaction history. Product and supplier records referenced by transactions are protected from hard deletion.
