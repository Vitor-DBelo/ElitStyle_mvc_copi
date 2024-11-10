document.addEventListener('DOMContentLoaded', function() {
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const menuMobile = document.querySelector('.menu-mobile');
    const closeBtn = document.querySelector('.menu-mobile .close-btn');
    const overlay = document.querySelector('.overlay');
    const carrinhoIconMobile = document.getElementById('carrinho-icon-mobile');

    // Função para abrir o menu
    function openMenu() {
        menuMobile.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Função para fechar o menu
    function closeMenu() {
        menuMobile.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Event listeners
    hamburgerMenu.addEventListener('click', function(e) {
        e.preventDefault();
        openMenu();
    });

    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    // Integrar o carrinho mobile com o modal existente
    carrinhoIconMobile.addEventListener('click', function(e) {
        e.preventDefault();
        closeMenu();
        const modalCarrinho = document.getElementById('modal-carrinho');
        if (modalCarrinho) {
            modalCarrinho.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    });

    // Adicionar scroll suave para links do menu
    const menuLinks = document.querySelectorAll('.menu-mobile a[href^="#"]');
    
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId !== '#') {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        closeMenu();
                        setTimeout(() => {
                            targetElement.scrollIntoView({ 
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }, 300);
                    }
                }
            }
        });
    });
}); 