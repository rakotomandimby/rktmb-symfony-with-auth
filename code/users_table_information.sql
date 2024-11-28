CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    first_name character varying(512),
    last_name character varying(512),
    roles character varying(1024)
);

INSERT INTO public.users VALUES (1, 'test@example.com', '$2a$12$rryh8u7AE27ySxMOstkMHuq4RX.sJefYN0W5JQ1.yqkLPOL.pjNBy', 'Mihamina', 'RAKOTOMANDIMBY', 'ROLE_MANAGER,ROLE_AGENT');

