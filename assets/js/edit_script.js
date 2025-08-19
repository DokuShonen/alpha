// script.js

const form = document.getElementById('depense-form');
const quantiteInput = document.getElementById('quantite');
const prixUnitaireInput = document.getElementById('prix_unitaire');
const montantInput = document.getElementById('montant');
const depensesTable = document.getElementById('depenses-table').getElementsByTagName('tbody')[0]; // Get table body
const totalValueSpan = document.getElementById('total-value');
let montantTotal = 0; // Initialize montantTotal

// Function to calculate amount
function calculateAmount() {
    const quantite = parseFloat(quantiteInput.value);
    const prixUnitaire = parseFloat(prixUnitaireInput.value);

    if (!isNaN(quantite) && !isNaN(prixUnitaire)) {
        const montant = quantite * prixUnitaire;
        montantInput.value = montant.toFixed(2); // Format to 2 decimal places
    } else {
        montantInput.value = '';
    }
}

// Attach calculateAmount function to the input fields' input event
quantiteInput.addEventListener('input', calculateAmount);
prixUnitaireInput.addEventListener('input', calculateAmount);

// Function to load data from the server
async function loadDepenses() {
    const response = await fetch('actions/get_depenses.php'); // Fetch data from server
    const depenses = await response.json();

    depensesTable.innerHTML = ''; // Clear existing table rows
    montantTotal = 0;

    depenses.forEach((depense, index) => {
        const row = depensesTable.insertRow();
        row.insertCell(0).textContent = index + 1;
        row.insertCell(1).textContent = depense.designation;
        row.insertCell(2).textContent = depense.quantite;
        row.insertCell(3).textContent = depense.prix_unitaire;
        row.insertCell(4).textContent = depense.montant;

        // Add action buttons
        const actionsCell = row.insertCell(5);
        actionsCell.innerHTML = `
            <a href="actions/edit_depense.php?id=${depense.id}">Modifier</a> |
            <a href="#" class="delete-button" data-id="${depense.id}">Supprimer</a>
        `;

        // Update montantTotal
        montantTotal += parseFloat(depense.montant);
    });

    totalValueSpan.textContent = montantTotal.toFixed(2) + ' FCFA';
}

// Load data when the page loads
loadDepenses();

// Event listener for form submission
form.addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent the default form submission

    // Get form data
    const formData = new FormData(form);

    try {
        const response = await fetch('actions/traitement.php', { // Send data to the server
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            // Reload data if the submission was successful
            loadDepenses();
            form.reset(); // Reset the form
        } else {
            console.error('Error submitting form');
        }
    } catch (error) {
        console.error('Network error:', error);
    }
});

// Event delegation for delete buttons
depensesTable.addEventListener('click', async (event) => {
    if (event.target.classList.contains('delete-button')) {
        const id = event.target.dataset.id;

        if (confirm('Êtes-vous sûr de vouloir supprimer cette dépense?')) {
            try {
                const response = await fetch(`actions/supprimer_depense.php?id=${id}`);

                if (response.ok) {
                    loadDepenses(); // Reload the table
                } else {
                    console.error('Error deleting depense');
                }
            } catch (error) {
                console.error('Network error:', error);
            }
        }
    }
});