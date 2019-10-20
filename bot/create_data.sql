drop table if exists public.users CASCADE;
drop table if exists public.chats CASCADE;
drop table if exists public.messages_history CASCADE;

CREATE TABLE public.messages_history (
  timemark timestamp,
  id integer UNIQUE,
  message text,
  chat_id integer,
  user_id integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.messages_history
    OWNER to postgres;


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

ALTER TABLE public.chats
    OWNER to postgres;