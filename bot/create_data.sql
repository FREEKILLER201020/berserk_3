drop table if exists public.users CASCADE;


CREATE TABLE public.users (
  timemark timestamp,
  id integer,
  game_id integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.users
    OWNER to postgres;

