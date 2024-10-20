function abrirModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = "block";
    setTimeout(() => {
        modal.style.opacity = "1";
    }, 10);
}

function fecharModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.opacity = "0";
    setTimeout(() => {
        modal.style.display = "none";
    }, 300);
}

function copiarCodigo() {
    const codigo = document.getElementById("pixCode").textContent;
    navigator.clipboard.writeText(codigo).then(() => {
        alert("Código copiado para a área de transferência!");
    });
}

function continuarPagamento() {
    const metodoSelecionado = document.querySelector('input[name="payment_method"]:checked');
    if (metodoSelecionado) {
        switch(metodoSelecionado.value) {
            case 'pix':
                abrirModal('modalPix');
                break;
            case 'cartao':
                abrirModal('modalCartao');
                break;
            case 'boleto':
                abrirModal('modalBoleto');
                break;
        }
    } else {
        alert("Por favor, selecione um método de pagamento antes de continuar.");
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const closeButtons = document.querySelectorAll('.close');

    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            fecharModal(this.closest('.modal').id);
        });
    });

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            fecharModal(event.target.id);
        }
    }
});