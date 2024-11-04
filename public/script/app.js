
// Seleciona todos os botões com a classe 'button_tamanho' pg produto.html
const buttons = document.querySelectorAll('.button_tamanho');


buttons.forEach(button => {
    button.addEventListener('click', function() {
        // Remove a classe 'selected' de todos os botões
        buttons.forEach(btn => btn.classList.remove('selected'));

        
        this.classList.add('selected');
    });
});

/* animação galeria filtro.html */
ScrollReveal().reveal('.galeria-item', {
    delay: 200,
    distance: '20px',
    origin: 'bottom',
    opacity: 0,
    scale: 1,
    easing: 'ease-in-out',
    interval: 100,
    beforeReveal: (el) => {
        el.classList.add('is-visible');
    }
});

