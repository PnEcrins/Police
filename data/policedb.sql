--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: interventions; Type: SCHEMA; Schema: -; Owner: policeuser
--

CREATE SCHEMA interventions;


ALTER SCHEMA interventions OWNER TO policeuser;

--
-- Name: layers; Type: SCHEMA; Schema: -; Owner: policeuser
--

CREATE SCHEMA layers;


ALTER SCHEMA layers OWNER TO policeuser;

--
-- Name: utilisateurs; Type: SCHEMA; Schema: -; Owner: policeuser
--

CREATE SCHEMA utilisateurs;


ALTER SCHEMA utilisateurs OWNER TO policeuser;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


SET search_path = utilisateurs, pg_catalog;

--
-- Name: modify_date_insert(); Type: FUNCTION; Schema: utilisateurs; Owner: policeuser
--

CREATE FUNCTION modify_date_insert() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.date_insert := now();
    NEW.date_update := now();
    RETURN NEW;
END;
$$;


ALTER FUNCTION utilisateurs.modify_date_insert() OWNER TO policeuser;

--
-- Name: modify_date_update(); Type: FUNCTION; Schema: utilisateurs; Owner: policeuser
--

CREATE FUNCTION modify_date_update() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.date_update := now();
    RETURN NEW;
END;
$$;


ALTER FUNCTION utilisateurs.modify_date_update() OWNER TO policeuser;

SET search_path = interventions, pg_catalog;

--
-- Name: bib_infractions_id_infraction_seq; Type: SEQUENCE; Schema: interventions; Owner: policeuser
--

CREATE SEQUENCE bib_infractions_id_infraction_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interventions.bib_infractions_id_infraction_seq OWNER TO policeuser;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: bib_infractions; Type: TABLE; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE TABLE bib_infractions (
    id_infraction integer DEFAULT nextval('bib_infractions_id_infraction_seq'::regclass) NOT NULL,
    infraction character varying(50) NOT NULL
);


ALTER TABLE interventions.bib_infractions OWNER TO policeuser;

--
-- Name: bib_qualification; Type: TABLE; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE TABLE bib_qualification (
    id_qualification integer NOT NULL,
    qualification character varying(100) NOT NULL,
    delai integer DEFAULT 0 NOT NULL
);


ALTER TABLE interventions.bib_qualification OWNER TO policeuser;

--
-- Name: bib_qualification_id_qualification_seq; Type: SEQUENCE; Schema: interventions; Owner: policeuser
--

CREATE SEQUENCE bib_qualification_id_qualification_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interventions.bib_qualification_id_qualification_seq OWNER TO policeuser;

--
-- Name: bib_qualification_id_qualification_seq; Type: SEQUENCE OWNED BY; Schema: interventions; Owner: policeuser
--

ALTER SEQUENCE bib_qualification_id_qualification_seq OWNED BY bib_qualification.id_qualification;


--
-- Name: bib_statut_zone_id_statutzone_seq; Type: SEQUENCE; Schema: interventions; Owner: policeuser
--

CREATE SEQUENCE bib_statut_zone_id_statutzone_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interventions.bib_statut_zone_id_statutzone_seq OWNER TO policeuser;

--
-- Name: bib_statutszone; Type: TABLE; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE TABLE bib_statutszone (
    id_statutzone integer DEFAULT nextval('bib_statut_zone_id_statutzone_seq'::regclass) NOT NULL,
    statutzone character varying(50) NOT NULL,
    ordre integer
);


ALTER TABLE interventions.bib_statutszone OWNER TO policeuser;

--
-- Name: bib_types_interventions_id_type_intervention_seq; Type: SEQUENCE; Schema: interventions; Owner: policeuser
--

CREATE SEQUENCE bib_types_interventions_id_type_intervention_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interventions.bib_types_interventions_id_type_intervention_seq OWNER TO policeuser;

--
-- Name: bib_types_interventions; Type: TABLE; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE TABLE bib_types_interventions (
    id_type_intervention integer DEFAULT nextval('bib_types_interventions_id_type_intervention_seq'::regclass) NOT NULL,
    type_intervention character varying(50) NOT NULL
);


ALTER TABLE interventions.bib_types_interventions OWNER TO policeuser;

--
-- Name: cor_interventions_agents; Type: TABLE; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE TABLE cor_interventions_agents (
    intervention_id integer NOT NULL,
    utilisateur_id integer NOT NULL
);


