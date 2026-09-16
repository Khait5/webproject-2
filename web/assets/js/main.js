document.addEventListener('DOMContentLoaded', () => {
    const statusContainer = document.getElementById('status-online-count');

    if (statusContainer) {
        updateServerStatus();
        // Update every 30 seconds
        setInterval(updateServerStatus, 30000);
    }
});

function updateServerStatus() {
    // Attempt to determine correct API path based on current location
    let apiPath = 'api/status.php';
    if (window.location.pathname.includes('/modules/')) {
        apiPath = '../../api/status.php';
    }

    fetch(apiPath)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const onlineCounter = document.getElementById('status-online-count');
            if (onlineCounter) {
                onlineCounter.textContent = data.online_players;
            }
        })
        .catch(error => {
            console.error('Error fetching server status:', error);
            const onlineCounter = document.getElementById('status-online-count');
            if (onlineCounter) {
                onlineCounter.textContent = 'Error';
            }
        });
}
