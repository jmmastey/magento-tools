create or replace view broken_configurables as
select oi.item_id, so.increment_id, oi.sku, oi.created_at, cast(oi.qty_ordered as decimal) ordered
  from sales_flat_order_item oi
  join sales_flat_order so on so.entity_id = oi.order_id
  where
    product_type = 'configurable' and
    item_id not in (
      select parent_item_id
        from sales_flat_order_item
        where parent_item_id is not null
    );