ALTER TABLE interventions.cor_interventions_agents OWNER TO policeuser;

--
-- Name: cor_interventions_infractions; Type: TABLE; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE TABLE cor_interventions_infractions (
    intervention_id integer NOT NULL,
    infraction_id integer NOT NULL,
    qualification_id integer NOT NULL
);


ALTER TABLE interventions.cor_interventions_infractions OWNER TO policeuser;

--
-- Name: t_interventions_id_intervention_seq; Type: SEQUENCE; Schema: interventions; Owner: policeuser
--

CREATE SEQUENCE t_interventions_id_intervention_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interventions.t_interventions_id_intervention_seq OWNER TO policeuser;

--
-- Name: t_interventions; Type: TABLE; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE TABLE t_interventions (
    id_intervention integer DEFAULT nextval('t_interventions_id_intervention_seq'::regclass) NOT NULL,
    date date NOT NULL,
    observation text,
    commune_id integer,
    statutzone_id integer,
    type_intervention_id integer,
    coord_x numeric(10,8),
    coord_y numeric(10,8),
    the_geom public.geometry,
    suivi_date_limite date,
    suivi_num_parquet character varying(20),
    suivi_suite_donnee text,
    suivi_commentaire text,
    nbcontrevenants integer,
    suivi_montant_amende integer,
    suivi_partie_civile boolean,
    suivi_montant_dommages integer,
    suivi_date_constitution date,
    secteur_id integer,
    suivi_appel_avocat boolean,
    suivi_date_audience date,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.st_ndims(the_geom) = 2)),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.st_srid(the_geom) = 27572))
);


ALTER TABLE interventions.t_interventions OWNER TO policeuser;

SET search_path = utilisateurs, pg_catalog;

--
-- Name: cor_role_droit_application; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE cor_role_droit_application (
    id_role integer NOT NULL,
    id_droit integer NOT NULL,
    id_application integer NOT NULL
);


ALTER TABLE utilisateurs.cor_role_droit_application OWNER TO policeuser;

--
-- Name: cor_roles; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE cor_roles (
    id_role_groupe integer NOT NULL,
    id_role_utilisateur integer NOT NULL
);


ALTER TABLE utilisateurs.cor_roles OWNER TO policeuser;

--
-- Name: t_roles_id_seq; Type: SEQUENCE; Schema: utilisateurs; Owner: policeuser
--

CREATE SEQUENCE t_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE utilisateurs.t_roles_id_seq OWNER TO policeuser;

--
-- Name: t_roles; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE t_roles (
    groupe boolean DEFAULT false NOT NULL,
    id_role integer DEFAULT nextval('t_roles_id_seq'::regclass) NOT NULL,
    identifiant character varying(100),
    nom_role character varying(50),
    prenom_role character varying(50),
    desc_role text,
    pass character varying(100),
    email character varying(250),
    organisme character(32),
    id_unite integer,
    pn boolean,
    session_appli character varying(50),
    date_insert timestamp without time zone,
    date_update timestamp without time zone,
    id_organisme integer,
    remarques text
);


ALTER TABLE utilisateurs.t_roles OWNER TO policeuser;

SET search_path = interventions, pg_catalog;

--
-- Name: vue_agents; Type: VIEW; Schema: interventions; Owner: policeuser
--

CREATE VIEW vue_agents AS
  SELECT a.groupe,
    a.id_role,
    a.identifiant,
    a.nom_role,
    a.prenom_role,
    a.desc_role,
    a.pass,
    a.email,
    a.organisme,
    a.id_unite,
    a.pn,
    a.session_appli,
    a.date_insert,
    a.date_update,
    max(a.id_droit) AS id_droit_police
  FROM ( SELECT u.groupe,
            u.id_role,
            u.identifiant,
            u.nom_role,
            u.prenom_role,
            u.desc_role,
            u.pass,
            u.email,
            u.organisme,
            u.id_unite,
            u.pn,
            u.session_appli,
            u.date_insert,
            u.date_update,
            u.id_organisme,
            u.remarques,
            c.id_droit
           FROM utilisateurs.t_roles u
             JOIN utilisateurs.cor_role_droit_application c ON c.id_role = u.id_role
          WHERE c.id_application = 3 AND u.groupe = false
        UNION
         SELECT u.groupe,
            u.id_role,
            u.identifiant,
            u.nom_role,
            u.prenom_role,
            u.desc_role,
            u.pass,
            u.email,
            u.organisme,
            u.id_unite,
            u.pn,
            u.session_appli,
            u.date_insert,
            u.date_update,
            u.id_organisme,
            u.remarques,
            c.id_droit
           FROM utilisateurs.t_roles u
             JOIN utilisateurs.cor_roles g ON g.id_role_utilisateur = u.id_role
             JOIN utilisateurs.cor_role_droit_application c ON c.id_role = g.id_role_groupe
          WHERE c.id_application = 3) a
  GROUP BY a.groupe, a.id_role, a.identifiant, a.nom_role, a.prenom_role, a.desc_role, a.pass, a.email, a.organisme, a.id_unite, a.pn, a.session_appli, a.date_insert, a.date_update;


