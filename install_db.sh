#!/bin/bash

# Make sure only root can run our script
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

. conf/settings.ini

function database_exists () {
    # /!\ Will return false if psql can't list database. Edit your pg_hba.conf
    # as appropriate.
    if [ -z $1 ]
        then
        # Argument is null
        return 0
    else
        # Grep db name in the list of database
        sudo -n -u postgres -s -- psql -tAl | grep -q "^$1|"
        return $?
    fi
}


if database_exists $db_name
then
        if $drop_apps_db
            then
            echo "Suppression de la base..."
            sudo -n -u postgres -s dropdb $db_name
        else
            echo "La base de données existe et le fichier de settings indique de ne pas la supprimer."
        fi
fi        
if ! database_exists $db_name 
then
    echo "Création de la base..."
    sudo -n -u postgres -s createdb -O $user_pg $db_name
    echo "Ajout de postgis à la base"
    sudo -n -u postgres -s psql -d $db_name -c "CREATE EXTENSION IF NOT EXISTS postgis;"
    sudo -n -u postgres -s psql -d $db_name -c "CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog; COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';"


    # Mise en place de la structure de la base et des données permettant son fonctionnement avec l'application
    echo "Grant..."
    sed -i "s/policeuser/$user_pg/g" data/grant.sql
    export PGPASSWORD=$admin_pg_pass;psql -h policedbhost -U $admin_pg -d $db_name -f data/grant.sql &> log/install_db.log
    echo "Création de la structure de la base..."
    sed -i "s/policeuser/$user_pg/g" data/policedb.sql
    sed -i "s/id_application = 3/id_application = $id_application/g" data/policedb.sql
    
    export PGPASSWORD=$user_pg_pass;psql -h policedbhost -U $user_pg -d $db_name -f data/policedb.sql  &>> log/install_db.log
    echo "Insertion des données de l'application dans la base..."
    sed -i "s/VALUES (3, 'application police'/VALUES ($id_application, 'application police'/g" data/policedb_data.sql
    sed -i "s/VALUES (20002, 2, 3);/VALUES (20002, 2, $id_application);/g" data/policedb_data.sql
    sed -i "s/VALUES (1, 6, 3);/VALUES (1, 6, $id_application);/g" data/policedb_data.sql
    sed -i "s/VALUES (1, 'Police - Agents', 'listes des agents de constation', 3);/VALUES ($id_menu, 'Police - Agents', 'listes des agents de constation', $id_application);/g" data/policedb_data.sql
    sed -i "s/INSERT INTO cor_role_menu (id_role, id_menu) VALUES (1, 1);/INSERT INTO cor_role_menu (id_role, id_menu) VALUES (1, $id_menu);/g" data/policedb_data.sql
    echo "" &>> log/install_db.log
    echo "" &>> log/install_db.log
    echo "INSERTION DES DONNEES DE l'APPLICATION" &>> log/install_db.log
    echo "=====================" &>> log/install_db.log
    echo "" &>> log/install_db.log
    export PGPASSWORD=$user_pg_pass;psql -h policedbhost -U $user_pg -d $db_name -f data/policedb_data.sql  &>> log/install_db.log
    
    echo "Insertion des données sig PNE de la base..."
    echo "" &>> log/install_db.log
    echo "" &>> log/install_db.log
    echo "INSERTION DES DONNEES SIG PNE" &>> log/install_db.log
    echo "=====================" &>> log/install_db.log
    echo "" &>> log/install_db.log
    export PGPASSWORD=$user_pg_pass;psql -h policedbhost -U $user_pg -d $db_name -f data/policedb_data_sig_pne.sql  &>> log/install_db.log

fi    