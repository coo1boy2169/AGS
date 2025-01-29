function openModal(date, time, paperFormat) {
    console.log("Modal geopend met:", date, time, paperFormat);

    // Vul de modal-velden
    document.getElementById("modalDate").innerText = date;
    document.getElementById("modalTime").innerText = time;
    document.getElementById("modalPaper").value = paperFormat || "A4"; // Standaardwaarde A4
    document.getElementById("modalExtraInfo").value = ""; 

    // Controleer beschikbaarheid
    checkAvailability(date, time).catch(error => {
        console.error("Fout bij beschikbaarheid controle:", error);
        closeModal();
    });

    // Toon de modal
    document.getElementById("appointmentModal").style.display = "block";
}

function checkAvailability(date, time) {
    return fetch('check_availability.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ date, time })
    })
    .then(response => response.json())
    .then(result => {
        const confirmButton = document.getElementById("confirmAppointment");

        if (result.success) {
            confirmButton.disabled = false;
            showMessage("Dit tijdstip is beschikbaar.", "green");
        } else {
            confirmButton.disabled = true;
            showMessage("Dit tijdstip is al bezet.", "red");
        }
    });
}

function closeModal() {
    document.getElementById("appointmentModal").style.display = "none";
}

function showMessage(text, color) {
    const messageDiv = document.getElementById("message");
    messageDiv.style.color = color;
    messageDiv.innerText = text;
    messageDiv.style.display = "block";

    setTimeout(() => {
        messageDiv.style.display = "none";
    }, 5000);
}

document.getElementById("confirmAppointment").addEventListener("click", function () {
    const data = {
        date: document.getElementById("modalDate").innerText,
        time: document.getElementById("modalTime").innerText,
        paperFormat: document.getElementById("modalPaper").value,
        extraInfo: document.getElementById("modalExtraInfo").value
    };

    fetch('save_appointment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showMessage("Afspraak succesvol opgeslagen!", "green");
        } else {
            showMessage("Fout: " + result.message, "red");
        }
        closeModal();
    })
    .catch(error => {
        console.error("Fout bij het opslaan van de afspraak:", error);
    });
});

let currentWeekStart = new Date(); // Default to current week

// Function to update the week header
function updateWeekHeader() {
    const weekStart = new Date(currentWeekStart);
    const weekEnd = new Date(weekStart);
    weekEnd.setDate(weekStart.getDate() + 4); // Add 4 days (Mon-Fri)

    document.getElementById('weekHeader').innerText =
        `Week van ${weekStart.toLocaleDateString()} tot ${weekEnd.toLocaleDateString()}`;
}

// Function to fetch appointments via AJAX
function fetchAppointments() {
    const formattedDate = currentWeekStart.toISOString().split('T')[0];

    fetch(`fetch_appointments.php?week_start=${formattedDate}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('appointmentGrid').innerHTML = html;
            updateWeekHeader();
        })
        .catch(err => console.error('Error fetching appointments:', err));
}

// Navigation buttons
document.getElementById('prevWeek').addEventListener('click', () => {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    fetchAppointments();
});

document.getElementById('nextWeek').addEventListener('click', () => {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    fetchAppointments();
});

// Modal functions
function openModal(date, time) {
    document.getElementById('modalDate').innerText = date;
    document.getElementById('modalTime').innerText = time;
    document.getElementById('appointmentModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('appointmentModal').style.display = 'none';
}

// Initial fetch on page load
document.addEventListener('DOMContentLoaded', () => {
    fetchAppointments();
});
