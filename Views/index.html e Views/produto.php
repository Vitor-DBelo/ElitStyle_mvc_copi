<script>
document.addEventListener('DOMContentLoaded', function() {
    const carrinhoIcon = document.getElementById('carrinho-icon');
    const modal = document.getElementById('modal-carrinho');
    const closeBtn = modal.querySelector('.close');

    carrinhoIcon.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        carregarCarrinho();
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    });

    function carregarCarrinho() {
        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        atualizarCarrinho(carrinho);
    }

    function atualizarCarrinho(carrinho) {
        let listaCarrinho = document.getElementById('itens-carrinho');
        let resumoCarrinho = document.getElementById('resumo-carrinho');
        
        listaCarrinho.innerHTML = '';
        let total = 0;

        carrinho.forEach((item, index) => {
            // ... (código existente para exibir itens do carrinho)
            total += item.preco * item.quantidade;
        });

        resumoCarrinho.innerHTML = `
            <h2>Resumo do Pedido</h2>
            <div class="total">
                <span>Total:</span>
                <span id="total-carrinho">R$ ${total.toFixed(2)}</span>
            </div>
            <button id="finalizar-compra" class="btn-finalizar">Finalizar Compra</button>
        `;

        document.getElementById('finalizar-compra').addEventListener('click', function() {
            window.location.href = 'compra.html';
        });
    }

    // ... (resto do código existente)
});
</script>
