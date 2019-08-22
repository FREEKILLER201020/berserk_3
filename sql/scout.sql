drop table if exists public.screenshots cascade;

CREATE TABLE public.screenshots
(
    id SERIAL,
    name text,
    file text,
    timemark timestamp, 
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.screenshots
    OWNER to postgres;


drop table if exists public.deck;

CREATE TABLE public.deck
(
    id SERIAL,
    player_id integer,
    cards integer[],
    screenshot_id integer REFERENCES screenshots(id),
    description text,
	timemark timestamp, 
	edited timestamp,
	type integer,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.deck
    OWNER to postgres;


DROP FUNCTION IF EXISTS public.players(integer);

CREATE OR REPLACE FUNCTION public.players(integer)
    RETURNS SETOF players 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY SELECT 
	distinct on (id)
	timemark, id, nick, frags, deaths, level, clan, folder
from players
where clan=$1
order by id, timemark desc;
END;
$BODY$;

ALTER FUNCTION public.players(integer)
    OWNER TO postgres;


DROP FUNCTION IF EXISTS public.cards();

CREATE OR REPLACE FUNCTION public.cards()
    RETURNS SETOF cards 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY SELECT 
	distinct on (id)
	id, type, health, kick, steps, race, name, proto, rarity, fly, "desc", crystal, "crystalCount", abilities, f, rows, "case", horde, rangeAttack, classes, series, typeEquipment, hate_class, hate_race, "only", unlim, number, author
from cards
order by id desc;
END;
$BODY$;

ALTER FUNCTION public.cards()
    OWNER TO postgres;