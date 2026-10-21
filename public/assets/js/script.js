/**
 * ALPHA - interactions de l'interface.
 *
 * Les requetes vers le serveur sont protegees par le jeton anti-CSRF
 * place dans la balise meta. Aucun gestionnaire n'est ecrit en ligne dans
 * le HTML.
 */
(function () {
    "use strict";

    const meta = document.querySelector('meta[name="csrf-token"]');
    const jeton = meta ? meta.getAttribute('content') : '';
    const metaBase = document.querySelector('meta[name="base-url"]');
    const base = metaBase ? metaBase.getAttribute('content') : '/';

    /* --- Menu mobile --------------------------------------------------- */
    const boutonMenu = document.querySelector('.menu-toggle');
    const menu = document.getElementById('menu-principal');

    if (boutonMenu && menu) {
        const basculer = (ouvrir) => {
            menu.classList.toggle('is-open', ouvrir);
            boutonMenu.setAttribute('aria-expanded', ouvrir ? 'true' : 'false');
            boutonMenu.setAttribute('aria-label', ouvrir ? 'Fermer le menu' : 'Ouvrir le menu');
        };

        boutonMenu.addEventListener('click', () => {
            basculer(!menu.classList.contains('is-open'));
        });

        menu.addEventListener('click', (evenement) => {
            if (evenement.target.tagName === 'A') {
                basculer(false);
            }
        });

        document.addEventListener('keydown', (evenement) => {
            if (evenement.key === 'Escape') {
                basculer(false);
            }
        });
    }

    /* --- Calcul automatique du montant ---------------------------------- */
    const quantite = document.getElementById('quantite');
    const prixUnitaire = document.getElementById('prix_unitaire');
    const montant = document.getElementById('montant');

    const calculerMontant = () => {
        if (!quantite || !prixUnitaire || !montant) {
            return;
        }
        const q = parseFloat(quantite.value);
        const p = parseFloat(prixUnitaire.value);
        if (!isNaN(q) && !isNaN(p)) {
            montant.value = (q * p).toFixed(2).replace('.', ',') + ' FCFA';
        } else {
            montant.value = '';
        }
    };

    if (quantite && prixUnitaire) {
        quantite.addEventListener('input', calculerMontant);
        prixUnitaire.addEventListener('input', calculerMontant);
        calculerMontant();
    }

    /* --- Chargement du budget ------------------------------------------- */
    const corpsTable = document.querySelector('#depenses-table tbody');
    const totalValeur = document.getElementById('total-value');
    const formDepense = document.getElementById('depense-form');

    const formater = (valeur) => Number(valeur || 0).toLocaleString('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const afficherDepenses = async () => {
        if (!corpsTable) {
            return;
        }

        try {
            const reponse = await fetch(base + 'actions/get_depenses.php', { headers: { 'Accept': 'application/json' } });
            if (!reponse.ok) {
                throw new Error('HTTP ' + reponse.status);
            }
            const depenses = await reponse.json();

            corpsTable.innerHTML = '';
            let total = 0;

            if (!depenses.length) {
                const ligne = corpsTable.insertRow();
                const cellule = ligne.insertCell();
                cellule.colSpan = 6;
                cellule.className = 'table-empty';
                cellule.textContent = 'Aucune depense enregistree pour le moment.';
            }

            depenses.forEach((depense, index) => {
                const ligne = corpsTable.insertRow();
                ligne.insertCell().textContent = index + 1;
                ligne.insertCell().textContent = depense.designation;
                ligne.insertCell().textContent = depense.quantite;
                ligne.insertCell().textContent = formater(depense.prix_unitaire);

                const celluleMontant = ligne.insertCell();
                celluleMontant.className = 'montant';
                celluleMontant.textContent = formater(depense.montant) + ' FCFA';

                const celluleActions = ligne.insertCell();
                celluleActions.className = 'actions';

                const lienModifier = document.createElement('a');
                lienModifier.href = base + 'actions/edit_depense.php?id=' + encodeURIComponent(depense.id);
                lienModifier.className = 'btn-small';
                lienModifier.textContent = 'Modifier';
                celluleActions.appendChild(lienModifier);

                const lienSupprimer = document.createElement('button');
                lienSupprimer.type = 'button';
                lienSupprimer.className = 'btn-small';
                lienSupprimer.textContent = 'Supprimer';
                lienSupprimer.addEventListener('click', () => supprimerDepense(depense.id));
                celluleActions.appendChild(lienSupprimer);

                total += Number(depense.montant || 0);
            });

            if (totalValeur) {
                totalValeur.textContent = formater(total) + ' FCFA';
            }
        } catch (erreur) {
            console.error('Chargement du budget impossible :', erreur);
            if (corpsTable) {
                corpsTable.innerHTML = '<tr><td colspan="6" class="table-empty">Le budget est momentanement indisponible.</td></tr>';
            }
        }
    };

    const supprimerDepense = async (id) => {
        if (!window.confirm('Supprimer definitivement cette depense ?')) {
            return;
        }

        const donnees = new FormData();
        donnees.append('id', id);
        donnees.append('csrf_token', jeton);

        try {
            const reponse = await fetch(base + 'actions/supprimer_depense.php', {
                method: 'POST',
                body: donnees,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const resultat = await reponse.json();

            if (resultat.ok) {
                await afficherDepenses();
            } else {
                window.alert(resultat.error || 'La suppression a echoue.');
            }
        } catch (erreur) {
            console.error('Suppression impossible :', erreur);
        }
    };

    if (corpsTable) {
        afficherDepenses();
    }

    if (formDepense) {
        formDepense.addEventListener('submit', async (evenement) => {
            evenement.preventDefault();

            const bouton = formDepense.querySelector('button[type="submit"]');
            const libelle = bouton ? bouton.textContent : '';
            if (bouton) {
                bouton.disabled = true;
                bouton.textContent = 'Enregistrement...';
            }

            try {
                const reponse = await fetch(base + 'actions/traitement.php', {
                    method: 'POST',
                    body: new FormData(formDepense),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const resultat = await reponse.json();

                if (resultat.ok) {
                    formDepense.reset();
                    await afficherDepenses();
                } else {
                    window.alert(resultat.error || 'L ajout a echoue.');
                }
            } catch (erreur) {
                console.error('Ajout impossible :', erreur);
                window.alert('Le serveur ne repond pas. Reessayez dans un instant.');
            } finally {
                if (bouton) {
                    bouton.disabled = false;
                    bouton.textContent = libelle;
                }
            }
        });
    }

    /* --- Formulaire de modification -------------------------------------- */
    const formEdition = document.getElementById('edit-depense-form');

    if (formEdition) {
        formEdition.addEventListener('submit', async (evenement) => {
            evenement.preventDefault();

            const bouton = formEdition.querySelector('button[type="submit"]');
            const libelle = bouton ? bouton.textContent : '';
            if (bouton) {
                bouton.disabled = true;
                bouton.textContent = 'Enregistrement...';
            }

            try {
                const reponse = await fetch(formEdition.dataset.action, {
                    method: 'POST',
                    body: new FormData(formEdition),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const resultat = await reponse.json();

                if (resultat.ok) {
                    window.location.href = base + 'formulaire.php';
                } else {
                    window.alert(resultat.error || 'La modification a echoue.');
                }
            } catch (erreur) {
                console.error('Modification impossible :', erreur);
            } finally {
                if (bouton) {
                    bouton.disabled = false;
                    bouton.textContent = libelle;
                }
            }
        });
    }

    /* --- Apparition des blocs -------------------------------------------- */
    const reduire = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const cibles = Array.from(document.querySelectorAll(
        '.hero > *, .bubble, .service-card, .card-form, .table-wrap, .section-title, .page-title, .eyebrow, .prose > *'
    ));

    if (!reduire && cibles.length && 'IntersectionObserver' in window) {
        const observateur = new IntersectionObserver((entrees) => {
            entrees.forEach((entree) => {
                if (entree.isIntersecting) {
                    entree.target.classList.add('is-visible');
                    observateur.unobserve(entree.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -4% 0px' });

        cibles.forEach((element) => {
            element.setAttribute('data-reveal', '');
            observateur.observe(element);
        });
    } else {
        cibles.forEach((element) => element.classList.add('is-visible'));
    }
})();
