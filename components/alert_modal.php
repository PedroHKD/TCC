<?php if (isset($_GET['error']) || isset($_GET['success'])): ?>
    <div class="modal" id="alertModal">
        <div class="modal-content <?= isset($_GET['error']) ? 'error' : 'success' ?>">
            <div class="modal-header">
                <h3><?= isset($_GET['error']) ? 'Erro' : 'Sucesso' ?></h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <?php if (isset($_GET['error'])): ?>
                    <span class="modal-icon">⚠️</span>
                    <p><?= htmlspecialchars(urldecode($_GET['error'])) ?></p>
                <?php else: ?>
                    <span class="modal-icon">✅</span>
                    <p><?= isset($_GET['success_message']) ? htmlspecialchars(urldecode($_GET['success_message'])) : 'Operação realizada com sucesso!' ?></p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal()" class="modal-button">Fechar</button>
            </div>
        </div>
    </div>
<?php endif; ?> 