ALTER TABLE interventions.vue_agents OWNER TO policeuser;

SET search_path = layers, pg_catalog;

--
-- Name: l_communes; Type: TABLE; Schema: layers; Owner: policeuser; Tablespace: 
--

CREATE TABLE l_communes (
    gid integer NOT NULL,
    insee text NOT NULL,
    commune text,
    the_geom public.geometry,
    id_commune integer NOT NULL,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.st_ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.st_srid(the_geom) = 27572))
);


ALTER TABLE layers.l_communes OWNER TO policeuser;

--
-- Name: l_secteurs; Type: TABLE; Schema: layers; Owner: policeuser; Tablespace: 
--

CREATE TABLE l_secteurs (
    id_sect integer NOT NULL,
    secteur character varying(20) NOT NULL,
    the_geom public.geometry,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.st_ndims(the_geom) = 2)),
    CONSTRAINT enforce_geotype_the_geom CHECK (((public.geometrytype(the_geom) = 'MULTIPOLYGON'::text) OR (the_geom IS NULL))),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.st_srid(the_geom) = 27572))
);


ALTER TABLE layers.l_secteurs OWNER TO policeuser;

--
-- Name: l_statut_zone; Type: TABLE; Schema: layers; Owner: policeuser; Tablespace: 
--

CREATE TABLE l_statut_zone (
    id bigint NOT NULL,
    intitule_objet character varying(255),
    the_geom public.geometry,
    id_statut_zone integer,
    ordre bigint,
    CONSTRAINT enforce_dims_the_geom CHECK ((public.st_ndims(the_geom) = 2)),
    CONSTRAINT enforce_srid_the_geom CHECK ((public.st_srid(the_geom) = 27572))
);


ALTER TABLE layers.l_statut_zone OWNER TO policeuser;

SET search_path = utilisateurs, pg_catalog;

--
-- Name: bib_droits; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE bib_droits (
    id_droit integer NOT NULL,
    nom_droit character varying(50),
    desc_droit text
);


ALTER TABLE utilisateurs.bib_droits OWNER TO policeuser;

--
-- Name: bib_organismes_id_seq; Type: SEQUENCE; Schema: utilisateurs; Owner: policeuser
--

CREATE SEQUENCE bib_organismes_id_seq
    START WITH 100
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE utilisateurs.bib_organismes_id_seq OWNER TO policeuser;

--
-- Name: bib_organismes; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE bib_organismes (
    nom_organisme character varying(100) NOT NULL,
    adresse_organisme character varying(128),
    cp_organisme character varying(5),
    ville_organisme character varying(100),
    tel_organisme character varying(14),
    fax_organisme character varying(14),
    email_organisme character varying(100),
    id_organisme integer DEFAULT nextval('bib_organismes_id_seq'::regclass) NOT NULL
);


ALTER TABLE utilisateurs.bib_organismes OWNER TO policeuser;

--
-- Name: bib_unites_id_seq; Type: SEQUENCE; Schema: utilisateurs; Owner: policeuser
--

CREATE SEQUENCE bib_unites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE utilisateurs.bib_unites_id_seq OWNER TO policeuser;

--
-- Name: bib_unites; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE bib_unites (
    nom_unite character varying(50) NOT NULL,
    adresse_unite character varying(128),
    cp_unite character varying(5),
    ville_unite character varying(100),
    tel_unite character varying(14),
    fax_unite character varying(14),
    email_unite character varying(100),
    id_unite integer DEFAULT nextval('bib_unites_id_seq'::regclass) NOT NULL
);


