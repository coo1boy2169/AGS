const calendar = document.getElementById('calendar');
const popup = document.getElementById('popup');
const popupDay = document.getElementById('popup-day');
const timeSlotsContainer = document.getElementById('time-slots');
const closePopup = document.getElementById('close-popup');
const appointmentForm = document.getElementById('appointment-form');
const appointmentDescription = document.getElementById('appointment-description');
const saveAppointmentButton = document.getElementById('save-appointment');
const prevWeekButton = document.getElementById('prev-week');
const nextWeekButton = document.getElementById('next-week');
const currentWeekDisplay = document.getElementById('current-week');

const startTime = 9 * 60; // 9:00 AM in minutes
const endTime = 18 * 60; // 6:00 PM in minutes
const interval = 15; // 15 minutes

let currentDate = new Date(); // Keeps track of the current week being viewed

// Appointments object
const appointments = {};

// Function to get the start and end dates of the current week
function getCurrentWeek() {
    const start = new Date(currentDate);
    start.setDate(currentDate.getDate() - currentDate.getDay() + 1); // Get Monday
    const end = new Date(start);
    end.setDate(start.getDate() + 4); // Get Friday
    return { start, end };
}

// Function to format a date as "YYYY-MM-DD"
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

// Update the current week display and calendar
function updateWeek() {
    const { start, end } = getCurrentWeek();
    currentWeekDisplay.textContent = `${start.toDateString()} - ${end.toDateString()}`;
    generateCalendar();
}

// Generate calendar
function generateCalendar() {
    calendar.innerHTML = ''; // Clear the calendar
    const { start } = getCurrentWeek();

    for (let i = 0; i < 5; i++) {
        const day = new Date(start);
        day.setDate(start.getDate() + i);

        const formattedDate = formatDate(day);
        if (!appointments[formattedDate]) {
            appointments[formattedDate] = {};
        }

        const dayContainer = document.createElement('div');
        dayContainer.className = 'day';

        const dayTitle = document.createElement('h3');
        dayTitle.textContent = day.toDateString();

        const appointmentsList = document.createElement('ul');
        appointmentsList.className = 'appointments-list';
        appointmentsList.id = `appointments-${formattedDate}`;

        // Populate appointments list
        Object.keys(appointments[formattedDate]).forEach(time => {
            const appointmentItem = createAppointmentListItem(formattedDate, time);
            appointmentsList.appendChild(appointmentItem);
        });

        // Show popup when clicking on a day
        dayContainer.addEventListener('click', () => showPopup(formattedDate));

        dayContainer.appendChild(dayTitle);
        dayContainer.appendChild(appointmentsList);
        calendar.appendChild(dayContainer);
    }
}

// Create a list item for an appointment
function createAppointmentListItem(date, time) {
    const appointmentItem = document.createElement('li');
    appointmentItem.textContent = `${time} - ${appointments[date][time]}`;
    appointmentItem.className = 'appointment-item';
    appointmentItem.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent opening the popup
        const confirmCancel = confirm(`Do you want to cancel the appointment at ${time}?`);
        if (confirmCancel) {
            delete appointments[date][time];
            updateAppointmentsList(date);
        }
    });
    return appointmentItem;
}

// Update the appointments list under a specific day
function updateAppointmentsList(date) {
    const appointmentsList = document.getElementById(`appointments-${date}`);
    appointmentsList.innerHTML = ''; // Clear existing list
    Object.keys(appointments[date]).forEach(time => {
        const appointmentItem = createAppointmentListItem(date, time);
        appointmentsList.appendChild(appointmentItem);
    });
}

// Show popup
function showPopup(date) {
    popup.classList.remove('hidden');
    popupDay.textContent = date;
    timeSlotsContainer.innerHTML = ''; // Clear previous slots
    appointmentForm.classList.add('hidden'); // Hide form initially

    for (let time = startTime; time < endTime; time += interval) {
        const hours = Math.floor(time / 60);
        const minutes = time % 60;
        const formattedTime = `${hours}:${minutes.toString().padStart(2, '0')}`;

        const slot = document.createElement('div');
        slot.className = 'slot';
        slot.textContent = formattedTime;

        // Check if this time already has an appointment
        if (appointments[date][formattedTime]) {
            slot.classList.add('selected');
            slot.title = appointments[date][formattedTime]; // Show description on hover
        }

        // Handle time slot selection
        slot.addEventListener('click', () => handleSlotSelection(date, formattedTime, slot));

        timeSlotsContainer.appendChild(slot);
    }
}

// Handle slot selection
function handleSlotSelection(date, time, slot) {
    if (appointments[date][time]) {
        alert('This time is already booked.');
    } else {
        // Show appointment form for new appointment
        appointmentForm.classList.remove('hidden');
        saveAppointmentButton.onclick = () => {
            const description = appointmentDescription.value.trim();
            if (description) {
                appointments[date][time] = description;
                slot.classList.add('selected');
                slot.title = description;
                appointmentForm.classList.add('hidden');
                appointmentDescription.value = ''; // Clear input
                updateAppointmentsList(date);
            } else {
                alert('Please provide a description for the appointment.');
            }
        };
    }
}

// Handle navigation
prevWeekButton.addEventListener('click', () => {
    currentDate.setDate(currentDate.getDate() - 7);
    updateWeek();
});

nextWeekButton.addEventListener('click', () => {
    currentDate.setDate(currentDate.getDate() + 7);
    updateWeek();
});

// Close popup
closePopup.addEventListener('click', () => {
    popup.classList.add('hidden');
    appointmentForm.classList.add('hidden');
    appointmentDescription.value = ''; // Clear input
});

// Initial load
updateWeek();
