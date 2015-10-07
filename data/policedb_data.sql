--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.9
-- Dumped by pg_dump version 9.3.1
-- Started on 2015-10-06 15:24:00

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = interventions, pg_catalog;

--
-- TOC entry 2840 (class 0 OID 28358)
-- Dependencies: 169
-- Data for Name: bib_infractions; Type: TABLE DATA; Schema: interventions; Owner: -
--

INSERT INTO bib_infractions (id_infraction, infraction) VALUES (2, 'VTT');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (3, 'Camping-bivouac');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (5, 'Circulation (autre que loi 91)');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (4, 'Chasse');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (9, 'Divagation de chien');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (10, 'Introduction de chien');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (12, 'Loi 4x4 motoneige');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (11, 'Loi 4X4 moto trial enduro');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (13, 'Loi 4x4 voiture');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (14, 'Loi 4x4 quad');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (17, 'Survol avion');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (19, 'Survol parapente');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (20, 'Survol planeur');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (22, 'Bivouac');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (23, 'Raccourci sentier');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (1, 'Atteinte à une espèce protégée');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (6, 'Détritus');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (8, 'Coupe transport végétaux');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (7, 'Feu espaces protégés');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (16, 'Trouble à la faune');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (15, 'Travaux non autorisés');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (21, 'Pêche');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (18, 'Survol hélico');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (24, 'Publicité');
INSERT INTO bib_infractions (id_infraction, infraction) VALUES (25, 'Autre');


--
-- TOC entry 2841 (class 0 OID 28362)
-- Dependencies: 170
-- Data for Name: bib_qualification; Type: TABLE DATA; Schema: interventions; Owner: -
--

INSERT INTO bib_qualification (id_qualification, qualification, delai) VALUES (1, 'Contravention', 90);
INSERT INTO bib_qualification (id_qualification, qualification, delai) VALUES (2, 'Délit', 120);


--
-- TOC entry 2850 (class 0 OID 0)
-- Dependencies: 171
-- Name: bib_qualification_id_qualification_seq; Type: SEQUENCE SET; Schema: interventions; Owner: -
--

SELECT pg_catalog.setval('bib_qualification_id_qualification_seq', 2, true);


--
-- TOC entry 2843 (class 0 OID 28376)
-- Dependencies: 175
-- Data for Name: bib_types_interventions; Type: TABLE DATA; Schema: interventions; Owner: -
--

INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (3, 'Remontrance verbale');
INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (5, 'Rapport au procureur');
INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (2, 'Timbre amende');
INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (7, 'PV grande voirie');
INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (1, 'Procès verbal');
INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (8, 'Infraction observée sans intervention');
INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (6, 'Avertissement formalisé');
INSERT INTO bib_types_interventions (id_type_intervention, type_intervention) VALUES (9, 'Rapport au directeur');


--
-- TOC entry 2828 (class 0 OID 28370)
-- Dependencies: 173
-- Data for Name: bib_statutszone; Type: TABLE DATA; Schema: interventions; Owner: -
--

INSERT INTO bib_statutszone (id_statutzone, statutzone, ordre) VALUES (3, 'Hors Parc', 1);
INSERT INTO bib_statutszone (id_statutzone, statutzone, ordre) VALUES (2, 'Aire d''adhésion', 2);
INSERT INTO bib_statutszone (id_statutzone, statutzone, ordre) VALUES (4, 'Réserve naturelle', 3);
INSERT INTO bib_statutszone (id_statutzone, statutzone, ordre) VALUES (1, 'Coeur du Parc', 4);
INSERT INTO bib_statutszone (id_statutzone, statutzone, ordre) VALUES (5, 'Réserve intégrale', 5);


SET search_path = utilisateurs, pg_catalog;

INSERT INTO bib_droits (id_droit, nom_droit, desc_droit) VALUES (5, 'validateur', 'Il valide bien sur');
INSERT INTO bib_droits (id_droit, nom_droit, desc_droit) VALUES (4, 'modérateur', 'Peu utilisé');
INSERT INTO bib_droits (id_droit, nom_droit, desc_droit) VALUES (0, 'aucun', 'aucun droit.');
INSERT INTO bib_droits (id_droit, nom_droit, desc_droit) VALUES (1, 'utilisateur', 'Ne peut que consulter');
INSERT INTO bib_droits (id_droit, nom_droit, desc_droit) VALUES (2, 'rédacteur', 'Il possède des droit d''écriture pour créer des enregistrements');
INSERT INTO bib_droits (id_droit, nom_droit, desc_droit) VALUES (6, 'administrateur', 'Il a tous les droits');
INSERT INTO bib_droits (id_droit, nom_droit, desc_droit) VALUES (3, 'référent', 'utilisateur ayant des droits complémentaires au rédacteur (par exemple exporter des données ou autre)');