ALTER TABLE utilisateurs.bib_unites OWNER TO policeuser;

--
-- Name: cor_role_menu; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE cor_role_menu (
    id_role integer NOT NULL,
    id_menu integer NOT NULL
);


ALTER TABLE utilisateurs.cor_role_menu OWNER TO policeuser;

--
-- Name: TABLE cor_role_menu; Type: COMMENT; Schema: utilisateurs; Owner: policeuser
--

COMMENT ON TABLE cor_role_menu IS 'gestion du contenu des menus utilisateurs dans les applications';


--
-- Name: t_applications; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE t_applications (
    id_application integer NOT NULL,
    nom_application character varying(50) NOT NULL,
    desc_application text
);


ALTER TABLE utilisateurs.t_applications OWNER TO policeuser;

--
-- Name: t_applications_id_application_seq; Type: SEQUENCE; Schema: utilisateurs; Owner: policeuser
--

CREATE SEQUENCE t_applications_id_application_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE utilisateurs.t_applications_id_application_seq OWNER TO policeuser;

--
-- Name: t_applications_id_application_seq; Type: SEQUENCE OWNED BY; Schema: utilisateurs; Owner: policeuser
--

ALTER SEQUENCE t_applications_id_application_seq OWNED BY t_applications.id_application;


--
-- Name: t_menus; Type: TABLE; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

CREATE TABLE t_menus (
    id_menu integer NOT NULL,
    nom_menu character varying(50) NOT NULL,
    desc_menu text,
    id_application integer
);


ALTER TABLE utilisateurs.t_menus OWNER TO policeuser;

--
-- Name: TABLE t_menus; Type: COMMENT; Schema: utilisateurs; Owner: policeuser
--

COMMENT ON TABLE t_menus IS 'table des menus déroulants des applications. Les roles de niveau groupes ou utilisateurs devant figurer dans un menu sont gérés dans la table cor_role_menu_application.';


--
-- Name: t_menus_id_menu_seq; Type: SEQUENCE; Schema: utilisateurs; Owner: policeuser
--

CREATE SEQUENCE t_menus_id_menu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE utilisateurs.t_menus_id_menu_seq OWNER TO policeuser;

--
-- Name: t_menus_id_menu_seq; Type: SEQUENCE OWNED BY; Schema: utilisateurs; Owner: policeuser
--

ALTER SEQUENCE t_menus_id_menu_seq OWNED BY t_menus.id_menu;


SET search_path = interventions, pg_catalog;

--
-- Name: id_qualification; Type: DEFAULT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY bib_qualification ALTER COLUMN id_qualification SET DEFAULT nextval('bib_qualification_id_qualification_seq'::regclass);


SET search_path = utilisateurs, pg_catalog;

--
-- Name: id_application; Type: DEFAULT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY t_applications ALTER COLUMN id_application SET DEFAULT nextval('t_applications_id_application_seq'::regclass);


--
-- Name: id_menu; Type: DEFAULT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY t_menus ALTER COLUMN id_menu SET DEFAULT nextval('t_menus_id_menu_seq'::regclass);


SET search_path = interventions, pg_catalog;

--
-- Name: fk_bib_infractions; Type: CONSTRAINT; Schema: interventions; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY bib_infractions
    ADD CONSTRAINT fk_bib_infractions PRIMARY KEY (id_infraction);


--
-- Name: fk_bib_interventions; Type: CONSTRAINT; Schema: interventions; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY bib_types_interventions
    ADD CONSTRAINT fk_bib_interventions PRIMARY KEY (id_type_intervention);


--
-- Name: fk_bib_qualification; Type: CONSTRAINT; Schema: interventions; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY bib_qualification
    ADD CONSTRAINT fk_bib_qualification PRIMARY KEY (id_qualification);


--
-- Name: fk_bib_statutzone; Type: CONSTRAINT; Schema: interventions; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY bib_statutszone
    ADD CONSTRAINT fk_bib_statutzone PRIMARY KEY (id_statutzone);


--
-- Name: fk_t_infractions; Type: CONSTRAINT; Schema: interventions; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY t_interventions
    ADD CONSTRAINT fk_t_infractions PRIMARY KEY (id_intervention);


