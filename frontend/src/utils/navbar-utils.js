function loadNavbar() {
    fetch('/frontend/src/utils/navbar.html')  // Load the navbar from an external HTML file
        .then(response => response.text())
        .then(data => {
            document.body.insertAdjacentHTML('afterbegin', data);  // Insert the navbar HTML into the body
        })
        .catch(error => console.error('Error loading navbar:', error));
}