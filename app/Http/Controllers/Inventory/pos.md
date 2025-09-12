pos
    pos-view

Statinory-items
    Statinory-items-store
    Statinory-items-delete
Statinory-suppliers
    Statinory-suppliers-store
    Statinory-suppliers-delete
Statinory-requisitions
    Statinory-requisitions-store
    Statinory-requisitions-delete
    Statinory-requisition-approval
Statinory-quotes
    Statinory-quotes-store
    Statinory-quotes-delete
Statinory-purchase-order
    Statinory-purchase-order-store
    Statinory-purchase-order-delete
Statinory-store-inventory
    Statinory-inventory-store
Statinory-bundles
    Statinory-products

Cafe-items
    Cafe-items-store
    Cafe-items-delete
Cafe-suppliers
    Cafe-suppliers-store
    Cafe-suppliers-delete
Cafe-requisitions
    Cafe-requisitions-store
    Cafe-requisitions-delete
    Cafe-requisition-approval
Cafe-quotes
    Cafe-quotes-store
    Cafe-quotes-delete
Cafe-purchase-order
    Cafe-purchase-order-store
    Cafe-purchase-order-delete
Cafe-store-inventory
    Cafe-inventory-store
Inventry
    cafe-inventry-store
Products
    Cafe-products
Completed-Goods
    Completed-Goods-store 
Student-Lunch
    Student-Lunch-create
Student-Lunch-Assigned
    Student-Lunch-Assigned-view
Staff-Lunch
    Staff-Lunch-create
Staff-Lunch-Assigned
    Staff-Lunch-Assigned-view


    INSERT INTO `permissions`(
    `id`,
    `name`,
    `main`,
    `parent_id`,
    `guard_name`,
    `created_at`,
    `updated_at`,
    `deleted_at`
)
VALUES(
    '[value-1]',
    '[value-2]',
    '[value-3]',
    '[value-4]',
    '[value-5]',
    '[value-6]',
    '[value-7]',
    '[value-8]'
)


createt sql for the following data use the main heading as parent_id