--
-- Name: ft_cor_interventions_infractions; Type: CONSTRAINT; Schema: interventions; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY cor_interventions_infractions
    ADD CONSTRAINT ft_cor_interventions_infractions PRIMARY KEY (intervention_id, infraction_id, qualification_id);


--
-- Name: pk_cor_interventions_agents; Type: CONSTRAINT; Schema: interventions; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY cor_interventions_agents
    ADD CONSTRAINT pk_cor_interventions_agents PRIMARY KEY (intervention_id, utilisateur_id);


SET search_path = layers, pg_catalog;

--
-- Name: pk_l_communes; Type: CONSTRAINT; Schema: layers; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY l_communes
    ADD CONSTRAINT pk_l_communes PRIMARY KEY (id_commune);


--
-- Name: pk_l_secteurs; Type: CONSTRAINT; Schema: layers; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY l_secteurs
    ADD CONSTRAINT pk_l_secteurs PRIMARY KEY (id_sect);


--
-- Name: prov_pkey; Type: CONSTRAINT; Schema: layers; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY l_statut_zone
    ADD CONSTRAINT prov_pkey PRIMARY KEY (id);


SET search_path = utilisateurs, pg_catalog;

--
-- Name: bib_droits_pkey; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY bib_droits
    ADD CONSTRAINT bib_droits_pkey PRIMARY KEY (id_droit);


--
-- Name: cor_role_droit_application_pkey; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY cor_role_droit_application
    ADD CONSTRAINT cor_role_droit_application_pkey PRIMARY KEY (id_role, id_droit, id_application);


--
-- Name: cor_role_menu_pkey; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY cor_role_menu
    ADD CONSTRAINT cor_role_menu_pkey PRIMARY KEY (id_role, id_menu);


--
-- Name: cor_roles_pkey; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY cor_roles
    ADD CONSTRAINT cor_roles_pkey PRIMARY KEY (id_role_groupe, id_role_utilisateur);


--
-- Name: pk_bib_organismes; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY bib_organismes
    ADD CONSTRAINT pk_bib_organismes PRIMARY KEY (id_organisme);


--
-- Name: pk_bib_services; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY bib_unites
    ADD CONSTRAINT pk_bib_services PRIMARY KEY (id_unite);


--
-- Name: pk_roles; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY t_roles
    ADD CONSTRAINT pk_roles PRIMARY KEY (id_role);


--
-- Name: t_applications_pkey; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY t_applications
    ADD CONSTRAINT t_applications_pkey PRIMARY KEY (id_application);


--
-- Name: t_menus_pkey; Type: CONSTRAINT; Schema: utilisateurs; Owner: policeuser; Tablespace: 
--

ALTER TABLE ONLY t_menus
    ADD CONSTRAINT t_menus_pkey PRIMARY KEY (id_menu);


SET search_path = interventions, pg_catalog;

--
-- Name: fki_cor_agents_bib_agents; Type: INDEX; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_cor_agents_bib_agents ON cor_interventions_agents USING btree (utilisateur_id);


--
-- Name: fki_cor_infractions_bib_infractions; Type: INDEX; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_cor_infractions_bib_infractions ON cor_interventions_infractions USING btree (infraction_id);


--
-- Name: fki_cor_infractions_bib_qualification; Type: INDEX; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_cor_infractions_bib_qualification ON cor_interventions_infractions USING btree (qualification_id);


--
-- Name: fki_t_interventions_bib_statutszone; Type: INDEX; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_t_interventions_bib_statutszone ON t_interventions USING btree (statutzone_id);


--
-- Name: fki_t_interventions_bib_types_interventions; Type: INDEX; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_t_interventions_bib_types_interventions ON t_interventions USING btree (type_intervention_id);


--
-- Name: fki_t_interventions_l_communes; Type: INDEX; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_t_interventions_l_communes ON t_interventions USING btree (commune_id);


--
-- Name: fki_t_interventions_l_secteurs; Type: INDEX; Schema: interventions; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_t_interventions_l_secteurs ON t_interventions USING btree (secteur_id);


SET search_path = layers, pg_catalog;

--
-- Name: fki_t_bib_statutszone_l_statut_zone; Type: INDEX; Schema: layers; Owner: policeuser; Tablespace: 
--

CREATE INDEX fki_t_bib_statutszone_l_statut_zone ON l_statut_zone USING btree (id_statut_zone);


