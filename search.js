document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('searchForm');
  form.addEventListener('submit', function(event) {
    event.preventDefault();

    const searchTerm = document.getElementById('searchTerm').value;

    fetch(`search_data.php?searchTerm=${searchTerm}`)
      .then(response => response.json())
      .then(data => {
        displayResults(data);
      })
      .catch(error => console.error('Error fetching data:', error));
  });
});

function displayResults(data) {
  const resultsDiv = document.getElementById('searchResults');
  resultsDiv.innerHTML = ''; // Clear
  
  if (data.length === 0) {
    resultsDiv.innerHTML = '<p>No results found.</p>';
    return;
  }

  const ul = document.createElement('ul');
  data.forEach(item => {
    const li = document.createElement('li');
    li.textContent = `ID: ${item.ID}, Name: ${item.Name}, Date: ${item.Date}`;
    ul.appendChild(li);
  });
  resultsDiv.appendChild(ul);
}
