# ALPHA

Laboratoire d'etudiants applique a l'intelligence artificielle et aux objets
connectes. Le site presente les travaux de l'equipe, recense les services
proposes et tient le budget des depenses du projet.

Une instance est en ligne sur <https://fasoluxe.alwaysdata.net/alpha/>.

## Fonctionnalites

- **Accueil** : presentation du laboratoire et de ses trois axes de travail.
- **A propos** :methode, objectifs et equipe.
- **Services** : analyse de donnees, automatisation et assistance virtuelle.
- **Budget** : suivi des depenses du laboratoire, avec ajout, modification et
  suppression, et un total calcule en direct.
- **Contact** : formulaire enregistre en base, avec reponse immediate.

## Prerequis

- PHP 8.0 ou superieur avec l'extension `pdo_mysql`.
- MySQL 8 ou MariaDB 10.
- Un serveur web Apache ou equivalent.

## Installation

### 1. Creer la base de donnees

Le fichier `alpha.sql` contient le schema et les donnees de demonstration. Il
est portable : il ne cree pas la base et ne la selectionne pas.

```bash
mysql -u root -p -e "CREATE DATABASE projet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p projet < alpha.sql
```

### 2. Configurer la connexion

Les valeurs par defaut de `config/db.php` correspondent a une installation
WampServer : hote `localhost`, port `3306`, utilisateur `root`, mot de passe
vide, base `projet`.

Pour une configuration personnelle, copiez le modele :

```bash
cp config/db.local.php.example config/db.local.php
```

Ce fichier n'est pas versionne. Les variables d'environnement restent
possibles et sont prioritaires sur lui :

```bash
export ALPHA_DB_HOST=127.0.0.1
export ALPHA_DB_PORT=3306
export ALPHA_DB_USER=root
export ALPHA_DB_PASS='votre_mot_de_passe'
export ALPHA_DB_NAME=projet
```

### 3. Lancer le site

Le dossier `public/` est la racine web. Les dossiers `config/`, `includes/` et
`alpha.sql` doivent rester hors de la racine, sinon ils sont accessibles.

```bash
php -S 127.0.0.1:8000 -t public
```

Puis ouvrir <http://127.0.0.1:8000/>.

L'URL de base est deduite du chemin de la requete : le site fonctionne a la
racine d'un domaine comme dans un sous-dossier. Deux variables permettent de
forcer le comportement :

| Variable | Role |
|---|---|
| `ALPHA_APP_DIR` | Dossier de l'application sur le disque, quand il n'est pas le parent de `config/` |
| `ALPHA_BASE_PATH` | Prefixe d'URL force, par exemple `/alpha/` |

## Structure du projet

```text
config/              configuration et connexion a la base
  db.php               valeurs par defaut, URL de base, connexion PDO
  db.local.php.example modele de configuration personnelle
includes/           en-tete, pied de page, session, jetons anti-CSRF
public/             racine web
  index.php             accueil
  blog.php              a propos
  service.php           services
  formulaire.php        budget des depenses
  contact.php           contact
  404.php               page d'erreur
  .htaccess             renvoi des URL inconnues vers la page 404
  actions/              appelements JSON (creation, modification, suppression)
  assets/               CSS, JavaScript, images
alpha.sql           schema et donnees de demonstration
```

## Modele de donnees

| Table | Role |
|---|---|
| `depenses` | Depenses du laboratoire : designation, quantite, prix unitaire, montant |
| `contact_messages` | Messages envoyes depuis le formulaire de contact |

Le montant d'une depense n'est jamais lu dans le formulaire : il est
recalcule par le serveur a partir de la quantite et du prix unitaire. Un
client ne peut donc pas declarer une somme arbitraire.

## Securite

- **Requetes preparees** partout, avec `PDO::ATTR_EMULATE_PREPARES` desactive.
- **Jetons anti-CSRF** sur les deux formulaires et sur tous les appels
  JavaScript. Le jeton est verifie cote serveur avant toute ecriture.
- **Actions destructrices en POST** : la suppression d'une depense n'accepte
  pas un GET, un simple lien ne peut donc pas la declencher.
- **Messages d'erreur generiques** : les details des erreurs SQL partent dans
  le journal du serveur, jamais a l'ecran.
- **Connexion paresseuse** : la base n'est ouverte qu'a la demande. Une page
  sans donnees reste affichee meme si MySQL est indisponible.
- **Identifiants externalises** dans `config/db.local.php`, non versionne, ou
  dans les variables d'environnement.

## Design

Identite "editorial computing" : fond profond, un seul accent menthe, grain
de papier discret. Les titres sont en Space Grotesk, le texte en Manrope, les
libelles et les donnees en JetBrains Mono. Les animations d'apparition sont
decoratives : sans JavaScript, ou si l'utilisateur demande une reduction des
animations, tout le contenu reste visible.
