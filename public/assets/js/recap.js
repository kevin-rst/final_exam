(() => {
    const scriptTag = document.getElementById('recapScript');
    const baseUrl = scriptTag?.dataset?.baseUrl || '';

    const formatNumber = (value) => {
        const number = Number(value ?? 0);
        return Number.isFinite(number) ? number.toLocaleString('fr-FR') : '0';
    };

    const updateRecapUI = (data) => {
        const totalBesoins = document.getElementById('totalBesoins');
        const totalSatisfait = document.getElementById('totalSatisfait');
        const totalRestant = document.getElementById('totalRestant');

        if (totalBesoins) totalBesoins.textContent = formatNumber(data.total_besoins);
        if (totalSatisfait) totalSatisfait.textContent = formatNumber(data.total_satisfait);
        if (totalRestant) totalRestant.textContent = formatNumber(data.total_restant);
    };

    const statusEl = document.getElementById('recapStatus');
    const refreshBtn = document.getElementById('refreshRecap');

    if (!refreshBtn) {
        return;
    }

    refreshBtn.addEventListener('click', async () => {
        if (statusEl) statusEl.textContent = 'Actualisation...';
        refreshBtn.disabled = true;

        try {
            const response = await fetch(`${baseUrl}/recap/data`);
            const data = await response.json();
            updateRecapUI(data || {});
            if (statusEl) statusEl.textContent = 'Mis à jour à l’instant.';
        } catch (error) {
            if (statusEl) statusEl.textContent = 'Erreur lors de la mise à jour.';
        } finally {
            refreshBtn.disabled = false;
        }
    });
})();
