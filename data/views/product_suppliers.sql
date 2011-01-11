create or replace view product_supplier as
  select entity_id, sku, pps_supplier_num, sup_name
     from catalog_product_entity ce
     left join purchase_product_supplier pps on ce.entity_id = pps.pps_product_id
     left join purchase_supplier ps on pps.pps_supplier_num = ps.sup_id;

create or replace view product_supplier_purchases as
  select entity_id, sku, pps_supplier_num, sup_name,
    (select count(*) from sales_flat_order_item where product_id = ce.entity_id) sold
     from catalog_product_entity ce
     left join purchase_product_supplier pps on ce.entity_id = pps.pps_product_id
     left join purchase_supplier ps on pps.pps_supplier_num = ps.sup_id; 
