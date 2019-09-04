drop table if exists public.players CASCADE;
drop table if exists public.eras CASCADE;
drop table if exists public.logs CASCADE;
drop table if exists public.cities CASCADE;
drop table if exists public.attacks CASCADE;
drop table if exists public.clans CASCADE;
drop table if exists public.clans_updates CASCADE;
drop table if exists public.players_updates CASCADE;



CREATE TABLE public.clans_updates (
  timemark timestamp,
  id text,
  title text,
  new_title text,
  created timestamp,
  gone timestamp
  -- primary key ("id", created)
  -- PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.clans_updates
    OWNER to postgres;


CREATE TABLE public.players_updates (
  timemark timestamp,
  id integer,
  nick text,
  new_nick text,
  old_clan text,
  new_clan text
  -- PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.players_updates
    OWNER to postgres;

CREATE TABLE public.clans (
  timemark timestamp,
  id integer,
  title text,
  points integer,
  created timestamp,
  gone timestamp
  -- PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.clans
    OWNER to postgres;

CREATE TABLE public.attacks
(
  "from" integer,
  "to" integer,
  attacker integer ,
  -- REFERENCES clans(id),
  defender integer ,
  -- REFERENCES clans(id),
  declared timestamp,
  resolved timestamp,
  ended timestamp,
  winer integer,
  folder text,
  primary key ("from", "to",attacker,defender,declared,resolved)
  -- REFERENCES clans(id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.attacks
    OWNER to postgres;

CREATE TABLE public.cities (
  timemark timestamp,
  id integer,
  name text,
  clan integer
  -- REFERENCES clans(id),
  -- PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cities
    OWNER to postgres;


CREATE TABLE public.eras
(
  id SERIAL,
  started date,
  ended date,
  lbz json,
  pointw integer
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.eras
    OWNER to postgres;

-- --------------------------------------------------------

--
-- Table structure for table `Logs`
--

CREATE TABLE public.logs
(
  timemark timestamp,
  "log" json
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.logs
    OWNER to postgres;

CREATE TABLE public.players
(
  timemark timestamp,
  id integer,
  nick text,
  frags integer,
  deaths integer,
  level integer,
  clan integer,
  folder text
  -- REFERENCES clans(id),
  -- PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.players
    OWNER to postgres;

INSERT INTO public.eras(
  id, started, ended, lbz, pointw)
  VALUES (52,'2019-01-28','2019-02-19','{"5":"Рарка","10":"1б","15":"1б + Возмездие","25":"1б + 2б"}',0);
INSERT INTO public.eras(
  id, started, ended, lbz, pointw)
  VALUES (53,'2019-03-22','2019-04-12','{"5":"Рарка","10":"1б","15":"1б + Возмездие","25":"1б + 2б"}',0);
INSERT INTO public.eras(
  id, started, ended, pointw)
  VALUES (54,'2019-05-29','2019-06-18',0);
INSERT INTO public.eras(
  id, started, ended, lbz, pointw)
  VALUES (55,'2019-07-01','2019-07-21','{"5":"Рарка","10":"1б","15":"1б + Возмездие","25":"1б + 2б","50":"2б + Ультра"}',0);
INSERT INTO public.eras(
  id, started, ended, lbz, pointw)
  VALUES (56,'2019-09-01','2019-09-21','{"5":"Рарка","10":"1б","15":"1б + Возмездие","25":"1б + 2б","40":"2б + Неофит"}',0);






