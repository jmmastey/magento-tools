create or replace view enterprise_invitation_users_v as 
  select inviter, 
    sum(case when status = 'sent' then 1 else 0 end) sent,
    sum(case when status = 'accepted' then 1 else 0 end) accepted,
    sum(case when status = 'canceled' then 1 else 0 end) cancelled 
  from enterprise_invitation_v group by inviter order by sent desc;
