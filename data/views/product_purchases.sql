create or replace view product_purchases as
select sku,
    cast(sum(qty_ordered-qty_refunded-qty_canceled) as decimal) ordered,
    cast(sum(qty_ordered) as decimal) base_ordered
  from sales_flat_order_item
  where product_type = 'simple'
  group by sku;

create or replace view product_purchase_omnibus as
select count(*) entries, cp.sku, oi.product_id, oi.name,
    sum(cast(qty_ordered-qty_refunded-qty_canceled as decimal)) ordered,
    sum(cast(qty_ordered as decimal)) base_ordered,
    ps.sup_name vendor, oi.product_type,
    cast(oi.created_at as date) purchase_date,
    attribute_set_name, cpi.price, cpi.min_price,
    (oi.price-oi.discount_amount) purchase_price,
    cost.value cost,
    (case
      when vis.value = 1 then 'Not Visible'
      when vis.value = 2 then 'Catalog'
      when vis.value = 3 then 'Search'
      when vis.value = 4 then 'Catalog/Search'
    end) visibility,
    (case
      when st.value = 1 then 'Enabled'
      else 'Disabled'
    end) enabled,
    group_concat(distinct ccev.value) event_names
  from sales_flat_order_item oi
    join sales_flat_order o on oi.order_id = o.entity_id
    left join catalog_product_entity cp on cp.entity_id = oi.product_id
    left join eav_attribute_set ast on ast.attribute_set_id = cp.attribute_set_id
    left join purchase_product_supplier pps on pps.pps_product_id = oi.product_id
    left join purchase_supplier ps on ps.sup_id = pps.pps_supplier_num
    left join catalog_product_index_price cpi on cpi.entity_id = oi.product_id
    left join catalog_product_entity_int st on st.entity_id = cp.entity_id
    left join catalog_product_entity_int vis on vis.entity_id = cp.entity_id
    left join catalog_product_entity_decimal cost on cost.entity_id = cp.entity_id
    left join catalog_category_product ccp on ccp.product_id = cp.entity_id
    left join catalog_category_entity cce on cce.entity_id = ccp.category_id
    left join catalog_category_entity_varchar ccev on ccev.entity_id = ccp.category_id
  where
    o.status != 'canceled' and
    cpi.customer_group_id = 0 and
    cpi.website_id = 1 and
    cpi.tax_class_id = 2 and
    st.attribute_id = 84 and
    ccev.attribute_id = 33 and
    vis.attribute_id = 91 and
    cost.attribute_id = 68
  group by
    oi.sku, oi.product_id, oi.name, ps.sup_name, cast(oi.created_at as date),
    attribute_set_name, cpi.price, cpi.min_price, cost.value, oi.price,
    oi.discount_amount, vis.value, st.value, oi.product_type
  order by
    event_names asc, sku asc, purchase_date desc
;
