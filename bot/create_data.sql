drop table if exists public.users CASCADE;
drop table if exists public.chats CASCADE;
drop table if exists public.messages_history CASCADE;
drop table if exists public.bot_notification CASCADE;


CREATE TABLE public.bot_notification (
  id integer UNIQUE,
  chat_id integer,
  user_id integer,
  game_id integer,
  pre_start integer,
  pre_start_time integer,
  results integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.bot_notification
    OWNER to postgres;

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
  chat_id integer,
  name text,
  game_id integer,
  chat_state text
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