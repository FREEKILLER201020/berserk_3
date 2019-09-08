drop table if exists public.notifications cascade;

CREATE TABLE public.notifications
(
    id SERIAL,
    user_id integer,
    user_key text UNIQUE,
    clan_id integer,
    type json,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.notifications
    OWNER to postgres;

insert into notifications (user_key,type,clan_id) values ('uuaj196grt8gjg6femsnjgc8tte1k8','{"attacks":"1","results":"1"}',171)
	insert into notifications (user_key,type,clan_id) values ('uuaj196grt8gjg6femsnjgc8tte1k8','{"attacks":"1"}',171)



drop table if exists public.clans_updates CASCADE;


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



INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 6, 'Fireborn', '2013-02-20 09:38:54');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 8, 'Красная Капелла', '2013-02-20 10:07:15');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 26, 'Орден Дракона', '2013-02-24 15:50:30');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 30, 'Альянс', '2013-03-05 06:31:49');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 165, 'Epic', '2015-10-31 09:16:10');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 171, '"Берсерк"', '2015-12-31 16:02:00');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 174, 'Апостолы', '2017-01-12 12:53:42');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 179, 'Хранители Тайн', '2017-06-02 18:24:09');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 186, 'Отряд Самоубийц', '2018-02-02 12:26:55');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 187, 'Белый Орден', '2018-08-28 07:24:08');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-01-26 21:00:01', 188, 'Phoenix Warriors', '2018-10-22 18:33:27');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-02-19 21:00:01', 189, 'Пирожочек', '2019-02-19 20:59:43');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-04-30 21:03:01', 190, 'IDDQD', '2019-04-30 12:26:44');
INSERT INTO public.clans_updates(timemark, id, title, created,gone) VALUES ('2019-06-04 09:38:02', 190, 'IDDQD', '2019-04-30 12:26:44','2019-06-04 09:38:02');
INSERT INTO public.clans_updates(timemark, id, title, new_title,created) VALUES ('2019-06-04 19:54:02', 186, 'Отряд Самоубийц','IDDQD', '2018-02-02 12:26:55');
INSERT INTO public.clans_updates(timemark, id, title, created) VALUES ('2019-09-02 12:04:02', 191, 'Generation Y', '2019-09-02 12:03:29');


