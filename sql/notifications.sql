drop table if exists public.notifications cascade;

CREATE TABLE public.notifications
(
    id SERIAL,
    user_id integer,
    user_key text UNIQUE,
    clan_id integer,
    type json,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.notifications
    OWNER to postgres;

insert into notifications (user_key,type,clan_id) values ('uuaj196grt8gjg6femsnjgc8tte1k8','{"attacks":"1","results":"1"}',171)