SET search_path = utilisateurs, pg_catalog;

--
-- Name: modify_date_insert_trigger; Type: TRIGGER; Schema: utilisateurs; Owner: policeuser
--

CREATE TRIGGER modify_date_insert_trigger BEFORE INSERT ON t_roles FOR EACH ROW EXECUTE PROCEDURE modify_date_insert();


--
-- Name: modify_date_update_trigger; Type: TRIGGER; Schema: utilisateurs; Owner: policeuser
--

CREATE TRIGGER modify_date_update_trigger BEFORE UPDATE ON t_roles FOR EACH ROW EXECUTE PROCEDURE modify_date_update();


SET search_path = interventions, pg_catalog;

--
-- Name: fk_cor_agents_t_interventions; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY cor_interventions_agents
    ADD CONSTRAINT fk_cor_agents_t_interventions FOREIGN KEY (intervention_id) REFERENCES t_interventions(id_intervention) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_cor_agents_t_roles; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY cor_interventions_agents
    ADD CONSTRAINT fk_cor_agents_t_roles FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs.t_roles(id_role) ON UPDATE CASCADE;


--
-- Name: fk_cor_infractions_bib_infractions; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY cor_interventions_infractions
    ADD CONSTRAINT fk_cor_infractions_bib_infractions FOREIGN KEY (infraction_id) REFERENCES bib_infractions(id_infraction) ON UPDATE CASCADE;


--
-- Name: fk_cor_infractions_bib_qualification; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY cor_interventions_infractions
    ADD CONSTRAINT fk_cor_infractions_bib_qualification FOREIGN KEY (qualification_id) REFERENCES bib_qualification(id_qualification) ON UPDATE CASCADE;


--
-- Name: fk_cor_infractions_t_interventions; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY cor_interventions_infractions
    ADD CONSTRAINT fk_cor_infractions_t_interventions FOREIGN KEY (intervention_id) REFERENCES t_interventions(id_intervention) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_t_interventions_bib_statutszone; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY t_interventions
    ADD CONSTRAINT fk_t_interventions_bib_statutszone FOREIGN KEY (statutzone_id) REFERENCES bib_statutszone(id_statutzone) ON UPDATE CASCADE;


--
-- Name: fk_t_interventions_bib_types_interventions; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY t_interventions
    ADD CONSTRAINT fk_t_interventions_bib_types_interventions FOREIGN KEY (type_intervention_id) REFERENCES bib_types_interventions(id_type_intervention) ON UPDATE CASCADE;


--
-- Name: fk_t_interventions_l_communes; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY t_interventions
    ADD CONSTRAINT fk_t_interventions_l_communes FOREIGN KEY (commune_id) REFERENCES layers.l_communes(id_commune) ON UPDATE CASCADE;


--
-- Name: fk_t_interventions_l_secteurs; Type: FK CONSTRAINT; Schema: interventions; Owner: policeuser
--

ALTER TABLE ONLY t_interventions
    ADD CONSTRAINT fk_t_interventions_l_secteurs FOREIGN KEY (secteur_id) REFERENCES layers.l_secteurs(id_sect) ON UPDATE CASCADE;


SET search_path = layers, pg_catalog;

--
-- Name: fk_t_bib_statutszone_l_statut_zone; Type: FK CONSTRAINT; Schema: layers; Owner: policeuser
--

ALTER TABLE ONLY l_statut_zone
    ADD CONSTRAINT fk_t_bib_statutszone_l_statut_zone FOREIGN KEY (id_statut_zone) REFERENCES interventions.bib_statutszone(id_statutzone);


SET search_path = utilisateurs, pg_catalog;

