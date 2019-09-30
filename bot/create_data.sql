drop table if exists public.users CASCADE;
drop table if exists public.chats CASCADE;


CREATE TABLE public.users (
  id integer UNIQUE,
  username text,
  game_id integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.users
    OWNER to postgres;


CREATE TABLE public.chats (
  id integer UNIQUE,
  type text,
  title text
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.users
    OWNER to postgres;