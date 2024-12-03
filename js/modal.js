document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('alertModal');
    if (modal) {
        modal.style.display = 'block';
        
        // Fechar modal quando clicar no X
        document.querySelector('.close-modal').addEventListener('click', closeModal);
        
        // Fechar modal quando clicar fora dele
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Fechar modal com a tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    }
});

function closeModal() {
    const modal = document.getElementById('alertModal');
    if (modal) {
        modal.style.display = 'none';
        
        // Limpar URL
        if (window.history.replaceState) {
            const cleanURL = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, cleanURL);
        }
    }
} 