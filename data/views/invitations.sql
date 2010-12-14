create or replace view enterprise_invitation_v as 
  select 
    (select email from customer_entity ce where ce.entity_id = ei.customer_id) inviter, 
    email invitee, status, date invite_date, signup_date 
  from enterprise_invitation ei order by date desc;
