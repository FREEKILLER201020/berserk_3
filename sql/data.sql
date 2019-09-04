-- clans
DROP FUNCTION IF EXISTS public.clans_list_newest();

CREATE OR REPLACE FUNCTION public.clans_list_newest(
    )
    RETURNS SETOF clans 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark,id,title, points, created, gone
from clans
order by id, timemark desc;
END;
$BODY$;

ALTER FUNCTION public.clans_list_newest()
    OWNER TO postgres;


DROP FUNCTION IF EXISTS public.clans_list_oldest();

CREATE OR REPLACE FUNCTION public.clans_list_oldest(
    )
    RETURNS SETOF clans 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark,id,title, points, created, gone
from clans
order by id, timemark asc;
END;
$BODY$;

ALTER FUNCTION public.clans_list_oldest()
    OWNER TO postgres;

DROP FUNCTION IF EXISTS public.clans_list_all();

CREATE OR REPLACE FUNCTION public.clans_list_all(
	)
    RETURNS SETOF clans 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY SELECT 
	distinct on (id)
    timemark,id,title, points, created, gone
from clans
order by id, timemark desc;
END;
$BODY$;

ALTER FUNCTION public.clans_list_all()
    OWNER TO postgres;

-- players
DROP FUNCTION IF EXISTS public.players_newest();

CREATE OR REPLACE FUNCTION public.players_newest(
    )
    RETURNS SETOF players 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark,id,nick,frags,deaths,level,clan,folder
from players
order by id, timemark desc;
END;
$BODY$;

ALTER FUNCTION public.players_newest()
    OWNER TO postgres;

DROP FUNCTION IF EXISTS public.players_oldest();

CREATE OR REPLACE FUNCTION public.players_oldest(
    )
    RETURNS SETOF players 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark,id,nick,frags,deaths,level,clan,folder
from players
order by id, timemark asc;
END;
$BODY$;

ALTER FUNCTION public.players_oldest()
    OWNER TO postgres;

DROP FUNCTION IF EXISTS public.players_all();

CREATE OR REPLACE FUNCTION public.players_all(
	)
    RETURNS SETOF players 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
	distinct on (id)
    timemark,id,nick,frags,deaths,level,clan,folder
from players
order by id, timemark desc;
END;
$BODY$;

ALTER FUNCTION public.players_all()
    OWNER TO postgres;

-- cities
DROP FUNCTION IF EXISTS public.cities_newest();

CREATE OR REPLACE FUNCTION public.cities_newest(
    )
    RETURNS SETOF cities 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark, id, name, clan 
from cities
order by id, timemark desc;
END;
$BODY$;

ALTER FUNCTION public.cities_newest()
    OWNER TO postgres;

DROP FUNCTION IF EXISTS public.cities_oldest();

CREATE OR REPLACE FUNCTION public.cities_oldest(
    )
    RETURNS SETOF cities 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark, id, name, clan 
from cities
order by id, timemark asc;
END;
$BODY$;

ALTER FUNCTION public.cities_oldest()
    OWNER TO postgres;


DROP FUNCTION IF EXISTS public.cities_all();

CREATE OR REPLACE FUNCTION public.cities_all(
	)
    RETURNS SETOF cities 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
	distinct on (id)
    timemark, id, name, clan 
from cities
order by id, timemark desc;
END;
$BODY$;

ALTER FUNCTION public.cities_all()
    OWNER TO postgres;


DROP FUNCTION IF EXISTS public.get_clan_id(text);

CREATE OR REPLACE FUNCTION public.get_clan_id(
	text
	)
    RETURNS SETOF clans 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
	distinct on (id)
    timemark,id,title, points, gone
from clans
where title=$1
order by id, timemark desc limit 1;
END;
$BODY$;

ALTER FUNCTION public.get_clan_id(text)
    OWNER TO postgres;

DROP FUNCTION IF EXISTS public.get_city_id(text);

CREATE OR REPLACE FUNCTION public.get_city_id(
    text
    )
    RETURNS SETOF cities 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark, id, name, clan 
from cities
where name=$1
order by id, timemark desc limit 1;
END;
$BODY$;

ALTER FUNCTION public.get_city_id(text)
    OWNER TO postgres;

DROP FUNCTION IF EXISTS public.get_city_by_id(integer);

CREATE OR REPLACE FUNCTION public.get_city_by_id(
    integer
    )
    RETURNS SETOF cities 
    LANGUAGE 'plpgsql'

AS $BODY$
BEGIN
    RETURN QUERY SELECT 
    distinct on (id)
    timemark, id, name, clan 
from cities
where id=$1
order by id, timemark desc limit 1;
END;
$BODY$;

ALTER FUNCTION public.get_city_by_id(integer)
    OWNER TO postgres;