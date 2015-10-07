--migration de la base police v2.1 vers la base en V2.2
-- Analyse
-- Schéma layers identique sauf un gid dans la base en V2.1 mais à priori inutilisé.
-- Schéma layers public
-- Schéma layers utilisateurs identique
-- Schéma layers interventions:
    -- bib_agents et bib_droits à supprimer en remplaçant leur usage par les tables du schéma utilisateurs
    -- bib_statutszone à migrer vers la table identique qui est dans layers
--
-- Modification de la FK de interventions.cor_interventions_agents pour pointer vers utilisateurs.t_roles
ALTER TABLE interventions.cor_interventions_agents DROP CONSTRAINT fk_cor_agents_bib_agents;
ALTER TABLE interventions.cor_interventions_agents
  ADD CONSTRAINT fk_cor_agents_t_roles FOREIGN KEY (utilisateur_id)
      REFERENCES t_roles (id_role) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE NO ACTION;
-- Suppression de la colonne contrevenant inutilisée
ALTER TABLE interventions.t_interventions DROP COLUMN contrevenants;
--ajout de 2 nouvelles colonnes
ALTER TABLE interventions.t_interventions ADD COLUMN suivi_appel_avocat boolean;
ALTER TABLE interventions.t_interventions ADD COLUMN suivi_date_audience date;
-- Ajout d'un INDEX
CREATE INDEX fki_t_interventions_l_secteurs
  ON interventions.t_interventions
  USING btree
  (secteur_id);
  
--création d'une vue des utilisateurs Police (attention, mettre à jour l'id_application)  
DROP VIEW IF EXISTS utilisateurs.vue_agents;
CREATE OR REPLACE VIEW interventions.vue_agents AS 
SELECT a.groupe, a.id_role, a.identifiant, a.nom_role, a.prenom_role, a.desc_role, a.pass, a.email, a.organisme, a.id_unite, a.pn, a.session_appli, a.date_insert, a.date_update, max(a.id_droit) AS id_droit_police
	FROM 
	(
		SELECT u.*, c.id_droit
		FROM utilisateurs.t_roles u
		JOIN utilisateurs.cor_role_droit_application c ON c.id_role = u.id_role
		WHERE c.id_application = 3
		UNION
		SELECT u.*, c.id_droit
		FROM utilisateurs.t_roles u
		JOIN utilisateurs.cor_roles g ON g.id_role_utilisateur = u.id_role
		JOIN utilisateurs.cor_role_droit_application c ON c.id_role = g.id_role_groupe
		WHERE c.id_application = 3
	) as a
GROUP BY a.groupe, a.id_role, a.identifiant, a.nom_role, a.prenom_role, a.desc_role, a.pass, a.email, a.organisme, a.id_unite, a.pn, a.session_appli, a.date_insert, a.date_update;
ALTER TABLE interventions.vue_agents OWNER TO cartopne;
  
--
-- A faire uniquement si les agents présents dans bib_agents ont été migrés vers utilisateurs.t_roles 
-- Le schéma utilisateurs est compatible avec UsersHub qui permet de gérer les utilisateurs, les listes et les droits d'accès à l'application
-- DROP TABLE interventions.bib_agents;
-- DROP TABLE interventions.bib_droits;
-- DROP TABLE interventions.svg_bib_agents;