document.addEventListener('DOMContentLoaded', () => {
    const btn1 = document.getElementById('btn-1');
    const btnIntro = document.getElementById('btn-intro');
    const popupModal = document.getElementById('popup-modal');
    const closePopup = document.getElementById('close-popup');
    const videoOverlay = document.getElementById('video-overlay');
    const closeVideo = document.getElementById('close-video');
    const videoIframe = document.getElementById('video-iframe');

    // Video URL (Google Drive Preview)
    const videoUrl = 'https://drive.google.com/file/d/1UO9EW13HcqdX7yAlMOz3YXGNViKtCHzA/preview';

    // Handle ###-1 Button Click
    btn1.addEventListener('click', () => {
        popupModal.classList.remove('hidden');
    });

    // Handle Popup Close Button Click
    closePopup.addEventListener('click', () => {
        popupModal.classList.add('hidden');
        // Redirect after closing the popup
        window.location.href = 'http://colombianaion.duckdns.org';
    });

    // Handle INTRODUCCIÓN Button Click
    btnIntro.addEventListener('click', () => {
        videoIframe.src = videoUrl;
        videoOverlay.classList.remove('hidden');
    });

    // Handle Video Close Button Click
    closeVideo.addEventListener('click', () => {
        videoOverlay.classList.add('hidden');
        // Stop the video from playing in the background
        videoIframe.src = '';
    });
});