-- 
-- TOC entry 3275 (class 0 OID 17821)
-- Dependencies: 256
-- Data for Name: bib_organismes; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 
INSERT INTO bib_organismes (nom_organisme, adresse_organisme, cp_organisme, ville_organisme, tel_organisme, fax_organisme, email_organisme, id_organisme) VALUES ('PNF', NULL, NULL, 'Montpellier', NULL, NULL, NULL, 1);
INSERT INTO bib_organismes (nom_organisme, adresse_organisme, cp_organisme, ville_organisme, tel_organisme, fax_organisme, email_organisme, id_organisme) VALUES ('Parc National des Ecrins', 'Domaine de Charance', '05000', 'GAP', '04 92 40 20 10', '', '', 2);
INSERT INTO bib_organismes (nom_organisme, adresse_organisme, cp_organisme, ville_organisme, tel_organisme, fax_organisme, email_organisme, id_organisme) VALUES ('Autre', '', '', '', '', '', '', 99);

-- 
-- TOC entry 3276 (class 0 OID 17827)
-- Dependencies: 258
-- Data for Name: bib_unites; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 

INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Virtuel', NULL, NULL, NULL, NULL, NULL, NULL, 1);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('personnels partis', NULL, NULL, NULL, NULL, NULL, NULL, 2);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Stagiaires', NULL, NULL, '', '', NULL, NULL, 3);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Secretariat général', '', '', '', '', NULL, NULL, 4);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Service scientifique', '', '', '', '', NULL, NULL, 5);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Service SI', '', '', '', '', NULL, NULL, 6);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Service Communication', '', '', '', '', NULL, NULL, 7);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Conseil scientifique', '', '', '', NULL, NULL, NULL, 8);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Conseil d''administration', '', '', '', NULL, NULL, NULL, 9);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Partenaire fournisseur', NULL, NULL, NULL, NULL, NULL, NULL, 10);
INSERT INTO bib_unites (nom_unite, adresse_unite, cp_unite, ville_unite, tel_unite, fax_unite, email_unite, id_unite) VALUES ('Autres', NULL, NULL, NULL, NULL, NULL, NULL, 99);
-- 
-- TOC entry 3278 (class 0 OID 17837)
-- Dependencies: 261
-- Data for Name: t_applications; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 
INSERT INTO t_applications (id_application, nom_application, desc_application) VALUES (1, 'application UsersHub', 'application permettant d''administrer les utilisateurs.');
INSERT INTO t_applications (id_application, nom_application, desc_application) VALUES (3, 'application police', 'Application permettant la gestion de toutes les actions relevant de la mission de police.');

-- 
-- TOC entry 3255 (class 0 OID 17445)
-- Dependencies: 189
-- Data for Name: t_roles; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 
INSERT INTO t_roles (groupe, id_role, identifiant, nom_role, prenom_role, desc_role, pass, email, organisme, id_unite, pn, session_appli, date_insert, date_update, id_organisme, remarques) VALUES (true, 20002, NULL, 'grp_en_poste', NULL, 'Tous les agents en poste', NULL, NULL, 'ma structure', 99, true, NULL, NULL, NULL, NULL,'groupe test');
INSERT INTO t_roles (groupe, id_role, identifiant, nom_role, prenom_role, desc_role, pass, email, organisme, id_unite, pn, session_appli, date_insert, date_update, id_organisme, remarques) VALUES (false, 1, 'admin', 'Administrateur', 'test', NULL, '21232f297a57a5a743894a0e4a801fc3', '', 'ma structure', 99, true, NULL, NULL, NULL, 99,'utilisateur test à modifier');
-- 
-- TOC entry 3277 (class 0 OID 17831)
-- Dependencies: 259
-- Data for Name: cor_role_droit_application; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 
INSERT INTO cor_role_droit_application (id_role, id_droit, id_application) VALUES (1, 6, 1);
INSERT INTO cor_role_droit_application (id_role, id_droit, id_application) VALUES (20002, 2, 3);
INSERT INTO cor_role_droit_application (id_role, id_droit, id_application) VALUES (1, 6, 3);
-- 
-- TOC entry 3279 (class 0 OID 17845)
-- Dependencies: 263
-- Data for Name: t_menus; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 
INSERT INTO t_menus (id_menu, nom_menu, desc_menu, id_application) VALUES (1, 'Police - Agents', 'listes des agents de constation', 3);

-- 
-- TOC entry 3253 (class 0 OID 17437)
-- Dependencies: 186
-- Data for Name: cor_role_menu; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 
INSERT INTO cor_role_menu (id_role, id_menu) VALUES (1, 1);

-- 
-- TOC entry 3254 (class 0 OID 17440)
-- Dependencies: 187
-- Data for Name: cor_roles; Type: TABLE DATA; Schema: utilisateurs; Owner: geonatuser
-- 
INSERT INTO cor_roles (id_role_groupe, id_role_utilisateur) VALUES (20002, 1);
-- Completed on 2015-10-06 15:24:57

--
-- PostgreSQL database dump complete
--

