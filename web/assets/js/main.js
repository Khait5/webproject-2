document.addEventListener('DOMContentLoaded', () => {
    const statusContainer = document.getElementById('status-container');

    if (statusContainer) {
        updateServerStatus();
        // Update every 30 seconds
        setInterval(updateServerStatus, 30000);
    }
});

function updateServerStatus() {
    fetch('api/status.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updateStatusElement('status-login', data.login_server);
            updateStatusElement('status-game', data.game_server);
            updateStatusElement('status-db', data.database);

            const onlineCounter = document.getElementById('status-online');
            if (onlineCounter) {
                onlineCounter.textContent = data.online_players;
            }
        })
        .catch(error => {
            console.error('Error fetching server status:', error);
            const indicators = ['status-login', 'status-game', 'status-db'];
            indicators.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = '<span class="loading">Error</span>';
            });
        });
}

function updateStatusElement(elementId, isOnline) {
    const element = document.getElementById(elementId);
    if (!element) return;

    if (isOnline) {
        element.innerHTML = '<span class="online">Online</span>';
    } else {
        element.innerHTML = '<span class="offline">Offline</span>';
    }
}