--
-- Name: cor_role_droit_application_id_application_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY cor_role_droit_application
    ADD CONSTRAINT cor_role_droit_application_id_application_fkey FOREIGN KEY (id_application) REFERENCES t_applications(id_application) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cor_role_droit_application_id_droit_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY cor_role_droit_application
    ADD CONSTRAINT cor_role_droit_application_id_droit_fkey FOREIGN KEY (id_droit) REFERENCES bib_droits(id_droit) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cor_role_droit_application_id_role_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY cor_role_droit_application
    ADD CONSTRAINT cor_role_droit_application_id_role_fkey FOREIGN KEY (id_role) REFERENCES t_roles(id_role) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cor_role_menu_application_id_menu_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY cor_role_menu
    ADD CONSTRAINT cor_role_menu_application_id_menu_fkey FOREIGN KEY (id_menu) REFERENCES t_menus(id_menu) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cor_role_menu_application_id_role_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY cor_role_menu
    ADD CONSTRAINT cor_role_menu_application_id_role_fkey FOREIGN KEY (id_role) REFERENCES t_roles(id_role) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cor_roles_id_role_groupe_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY cor_roles
    ADD CONSTRAINT cor_roles_id_role_groupe_fkey FOREIGN KEY (id_role_groupe) REFERENCES t_roles(id_role) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: cor_roles_id_role_utilisateur_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY cor_roles
    ADD CONSTRAINT cor_roles_id_role_utilisateur_fkey FOREIGN KEY (id_role_utilisateur) REFERENCES t_roles(id_role) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: t_menus_id_application_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY t_menus
    ADD CONSTRAINT t_menus_id_application_fkey FOREIGN KEY (id_application) REFERENCES t_applications(id_application) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: t_roles_id_organisme_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY t_roles
    ADD CONSTRAINT t_roles_id_organisme_fkey FOREIGN KEY (id_organisme) REFERENCES bib_organismes(id_organisme) ON UPDATE CASCADE;


--
-- Name: t_roles_id_unite_fkey; Type: FK CONSTRAINT; Schema: utilisateurs; Owner: policeuser
--

ALTER TABLE ONLY t_roles
    ADD CONSTRAINT t_roles_id_unite_fkey FOREIGN KEY (id_unite) REFERENCES bib_unites(id_unite) ON UPDATE CASCADE;


--
-- Name: interventions; Type: ACL; Schema: -; Owner: policeuser
--

REVOKE ALL ON SCHEMA interventions FROM PUBLIC;
REVOKE ALL ON SCHEMA interventions FROM policeuser;
GRANT ALL ON SCHEMA interventions TO policeuser;


--
-- Name: layers; Type: ACL; Schema: -; Owner: policeuser
--

REVOKE ALL ON SCHEMA layers FROM PUBLIC;
REVOKE ALL ON SCHEMA layers FROM policeuser;
GRANT ALL ON SCHEMA layers TO policeuser;


--
-- Name: utilisateurs; Type: ACL; Schema: -; Owner: policeuser
--

REVOKE ALL ON SCHEMA utilisateurs FROM PUBLIC;
REVOKE ALL ON SCHEMA utilisateurs FROM policeuser;
GRANT ALL ON SCHEMA utilisateurs TO policeuser;


SET search_path = interventions, pg_catalog;

--
-- Name: bib_infractions; Type: ACL; Schema: interventions; Owner: policeuser
--

REVOKE ALL ON TABLE bib_infractions FROM PUBLIC;
REVOKE ALL ON TABLE bib_infractions FROM policeuser;
GRANT ALL ON TABLE bib_infractions TO policeuser;


--
-- Name: bib_qualification; Type: ACL; Schema: interventions; Owner: policeuser
--

REVOKE ALL ON TABLE bib_qualification FROM PUBLIC;
REVOKE ALL ON TABLE bib_qualification FROM policeuser;
GRANT ALL ON TABLE bib_qualification TO policeuser;


--
-- Name: bib_statutszone; Type: ACL; Schema: interventions; Owner: policeuser
--

REVOKE ALL ON TABLE bib_statutszone FROM PUBLIC;
REVOKE ALL ON TABLE bib_statutszone FROM policeuser;
GRANT ALL ON TABLE bib_statutszone TO policeuser;


--
-- Name: bib_types_interventions; Type: ACL; Schema: interventions; Owner: policeuser
--

REVOKE ALL ON TABLE bib_types_interventions FROM PUBLIC;
REVOKE ALL ON TABLE bib_types_interventions FROM policeuser;
GRANT ALL ON TABLE bib_types_interventions TO policeuser;


--
-- Name: cor_interventions_agents; Type: ACL; Schema: interventions; Owner: policeuser
--

REVOKE ALL ON TABLE cor_interventions_agents FROM PUBLIC;
REVOKE ALL ON TABLE cor_interventions_agents FROM policeuser;
GRANT ALL ON TABLE cor_interventions_agents TO policeuser;


