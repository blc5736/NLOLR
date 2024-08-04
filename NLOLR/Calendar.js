document.addEventListener('DOMContentLoaded', function() {
  // Fetch event data from the server
  fetchEvents('get_events.php');
});

function fetchEvents(url) {
  fetch(url)
    .then(response => response.json())
    .then(data => {
      // Process events data and populate the calendar
      const calendarDiv = document.getElementById('calendar');
      const events = data.events;

      events.forEach(event => {
        const eventDiv = document.createElement('div');
        eventDiv.classList.add('event');
        eventDiv.innerHTML = `<strong>${event.title}</strong>: ${event.description}`;
        calendarDiv.appendChild(eventDiv);
      });
    })
    .catch(error => console.error('Error fetching events:', error));
}