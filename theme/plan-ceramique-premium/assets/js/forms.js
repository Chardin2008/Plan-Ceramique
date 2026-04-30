document.addEventListener('submit', async (event) => {
  const form = event.target;

  if (!form.matches('[data-pcp-form]')) {
    return;
  }

  event.preventDefault();

  const config = window.pcpForms || {};
  const status = form.querySelector('[data-pcp-form-status]');
  const submit = form.querySelector('[type="submit"], [data-wizard-next]');
  const data = new FormData(form);

  if (!config.ajaxUrl && !config.submitUrl) {
    if (status) {
      status.textContent = 'Le formulaire est momentanément indisponible.';
    }
    return;
  }

  data.append('action', 'pcp_submit_form');
  data.append('nonce', config.nonce || '');

  if (status) {
    status.textContent = 'Envoi en cours...';
  }

  if (submit) {
    submit.disabled = true;
  }

  try {
    const response = await fetch(config.submitUrl || config.ajaxUrl, {
      method: 'POST',
      body: data,
      credentials: 'same-origin',
    });

    const result = await response.json().catch(() => ({
      success: false,
      data: { message: 'Réponse serveur invalide.' },
    }));

    if (!response.ok || !result.success) {
      throw new Error(result.data?.message || 'Une erreur est survenue.');
    }

    form.reset();

    if (status) {
      status.textContent = result.data.message;
    }

    if (config.ajaxUrl && result.data?.queueId && (result.data?.queueNonce || result.data?.queueToken)) {
      const queued = new FormData();
      queued.append('action', result.data.queueToken ? 'pcp_process_fast_direct_form_mail' : 'pcp_process_queued_form_mail');
      queued.append('queue_id', result.data.queueId);

      if (result.data.queueToken) {
        queued.append('queue_token', result.data.queueToken);
      } else {
        queued.append('nonce', result.data.queueNonce);
      }

      fetch(config.ajaxUrl, {
        method: 'POST',
        body: queued,
        credentials: 'same-origin',
        keepalive: true,
      }).catch(() => {});
    }
  } catch (error) {
    if (status) {
      status.textContent = error.message || 'Une erreur est survenue.';
    }
  } finally {
    if (submit) {
      submit.disabled = false;
    }
  }
});