--
-- Name: cor_interventions_infractions; Type: ACL; Schema: interventions; Owner: policeuser
--

REVOKE ALL ON TABLE cor_interventions_infractions FROM PUBLIC;
REVOKE ALL ON TABLE cor_interventions_infractions FROM policeuser;
GRANT ALL ON TABLE cor_interventions_infractions TO policeuser;


--
-- Name: t_interventions; Type: ACL; Schema: interventions; Owner: policeuser
--

REVOKE ALL ON TABLE t_interventions FROM PUBLIC;
REVOKE ALL ON TABLE t_interventions FROM policeuser;
GRANT ALL ON TABLE t_interventions TO policeuser;


SET search_path = utilisateurs, pg_catalog;

--
-- Name: cor_role_droit_application; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE cor_role_droit_application FROM PUBLIC;
REVOKE ALL ON TABLE cor_role_droit_application FROM policeuser;
GRANT ALL ON TABLE cor_role_droit_application TO policeuser;


--
-- Name: cor_roles; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE cor_roles FROM PUBLIC;
REVOKE ALL ON TABLE cor_roles FROM policeuser;
GRANT ALL ON TABLE cor_roles TO policeuser;


--
-- Name: t_roles_id_seq; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON SEQUENCE t_roles_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE t_roles_id_seq FROM policeuser;
GRANT ALL ON SEQUENCE t_roles_id_seq TO policeuser;


--
-- Name: t_roles; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE t_roles FROM PUBLIC;
REVOKE ALL ON TABLE t_roles FROM policeuser;
GRANT ALL ON TABLE t_roles TO policeuser;


SET search_path = layers, pg_catalog;

--
-- Name: l_communes; Type: ACL; Schema: layers; Owner: policeuser
--

REVOKE ALL ON TABLE l_communes FROM PUBLIC;
REVOKE ALL ON TABLE l_communes FROM policeuser;
GRANT ALL ON TABLE l_communes TO policeuser;


--
-- Name: l_secteurs; Type: ACL; Schema: layers; Owner: policeuser
--

REVOKE ALL ON TABLE l_secteurs FROM PUBLIC;
REVOKE ALL ON TABLE l_secteurs FROM policeuser;
GRANT ALL ON TABLE l_secteurs TO policeuser;


--
-- Name: l_statut_zone; Type: ACL; Schema: layers; Owner: policeuser
--

REVOKE ALL ON TABLE l_statut_zone FROM PUBLIC;
REVOKE ALL ON TABLE l_statut_zone FROM policeuser;
GRANT ALL ON TABLE l_statut_zone TO policeuser;


SET search_path = utilisateurs, pg_catalog;

--
-- Name: bib_droits; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE bib_droits FROM PUBLIC;
REVOKE ALL ON TABLE bib_droits FROM policeuser;
GRANT ALL ON TABLE bib_droits TO policeuser;


--
-- Name: bib_organismes_id_seq; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON SEQUENCE bib_organismes_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE bib_organismes_id_seq FROM policeuser;
GRANT ALL ON SEQUENCE bib_organismes_id_seq TO policeuser;


--
-- Name: bib_organismes; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE bib_organismes FROM PUBLIC;
REVOKE ALL ON TABLE bib_organismes FROM policeuser;
GRANT ALL ON TABLE bib_organismes TO policeuser;


--
-- Name: bib_unites; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE bib_unites FROM PUBLIC;
REVOKE ALL ON TABLE bib_unites FROM policeuser;
GRANT ALL ON TABLE bib_unites TO policeuser;


--
-- Name: cor_role_menu; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE cor_role_menu FROM PUBLIC;
REVOKE ALL ON TABLE cor_role_menu FROM policeuser;
GRANT ALL ON TABLE cor_role_menu TO policeuser;


--
-- Name: t_applications; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE t_applications FROM PUBLIC;
REVOKE ALL ON TABLE t_applications FROM policeuser;
GRANT ALL ON TABLE t_applications TO policeuser;


--
-- Name: t_menus; Type: ACL; Schema: utilisateurs; Owner: policeuser
--

REVOKE ALL ON TABLE t_menus FROM PUBLIC;
REVOKE ALL ON TABLE t_menus FROM policeuser;
GRANT ALL ON TABLE t_menus TO policeuser;


--
-- PostgreSQL database dump complete
--

