document.addEventListener('submit', async (event) => {
  const form = event.target;

  if (!form.matches('[data-pcp-form]')) {
    return;
  }

  event.preventDefault();

  const status = form.querySelector('[data-pcp-form-status]');
  const submit = form.querySelector('[type="submit"]');
  const data = new FormData(form);

  data.append('action', 'pcp_submit_form');
  data.append('nonce', pcpForms.nonce);

  if (status) {
    status.textContent = 'Envoi en cours...';
  }

  if (submit) {
    submit.disabled = true;
  }

  try {
    const response = await fetch(pcpForms.submitUrl || pcpForms.ajaxUrl, {
      method: 'POST',
      body: data,
      credentials: 'same-origin',
    });

    const result = await response.json();

    if (!response.ok || !result.success) {
      throw new Error(result.data?.message || 'Une erreur est survenue.');
    }

    form.reset();

    if (status) {
      status.textContent = result.data.message;
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
