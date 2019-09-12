drop table if exists public.cards cascade;
drop table if exists public.cards_type cascade;
drop table if exists public.cards_race cascade;
drop table if exists public.cards_rarity cascade;
drop table if exists public.cards_crystal cascade;
drop table if exists public.cards_typeEquipment cascade;
drop table if exists public.cards_hate_race cascade;
drop table if exists public.cards_hate_class cascade;

CREATE TABLE public.cards_type
(
    id SERIAL,
    type text UNIQUE,
    "desc" text,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards_type
    OWNER to postgres;

CREATE TABLE public.cards_race
(
    id SERIAL,
    race text UNIQUE,
    "desc" text,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards_race
    OWNER to postgres;

CREATE TABLE public.cards_rarity
(
    id SERIAL,
    rarity text UNIQUE,
    "desc" text,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards_rarity
    OWNER to postgres;

CREATE TABLE public.cards_crystal
(
    id SERIAL,
    crystal text UNIQUE,
    "desc" text,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards_crystal
    OWNER to postgres;

CREATE TABLE public.cards_typeEquipment
(
    id SERIAL,
    typeEquipment text UNIQUE,
    "desc" text,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards_typeEquipment
    OWNER to postgres;

CREATE TABLE public.cards_hate_class
(
    id SERIAL,
    hate_class text UNIQUE,
    "desc" text,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards_hate_class
    OWNER to postgres;

CREATE TABLE public.cards_hate_race
(
    id SERIAL,
    hate_race text UNIQUE,
    "desc" text,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards_hate_race
    OWNER to postgres;

CREATE TABLE public.cards
(
    id SERIAL,
    type integer REFERENCES cards_type(id),
    health integer,
    kick json,
    steps integer,
    race integer REFERENCES cards_race(id),
    name text,
    proto text UNIQUE,
    rarity integer REFERENCES cards_rarity(id),
    fly boolean,
    "desc" text,
    crystal integer REFERENCES cards_crystal(id),
    "crystalCount" integer,
    abilities json,
    f integer,
    rows json,
    "case" json,
    horde json,
    rangeAttack json,
    classes json,
    series integer,
    typeEquipment integer REFERENCES cards_typeEquipment(id),
    hate_class integer REFERENCES cards_hate_class(id),
    hate_race integer REFERENCES cards_hate_race(id),
    "only" integer,
    unlim integer,
    number integer,
    author text,
    main integer REFERENCES cards(id),
    PRIMARY KEY (id),
    UNIQUE (proto)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.cards
    OWNER to postgres;
