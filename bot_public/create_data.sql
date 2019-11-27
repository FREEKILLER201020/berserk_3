drop table if exists public.users CASCADE;
drop table if exists public.chats CASCADE;
drop table if exists public.messages_history CASCADE;
drop table if exists public.bot_notification CASCADE;


CREATE TABLE public.bot_notification (
  id SERIAL,
  chat_id integer,
  user_id integer,
  notification_type integer,
  -- pre_start integer,
  pre_start_time integer
  -- results integer
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


drop table if exists public.users_public CASCADE;
drop table if exists public.chats_public CASCADE;
drop table if exists public.messages_history_public CASCADE;
drop table if exists public.bot_notification_public CASCADE;


CREATE TABLE public.bot_notification_public (
  id SERIAL,
  chat_id integer,
  user_id integer,
  notification_type integer,
  -- pre_start integer,
  pre_start_time integer
  -- results integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.bot_notification_public
    OWNER to postgres;

CREATE TABLE public.messages_history_public (
  timemark timestamp,
  id integer UNIQUE,
  message text,
  chat_id integer,
  user_id integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.messages_history_public
    OWNER to postgres;


CREATE TABLE public.users_public (
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

ALTER TABLE public.users_public
    OWNER to postgres;


CREATE TABLE public.chats_public (
  id integer UNIQUE,
  type text,
  title text
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.chats_public
    OWNER to postgres;