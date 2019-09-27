drop table if exists public.users CASCADE;


CREATE TABLE public.users (
  id integer UNIQUE,
  game_id integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.users
    OWNER to postgres;

