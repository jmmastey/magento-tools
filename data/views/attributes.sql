create or replace view v_eav_attributes as
select attribute_id, attribute_code, backend_type, entity_type_code 
  from eav_attribute ea
  join eav_entity_type et on et.entity_type_id = ea.entity_type_id
;
